<?php
    session_start();
    require_once('../data/config.php');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../login.php');
        exit();
    }

    $codigo = $_POST['codigo'] ?? '';
    $senha  = $_POST['senha']  ?? '';

    try {
        $stmt = $pdo->prepare("SELECT nome, senha_hash FROM directores WHERE codigo_acesso = :codigo AND status = true");
        $stmt->execute(['codigo' => $codigo]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && $senha === $admin['senha_hash']) {
            $_SESSION['adminLog']  = true;
            $_SESSION['adminNome'] = $admin['nome'];
            header('Location: ../dashboards/dashboard_admin.php');
            exit();
        }

        $stmt = $pdo->prepare("SELECT id, nome, senha_hash, departamento, cargo FROM funcionarios WHERE codigo_acesso = :codigo AND status = true");
        $stmt->execute(['codigo' => $codigo]);
        $func = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($func && $senha === $func['senha_hash']) {
            $_SESSION['funcLog']   = true;
            $_SESSION['funcNome']  = $func['nome'];
            $_SESSION['funcId']    = $func['id'];
            $_SESSION['funcDept']  = $func['departamento'];
            $_SESSION['funcCargo'] = $func['cargo'];
            header('Location: ../dashboards/dashboard_funcionario.php');
            exit();
        }

        header('Location: ../login.php?erro=1');
        exit();

    } catch (PDOException $e) {
        header('Location: ../login.php?erro=1');
        exit();
    }
