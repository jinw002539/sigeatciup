<?php
    session_start();
    header('Cache-Control: no-cache, no-store, must-revalidate');
    if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
        header('Location: ../login.php');
        exit();
    }
    require_once('../data/config.php');
    $nomeAdmin = htmlspecialchars($_SESSION['adminNome']);

    // Stats reais
    $totalFunc     = $pdo->query("SELECT COUNT(*) FROM funcionarios WHERE status = true")->fetchColumn();
    $totalTarefas  = $pdo->query("SELECT COUNT(*) FROM tarefas")->fetchColumn();
    $pendentes     = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE autorizada_por_direcao = false AND estado != 'Cancelada'")->fetchColumn();
    $bloqueadas    = $pdo->query("SELECT COUNT(*) FROM tarefas WHERE bloqueada = true")->fetchColumn();

    // Tarefas por departamento para o gráfico
    $porDept = $pdo->query("SELECT id_departamento, COUNT(*) as total FROM tarefas WHERE id_departamento IS NOT NULL GROUP BY id_departamento")->fetchAll(PDO::FETCH_ASSOC);
    $deptLabels = json_encode(array_column($porDept, 'id_departamento'));
    $deptTotais = json_encode(array_column($porDept, 'total'));

    // Tarefas por mês (semestre actual)
    $porMes = $pdo->query("SELECT TO_CHAR(prazo_execucao, 'Mon') as mes, COUNT(*) as total FROM tarefas WHERE EXTRACT(YEAR FROM prazo_execucao) = EXTRACT(YEAR FROM CURRENT_DATE) GROUP BY mes, EXTRACT(MONTH FROM prazo_execucao) ORDER BY EXTRACT(MONTH FROM prazo_execucao)")->fetchAll(PDO::FETCH_ASSOC);
    $mesLabels = json_encode(array_column($porMes, 'mes'));
    $mesTotais = json_encode(array_column($porMes, 'total'));
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Painel Geral — SIGATCIUP</title>
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
                <small>Direcção Geral</small>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Home</a>
                <a href="../cadastro/funcionario.php" class="link-menu"><i class="bi bi-people"></i> Funcionários</a>
                <a href="../tarefas/tarefas.php" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas</a>
                <a href="../cadastro/director.php" class="link-menu"><i class="bi bi-person-badge"></i> Diretores</a>
                <a href="#" class="link-menu"><i class="bi bi-file-earmark-pdf"></i> Relatórios</a>
                <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </nav>
        </aside>

        <main class="area-principal">

            <div class="cabecalho-pagina revelar visivel">
                <div>
                    <h1>Olá, <?php echo $nomeAdmin; ?> <i class="bi bi-hand-wave" style="font-size:1.2rem"></i></h1>
                    <p><?php echo date('d \d\e F \d\e Y'); ?> · Direcção Geral do CIUP</p>
                </div>
                <button class="sino-notificacoes <?php echo $pendentes > 0 ? 'com-alerta' : ''; ?>" id="btnSino" title="Tarefas pendentes de aprovação">
                    <i class="bi bi-bell<?php echo $pendentes > 0 ? '-fill' : ''; ?>"></i>
                    <?php if ($pendentes > 0): ?>
                        <span class="contador-sino"><?php echo $pendentes; ?></span>
                    <?php endif; ?>
                </button>
            </div>

            <?php if ($pendentes > 0): ?>
            <div class="alerta-pendentes revelar visivel">
                <i class="bi bi-hourglass-split me-2"></i>
                <strong><?php echo $pendentes; ?> tarefa(s)</strong> aguardam a sua aprovação.
                <a href="../tarefas/tarefas.php?filtro=pendentes" class="link-alerta">Ver agora →</a>
            </div>
            <?php endif; ?>

            <div class="grelha-estatisticas">
                <article class="cartao-stat revelar atraso-1">
                    <h3><i class="bi bi-people me-1"></i> Funcionários</h3>
                    <span class="numero"><?php echo $totalFunc; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-2">
                    <h3><i class="bi bi-check2-all me-1"></i> Tarefas Totais</h3>
                    <span class="numero"><?php echo $totalTarefas; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-3">
                    <h3><i class="bi bi-hourglass me-1"></i> Pendentes</h3>
                    <span class="numero <?php echo $pendentes > 0 ? 'texto-alerta' : ''; ?>"><?php echo $pendentes; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-4">
                    <h3><i class="bi bi-lock me-1"></i> Bloqueadas</h3>
                    <span class="numero <?php echo $bloqueadas > 0 ? 'texto-perigo' : ''; ?>"><?php echo $bloqueadas; ?></span>
                </article>
            </div>

            <div class="row g-4 mt-1">
                <div class="col-lg-6 revelar atraso-2">
                    <div class="cartao-grafico">
                        <h3 class="titulo-grafico"><i class="bi bi-bar-chart me-2"></i>Tarefas por Departamento</h3>
                        <canvas id="graficoDept"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 revelar atraso-3">
                    <div class="cartao-grafico">
                        <h3 class="titulo-grafico"><i class="bi bi-graph-up me-2"></i>Distribuição Mensal</h3>
                        <canvas id="graficoMes"></canvas>
                    </div>
                </div>
            </div>

        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            // Animações de revelação
            const observador = new IntersectionObserver(entradas => {
                entradas.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visivel'); observador.unobserve(e.target); } });
            }, { threshold: 0.1 });
            document.querySelectorAll('.revelar').forEach(el => observador.observe(el));

            // Gráfico por departamento
            new Chart(document.getElementById('graficoDept'), {
                type: 'bar',
                data: {
                    labels: <?php echo $deptLabels; ?>,
                    datasets: [{
                        label: 'Tarefas',
                        data: <?php echo $deptTotais; ?>,
                        backgroundColor: ['#004d40', '#00695c', '#0056b3', '#80cbc4'],
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
                }
            });

            // Gráfico mensal
            new Chart(document.getElementById('graficoMes'), {
                type: 'line',
                data: {
                    labels: <?php echo $mesLabels; ?>,
                    datasets: [{
                        label: 'Tarefas',
                        data: <?php echo $mesTotais; ?>,
                        borderColor: '#004d40',
                        backgroundColor: 'rgba(0,77,64,.1)',
                        borderWidth: 2.5,
                        pointBackgroundColor: '#004d40',
                        pointRadius: 5,
                        fill: true,
                        tension: 0.4,
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } }
                }
            });

            // Verificar bloqueio automático ao carregar
            fetch('../acoes_php_BD/tarefa_logica.php?verificar_bloqueio=1');
        </script>
    </body>
</html>
