<?php
header('Content-Type: application/json');
require_once('../data/config.php');

try {
    $sql = "SELECT d.nome, d.nivel, d.codigo_acesso, dep.nome_departamento 
            FROM directores d
            JOIN departamentos dep ON d.id_departamento = dep.id
            WHERE d.status = true ORDER BY d.id DESC";
    $stmt = $pdo->query($sql);
    echo json_encode(['sucesso' => true, 'dados' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}