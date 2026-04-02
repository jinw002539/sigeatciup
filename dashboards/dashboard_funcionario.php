<?php
    session_start();
    header('Cache-Control: no-cache, no-store, must-revalidate');
    if (!isset($_SESSION['funcLog']) || $_SESSION['funcLog'] !== true) {
        header('Location: ../login.php');
        exit();
    }
    require_once('../data/config.php');
    $nome  = htmlspecialchars($_SESSION['funcNome']);
    $dept  = htmlspecialchars($_SESSION['funcDept']);
    $cargo = htmlspecialchars($_SESSION['funcCargo']);
    $idFunc = (int)$_SESSION['funcId'];

    // Tarefas reais do funcionário
    $stmt = $pdo->prepare("SELECT id, actividade, objectivos, prazo_execucao, estado, bloqueada FROM tarefas WHERE id_funcionario = :id ORDER BY prazo_execucao ASC");
    $stmt->execute(['id' => $idFunc]);
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total      = count($tarefas);
    $emCurso    = count(array_filter($tarefas, fn($t) => $t['estado'] === 'Em curso'));
    $concluidas = count(array_filter($tarefas, fn($t) => $t['estado'] === 'Concluída'));
    $pendentes  = count(array_filter($tarefas, fn($t) => !in_array($t['estado'], ['Em curso', 'Concluída', 'Cancelada'])));

    // Tarefas a menos de 7 dias do prazo
    $hoje   = new DateTime();
    $urgentes = array_filter($tarefas, function($t) use ($hoje) {
        if ($t['estado'] === 'Concluída' || $t['estado'] === 'Cancelada') return false;
        $diff = $hoje->diff(new DateTime($t['prazo_execucao']))->days;
        return $diff <= 7 && $hoje <= new DateTime($t['prazo_execucao']);
    });
    $numUrgentes = count($urgentes);

    function badgeEstado($estado) {
        $mapa = ['Em curso' => 'bg-warning text-dark', 'Concluída' => 'bg-success', 'Cancelada' => 'bg-danger', 'Autorizada' => 'bg-primary'];
        return '<span class="badge ' . ($mapa[$estado] ?? 'bg-secondary') . '">' . htmlspecialchars($estado) . '</span>';
    }
    function prazoRestante($prazo) {
        $diff = (new DateTime())->diff(new DateTime($prazo));
        if ($diff->invert) return '<span class="prazo-expirado">Expirado</span>';
        if ($diff->days <= 7) return '<span class="prazo-urgente">' . $diff->days . ' dias</span>';
        return '<span class="prazo-normal">' . date('d/m/Y', strtotime($prazo)) . '</span>';
    }
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
    <body class="pagina-com-menu">

        <aside class="menu-lateral">
            <div class="logotipo">
                <img src="../imagens/imagem.png" alt="Logo">
                <h2>SIGATCIUP</h2>
                <small>Área do Funcionário</small>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Início</a>
                <a href="#" class="link-menu"><i class="bi bi-check2-square"></i> Minhas Tarefas</a>
                <a href="#" class="link-menu"><i class="bi bi-person-circle"></i> Perfil</a>
                <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </nav>
        </aside>

        <main class="area-principal">

            <div class="cabecalho-pagina revelar visivel">
                <div>
                    <h1>Olá, <?php echo $nome; ?> <i class="bi bi-hand-wave" style="font-size:1.1rem"></i></h1>
                    <p><?php echo $cargo; ?> · Dep. <?php echo $dept; ?> · <?php echo date('d \d\e F \d\e Y'); ?></p>
                </div>
                <button class="sino-notificacoes <?php echo $numUrgentes > 0 ? 'com-alerta' : ''; ?>" title="Tarefas urgentes">
                    <i class="bi bi-bell<?php echo $numUrgentes > 0 ? '-fill' : ''; ?>"></i>
                    <?php if ($numUrgentes > 0): ?>
                        <span class="contador-sino"><?php echo $numUrgentes; ?></span>
                    <?php endif; ?>
                </button>
            </div>

            <?php if ($numUrgentes > 0): ?>
            <div class="alerta-pendentes revelar visivel">
                <i class="bi bi-alarm me-2"></i>
                <strong><?php echo $numUrgentes; ?> tarefa(s)</strong> com prazo a expirar em menos de 7 dias!
            </div>
            <?php endif; ?>

            <div class="grelha-estatisticas">
                <article class="cartao-stat revelar atraso-1">
                    <h3><i class="bi bi-check2-all me-1"></i> Atribuídas</h3>
                    <span class="numero"><?php echo $total; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-2">
                    <h3><i class="bi bi-hourglass-split me-1"></i> Em Curso</h3>
                    <span class="numero"><?php echo $emCurso; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-3">
                    <h3><i class="bi bi-check-circle me-1"></i> Concluídas</h3>
                    <span class="numero"><?php echo $concluidas; ?></span>
                </article>
                <article class="cartao-stat revelar atraso-4">
                    <h3><i class="bi bi-exclamation-circle me-1"></i> Pendentes</h3>
                    <span class="numero <?php echo $pendentes > 0 ? 'texto-alerta' : ''; ?>"><?php echo $pendentes; ?></span>
                </article>
            </div>

            <section class="secao-tarefas revelar atraso-2">
                <h2 class="titulo-secao-func mb-4"><i class="bi bi-list-task me-2"></i>Minhas Tarefas</h2>

                <?php if (empty($tarefas)): ?>
                <div class="estado-vazio">
                    <i class="bi bi-inbox"></i>
                    <p>Nenhuma tarefa atribuída até ao momento.</p>
                </div>
                <?php else: ?>
                <div class="tabela-envolvente">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Actividade</th>
                                <th>Objectivos</th>
                                <th>Prazo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tarefas as $t): ?>
                            <tr <?php echo $t['bloqueada'] ? 'class="linha-bloqueada"' : ''; ?>>
                                <td>
                                    <?php if ($t['bloqueada']): ?>
                                        <i class="bi bi-lock-fill text-danger me-2" title="Bloqueada por prazo expirado"></i>
                                    <?php endif; ?>
                                    <strong><?php echo htmlspecialchars($t['actividade']); ?></strong>
                                </td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($t['objectivos'] ?? '—'); ?></small></td>
                                <td><?php echo prazoRestante($t['prazo_execucao']); ?></td>
                                <td><?php echo badgeEstado($t['estado']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </section>

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
