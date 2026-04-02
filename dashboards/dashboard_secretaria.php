<?php
    session_start();
    header('Cache-Control: no-cache, no-store, must-revalidate');
    if (!isset($_SESSION['funcLog']) || ($_SESSION['funcNivel'] ?? '') !== 'Secretaria') {
        header('Location: ../login.php');
        exit();
    }
    require_once('../data/config.php');
    $nome  = htmlspecialchars($_SESSION['funcNome']);
    $idFunc = (int)$_SESSION['funcId'];

    $stmt = $pdo->prepare("SELECT t.id, t.actividade, t.objectivos, t.prazo_execucao, t.estado, t.id_departamento, t.bloqueada FROM tarefas t WHERE t.id_funcionario = :id ORDER BY t.prazo_execucao ASC");
    $stmt->execute(['id' => $idFunc]);
    $tarefas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $hoje = new DateTime();
    $numUrgentes = count(array_filter($tarefas, function($t) use ($hoje) {
        if (in_array($t['estado'], ['Concluída', 'Cancelada'])) return false;
        $diff = $hoje->diff(new DateTime($t['prazo_execucao']))->days;
        return $diff <= 7 && $hoje <= new DateTime($t['prazo_execucao']);
    }));

    function badgeEstado($e) {
        $m = ['Em curso' => 'bg-warning text-dark', 'Concluída' => 'bg-success', 'Cancelada' => 'bg-danger', 'Autorizada' => 'bg-primary'];
        return '<span class="badge ' . ($m[$e] ?? 'bg-secondary') . '">' . htmlspecialchars($e) . '</span>';
    }
?>
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Secretaria — SIGATCIUP</title>
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
                <small>Secretaria</small>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo"><i class="bi bi-house-door"></i> Início</a>
                <a href="#" class="link-menu"><i class="bi bi-envelope"></i> Memorandos</a>
                <a href="#" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas Delegadas</a>
                <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
            </nav>
        </aside>

        <main class="area-principal">

            <div class="cabecalho-pagina revelar visivel">
                <div>
                    <h1>Olá, <?php echo $nome; ?></h1>
                    <p>Secretaria · <?php echo date('d \d\e F \d\e Y'); ?></p>
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
                <strong><?php echo $numUrgentes; ?> tarefa(s)</strong> com prazo a expirar em breve.
            </div>
            <?php endif; ?>

            <section class="secao-tarefas revelar atraso-1">
                <h2 class="titulo-secao-func mb-4"><i class="bi bi-list-check me-2"></i>Tarefas Delegadas</h2>
                <?php if (empty($tarefas)): ?>
                <div class="estado-vazio">
                    <i class="bi bi-inbox"></i>
                    <p>Nenhuma tarefa delegada até ao momento.</p>
                </div>
                <?php else: ?>
                <div class="tabela-envolvente">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Actividade</th>
                                <th>Departamento</th>
                                <th>Prazo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tarefas as $t): ?>
                            <tr <?php echo $t['bloqueada'] ? 'class="linha-bloqueada"' : ''; ?>>
                                <td>
                                    <?php if ($t['bloqueada']): ?><i class="bi bi-lock-fill text-danger me-2"></i><?php endif; ?>
                                    <strong><?php echo htmlspecialchars($t['actividade']); ?></strong>
                                </td>
                                <td><span class="badge-depto"><?php echo htmlspecialchars($t['departamento_origem'] ?? '—'); ?></span></td>
                                <td><?php echo date('d/m/Y', strtotime($t['prazo_execucao'])); ?></td>
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
