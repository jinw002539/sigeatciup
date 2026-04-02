<?php
    session_start();
    header('Cache-Control: no-cache, no-store, must-revalidate');

    // Verifica se é um diretor de departamento
    if (!isset($_SESSION['dirLog']) || $_SESSION['dirNivel'] !== 'departamento') {
        header('Location: ../login.php');
        exit();
    }

    require_once('../data/config.php');

    // Pegamos os dados corretos da sessão (definidos no login_acao.php)
    $nome   = htmlspecialchars($_SESSION['dirNome']);
    $deptId = $_SESSION['dirDeptId']; // ID numérico do departamento
    $nivel  = htmlspecialchars($_SESSION['dirNivel']);

    try {
        // 1. Total de tarefas do departamento deste diretor
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE id_departamento = :dept");
        $stmt->execute(['dept' => $deptId]);
        $totalDept = $stmt->fetchColumn();

        // 2. Tarefas autorizadas
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE id_departamento = :dept AND autorizada_por_direcao = true");
        $stmt->execute(['dept' => $deptId]);
        $autorizadas = $stmt->fetchColumn();

        // 3. Aguardando autorização (não canceladas)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tarefas WHERE id_departamento = :dept AND autorizada_por_direcao = false AND estado != 'Cancelada'");
        $stmt->execute(['dept' => $deptId]);
        $aguardando = $stmt->fetchColumn();

        // 4. Número de funcionários do departamento (Usando a tabela correta)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM funcionarios WHERE id_departamento = :dept AND status = true");
        $stmt->execute(['dept' => $deptId]);
        $funcDept = $stmt->fetchColumn();

    } catch (PDOException $e) {
        // Se houver erro de base de dados, pelo menos a página não morre silenciosamente
        die("Erro no carregamento de dados: " . $e->getMessage());
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Painel — Director <?php echo $dept; ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../css/base.css">
        <link rel="stylesheet" href="../css/dashboard_admin.css">
        <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
    </head>
    <body class="pagina-com-menu">

        <aside class="menu-lateral">
            <div class="logotipo">
                <img src="../imagens/imagem.png" alt="Logo">
                <h2>SIGATCIUP</h2>
                <small>Dep. <?php echo $dept; ?></small>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Home</a>
                <a href="../tarefas/tarefas.php" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas</a>
                <a href="../cadastro/funcionario.php" class="link-menu"><i class="bi bi-person-plus"></i> Cadastrar</a>
                <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </nav>
        </aside>

        <main class="area-principal">

            <div class="cabecalho-pagina revelar visivel">
                <div>
                    <h1>Olá, <?php echo $nome; ?></h1>
                    <p><?php echo $cargo; ?> · Departamento de <?php echo $dept; ?> · <?php echo date('d \d\e F \d\e Y'); ?></p>
                </div>
                <button class="sino-notificacoes <?php echo $aguardando > 0 ? 'com-alerta' : ''; ?>" title="Tarefas aguardando aprovação">
                    <i class="bi bi-bell<?php echo $aguardando > 0 ? '-fill' : ''; ?>"></i>
                    <?php if ($aguardando > 0): ?>
                        <span class="contador-sino"><?php echo $aguardando; ?></span>
                    <?php endif; ?>
                </button>
            </div>

            <?php if ($aguardando > 0): ?>
            <div class="alerta-pendentes revelar visivel">
                <i class="bi bi-hourglass-split me-2"></i>
                <strong><?php echo $aguardando; ?> tarefa(s)</strong> aguardam aprovação do Director Geral.
            </div>
            <?php endif; ?>

            <div class="grelha-estatisticas">
                <article class="cartao-stat revelar atraso-1">
                    <h3><i class="bi bi-people me-1"></i> Funcionários</h3>
                    <span class="numero"><?php echo $funcDept; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-2">
                    <h3><i class="bi bi-list-task me-1"></i> Tarefas do Dept.</h3>
                    <span class="numero"><?php echo $totalDept; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-3">
                    <h3><i class="bi bi-check2-all me-1"></i> Autorizadas</h3>
                    <span class="numero"><?php echo $autorizadas; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-4">
                    <h3><i class="bi bi-hourglass me-1"></i> Aguardando</h3>
                    <span class="numero <?php echo $aguardando > 0 ? 'texto-alerta' : ''; ?>"><?php echo $aguardando; ?></span>
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
