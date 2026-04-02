<?php
    require_once('../data/config.php');

    $mensagem = "";
    $token_valido = false;
    $email_usuario = "";

    // 1. Validar se o token existe e o tempo de expiração (15 minutos)
    if (isset($_GET['token'])) {
        $token = $_GET['token'];

        $sql = "SELECT email, data_criacao FROM tokens_acesso
                WHERE token = :token AND usado = false
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token]);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultado) {
            $data_criacao = new DateTime($resultado['data_criacao']);
            $agora = new DateTime();
            $diferenca = $agora->getTimestamp() - $data_criacao->getTimestamp();

            // 900 segundos = 15 minutos
            if ($diferenca <= 900) {
                $token_valido = true;
                $email_usuario = $resultado['email'];
            } else {
                $mensagem = "Este link expirou (limite de 15 minutos). Solicite um novo acesso ao Administrador.";
            }
        } else {
            $mensagem = "Token inválido ou já utilizado.";
        }
    }

    // 2. Processar a nova senha
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valido) {
        $senha = $_POST['senha'];
        $confirma = $_POST['confirma_senha'];

        // Validação de força da senha: mín 8 caracteres e 2 especiais
        // Caracteres especiais: !@#$%^&*(),.?":{}|<>
        preg_match_all('/[!@#$%^&*(),.?":{}|<>]/', $senha, $especiais);

        if (strlen($senha) < 8 || count($especiais[0]) < 2) {
            $mensagem = "A senha deve ter pelo menos 8 caracteres e 2 símbolos especiais.";
        } elseif ($senha !== $confirma) {
            $mensagem = "As senhas não coincidem.";
        } else {
            try {
                $pdo->beginTransaction();
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

                // 1. Tenta atualizar na tabela de FUNCIONÁRIOS
                $sqlFunc = "UPDATE funcionarios SET senha_hash = :senha, status = true WHERE email = :email";
                $stmtFunc = $pdo->prepare($sqlFunc);
                $stmtFunc->execute(['senha' => $senha_hash, 'email' => $email_usuario]);

                // 2. Se não afetou nenhuma linha em funcionários, tenta em DIRECTORES
                if ($stmtFunc->rowCount() === 0) {
                    $sqlDir = "UPDATE directores SET senha_hash = :senha, status = true WHERE email = :email";
                    $stmtDir = $pdo->prepare($sqlDir);
                    $stmtDir->execute(['senha' => $senha_hash, 'email' => $email_usuario]);
                }

                // 3. Marcar token como usado
                $sqlToken = "UPDATE tokens_acesso SET usado = true WHERE token = :token";
                $stmtToken = $pdo->prepare($sqlToken);
                $stmtToken->execute(['token' => $_GET['token']]);

                $pdo->commit();
                header("Location: ../login.php?sucesso=1");
                exit();

            } catch (Exception $e) {
                $pdo->rollBack();
                $mensagem = "Erro ao salvar senha: " . $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Definir Senha — SIGATCIUP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../css/base.css">
        <style>
            .caixa-senha { max-width: 400px; margin: 100px auto; padding: 20px; border-radius: 8px; background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        </style>
    </head>
    <body class="bg-light">
        <div class="caixa-senha">
            <h3 class="text-center">Nova Senha</h3>
            <p class="text-muted small text-center">Crie uma senha segura para aceder ao sistema.</p>

            <?php if ($mensagem): ?>
                <div class="alert alert-info"><?php echo $mensagem; ?></div>
            <?php endif; ?>

            <?php if ($token_valido): ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nova Senha</label>
                        <input type="password" name="senha" class="form-control" placeholder="Mín. 8 chars, 2 especiais" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar Senha</label>
                        <input type="password" name="confirma_senha" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Guardar Senha</button>
                </form>
            <?php else: ?>
                <a href="../login.php" class="btn btn-secondary w-100 mt-3">Voltar ao Login</a>
            <?php endif; ?>
        </div>
    </body>
</html>
