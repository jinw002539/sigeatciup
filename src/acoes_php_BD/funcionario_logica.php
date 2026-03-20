<?php
header('Content-Type: application/json');
session_start();
require_once('../data/config.php');

if (!isset($_SESSION['adminLog'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
    exit();
}

class Funcionario {
    private $pdo;

    public function __construct($conexao) {
        $this->pdo = $conexao;
    }

    public function cadastrar($dados) {
        try {
            $nivel   = '01';
            $random  = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $ano     = date('Y');
            $codigo  = "$nivel.$random.$ano";

            $sql = "INSERT INTO funcionarios (nome, genero, bi, data_nascimento, departamento, cargo, codigo_acesso, senha_hash, status)
                    VALUES (:nome, :genero, :bi, :data_nas, :dept, :cargo, :codigo, :senha, true)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'nome'     => $dados['nome'],
                'genero'   => ($dados['genero'] === 'masculino') ? 'M' : 'F',
                'bi'       => $dados['BI'],
                'data_nas' => $dados['data_nas'],
                'dept'     => $dados['departamento'],
                'cargo'    => $dados['cargo'],
                'codigo'   => $codigo,
                'senha'    => $codigo,
            ]);
            return $codigo;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function eliminar($id) {
        $sql  = "UPDATE funcionarios SET status = false WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function editar($dados) {
        $sql  = "UPDATE funcionarios SET nome = :nome, departamento = :dept, cargo = :cargo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nome' => $dados['nome'],
            'dept' => $dados['departamento'],
            'cargo'=> $dados['cargo'],
            'id'   => $dados['id'],
        ]);
    }
}

$func   = new Funcionario($pdo);
$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'editar') {
        echo json_encode(['sucesso' => $func->editar($_POST)]);
    } else {
        $res = $func->cadastrar($_POST);
        echo json_encode($res ? ['sucesso' => true, 'codigo' => $res] : ['sucesso' => false]);
    }
} elseif ($metodo === 'GET') {
    if (isset($_GET['eliminar'])) {
        echo json_encode(['sucesso' => $func->eliminar($_GET['eliminar'])]);
    }
}
