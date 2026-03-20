<?php
session_start();
header('Cache-Control: no-cache, no-store, must-revalidate');
if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
    header('Location: login.php');
    exit();
}
$nomeAdmin = htmlspecialchars($_SESSION['adminNome']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Admin — SIGATCIUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
    <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
</head>
<body style="display:flex; min-height:100vh;">

    <aside class="menu-lateral">
        <div class="logotipo">
            <img src="../imagens/imagem.png" alt="Logo">
            <h2>SIGATCIUP</h2>
            <small>Sistema de Gestão</small>
        </div>
        <nav class="navegacao">
            <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Home</a>
            <a href="../cadastro/funcionario.php" class="link-menu"><i class="bi bi-people"></i> Funcionários</a>
            <a href="../tarefas/tarefas.php" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas</a>
            <a href="#" class="link-menu"><i class="bi bi-person-badge"></i> Diretores</a>
            <a href="#" class="link-menu"><i class="bi bi-file-earmark-pdf"></i> Relatórios</a>
            <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>

    <main class="area-principal">
        <div class="cabecalho-pagina revelar visivel">
            <h1>Olá, <?php echo $nomeAdmin; ?> <i class="bi bi-hand-wave" style="font-size:1.2rem"></i></h1>
            <p>Bem-vindo ao painel de controlo do CIUP. Hoje é <?php echo date('d \d\e F \d\e Y'); ?>.</p>
        </div>

        <div class="grelha-estatisticas">
            <article class="cartao-stat revelar atraso-1">
                <h3><i class="bi bi-people me-1"></i> Funcionários</h3>
                <span class="numero">13</span>
            </article>
            <article class="cartao-stat revelar atraso-2">
                <h3><i class="bi bi-check2-all me-1"></i> Tarefas Anuais</h3>
                <span class="numero">140</span>
            </article>
            <article class="cartao-stat revelar atraso-3">
                <h3><i class="bi bi-calendar3 me-1"></i> Trimestre I</h3>
                <span class="numero">50</span>
            </article>
            <article class="cartao-stat revelar atraso-4">
                <h3><i class="bi bi-diagram-3 me-1"></i> Dep. Redes</h3>
                <span class="numero">16</span>
            </article>
            <article class="cartao-stat revelar atraso-4">
                <h3><i class="bi bi-cpu me-1"></i> Dep. Sistemas</h3>
                <span class="numero">18</span>
            </article>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const observador = new IntersectionObserver(entradas => {
            entradas.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visivel'); observador.unobserve(e.target); } });
        }, { threshold: 0.1 });
        document.querySelectorAll('.revelar').forEach(el => observador.observe(el));
    </script>
</body>
</html>
