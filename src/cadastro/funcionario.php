<?php
session_start();
$ehAdmin   = isset($_SESSION['adminLog']) && $_SESSION['adminLog'] === true;
$ehDirDept = isset($_SESSION['dirLog'])   && ($_SESSION['dirNivel'] ?? '') === 'departamento';

if (!$ehAdmin && !$ehDirDept) {
    header('Location: ../login.php');
    exit();
}

require_once('../data/config.php');
$stmtDepts = $pdo->query("SELECT id, nome_departamento FROM departamentos ORDER BY nome_departamento ASC");
$departamentos = $stmtDepts->fetchAll(PDO::FETCH_ASSOC);

$dashboardLink = $ehAdmin ? '../dashboards/dashboard_admin.php' : '../dashboards/dashboard_director_dept.php';
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários — SIGATCIUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
    <link rel="stylesheet" href="../css/funcionario.css">
    <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
</head>
<body class="pagina-com-menu">

    <div id="notificacao"></div>

    <aside class="menu-lateral">
        <div class="logotipo">
            <img src="../imagens/imagem.png" alt="Logo">
            <h2>SIGATCIUP</h2>
            <small><?php echo $ehAdmin ? 'Direcção Geral' : 'Dep. ' . htmlspecialchars($_SESSION['dirDeptNome'] ?? ''); ?></small>
        </div>
        <nav class="navegacao">
            <a href="<?php echo $dashboardLink; ?>" class="link-menu"><i class="bi bi-house-door"></i> Home</a>
            <a href="#" class="link-menu ativo"><i class="bi bi-people"></i> Funcionários</a>
            <a href="../tarefas/tarefas.php" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas</a>
            <?php if ($ehAdmin): ?>
            <a href="director.php" class="link-menu"><i class="bi bi-person-badge"></i> Diretores</a>
            <?php endif; ?>
            <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>

    <main class="area-principal">

        <div class="cabecalho-pagina revelar visivel">
            <div>
                <h1><i class="bi bi-people me-2"></i>Gestão de Funcionários</h1>
                <p>Registe e controle os utilizadores do sistema.</p>
            </div>
            <div class="acoes-topo">
                <button class="botao-aba ativa" id="btn-cadastro" onclick="alternarSessao('cadastro')">
                    <i class="bi bi-person-plus me-2"></i>Novo Registo
                </button>
                <button class="botao-aba" id="btn-lista" onclick="alternarSessao('lista')">
                    <i class="bi bi-table me-2"></i>Listagem
                </button>
            </div>
        </div>

        <section id="sessao-cadastro" class="cartao-conteudo revelar visivel">
            <form id="formCadastro">
                <div class="grade-formulario">
                    <div class="grupo-campo">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" id="nome" class="campo-entrada" placeholder="Ex: João Silva" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Género</label>
                        <select name="genero" id="genero" class="campo-entrada">
                            <option value="M">Masculino</option>
                            <option value="F">Feminino</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label>Nº do BI</label>
                        <input type="text" name="bi" id="bi" class="campo-entrada" placeholder="000000000000A" required>
                    </div>
                    <div class="grupo-campo">
                        <label>E-mail Institucional</label>
                        <input type="email" name="email" id="email" class="campo-entrada" placeholder="joao@up.ac.mz" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Departamento</label>
                        <?php if ($ehAdmin): ?>
                            <select name="id_departamento" id="id_departamento" class="campo-entrada" required>
                                <option value="">Selecione...</option>
                                <?php foreach ($departamentos as $dept): ?>
                                    <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['nome_departamento']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <input type="text" class="campo-entrada" value="<?= htmlspecialchars($_SESSION['dirDeptNome'] ?? '') ?>" readonly>
                            <input type="hidden" name="id_departamento" id="id_departamento" value="<?= $_SESSION['dirDeptId'] ?? '' ?>">
                        <?php endif; ?>
                    </div>
                    <?php if ($ehDirDept): ?>
                    <div class="grupo-campo">
                        <label>Cargo</label>
                        <input type="text" name="cargo" id="cargo" class="campo-entrada" placeholder="Ex: Técnico de Redes" required>
                    </div>
                    <?php else: ?>
                    <input type="hidden" name="cargo" value="">
                    <?php endif; ?>
                    <div class="grupo-campo">
                        <label>Nível de Acesso</label>
                        <select name="nivel_acesso" id="nivel_acesso" class="campo-entrada">
                            <option value="tecnico">Técnico / Funcionário</option>
                            <option value="Secretaria">Secretaria</option>
                        </select>
                    </div>
                </div>
                <div class="rodape-form">
                    <button type="button" class="botao-prever" onclick="revisarDados()">
                        <i class="bi bi-eye me-2"></i>Revisar e Guardar
                    </button>
                </div>
            </form>
        </section>

        <section id="sessao-lista" class="cartao-conteudo revelar" style="display:none;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="tabela-cabecalho">
                        <tr>
                            <th>Nome</th>
                            <th>BI</th>
                            <th>Departamento</th>
                            <th>Nível</th>
                            <th>Código</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tabelaCorpo">
                        <tr><td colspan="6" class="text-center text-muted py-4">
                            <span class="spinner-border spinner-border-sm me-2"></span>A carregar...
                        </td></tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <!-- Modal Revisão — usa fundo-modal + display:flex como o original -->
    <div id="fundo-modal" class="sobreposicao-modal">
        <div class="caixa-modal-func">
            <div class="cabecalho-modal-func">
                <h3><i class="bi bi-clipboard-check me-2"></i>Confirmar Dados</h3>
                <button class="fechar-modal-btn" onclick="fecharModal()"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="dadosResumo"></div>
            <div class="acoes-modal-func">
                <button class="btn-confirmar-func" onclick="enviarParaBD()">
                    <i class="bi bi-check-lg me-2"></i>Confirmar
                </button>
                <button type="button" class="btn-cancelar-func" onclick="fecharModal()">
                    <i class="bi bi-x-lg me-2"></i>Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Sucesso -->
    <div id="fundo-sucesso" class="sobreposicao-modal">
        <div class="caixa-modal-func caixa-sucesso-func">
            <div class="icone-sucesso-func"><i class="bi bi-check-circle-fill"></i></div>
            <h3 class="titulo-sucesso-func">Funcionário Cadastrado!</h3>
            <p class="subtitulo-sucesso-func">Guarde os dados de acesso abaixo.</p>
            <div class="cartao-credenciais-func">
                <div class="linha-credencial-func">
                    <span><i class="bi bi-person me-2"></i>Código de Acesso</span>
                    <strong id="sucesso-codigo"></strong>
                </div>
                <div class="linha-credencial-func">
                    <span><i class="bi bi-key me-2"></i>Senha Provisória</span>
                    <strong id="sucesso-senha"></strong>
                </div>
            </div>
            <p class="nota-sucesso-func"><i class="bi bi-info-circle me-1"></i>Um link de ativação será enviado para o e-mail registado.</p>
            <button class="btn-confirmar-func w-100 mt-3" onclick="fecharSucesso()">
                <i class="bi bi-check me-2"></i>Entendido
            </button>
        </div>
    </div>

    <script src="../js/funcionario.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const observador = new IntersectionObserver(e => {
            e.forEach(el => { if (el.isIntersecting) { el.target.classList.add('visivel'); observador.unobserve(el.target); } });
        }, { threshold: 0.1 });
        document.querySelectorAll('.revelar').forEach(el => observador.observe(el));
    </script>
</body>
</html>
