<?php
header('Content-Type: application/json');
session_start();
require_once('../data/config.php');

try {
    $where = "WHERE f.status = true";
    $params = [];

    // Se não for Admin Geral, filtra apenas pelo departamento do Diretor logado
    if (!isset($_SESSION['adminLog'])) {
        $where .= " AND f.id_departamento = :deptId";
        $params['deptId'] = $_SESSION['dirDeptId'];
    }

    $sql = "SELECT f.nome, f.bi, d.nome_departamento as departamento, 
                   f.nivel_acesso, f.codigo_acesso, f.status
            FROM funcionarios f
            LEFT JOIN departamentos d ON f.id_departamento = d.id
            $where
            ORDER BY f.id DESC";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['sucesso' => true, 'dados' => $dados]);
} catch (Exception $e) {
    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
}