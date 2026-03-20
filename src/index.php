<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>SIGATCIUP — Centro de Informática UP</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="css/index.css">
        <link rel="stylesheet" href="css/base.css">
        <link rel="shortcut icon" href="imagens/image.ico" type="image/x-icon">
    </head>
    <body>

        <nav class="barra-topo navbar navbar-expand-lg sticky-top">
            <div class="container">
                <a class="marca navbar-brand d-flex align-items-center gap-3" href="#">
                    <img src="imagens/imagem.png" alt="UP Maputo">
                    <span>SIGATCIUP</span>
                </a>
                <a href="login.php" class="btn-portal btn">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Portal
                </a>
            </div>
        </nav>

        <header class="heroi text-white">
            <div class="container py-3">
                <p class="etiqueta-heroi revelar">Centro de Informática · UP Maputo</p>
                <h1 class="titulo-heroi revelar atraso-1">Sistema de Gestão e<br>Agendamento de Tarefas</h1>
                <p class="subtitulo-heroi revelar atraso-2">Acompanhamento em tempo real das metas e desempenho da instituição.</p>
            </div>
        </header>

        <section class="secao-metricas">
            <div class="container">
                <div class="row g-3">
                    <div class="col-6 col-md-3 revelar atraso-1">
                        <article class="cartao-metrica">
                            <span class="metrica-numero text-success">92%</span>
                            <span class="metrica-label">Pulso Anual</span>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 revelar atraso-2">
                        <article class="cartao-metrica">
                            <span class="metrica-numero text-primary">145</span>
                            <span class="metrica-label">Tarefas Concluídas</span>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 revelar atraso-3">
                        <article class="cartao-metrica">
                            <span class="metrica-numero text-warning">08</span>
                            <span class="metrica-label">Frentes Ativas</span>
                        </article>
                    </div>
                    <div class="col-6 col-md-3 revelar atraso-4">
                        <article class="cartao-metrica">
                            <span class="metrica-numero" style="color:var(--verde)">13</span>
                            <span class="metrica-label">Funcionários</span>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="secao-painel container my-5">
            <div class="row g-4 align-items-start">

                <div class="col-lg-4 revelar">
                    <h2 class="titulo-secao">Atividades em Curso</h2>
                    <ul class="lista-tarefas list-unstyled mt-4">
                        <li class="item-tarefa">
                            <div>
                                <span class="depto-label">Redes</span>
                                <strong>Ajuste de Sinal Wireless — Campus Lhanguene</strong>
                            </div>
                            <span class="badge bg-success">Pronto</span>
                        </li>
                        <li class="item-tarefa">
                            <div>
                                <span class="depto-label">Informática</span>
                                <strong>Configuração de Backup de Dados Anuais</strong>
                            </div>
                            <span class="badge bg-warning text-dark">Em Processo</span>
                        </li>
                        <li class="item-tarefa">
                            <div>
                                <span class="depto-label">Secretaria</span>
                                <strong>Triagem e Digitalização de Documentos</strong>
                            </div>
                            <span class="badge bg-primary">Em Fila</span>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-8 revelar atraso-2">
                    <h2 class="titulo-secao">Funcionários Registados</h2>
                    <div class="tabela-envolvente mt-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Departamento</th>
                                    <th>Cargo</th>
                                    <th class="text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody id="corpo-tabela-inicio">
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <span class="spinner-border spinner-border-sm me-2"></span>A carregar...
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </section>

        <footer class="rodape text-center">
            <p class="mb-1 fw-bold text-white">Universidade Pedagógica de Maputo</p>
            <small>Centro de Informática — SIGATCIUP 2026</small>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            const observador = new IntersectionObserver(entradas => {
                entradas.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('visivel');
                        observador.unobserve(e.target);
                    }
                });
            }, { threshold: 0.12 });

            document.querySelectorAll('.revelar').forEach(el => observador.observe(el));

            fetch('acoes_php_BD/listar_funcionarios.php')
                .then(r => r.json())
                .then(res => {
                    const corpo = document.getElementById('corpo-tabela-inicio');
                    if (!res.sucesso || !res.dados.length) {
                        corpo.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-3">Sem dados disponíveis.</td></tr>';
                        return;
                    }
                    corpo.innerHTML = res.dados.map(f => `
                        <tr>
                            <td><i class="bi bi-person-circle me-2 text-muted"></i>${f.nome}</td>
                            <td><span class="badge-depto">${f.departamento}</span></td>
                            <td>${f.cargo}</td>
                            <td class="text-center"><span class="badge bg-success">Ativo</span></td>
                        </tr>`).join('');
                })
                .catch(() => {
                    document.getElementById('corpo-tabela-inicio').innerHTML =
                        '<tr><td colspan="4" class="text-center text-muted py-3">Não foi possível carregar os dados.</td></tr>';
                });
        </script>
    </body>
</html>
