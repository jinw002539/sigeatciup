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
            // $sql = "SELECT t.*, d.nome_departamento 
            //         FROM tarefas t
            //         LEFT JOIN departamentos d ON t.id_departamento = d.id
            //         $where 
            //         ORDER BY t.criado_em DESC";

            $sql = "SELECT id, actividade, objectivos, resultado_esperado, 
               prazo_execucao, estado, id_departamento, 
               bloqueada, autorizada_por_direcao 
            FROM tarefas " . $where . " ORDER BY criado_em DESC";
            
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
    if ($metodo === 'POST' && isset($_GET['salvar'])) {
        $dados = $_POST;
        $id_periodo = $dados['id_periodo'] ?? null;
        
        // Define o departamento: se for admin, usa o do POST, se for diretor, usa o da sessão
        $id_dept = ($ehAdmin) ? ($dados['id_departamento'] ?? null) : ($_SESSION['dirDeptId'] ?? null);

        if (!$id_periodo) {
            echo json_encode(['sucesso' => false, 'erro' => 'Período obrigatório.']);
            exit();
        }

        try {
            // 1. Inteligência: Buscar o mês final do período para calcular o prazo
            $stmtP = $pdo->prepare("SELECT mes_fim FROM periodos_config WHERE id = ?");
            $stmtP->execute([$id_periodo]);
            $conf = $stmtP->fetch(PDO::FETCH_ASSOC);
            
            if (!$conf) {
                echo json_encode(['sucesso' => false, 'erro' => 'Configuração de período não encontrada.']);
                exit();
            }

            $ano_atual = date('Y');
            // Calcula o último dia do mês final (ex: se mes_fim=3, gera 2026-03-31)
            $data_prazo = date("$ano_atual-{$conf['mes_fim']}-t");

            if (!empty($dados['id'])) {
                // EDITAR TAREFA EXISTENTE
                $sql = "UPDATE tarefas SET 
                        actividade = :act, 
                        objectivos = :obj, 
                        resultado_esperado = :res, 
                        prazo_execucao = :prazo, 
                        id_departamento = :dept, 
                        id_periodo = :per, 
                        estado = :est
                        WHERE id = :id";
                
                $params = [
                    'act'   => $dados['actividade'],
                    'obj'   => $dados['objectivos'],
                    'res'   => $dados['resultado_esperado'],
                    'prazo' => $data_prazo,
                    'dept'  => $id_dept,
                    'per'   => $id_periodo,
                    'est'   => $dados['estado'],
                    'id'    => $dados['id']
                ];
            } else {
                // CRIAR NOVA TAREFA
                $estadoInicial = $ehAdmin ? 'Autorizada' : 'Aguardando';
                $autorizada    = $ehAdmin ? 't' : 'f'; // 't'/'f' para compatibilidade com o teu Postgres

                $sql = "INSERT INTO tarefas (actividade, objectivos, resultado_esperado, prazo_execucao, estado, id_departamento, id_periodo, autorizada_por_direcao)
                        VALUES (:act, :obj, :res, :prazo, :est, :dept, :per, :aut)";
                
                $params = [
                    'act'   => $dados['actividade'],
                    'obj'   => $dados['objectivos'],
                    'res'   => $dados['resultado_esperado'],
                    'prazo' => $data_prazo,
                    'est'   => $estadoInicial,
                    'dept'  => $id_dept,
                    'per'   => $id_periodo,
                    'aut'   => $autorizada
                ];
            }

            $stmt = $pdo->prepare($sql);
            $resultado = $stmt->execute($params);
            
            echo json_encode(['sucesso' => $resultado]);

        } catch (PDOException $e) {
            // Se houver erro de FK ou coluna, ele dirá exatamente o quê
            echo json_encode(['sucesso' => false, 'erro' => "Erro na BD: " . $e->getMessage()]);
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