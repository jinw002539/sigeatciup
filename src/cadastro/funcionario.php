<?php
    session_start();
    if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
        header('Location: ../login.php');
        exit();
    }
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
    <body style="display:flex; min-height:100vh;">

        <div id="notificacao"></div>

        <aside class="menu-lateral">
            <div class="logotipo">
                <img src="../imagens/imagem.png" alt="Logo">
                <h2>SIGATCIUP</h2>
            </div>
            <nav class="navegacao">
                <a href="#" class="link-menu ativo" onclick="alternarSessao('cadastro'); return false;">
                    <i class="bi bi-person-plus"></i> Cadastro
                </a>
                <a href="#" class="link-menu" onclick="alternarSessao('lista'); return false;">
                    <i class="bi bi-person-lines-fill"></i> Listar Todos
                </a>
                <a href="../dashboards/dashboard_admin.php" class="link-menu">
                    <i class="bi bi-arrow-left-circle"></i> Voltar
                </a>
            </nav>
        </aside>

        <main class="area-principal d-flex justify-content-center align-items-start pt-5">

            <section id="sessao-cadastro" class="cartao-formulario revelar visivel">
                <h1>Cadastro de Funcionário</h1>
                <form id="formCadastro" onsubmit="return false;">
                    <div class="grupo-campo">
                        <label>Nome Completo</label>
                        <input type="text" id="nome" name="nome" class="campo-entrada" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 grupo-campo">
                            <label>Género</label>
                            <select id="genero" name="genero" class="campo-entrada">
                                <option value="masculino">Masculino</option>
                                <option value="feminino">Feminino</option>
                            </select>
                        </div>
                        <div class="col-md-6 grupo-campo">
                            <label>Número do BI</label>
                            <input type="text" id="bi" name="BI" class="campo-entrada" maxlength="13" placeholder="12 dígitos + 1 letra" required>
                        </div>
                    </div>
                    <div class="grupo-campo">
                        <label>Data de Nascimento</label>
                        <input type="date" id="data_nas" name="data_nas" class="campo-entrada" required>
                    </div>
                    <div class="grupo-campo">
                        <label>Departamento</label>
                        <select id="departamento" name="departamento" class="campo-entrada">
                            <option value="Redes">Redes</option>
                            <option value="Sistemas">Sistemas</option>
                            <option value="Recursos Humanos">Recursos Humanos</option>
                        </select>
                    </div>
                    <div class="grupo-campo">
                        <label>Cargo</label>
                        <input type="text" id="cargo" name="cargo" class="campo-entrada" required>
                    </div>
                    <button type="button" class="botao-primario" onclick="revisarDados()">
                        <i class="bi bi-eye me-2"></i>Revisar e Cadastrar
                    </button>
                </form>
            </section>

            <section id="sessao-lista" style="display:none; width:100%;">
                <div class="cabecalho-pagina mb-4">
                    <h1 style="font-size:1.4rem"><i class="bi bi-people-fill me-2"></i>Funcionários Registados</h1>
                </div>
                <div class="tabela-envolvente">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>BI</th>
                                <th>Departamento</th>
                                <th>Cargo</th>
                                <th>Código</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaCorpo"></tbody>
                    </table>
                </div>
            </section>

        </main>

        <div id="fundo-modal" class="fundo-modal" style="display:none;">
            <div class="caixa-modal">
                <h3><i class="bi bi-clipboard-check me-2"></i>Confirmar Dados</h3>
                <hr>
                <div id="dadosResumo"></div>
                <div class="acoes-modal">
                    <button class="botao-confirmar-modal" onclick="enviarParaBD()">
                        <i class="bi bi-check-lg me-1"></i>Confirmar
                    </button>
                    <button class="botao-cancelar-modal" onclick="fecharModal()">
                        <i class="bi bi-x-lg me-1"></i>Cancelar
                    </button>
                </div>
            </div>
        </div>

        <script src="../js/funcionario.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
