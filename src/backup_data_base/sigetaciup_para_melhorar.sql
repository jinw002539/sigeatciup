--
-- PostgreSQL database dump
--

\restrict yVbk2Q7FobQPiACm7IVve0UzgsWZKSY9yNQfcEcvaoQA44JT1Z0qpH7dk9T1FlF

-- Dumped from database version 14.22 (Ubuntu 14.22-0ubuntu0.22.04.1)
-- Dumped by pg_dump version 14.22 (Ubuntu 14.22-0ubuntu0.22.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: departamentos; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.departamentos (
    id integer NOT NULL,
    nome_departamento character varying(100) NOT NULL
);


ALTER TABLE public.departamentos OWNER TO kaly;

--
-- Name: departamentos_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.departamentos_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.departamentos_id_seq OWNER TO kaly;

--
-- Name: departamentos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.departamentos_id_seq OWNED BY public.departamentos.id;


--
-- Name: directores; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.directores (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    genero character(1),
    data_nascimento date,
    bi character varying(13) NOT NULL,
    cargo character varying(100),
    codigo_acesso character varying(12) NOT NULL,
    senha_hash text NOT NULL,
    status boolean DEFAULT true,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel character varying(50) DEFAULT 'geral'::character varying
);


ALTER TABLE public.directores OWNER TO kaly;

--
-- Name: directores_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.directores_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.directores_id_seq OWNER TO kaly;

--
-- Name: directores_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.directores_id_seq OWNED BY public.directores.id;


--
-- Name: funcionarios; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.funcionarios (
    id integer NOT NULL,
    nome character varying(100) NOT NULL,
    genero character(1),
    bi character varying(13) NOT NULL,
    data_nascimento date,
    departamento character varying(50),
    cargo character varying(50),
    codigo_acesso character varying(15) NOT NULL,
    senha_hash text NOT NULL,
    status boolean DEFAULT true,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    email character varying(150),
    id_departamento integer,
    nivel_acesso character varying(50) DEFAULT 'Tecnico'::character varying
);


ALTER TABLE public.funcionarios OWNER TO kaly;

--
-- Name: funcionarios_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.funcionarios_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.funcionarios_id_seq OWNER TO kaly;

--
-- Name: funcionarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.funcionarios_id_seq OWNED BY public.funcionarios.id;


--
-- Name: tarefas; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.tarefas (
    id integer NOT NULL,
    actividade text NOT NULL,
    objectivos text,
    resultado_esperado text,
    prazo_execucao date NOT NULL,
    estado character varying(50) DEFAULT 'Por atribuir'::character varying,
    id_funcionario integer,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    autorizado_por_direcao boolean DEFAULT false,
    bloqueado boolean DEFAULT false,
    id_departamento character varying(90)
);


ALTER TABLE public.tarefas OWNER TO kaly;

--
-- Name: tarefas_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.tarefas_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tarefas_id_seq OWNER TO kaly;

--
-- Name: tarefas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.tarefas_id_seq OWNED BY public.tarefas.id;


--
-- Name: tokens_acesso; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.tokens_acesso (
    id integer NOT NULL,
    email character varying(150) NOT NULL,
    token character varying(255) NOT NULL,
    data_criado timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    usado boolean DEFAULT false
);


ALTER TABLE public.tokens_acesso OWNER TO kaly;

--
-- Name: tokens_acesso_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.tokens_acesso_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tokens_acesso_id_seq OWNER TO kaly;

--
-- Name: tokens_acesso_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.tokens_acesso_id_seq OWNED BY public.tokens_acesso.id;


--
-- Name: departamentos id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.departamentos ALTER COLUMN id SET DEFAULT nextval('public.departamentos_id_seq'::regclass);


--
-- Name: directores id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores ALTER COLUMN id SET DEFAULT nextval('public.directores_id_seq'::regclass);


--
-- Name: funcionarios id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.funcionarios ALTER COLUMN id SET DEFAULT nextval('public.funcionarios_id_seq'::regclass);


--
-- Name: tarefas id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas ALTER COLUMN id SET DEFAULT nextval('public.tarefas_id_seq'::regclass);


--
-- Name: tokens_acesso id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tokens_acesso ALTER COLUMN id SET DEFAULT nextval('public.tokens_acesso_id_seq'::regclass);


--
-- Data for Name: departamentos; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.departamentos (id, nome_departamento) FROM stdin;
1	Redes
2	Secretaria
3	Administrativo
\.


--
-- Data for Name: directores; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.directores (id, nome, genero, data_nascimento, bi, cargo, codigo_acesso, senha_hash, status, criado_em, nivel) FROM stdin;
1	Claudia Jovo	F	\N	199783991089F	Director Geral CIUP	00.1234.2026	drgeral	t	2026-03-11 23:02:02.245567	geral
\.


--
-- Data for Name: funcionarios; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.funcionarios (id, nome, genero, bi, data_nascimento, departamento, cargo, codigo_acesso, senha_hash, status, criado_em, email, id_departamento, nivel_acesso) FROM stdin;
1	Herold Fintch	M	778985678754K	1998-09-07	Sistemas	Desenvolvedor	01.4659.2026	01.4659.2026	t	2026-03-12 21:34:17.714367	\N	\N	Tecnico
2	Carlos Barbosa	M	441982674987D	1977-02-10	Redes	Instalador	01.5070.2026	01.5070.2026	t	2026-03-13 18:52:30.81535	\N	\N	Tecnico
3	Natasha Miranda	F	123456780987F	1989-09-08	Recursos Humanos	Secretaria	01.1952.2026	01.1952.2026	t	2026-03-13 20:02:07.176117	\N	\N	Tecnico
4	Miguel Souza	M	776678765443A	1987-06-29	Redes	Intalador LAN	01.7485.2026	01.7485.2026	t	2026-03-13 20:57:49.120077	\N	\N	Tecnico
5	Johnny Reese	M	725272626282G	1977-05-12	Sistemas	Analista 	01.6445.2026	01.6445.2026	t	2026-03-14 16:41:06.418685	\N	\N	Tecnico
6	Gerson Tovele	M	177253835733I	2002-01-03	Sistemas	Analista de sistemas	01.5985.2026	01.5985.2026	t	2026-03-19 08:37:56.792939	\N	\N	Tecnico
7	Trenton Sunita	F	887256335735K	1987-09-12	Redes	Arquiteto de Redes	01.5484.2026	01.5484.2026	t	2026-03-21 12:55:45.842228	\N	\N	Tecnico
8	Edvania Sambo	F	234567887654G	1988-06-08	Recursos Humanos	Dr de Pessoal	01.0572.2026	01.0572.2026	t	2026-03-23 15:51:12.370286	\N	\N	Tecnico
\.


--
-- Data for Name: tarefas; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.tarefas (id, actividade, objectivos, resultado_esperado, prazo_execucao, estado, id_funcionario, criado_em, autorizado_por_direcao, bloqueado, id_departamento) FROM stdin;
1	Inventário Geral de Hardware	Levantar todos os equipamentos activos e obsoletos	Relatório detalhado com números de série e estado	2026-01-30	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
2	Actualização de Servidores	Aplicar patches de segurança críticos em Windows/Linux	Servidores protegidos e sem vulnerabilidades	2026-02-05	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
3	Revisão da Infraestrutura de Rede	Identificar pontos de rede danificados nos laboratórios	Substituição de conectores e organização de racks	2026-02-15	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
4	Configuração de Backups Externos	Garantir a redundância dos dados do SIGATCIUP	Backup semanal configurado em nuvem	2026-01-20	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
5	Formação em Cibersegurança	Instruir funcionários sobre Phishing e senhas	Redução de incidentes por erro humano	2026-03-10	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
6	Limpeza Física de Computadores	Remover poeira e trocar pasta térmica (Lab 1)	Aumento da vida útil dos equipamentos	2026-03-05	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
8	Optimização do PostgreSQL	Criar índices e limpar logs antigos	Consultas ao sistema 30% mais rápidas	2026-03-20	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
10	Migração de Emails	Padronizar contas institucionais de novos docentes	50 novos emails criados e configurados	2026-03-31	Pendente	\N	2026-03-19 13:15:36.867908	f	f	\N
11	Desenvolvimento do Módulo IA	Criar leitura automática de relatórios (RAG)	IA capaz de responder sobre custos e prazos	2026-05-15	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
12	Instalação de Wi-Fi (Piso 2)	Eliminar zonas mortas no bloco administrativo	Cobertura de sinal estável em 100% da área	2026-04-10	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
13	Pentest no Portal	Encontrar falhas de segurança no sistema de login	Relatório técnico com correcções aplicadas	2026-06-05	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
14	Reestruturação do Active Directory	Organizar permissões por departamentos	Acesso restrito a pastas sensíveis	2026-04-25	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
15	Upgrade de RAM nos Servidores	Suportar aumento de utilizadores simultâneos	Fim dos travamentos em horários de pico	2026-05-20	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
16	Implementação de Helpdesk	Organizar pedidos de reparação via tickets	Tempo de resposta reduzido para < 4 horas	2026-06-15	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
17	Padronização de Imagens SO	Criar imagem padrão com softwares base	Formatação de PC em menos de 15 minutos	2026-05-30	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
18	Revisão de No-Breaks	Garantir autonomia do Data Center	Baterias viciadas substituídas	2026-04-05	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
19	Documentação Técnica SIGATCIUP	Mapear estrutura do código e base de dados	Manual técnico em PDF disponível	2026-06-30	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
20	Workshop Drive/Teams	Ensinar uso de ferramentas colaborativas	Redução do uso de papel e anexos pesados	2026-05-10	Pendente	\N	2026-03-19 13:15:36.95793	f	f	\N
7	Auditoria de Licenças	Verificar activação legal de SO e Softwares	Lista de softwares para renovação	2026-02-28	Por atribuir	\N	2026-03-19 13:15:36.867908	f	f	\N
9	Monitorização de Tráfego	Visualizar consumo de banda larga em tempo real	Gráficos de consumo disponíveis no dashboard	2026-01-15	Cancelada	\N	2026-03-19 13:15:36.867908	f	f	\N
\.


--
-- Data for Name: tokens_acesso; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.tokens_acesso (id, email, token, data_criado, usado) FROM stdin;
\.


--
-- Name: departamentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.departamentos_id_seq', 3, true);


--
-- Name: directores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.directores_id_seq', 1, true);


--
-- Name: funcionarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.funcionarios_id_seq', 8, true);


--
-- Name: tarefas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.tarefas_id_seq', 20, true);


--
-- Name: tokens_acesso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.tokens_acesso_id_seq', 1, false);


--
-- Name: departamentos departamentos_nome_departamento_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.departamentos
    ADD CONSTRAINT departamentos_nome_departamento_key UNIQUE (nome_departamento);


--
-- Name: departamentos departamentos_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.departamentos
    ADD CONSTRAINT departamentos_pkey PRIMARY KEY (id);


--
-- Name: directores directores_bi_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores
    ADD CONSTRAINT directores_bi_key UNIQUE (bi);


--
-- Name: directores directores_codigo_acesso_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores
    ADD CONSTRAINT directores_codigo_acesso_key UNIQUE (codigo_acesso);


--
-- Name: directores directores_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores
    ADD CONSTRAINT directores_pkey PRIMARY KEY (id);


--
-- Name: funcionarios funcionarios_bi_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.funcionarios
    ADD CONSTRAINT funcionarios_bi_key UNIQUE (bi);


--
-- Name: funcionarios funcionarios_codigo_acesso_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.funcionarios
    ADD CONSTRAINT funcionarios_codigo_acesso_key UNIQUE (codigo_acesso);


--
-- Name: funcionarios funcionarios_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.funcionarios
    ADD CONSTRAINT funcionarios_pkey PRIMARY KEY (id);


--
-- Name: tarefas tarefas_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT tarefas_pkey PRIMARY KEY (id);


--
-- Name: tokens_acesso tokens_acesso_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tokens_acesso
    ADD CONSTRAINT tokens_acesso_pkey PRIMARY KEY (id);


--
-- Name: funcionarios funcionarios_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.funcionarios
    ADD CONSTRAINT funcionarios_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id);


--
-- Name: tarefas tarefas_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT tarefas_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES public.departamentos(nome_departamento);


--
-- Name: tarefas tarefas_id_funcionario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT tarefas_id_funcionario_fkey FOREIGN KEY (id_funcionario) REFERENCES public.funcionarios(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict yVbk2Q7FobQPiACm7IVve0UzgsWZKSY9yNQfcEcvaoQA44JT1Z0qpH7dk9T1FlF

