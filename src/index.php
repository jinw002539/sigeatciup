<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGEATCIUP — Centro de Informática UP</title>
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
                <span>SIGEATCIUP</span>
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
            <p class="subtitulo-heroi revelar atraso-2">Acompanhamento em tempo real das metas, actividades e desempenho da instituição.</p>
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

    <section class="secao-abas container my-5">

        <!-- Navegação das abas -->
        <div class="abas-navegacao revelar">
            <button class="aba-btn ativa" onclick="mudarAba('sobre')">
                <i class="bi bi-info-circle me-2"></i>Sobre o CIUP
            </button>
            <button class="aba-btn" onclick="mudarAba('departamentos')">
                <i class="bi bi-diagram-3 me-2"></i>Departamentos
            </button>
            <button class="aba-btn" onclick="mudarAba('missao')">
                <i class="bi bi-bullseye me-2"></i>Missão e Visão
            </button>
            <button class="aba-btn" onclick="mudarAba('sistema')">
                <i class="bi bi-grid me-2"></i>O Sistema
            </button>
        </div>

        <!-- Aba: Sobre -->
        <div id="aba-sobre" class="conteudo-aba ativa revelar atraso-1">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao"><i class="bi bi-building"></i></div>
                        <h3>Centro de Informática</h3>
                        <p>O CIUP é o órgão responsável pela gestão de toda a infraestrutura tecnológica da Universidade Pedagógica de Maputo.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao"><i class="bi bi-people"></i></div>
                        <h3>Equipa Técnica</h3>
                        <p>Constituída por profissionais especializados em Redes, Sistemas, Suporte e Administração, prontos para servir toda a comunidade académica.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao"><i class="bi bi-clock-history"></i></div>
                        <h3>Horário de Serviço</h3>
                        <p>Segunda a Sexta-feira, das 07h30 às 16h30. Suporte de emergência disponível para serviços críticos da instituição.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba: Departamentos -->
        <div id="aba-departamentos" class="conteudo-aba revelar atraso-1">
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="cartao-info destaque-redes">
                        <div class="icone-cartao"><i class="bi bi-wifi"></i></div>
                        <h3>Departamento de Redes</h3>
                        <p>Gestão da infraestrutura de rede, wireless, switching e conectividade entre todos os campi da UP Maputo.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="cartao-info destaque-sistemas">
                        <div class="icone-cartao"><i class="bi bi-cpu"></i></div>
                        <h3>Departamento de Sistemas</h3>
                        <p>Desenvolvimento, manutenção e suporte dos sistemas de informação institucionais, servidores e bases de dados.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="cartao-info destaque-secretaria">
                        <div class="icone-cartao"><i class="bi bi-file-earmark-text"></i></div>
                        <h3>Secretaria</h3>
                        <p>Apoio administrativo, gestão de memorandos, correspondência institucional e arquivo documental do CIUP.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="cartao-info destaque-admin">
                        <div class="icone-cartao"><i class="bi bi-shield-check"></i></div>
                        <h3>Administrativo</h3>
                        <p>Coordenação geral, planeamento estratégico e supervisão das actividades de todos os departamentos do CIUP.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba: Missão e Visão -->
        <div id="aba-missao" class="conteudo-aba revelar atraso-1">
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="cartao-info cartao-missao">
                        <div class="icone-cartao"><i class="bi bi-rocket-takeoff"></i></div>
                        <h3>Missão</h3>
                        <p>Prover soluções tecnológicas inovadoras e sustentáveis que suportem os processos académicos e administrativos da Universidade Pedagógica de Maputo, garantindo a segurança, disponibilidade e eficiência dos sistemas de informação.</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="cartao-info cartao-visao">
                        <div class="icone-cartao"><i class="bi bi-eye"></i></div>
                        <h3>Visão</h3>
                        <p>Ser reconhecido como um centro de excelência em tecnologia de informação no ensino superior moçambicano, promovendo a transformação digital e a capacitação contínua da comunidade universitária.</p>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="cartao-info cartao-valores">
                        <div class="icone-cartao"><i class="bi bi-star"></i></div>
                        <h3>Valores</h3>
                        <div class="row g-3 mt-1">
                            <div class="col-6 col-md-3">
                                <div class="valor-item"><i class="bi bi-check2-circle"></i><span>Excelência</span></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="valor-item"><i class="bi bi-shield"></i><span>Integridade</span></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="valor-item"><i class="bi bi-lightbulb"></i><span>Inovação</span></div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="valor-item"><i class="bi bi-people"></i><span>Colaboração</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aba: O Sistema -->
        <div id="aba-sistema" class="conteudo-aba revelar atraso-1">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao texto-azul"><i class="bi bi-check2-square"></i></div>
                        <h3>Gestão de Tarefas</h3>
                        <p>Criação, aprovação e acompanhamento de todas as actividades do CIUP, com fluxo hierárquico de autorização entre directores.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao texto-verde"><i class="bi bi-person-badge"></i></div>
                        <h3>Controlo de Acessos</h3>
                        <p>Sistema de roles com quatro níveis: Director Geral, Director de Departamento, Técnico e Secretaria, cada um com permissões específicas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cartao-info">
                        <div class="icone-cartao texto-laranja"><i class="bi bi-bell"></i></div>
                        <h3>Notificações</h3>
                        <p>Alertas automáticos para prazos próximos, tarefas pendentes de aprovação e novas atribuições de actividades.</p>
                    </div>
                </div>
            </div>
        </div>

    </section>

    <footer class="rodape text-center">
        <p class="mb-1 fw-bold text-white">Universidade Pedagógica de Maputo</p>
        <small>Centro de Informática — SIGEATCIUP 2026</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const observador = new IntersectionObserver(entradas => {
            entradas.forEach(e => {
                if (e.isIntersecting) { e.target.classList.add('visivel'); observador.unobserve(e.target); }
            });
        }, { threshold: 0.12 });
        document.querySelectorAll('.revelar').forEach(el => observador.observe(el));

        function mudarAba(nome) {
            document.querySelectorAll('.conteudo-aba').forEach(a => a.classList.remove('ativa'));
            document.querySelectorAll('.aba-btn').forEach(b => b.classList.remove('ativa'));
            document.getElementById('aba-' + nome).classList.add('ativa');
            event.currentTarget.classList.add('ativa');
        }
    </script>
</body>
</html>
