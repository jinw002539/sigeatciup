<?php
    header('Content-Type: application/json');
    session_start();
    require_once('../data/config.php');

    // Segurança: Apenas Diretores ou Admin podem cadastrar
    if (!isset($_SESSION['adminLog']) && !isset($_SESSION['dirLog'])) {
        echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
        exit();
    }

    class GestaoUtilizador {
        private $pdo;

        public function __construct($conexao) {
            $this->pdo = $conexao;
        }

        public function cadastrar($dados) {
            try {
                // LÓGICA DE DEPARTAMENTO: 
                // Se for Diretor de Dept, usa o ID da sessão. Se for Admin, usa o do formulário.
                $id_dept = isset($_SESSION['dirLog']) && !isset($_SESSION['adminLog']) 
                           ? $_SESSION['dirDeptId'] 
                           : ($dados['id_departamento'] ?? null);

                if (empty($dados['nome']) || empty($dados['bi']) || empty($dados['email']) || empty($id_dept)) {
                    return ['sucesso' => false, 'erro' => 'Preencha todos os campos obrigatórios.'];
                }

                $this->pdo->beginTransaction();

                // 1. Gerar Código de Acesso (Prefixo 01 para funcionários)
                $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $codigo = "01." . $random . "." . date('Y');

                // 2. Definir Senha Padrão encriptada
                $senhaPadrao = "sige#2026";
                $senhaHash = password_hash($senhaPadrao, PASSWORD_DEFAULT);

                // 3. Inserir no Banco de Dados
                $sql = "INSERT INTO funcionarios (nome, genero, bi, email, id_departamento, codigo_acesso, senha_hash, nivel_acesso, status) 
                        VALUES (:nome, :genero, :bi, :email, :id_dept, :codigo, :senha, :nivel, true)";
                
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([
                    'nome'    => $dados['nome'],
                    'genero'  => $dados['genero'] ?? 'M',
                    'bi'      => $dados['bi'],
                    'email'   => $dados['email'],
                    'id_dept' => $id_dept,
                    'codigo'  => $codigo,
                    'senha'   => $senhaHash,
                    'nivel'   => $dados['nivel_acesso'] ?? 'tecnico'
                ]);

                $this->pdo->commit();

                return [
                    'sucesso' => true,
                    'codigo'  => $codigo,
                    'senha_provisoria' => $senhaPadrao
                ];

            } catch (PDOException $e) {
                if ($this->pdo->inTransaction()) {
                    $this->pdo->rollBack();
                }
                if ($e->getCode() == 23505) {
                    return ['sucesso' => false, 'erro' => 'Este BI ou E-mail já está cadastrado.'];
                }
                return ['sucesso' => false, 'erro' => 'Erro na BD: ' . $e->getMessage()];
            }
        }

        public function eliminar($bi) {
            try {
                $sql = "UPDATE funcionarios SET status = false WHERE bi = :bi";
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute(['bi' => $bi]);
                return ['sucesso' => $res];
            } catch (PDOException $e) {
                return ['sucesso' => false, 'erro' => $e->getMessage()];
            }
        }
    }

    $gestao = new GestaoUtilizador($pdo);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $acao = $_POST['acao'] ?? 'cadastrar';
        if ($acao === 'cadastrar') {
            echo json_encode($gestao->cadastrar($_POST));
        } 
        elseif ($acao === 'eliminar') {
            echo json_encode($gestao->eliminar($_POST['bi'] ?? ''));
        }
    } 
    elseif (isset($_GET['eliminar'])) {
        echo json_encode($gestao->eliminar($_GET['bi'] ?? ''));
    }
?>