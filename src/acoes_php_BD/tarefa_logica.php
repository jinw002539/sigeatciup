<?php
header('Content-Type: application/json');
session_start();
require_once('../data/config.php');

if (!isset($_SESSION['adminLog'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado.']);
    exit();
}

$metodo = $_SERVER['REQUEST_METHOD'];

if ($metodo === 'GET' && isset($_GET['listar'])) {
    $periodo = $_GET['periodo'] ?? 'todos';
    $where   = '';

    if ($periodo === 't1') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 1 AND 3";
    if ($periodo === 't2') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 4 AND 6";
    if ($periodo === 't3') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 7 AND 9";
    if ($periodo === 't4') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 10 AND 12";
    if ($periodo === 's1') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 1 AND 6";
    if ($periodo === 's2') $where = "AND EXTRACT(MONTH FROM prazo_execucao) BETWEEN 7 AND 12";

    try {
        $stmt = $pdo->query("SELECT id, actividade, objectivos, resultado_esperado, prazo_execucao, estado FROM tarefas WHERE true $where ORDER BY prazo_execucao ASC");
        echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false]);
    }
    exit();
}

if ($metodo === 'GET' && isset($_GET['eliminar'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
        echo json_encode(['sucesso' => $stmt->execute(['id' => $_GET['eliminar']])]);
    } catch (PDOException $e) {
        echo json_encode(['sucesso' => false]);
    }
    exit();
}

if ($metodo === 'POST') {
    $dados = json_decode(file_get_contents('php://input'), true);

    if (!empty($dados['id'])) {
        try {
            $stmt = $pdo->prepare("UPDATE tarefas SET actividade = :act, objectivos = :obj, resultado_esperado = :res, prazo_execucao = :prazo, estado = :estado WHERE id = :id");
            $ok   = $stmt->execute([
                'act'    => $dados['actividade'],
                'obj'    => $dados['objectivos'],
                'res'    => $dados['resultado_esperado'],
                'prazo'  => $dados['prazo_execucao'],
                'estado' => $dados['estado'],
                'id'     => $dados['id'],
            ]);
            echo json_encode(['sucesso' => $ok]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false]);
        }
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO tarefas (actividade, objectivos, resultado_esperado, prazo_execucao, estado) VALUES (:act, :obj, :res, :prazo, :estado)");
            $ok   = $stmt->execute([
                'act'    => $dados['actividade'],
                'obj'    => $dados['objectivos'],
                'res'    => $dados['resultado_esperado'],
                'prazo'  => $dados['prazo_execucao'],
                'estado' => $dados['estado'] ?? 'Por atribuir',
            ]);
            echo json_encode(['sucesso' => $ok]);
        } catch (PDOException $e) {
            echo json_encode(['sucesso' => false]);
        }
    }
    exit();
}
