<?php
header('Content-Type: application/json');
session_start();
require_once('../data/config.php');
require_once('servico_email.php');

if (!isset($_SESSION['adminLog']) && !isset($_SESSION['dirLog'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
    exit();
}

class GestaoUtilizador {
    private $pdo;

    public function __construct($conexao) {
        $this->pdo = $conexao;
    }

    public function listar() {
        try {
            // Se for Diretor de Dept, filtra. Se for Admin, mostra tudo.
            if (isset($_SESSION['dirLog']) && !isset($_SESSION['adminLog'])) {
                $sql = "SELECT f.*, d.nome_departamento 
                        FROM funcionarios f 
                        INNER JOIN departamentos d ON f.id_departamento = d.id 
                        WHERE f.id_departamento = :id_dept 
                        ORDER BY f.nome ASC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id_dept' => $_SESSION['dirDeptId']]);
            } else {
                $sql = "SELECT f.*, d.nome_departamento 
                        FROM funcionarios f 
                        INNER JOIN departamentos d ON f.id_departamento = d.id 
                        ORDER BY f.nome ASC";
                $stmt = $this->pdo->query($sql);
            }
            return ['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
        } catch (PDOException $e) {
            return ['sucesso' => false, 'erro' => $e->getMessage()];
        }
    }

    public function cadastrar($dados) {
        try {
            $id_dept = (isset($_SESSION['dirLog']) && !isset($_SESSION['adminLog'])) 
                       ? $_SESSION['dirDeptId'] : ($dados['id_departamento'] ?? null);

            $this->pdo->beginTransaction();
            $codigo = "01." . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . "." . date('Y');

            $sql = "INSERT INTO funcionarios (nome, genero, bi, email, id_departamento, codigo_acesso, nivel_acesso, status) 
                    VALUES (:nome, :genero, :bi, :email, :id_dept, :codigo, :nivel, false)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome'    => $dados['nome'],
                'genero'  => $dados['genero'] ?? 'M',
                'bi'      => $dados['bi'],
                'email'   => $dados['email'],
                'id_dept' => $id_dept,
                'codigo'  => $codigo,
                'nivel'   => $dados['nivel_acesso'] ?? 'tecnico'
            ]);

            $token = bin2hex(random_bytes(32));
            $this->pdo->prepare("INSERT INTO tokens_acesso (email, token) VALUES (?, ?)")->execute([$dados['email'], $token]);

            if (enviarLinkAcesso($dados['email'], $dados['nome'], $token)) {
                $this->pdo->commit();
                return ['sucesso' => true, 'codigo' => $codigo];
            } else {
                throw new Exception('Erro ao enviar e-mail.');
            }
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            return ['sucesso' => false, 'erro' => $e->getMessage()];
        }
    }
}

$gestao = new GestaoUtilizador($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo json_encode($gestao->cadastrar($_POST));
} elseif (isset($_GET['listar'])) {
    echo json_encode($gestao->listar());
}
