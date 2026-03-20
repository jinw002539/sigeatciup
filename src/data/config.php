<?php
    $host   = 'localhost';
    $port   = '5432';
    $dbname = 'sigetaciup';
    $user   = 'kaly';
    $pass   = 'kalylinux';

    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$pass";

    try {
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(500);
        die(json_encode(['erro' => 'Falha na ligação à base de dados.']));
    }
