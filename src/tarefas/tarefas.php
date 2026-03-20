<?php
session_start();
header('Cache-Control: no-cache, no-store, must-revalidate');
if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
    header('Location: ../login.php');
    exit();
}
$nomeAdmin = htmlspecialchars($_SESSION['adminNome']);
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarefas — SIGATCIUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/base.css">
    <link rel="stylesheet" href="../css/dashboard_admin.css">
    <link rel="stylesheet" href="../css/tarefas.css">
    <link rel="shortcut icon" href="../imagens/image.ico" type="image/x-icon">
</head>
<body class="pagina-com-menu">

    <aside class="menu-lateral">
        <div class="logotipo">
            <img src="../imagens/imagem.png" alt="Logo">
            <h2>SIGATCIUP</h2>
            <small>Sistema de Gestão</small>
        </div>
        <nav class="navegacao">
            <a href="../dashboards/dashboard_admin.php" class="link-menu"><i class="bi bi-house-door"></i> Home</a>
            <a href="../cadastro/funcionario.php" class="link-menu"><i class="bi bi-people"></i> Funcionários</a>
            <a href="#" class="link-menu ativo"><i class="bi bi-check2-square"></i> Tarefas</a>
            <a href="#" class="link-menu"><i class="bi bi-person-badge"></i> Diretores</a>
            <a href="#" class="link-menu"><i class="bi bi-file-earmark-pdf"></i> Relatórios</a>
            <a href="../logout.php" class="link-menu sair"><i class="bi bi-box-arrow-right"></i> Sair</a>
        </nav>
    </aside>

    <main class="area-principal">

        <div class="cabecalho-pagina revelar visivel">
            <h1><i class="bi bi-check2-square me-2"></i>Plano de Actividades</h1>
            <p>Gestão de tarefas do Centro de Informática da UP</p>
        </div>

        <div class="barra-acoes revelar atraso-1">
            <select id="filtro-periodo" class="filtro-select" onchange="carregarTarefas()">
                <option value="todos">Todos os Períodos</option>
                <option value="t1">1º Trimestre (Jan–Mar)</option>
                <option value="t2">2º Trimestre (Abr–Jun)</option>
                <option value="t3">3º Trimestre (Jul–Set)</option>
                <option value="t4">4º Trimestre (Out–Dez)</option>
                <option value="s1">1º Semestre (Jan–Jun)</option>
                <option value="s2">2º Semestre (Jul–Dez)</option>
            </select>
            <button class="btn-nova-tarefa" onclick="abrirModal()">
                <i class="bi bi-plus-lg me-2"></i>Nova Tarefa
            </button>
        </div>

        <div class="tabela-envolvente revelar atraso-2">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Actividade</th>
                        <th>Objectivos</th>
                        <th>Resultado Esperado</th>
                        <th>Prazo</th>
                        <th>Estado</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabelaTarefasCorpo">
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            <span class="spinner-border spinner-border-sm me-2"></span>A carregar...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

    <!-- Modal fora do flex para centrar correctamente -->
    <div id="fundo-modal" class="sobreposicao-tarefa">
        <div class="caixa-tarefa">

            <div class="cabecalho-modal">
                <h3 id="modal-titulo"><i class="bi bi-plus-circle me-2"></i>Nova Tarefa</h3>
                <button class="fechar-modal-btn" onclick="fecharModal()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form id="formTarefa" onsubmit="return false;">
                <input type="hidden" id="tarefa-id">

                <div class="grupo-campo">
                    <label>Actividade</label>
                    <input type="text" id="tarefa-actividade" class="campo-entrada" placeholder="Descreva a actividade" required>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 grupo-campo">
                        <label>Objectivos</label>
                        <textarea id="tarefa-objectivos" class="campo-entrada" rows="3" placeholder="Quais os objectivos?"></textarea>
                    </div>
                    <div class="col-md-6 grupo-campo">
                        <label>Resultado Esperado</label>
                        <textarea id="tarefa-resultado" class="campo-entrada" rows="3" placeholder="O que se espera alcançar?"></textarea>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6 grupo-campo">
                        <label>Prazo de Execução</label>
                        <input type="date" id="tarefa-prazo" class="campo-entrada" required>
                    </div>
                    <div class="col-md-6 grupo-campo">
                        <label>Estado</label>
                        <select id="tarefa-estado" class="campo-entrada">
                            <option value="Por atribuir">Por atribuir</option>
                            <option value="Em curso">Em curso</option>
                            <option value="Concluída">Concluída</option>
                            <option value="Cancelada">Cancelada</option>
                        </select>
                    </div>
                </div>

                <div class="acoes-modal-tarefa">
                    <button class="botao-guardar" onclick="guardarTarefa()">
                        <i class="bi bi-check-lg me-2"></i>Guardar
                    </button>
                    <button type="button" class="botao-cancelar" onclick="fecharModal()">
                        <i class="bi bi-x-lg me-2"></i>Cancelar
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script src="../js/tarefas.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const observador = new IntersectionObserver(entradas => {
            entradas.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visivel'); observador.unobserve(e.target); }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.revelar').forEach(el => observador.observe(el));

        carregarTarefas();
    </script>
</body>
</html>
