<?php
    session_start();
    require_once('../data/config.php');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../login.php');
        exit();
    }

    $codigo = trim($_POST['codigo'] ?? '');
    $senha  = trim($_POST['senha']  ?? '');

    try {
        // 1. PROCURAR EM DIRETORES
        $stmt = $pdo->prepare("SELECT id, nome, senha_hash, id_departamento, nivel 
                               FROM directores 
                               WHERE codigo_acesso = :codigo AND status = true");
        $stmt->execute(['codigo' => $codigo]);
        $director = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($director) {
            $senhaValida = false;

            // Lógica de Senha para Diretores
            if ($director['nivel'] === 'geral') {
                // Diretor Geral: Aceita a senha mestre '123456' OU o hash da BD
                if ($senha === '123456' || password_verify($senha, $director['senha_hash'])) {
                    $senhaValida = true;
                }
            } else {
                // Diretores de Departamento: Apenas o hash da BD (Sige@2026)
                if (password_verify($senha, $director['senha_hash'])) {
                    $senhaValida = true;
                }
            }

            if ($senhaValida) {
                $_SESSION['dirLog']    = true;
                $_SESSION['dirId']     = $director['id'];
                $_SESSION['dirNome']   = $director['nome'];
                $_SESSION['dirDeptId'] = $director['id_departamento'];
                $_SESSION['dirNivel']  = $director['nivel'];

                if ($director['nivel'] === 'geral') {
                    $_SESSION['adminLog']  = true;
                    $_SESSION['adminNome'] = $director['nome'];
                    header('Location: ../dashboards/dashboard_admin.php');
                } else {
                    header('Location: ../dashboards/dashboard_director_dept.php');
                }
                exit();
            }
        }

        // 2. PROCURAR EM FUNCIONÁRIOS (Se não encontrou diretor ou senha falhou)
        $sqlFunc = "SELECT f.id, f.nome, f.senha_hash, f.nivel_acesso, f.id_departamento, f.status, d.nome_departamento
                    FROM funcionarios f
                    LEFT JOIN departamentos d ON f.id_departamento = d.id
                    WHERE f.codigo_acesso = :codigo";

        $stmtFunc = $pdo->prepare($sqlFunc);
        $stmtFunc->execute(['codigo' => $codigo]);
        $func = $stmtFunc->fetch(PDO::FETCH_ASSOC);

        if ($func && $func['status'] === true) {
            // Funcionários: Apenas o hash da BD (sige#2026)
            if (password_verify($senha, $func['senha_hash'])) {
                $_SESSION['funcLog']      = true;
                $_SESSION['funcId']       = $func['id'];
                $_SESSION['funcNome']     = $func['nome'];
                $_SESSION['funcNivel']    = $func['nivel_acesso'];
                $_SESSION['funcDeptId']   = $func['id_departamento'];
                $_SESSION['funcDeptNome'] = $func['nome_departamento'];

                if ($func['nivel_acesso'] === 'Secretaria') {
                    header('Location: ../dashboards/dashboard_secretaria.php');
                } else {
                    header('Location: ../dashboards/dashboard_funcionario.php');
                }
                exit();
            }
        }

        // Se chegar aqui, as credenciais estão erradas
        header('Location: ../login.php?erro=1');
        exit();

    } catch (PDOException $e) {
        // Log de erro básico para debug
        error_log("Erro no Login: " . $e->getMessage());
        header('Location: ../login.php?erro=db');
        exit();
    }