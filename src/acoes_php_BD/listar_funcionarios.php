<?php
    header('Content-Type: application/json');
    require_once('../data/config.php');

    try {
        $sql  = "SELECT nome, bi, departamento, cargo, codigo_acesso, status
                FROM funcionarios
                WHERE status = true
                ORDER BY id DESC";
        $stmt = $pdo->query($sql);
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(['sucesso' => true, 'dados' => $dados]);
    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'erro' => 'Erro ao carregar dados.']);
    }
