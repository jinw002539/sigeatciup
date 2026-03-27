<?php
    header('Content-Type: application/json');
    session_start();
    require_once('../data/config.php');

    // ── CONFIGURAÇÃO DE ACESSOS ────────────────────────────────
    $ehAdmin     = isset($_SESSION['adminLog']) && $_SESSION['adminLog'] === true;
    $ehDirDept   = isset($_SESSION['dirLog'])   && ($_SESSION['dirNivel'] ?? '') === 'departamento';
    $ehFunc      = isset($_SESSION['funcLog'])  && $_SESSION['funcLog']  === true;

    if (!$ehAdmin && !$ehDirDept && !$ehFunc) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
        exit();
    }

    $metodo = $_SERVER['REQUEST_METHOD'];

    // ── 1. LISTAR TAREFAS ──────────────────────────────────────
    if ($metodo === 'GET' && isset($_GET['listar'])) {
        $periodo = $_GET['periodo'] ?? 'todos';
        $where   = 'WHERE true';
        $params  = [];

        // Filtro de Segurança: Diretores e Funcionários só vêem o seu departamento
        if (!$ehAdmin) {
            $where .= " AND t.id_departamento = :meuDept";
            $params['meuDept'] = $_SESSION['dirDeptId'] ?? $_SESSION['funcDeptId'];
        }

        // Filtros de Período (Trimestres)
        if ($periodo === 't1') $where .= " AND EXTRACT(MONTH FROM t.prazo_execucao) BETWEEN 1 AND 3";
        if ($periodo === 't2') $where .= " AND EXTRACT(MONTH FROM t.prazo_execucao) BETWEEN 4 AND 6";
        if ($periodo === 't3') $where .= " AND EXTRACT(MONTH FROM t.prazo_execucao) BETWEEN 7 AND 9";
        if ($periodo === 't4') $where .= " AND EXTRACT(MONTH FROM t.prazo_execucao) BETWEEN 10 AND 12";

        try {
            $sql = "SELECT t.*, d.nome_departamento 
                    FROM tarefas t
                    LEFT JOIN departamentos d ON t.id_departamento = d.id
                    $where 
                    ORDER BY t.criado_em DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['sucesso' => true, 'tarefas' => $tarefas]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
        }
        exit();
    }

    // ── 2. CRIAR OU EDITAR TAREFA ──────────────────────────────
    if ($metodo === 'POST') {
        $dados = $_POST;
        $id    = $dados['id'] ?? null;

        // Lógica de Atribuição de Departamento e Autorização
        if ($ehDirDept) {
            // Diretor de Dept: Força o seu próprio departamento e precisa de aprovação
            $id_departamento = $_SESSION['dirDeptId'];
            $autorizada = false; 
            $estado = 'Pendente';
        } else if ($ehAdmin) {
            // Admin: Usa o departamento vindo do form e já nasce autorizada
            $id_departamento = $dados['id_departamento'];
            $autorizada = true;
            $estado = 'Aguardando';
        } else {
            echo json_encode(['sucesso' => false, 'erro' => 'Funcionários não criam tarefas.']);
            exit();
        }

        if ($id) {
            // EDITAR TAREFA
            try {
                $sql = "UPDATE tarefas SET 
                        actividade = :act, 
                        objectivos = :obj, 
                        resultado_esperado = :res, 
                        prazo_execucao = :prazo 
                        WHERE id = :id";
                
                $params = [
                    'act'   => $dados['actividade'],
                    'obj'   => $dados['objectivos'],
                    'res'   => $dados['resultado_esperado'],
                    'prazo' => $dados['prazo_execucao'],
                    'id'    => $id
                ];

                // Se for Diretor de Dept, a edição volta a tarefa para "Pendente" de autorização
                if ($ehDirDept) {
                    $sql = str_replace("WHERE", ", autorizada_por_direcao = false, estado = 'Pendente' WHERE", $sql);
                }

                $stmt = $pdo->prepare($sql);
                echo json_encode(['sucesso' => $stmt->execute($params)]);
            } catch (PDOException $e) {
                echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
            }
        } else {
            // CRIAR NOVA TAREFA
            try {
                $sql = "INSERT INTO tarefas (actividade, objectivos, resultado_esperado, prazo_execucao, estado, id_departamento, autorizada_por_direcao)
                        VALUES (:act, :obj, :res, :prazo, :estado, :dept, :aut)";
                
                $stmt = $pdo->prepare($sql);
                $ok = $stmt->execute([
                    'act'    => $dados['actividade'],
                    'obj'    => $dados['objectivos'] ?? '',
                    'res'    => $dados['resultado_esperado'] ?? '',
                    'prazo'  => $dados['prazo_execucao'],
                    'estado' => $estado,
                    'dept'   => $id_departamento,
                    'aut'    => $autorizada
                ]);
                echo json_encode(['sucesso' => $ok]);
            } catch (PDOException $e) {
                echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
            }
        }
        exit();
    }

    // ── 3. AUTORIZAR TAREFA (APENAS ADMIN GERAL) ────────────────
    if ($metodo === 'POST' && isset($_GET['autorizar']) && $ehAdmin) {
        try {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("UPDATE tarefas SET autorizada_por_direcao = true, estado = 'Aguardando' WHERE id = :id");
            echo json_encode(['sucesso' => $stmt->execute(['id' => $id])]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
        }
        exit();
    }

    // ── 4. ELIMINAR TAREFA ─────────────────────────────────────
    if ($metodo === 'POST' && isset($_GET['eliminar'])) {
        if (!$ehAdmin && !$ehDirDept) {
            echo json_encode(['sucesso' => false, 'erro' => 'Sem permissão.']);
            exit();
        }
        
        try {
            $id = $_POST['id'];
            $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
            echo json_encode(['sucesso' => $stmt->execute(['id' => $id])]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
        }
        exit();
    }

    // ── 5. VERIFICAR BLOQUEIOS (SISTEMA/ADMIN) ──────────────────
    if ($metodo === 'GET' && isset($_GET['verificar_bloqueio']) && $ehAdmin) {
        try {
            $pdo->exec("UPDATE tarefas SET bloqueada = true 
                        WHERE prazo_execucao < CURRENT_DATE 
                        AND estado NOT IN ('Concluída', 'Cancelada') 
                        AND bloqueada = false");
            echo json_encode(['sucesso' => true]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
        }
        exit();
    }

    // Se chegar aqui sem acção válida
    echo json_encode(['sucesso' => false, 'erro' => 'Acção não reconhecida.']);