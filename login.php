<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login — SIGEATCIUP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/login.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="shortcut icon" href="imagens/image.ico" type="image/x-icon">
    </head>
    <body class="pagina-login">

        <div class="cartao-login revelar visivel">
            <aside class="lado-imagem">
                <img src="imagens/imagem.png" alt="Logo UP">
                <h2>SIGEATCIUP</h2>
                <p>Sistema de Gestão e Agendamento de Tarefas do Centro de Informática da UP</p>
            </aside>
            <main class="lado-formulario">
                <h3>Bem-vindo</h3>
                <small>Introduza as suas credenciais</small>

                <form action="acoes_php_BD/login_acao.php" method="post">
                    <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.5px;color:var(--texto-suave)">Código de Acesso</label>
                    <div class="grupo-input-icone">
                        <i class="bi bi-person"></i>
                        <input type="text" name="codigo" required>
                    </div>

                    <label class="form-label fw-semibold" style="font-size:.82rem;text-transform:uppercase;letter-spacing:.5px;color:var(--texto-suave)">Senha</label>
                    <div class="grupo-input-icone">
                        <i class="bi bi-lock"></i>
                        <input type="password" name="senha" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="botao-autenticar">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Autenticar
                    </button>
                </form>

                <div class="links-rodape-login">
                    <a href="recuperacao_senha/senha.php"><i class="bi bi-key me-1"></i>Esqueci a senha</a>
                    <a href="index.php"><i class="bi bi-house me-1"></i>Página inicial</a>
                </div>
            </main>
        </div>

        <div id="sobreposicao-erro" class="sobreposicao-erro">
            <div class="caixa-erro">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <h4>Erro de Autenticação</h4>
                <p>Código ou senha incorretos. Verifique e tente novamente.</p>
                <button class="botao-fechar-erro" onclick="document.getElementById('sobreposicao-erro').classList.remove('aberto')">
                    Tentar Novamente
                </button>
            </div>
        </div>

        <?php if (isset($_GET['erro'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.getElementById('sobreposicao-erro').classList.add('aberto');
            });
        </script>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
