<?php
session_start();
if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
    header('Location: ../login.php');
    exit();
}
require_once('../data/config.php');
$stmtDepts = $pdo->query("SELECT id, nome_departamento FROM departamentos ORDER BY nome_departamento ASC");
$departamentos = $stmtDepts->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diretores — SIGATCIUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
    <link rel="stylesheet" href="../css/director.css">
    <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
</head>
<body class="pagina-com-menu">

    <div id="notificacao"></div>

    <aside class="menu-lateral">
        <div class="logotipo">
            <img src="../imagens/imagem.png" alt="Logo">
            <h2>SIGATCIUP</h2>
            <small>Direcção Geral</small>
        </div>
        <nav class="navegacao">
            <a href="../dashboards/dashboard_admin.php" class="link-menu"><i class="bi bi-house-door"></i> Home</a>
            <a href="funcionario.php" class="link-menu"><i class="bi bi-people"></i> Funcionários</a>
            <a href="../tarefas/tarefas.php" class="link-menu"><i class="bi bi-check2-square"></i> Tarefas</a>
            <a href="#" class="link-menu ativo"><i class="bi bi-person-badge"></i> Diretores</a>
            <a href="#" class="link-menu"><i class="bi bi-file-earmark-pdf"></i> Relatórios</a>
            <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>

    <main class="area-principal">

        <div class="cabecalho-pagina revelar visivel">
            <div>
                <h1><i class="bi bi-person-badge me-2"></i>Corpo Diretivo</h1>
                <p>Gerir os diretores e responsáveis de departamento.</p>
            </div>
        </div>

        <div class="row g-4">

            <!-- Formulário -->
            <div class="col-xl-4 col-lg-5 revelar atraso-1">
                <div class="cartao-director">
                    <div class="cabecalho-cartao-director">
                        <i class="bi bi-person-plus"></i>
                        <div>
                            <h3>Novo Diretor</h3>
                            <small>Registar responsável de área</small>
                        </div>
                    </div>

                    <form id="formDirector">
                        <input type="hidden" name="acao" value="cadastrar">

                        <div class="grupo-campo-dir">
                            <label><i class="bi bi-person me-1"></i>Nome Completo</label>
                            <input type="text" id="nome" name="nome" class="campo-dir" placeholder="Nome e apelido" required>
                        </div>
                        <div class="grupo-campo-dir">
                            <label><i class="bi bi-card-text me-1"></i>Bilhete de Identidade</label>
                            <input type="text" id="bi" name="bi" class="campo-dir" placeholder="12 dígitos + 1 letra" required>
                        </div>
                        <div class="grupo-campo-dir">
                            <label><i class="bi bi-envelope me-1"></i>E-mail Institucional</label>
                            <input type="email" id="email" name="email" class="campo-dir" placeholder="nome@up.ac.mz" required>
                        </div>
                        <div class="row g-2">
                            <div class="col-6 grupo-campo-dir">
                                <label><i class="bi bi-gender-ambiguous me-1"></i>Género</label>
                                <select name="genero" id="genero" class="campo-dir">
                                    <option value="M">Masculino</option>
                                    <option value="F">Feminino</option>
                                </select>
                            </div>
                            <div class="col-6 grupo-campo-dir">
                                <label><i class="bi bi-diagram-3 me-1"></i>Departamento</label>
                                <select id="id_departamento" name="id_departamento" class="campo-dir" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($departamentos as $d): ?>
                                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['nome_departamento']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="grupo-campo-dir">
                            <label><i class="bi bi-shield-check me-1"></i>Nível de Direção</label>
                            <div class="opcoes-nivel">
                                <label class="opcao-nivel">
                                    <input type="radio" name="nivel" value="departamento" checked>
                                    <span>
                                        <i class="bi bi-person-workspace"></i>
                                        Dep. Departamento
                                    </span>
                                </label>
                                <label class="opcao-nivel">
                                    <input type="radio" name="nivel" value="geral">
                                    <span>
                                        <i class="bi bi-shield-fill-check"></i>
                                        Diretor Geral
                                    </span>
                                </label>
                            </div>
                        </div>

                        <button type="button" class="botao-registar" onclick="revisarDirector()">
                            <i class="bi bi-eye me-2"></i>Revisar e Cadastrar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tabela -->
            <div class="col-xl-8 col-lg-7 revelar atraso-2">
                <div class="cartao-director">
                    <div class="cabecalho-cartao-director">
                        <i class="bi bi-people"></i>
                        <div>
                            <h3>Diretores Registados</h3>
                            <small>Corpo diretivo actual do CIUP</small>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="tabela-cabecalho-dir">
                                <tr>
                                    <th>Nome</th>
                                    <th>Nível</th>
                                    <th>Departamento</th>
                                    <th>Código</th>
                                </tr>
                            </thead>
                            <tbody id="listaDiretoresCorpo">
                                <tr><td colspan="4" class="text-center text-muted py-4">
                                    <span class="spinner-border spinner-border-sm me-2"></span>A carregar...
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal Revisão -->
    <div id="fundo-modal" class="sobreposicao-dir" style="display:none;">
        <div class="caixa-modal-dir">
            <div class="cabecalho-modal-dir">
                <h3><i class="bi bi-clipboard-check me-2"></i>Confirmar Dados</h3>
                <button class="fechar-dir-btn" onclick="fecharModal()"><i class="bi bi-x-lg"></i></button>
            </div>
            <div id="dadosResumo" class="mt-2"></div>
            <div class="acoes-modal-dir">
                <button class="btn-confirmar-dir" onclick="enviarDirector()"><i class="bi bi-check-lg me-2"></i>Confirmar</button>
                <button type="button" class="btn-cancelar-dir" onclick="fecharModal()"><i class="bi bi-x-lg me-2"></i>Cancelar</button>
            </div>
        </div>
    </div>

    <!-- Modal Sucesso -->
    <div id="fundo-sucesso" class="sobreposicao-dir" style="display:none;">
        <div class="caixa-modal-dir caixa-sucesso-dir">
            <div class="icone-sucesso-dir"><i class="bi bi-check-circle-fill"></i></div>
            <h3 class="titulo-sucesso-dir">Diretor Cadastrado!</h3>
            <p class="sub-sucesso-dir">Entregue os dados de acesso ao utilizador.</p>
            <div class="credenciais-dir">
                <div class="linha-cred-dir">
                    <span><i class="bi bi-person me-2"></i>Código de Acesso</span>
                    <strong id="sucesso-codigo"></strong>
                </div>
                <div class="linha-cred-dir">
                    <span><i class="bi bi-key me-2"></i>Senha Provisória</span>
                    <strong id="sucesso-senha"></strong>
                </div>
            </div>
            <button class="btn-confirmar-dir w-100 mt-3" onclick="fecharSucesso()">
                <i class="bi bi-check me-2"></i>Entendido
            </button>
        </div>
    </div>

    <script src="../js/directores.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const observador = new IntersectionObserver(e => {
            e.forEach(el => { if (el.isIntersecting) { el.target.classList.add('visivel'); observador.unobserve(el.target); } });
        }, { threshold: 0.1 });
        document.querySelectorAll('.revelar').forEach(el => observador.observe(el));
    </script>
</body>
</html>
