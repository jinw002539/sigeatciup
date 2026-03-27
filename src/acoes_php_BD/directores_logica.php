<?php
header('Content-Type: application/json');
session_start();
require_once('../data/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $nome    = $_POST['nome'];
        $email   = $_POST['email'];
        $genero  = $_POST['genero'] ?? 'M';
        $bi      = $_POST['bi'];
        $id_dept = $_POST['id_departamento'];
        $nivel   = $_POST['nivel'];

        // Senha padrão para todos os novos cadastros
        // O Diretor poderá mudar depois no perfil dele
        $senhaPadrao = "Sige@2026"; 
        $senhaHash = password_hash($senhaPadrao, PASSWORD_DEFAULT);

        // Gerar código de acesso
        $codigo = "00." . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . "." . date('Y');

        $sql = "INSERT INTO directores (nome, genero, bi, email, id_departamento, nivel, codigo_acesso, senha_hash, status) 
                VALUES (:nome, :genero, :bi, :email, :id_dept, :nivel, :codigo, :senha, true)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nome'    => $nome,
            'genero'  => $genero,
            'bi'      => $bi,
            'email'   => $email,
            'id_dept' => $id_dept,
            'nivel'   => $nivel,
            'codigo'  => $codigo,
            'senha'   => $senhaHash
        ]);

        echo json_encode([
            'sucesso' => true, 
            'codigo'  => $codigo,
            'senha_provisoria' => $senhaPadrao,
            'mensagem' => "Director cadastrado! A senha de acesso é: $senhaPadrao"
        ]);

    } catch (Exception $e) {
        echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
    }
}