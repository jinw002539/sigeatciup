<?php
    session_start();
    header('Cache-Control: no-cache, no-store, must-revalidate');
    if (!isset($_SESSION['funcLog']) || $_SESSION['funcLog'] !== true) {
        header('Location: ../login.php');
        exit();
    }
    $nome  = htmlspecialchars($_SESSION['funcNome']);
    $dept  = htmlspecialchars($_SESSION['funcDept']);
    $cargo = htmlspecialchars($_SESSION['funcCargo']);
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Painel — <?php echo $nome; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="../css/dashboard_admin.css">
        <link rel="stylesheet" href="../css/dashboard_funcionario.css">
        <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
    </head>
    <body style="display:flex; min-height:100vh;">

        <aside class="menu-lateral">
            <div class="logotipo">
                <img src="../imagens/imagem.png" alt="Logo">
                <h2>SIGATCIUP</h2>
                <small>Área do Funcionário</small>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Início</a>
                <a href="#" class="link-menu"><i class="bi bi-check2-square"></i> Minhas Tarefas</a>
                <a href="#" class="link-menu"><i class="bi bi-calendar3"></i> Agenda</a>
                <a href="#" class="link-menu"><i class="bi bi-person-circle"></i> Perfil</a>
                <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </nav>
        </aside>

        <main class="area-principal">

            <div class="cabecalho-pagina revelar visivel">
                <h1>Olá, <?php echo $nome; ?> <i class="bi bi-hand-wave" style="font-size:1.2rem"></i></h1>
                <p><?php echo $cargo; ?> &middot; Departamento de <?php echo $dept; ?> &middot; <?php echo date('d \d\e F \d\e Y'); ?></p>
            </div>

            <div class="grelha-estatisticas">
                <article class="cartao-stat revelar atraso-1">
                    <h3><i class="bi bi-check2-all me-1"></i> Tarefas Atribuídas</h3>
                    <span class="numero">12</span>
                </article>
                <article class="cartao-stat revelar atraso-2">
                    <h3><i class="bi bi-hourglass-split me-1"></i> Em Curso</h3>
                    <span class="numero">04</span>
                </article>
                <article class="cartao-stat revelar atraso-3">
                    <h3><i class="bi bi-check-circle me-1"></i> Concluídas</h3>
                    <span class="numero">08</span>
                </article>
                <article class="cartao-stat revelar atraso-4">
                    <h3><i class="bi bi-exclamation-circle me-1"></i> Pendentes</h3>
                    <span class="numero">02</span>
                </article>
            </div>

            <section class="secao-tarefas revelar atraso-2">
                <h2 class="titulo-secao-func mb-4">Tarefas Recentes</h2>
                <div class="lista-tarefas-func">

                    <article class="cartao-tarefa">
                        <div class="tarefa-info">
                            <span class="tarefa-prioridade alta">Alta</span>
                            <strong>Manutenção de Servidores — Sala B</strong>
                            <small><i class="bi bi-calendar2 me-1"></i>Prazo: 25 de Março de 2026</small>
                        </div>
                        <span class="badge bg-warning text-dark">Em Curso</span>
                    </article>

                    <article class="cartao-tarefa">
                        <div class="tarefa-info">
                            <span class="tarefa-prioridade media">Média</span>
                            <strong>Relatório Trimestral de Infraestrutura</strong>
                            <small><i class="bi bi-calendar2 me-1"></i>Prazo: 31 de Março de 2026</small>
                        </div>
                        <span class="badge bg-primary">Em Fila</span>
                    </article>

                    <article class="cartao-tarefa">
                        <div class="tarefa-info">
                            <span class="tarefa-prioridade baixa">Baixa</span>
                            <strong>Actualização de Documentação Técnica</strong>
                            <small><i class="bi bi-calendar2 me-1"></i>Prazo: 10 de Abril de 2026</small>
                        </div>
                        <span class="badge bg-success">Concluída</span>
                    </article>

                </div>
            </section>

        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const observador = new IntersectionObserver(entradas => {
                entradas.forEach(e => {
                    if (e.isIntersecting) { e.target.classList.add('visivel'); observador.unobserve(e.target); }
                });
            }, { threshold: 0.1 });
            document.querySelectorAll('.revelar').forEach(el => observador.observe(el));
        </script>
    </body>
</html>
