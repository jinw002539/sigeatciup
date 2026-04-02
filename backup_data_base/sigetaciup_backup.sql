--
-- PostgreSQL database dump
--

\restrict JhqKVz0SxeMaDobGvx08nk1LVScKXsb1rU2xlfG8Ahtjmk8ZVJefg3iAFtG7lo1

-- Dumped from database version 18.3
-- Dumped by pg_dump version 18.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
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


ALTER SEQUENCE public.departamentos_id_seq OWNER TO kaly;

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
    bi character varying(13) NOT NULL,
    codigo_acesso character varying(12) NOT NULL,
    senha_hash text NOT NULL,
    status boolean DEFAULT true,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    nivel character varying(50) DEFAULT 'geral'::character varying,
    email character varying(150),
    id_departamento integer
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


ALTER SEQUENCE public.directores_id_seq OWNER TO kaly;

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


ALTER SEQUENCE public.funcionarios_id_seq OWNER TO kaly;

--
-- Name: funcionarios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.funcionarios_id_seq OWNED BY public.funcionarios.id;


--
-- Name: periodos_config; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.periodos_config (
    id integer NOT NULL,
    rotulo character varying(20),
    mes_inicio integer,
    mes_fim integer,
    ativo boolean DEFAULT true
);


ALTER TABLE public.periodos_config OWNER TO kaly;

--
-- Name: periodos_config_id_seq; Type: SEQUENCE; Schema: public; Owner: kaly
--

CREATE SEQUENCE public.periodos_config_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.periodos_config_id_seq OWNER TO kaly;

--
-- Name: periodos_config_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.periodos_config_id_seq OWNED BY public.periodos_config.id;


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
    id_departamento character varying(90),
    autorizada_por_direcao boolean DEFAULT false,
    relatorio_entregue boolean DEFAULT false,
    bloqueada boolean DEFAULT false,
    caminho_arquivo text,
    observacoes_falha text,
    id_periodo integer
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


ALTER SEQUENCE public.tarefas_id_seq OWNER TO kaly;

--
-- Name: tarefas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: kaly
--

ALTER SEQUENCE public.tarefas_id_seq OWNED BY public.tarefas.id;


--
-- Name: tokens_acesso; Type: TABLE; Schema: public; Owner: kaly
--

CREATE TABLE public.tokens_acesso (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    data_criacao timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
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


ALTER SEQUENCE public.tokens_acesso_id_seq OWNER TO kaly;

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
-- Name: periodos_config id; Type: DEFAULT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.periodos_config ALTER COLUMN id SET DEFAULT nextval('public.periodos_config_id_seq'::regclass);


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
5	Sistemas
6	Recursos Humanos
\.


--
-- Data for Name: directores; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.directores (id, nome, genero, bi, codigo_acesso, senha_hash, status, criado_em, nivel, email, id_departamento) FROM stdin;
2	Director Geral CIUP	F	199783991089F	00.1234.2026	$2y$10$fW3r7S7ZAnmOq9rSj38S8eN199p7k3r.M10I2hK3M2f2y/G2.S2qW	t	2026-03-25 23:04:33.05585	geral	admin@ciup.up	3
7	Herold Fintch	M	736467340165A	00.4807.2026	$2y$12$zQrYC6ldVcALrtGuEKOoJeJzKNp72xa96/yOQFi9djWmtMLUC4Rsy	t	2026-03-26 11:40:07.96213	departamento	fintchh@gmail.com	5
8	Elliot Alderson	M	562340987633S	00.2548.2026	$2y$12$lbYP8zWZgZ9g7pHuwsMDUeQfMMhAFuPxCuT1kgVIimbJ4A2MaAISu	t	2026-03-26 13:59:01.129012	departamento	aldersone@gmail.com	1
\.


--
-- Data for Name: funcionarios; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.funcionarios (id, nome, genero, bi, codigo_acesso, senha_hash, status, criado_em, email, id_departamento, nivel_acesso) FROM stdin;
1	Paulino Souza	M	725373527363Y	01.8861.2026	$2y$12$SzlJWpvx4DZiinFSXnkNsOBgLBYKLGGkFBjsojZMYUby8vPMkEkgS	t	2026-03-26 11:24:27.51344	paulosz@gmail.com	1	Tecnico
2	Mario Bela	M	836383638464H	01.2821.2026	$2y$12$pfwDkOw/uFr8WjLp3RyLZeb5xGLFi3coodxfHOJ5FJS.dTq85tgDG	t	2026-03-26 11:25:13.810195	belam@gmail.com	1	Tecnico
3	Maulate Fumo	M	946484647464H	01.1860.2026	$2y$12$veZzoNvviXrmiID6pOktROXQjRkOxRegr/BQtxM6C.BcwmlZGBTOa	t	2026-03-26 11:26:11.537541	fumom@gamil.com	5	Tecnico
4	Natasha Miranda	F	835373538735G	01.6092.2026	$2y$12$jOZD17LFuToNwHO39Pl.LOX6zNMYmHgPn2B06gHtlj20/ywleL2.W	t	2026-03-26 11:27:05.579862	mitansha@gmail.com	2	Secretaria
5	Maulate Antonio	M	652871083675W	01.6309.2026	$2y$12$YFDqEDWrZxl.RDOHfXjgqOSihdGte.l5x7GnQZxRgtVZP5VzsOhim	t	2026-03-26 13:49:40.998183	maulatezel@gmail.com	2	tecnico
6	Remigio Bila	M	872356109367D	01.8303.2026	$2y$12$hsZEshk5Xrgr472ZOu92..AXxgijEhmTAVrA/BrGGKd4uvbJ/d.Vi	t	2026-03-27 10:01:57.066315	bilagio@gmail.com	1	tecnico
7	Alex Sergio	M	556588712345H	01.9118.2026	$2y$12$edU3776UC4kXe2x3whHmzO.AC6ElpBxYEiPy916dyqVRbCMb7QgHC	t	2026-03-27 10:57:16.465812	asergio@gmail.com	5	tecnico
8	Alexandre Mucapera	M	123456789012W	01.5376.2026	$2y$12$/w8UQ5ZdFoOTa8rfyy/MFe6IuYVBQ0BZUfKrLtFpVIby2wbaANoCK	t	2026-03-31 02:48:24.289303	alexmuc@gmail.com	5	tecnico
9	Carlos Cossa	M	627363737594K	01.0101.2026	$2y$12$SKHQjbRtOPa9.jYYRtSDUuJHENX6xTYtoIse7bSQTCfhNkHD3ZUGK	t	2026-03-31 03:21:03.346503	ccossa@gmail.com	5	tecnico
10	Marcela Capezula	M	457465847654H	01.8718.2026	$2y$12$ZUVyEs1CtwDgollwp2/ys.FZYrKwBD3Cnrhf48LHRc9YStL.rHDpa	t	2026-03-31 03:23:06.767354	capezula@gmail.com	1	Secretaria
\.


--
-- Data for Name: periodos_config; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.periodos_config (id, rotulo, mes_inicio, mes_fim, ativo) FROM stdin;
1	1º Trimestre	1	3	t
2	2º Trimestre	4	6	t
3	3º Trimestre	7	9	t
4	4º Trimestre	10	12	t
5	1º Semestre	1	6	t
6	2º Semestre	7	12	t
\.


--
-- Data for Name: tarefas; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.tarefas (id, actividade, objectivos, resultado_esperado, prazo_execucao, estado, id_funcionario, criado_em, autorizado_por_direcao, bloqueado, id_departamento, autorizada_por_direcao, relatorio_entregue, bloqueada, caminho_arquivo, observacoes_falha, id_periodo) FROM stdin;
49	Monitorização de Memoria RAM	Troca de RAM em todos os PCs	PCs mais rapidos e eficientes	2026-01-06	Aguardando	\N	2026-03-27 10:58:34.737132	f	f	Redes	t	f	t	\N	\N	\N
50	Monitorização de Memoria RAM	dsvid	bvsjdhbvi	2026-01-06	Aguardando	\N	2026-03-27 10:59:08.160423	f	f	Sistemas	t	f	t	\N	\N	\N
51	troca de RAM	Todos com boa RAM	Todos pcs com novas RAM	2026-08-02	Aguardando	\N	2026-03-31 03:57:56.506649	f	f	Sistemas	t	f	f	\N	\N	\N
\.


--
-- Data for Name: tokens_acesso; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.tokens_acesso (id, email, token, data_criacao, usado) FROM stdin;
1	mucapera8@gmail.com	804cf355ac816abcfede29cacb59c5fe0ac17c5ed3084dee40aed864a1354c0c	2026-03-26 10:33:34.447145	f
\.


--
-- Name: departamentos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.departamentos_id_seq', 7, true);


--
-- Name: directores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.directores_id_seq', 8, true);


--
-- Name: funcionarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.funcionarios_id_seq', 10, true);


--
-- Name: periodos_config_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.periodos_config_id_seq', 12, true);


--
-- Name: tarefas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.tarefas_id_seq', 51, true);


--
-- Name: tokens_acesso_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.tokens_acesso_id_seq', 1, true);


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
-- Name: directores directores_email_key; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores
    ADD CONSTRAINT directores_email_key UNIQUE (email);


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
-- Name: periodos_config periodos_config_pkey; Type: CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.periodos_config
    ADD CONSTRAINT periodos_config_pkey PRIMARY KEY (id);


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
-- Name: directores directores_id_departamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.directores
    ADD CONSTRAINT directores_id_departamento_fkey FOREIGN KEY (id_departamento) REFERENCES public.departamentos(id);


--
-- Name: tarefas fk_periodo; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT fk_periodo FOREIGN KEY (id_periodo) REFERENCES public.periodos_config(id);


--
-- Name: tarefas fk_tarefa_funcionario; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT fk_tarefa_funcionario FOREIGN KEY (id_funcionario) REFERENCES public.funcionarios(id) ON DELETE SET NULL;


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

\unrestrict JhqKVz0SxeMaDobGvx08nk1LVScKXsb1rU2xlfG8Ahtjmk8ZVJefg3iAFtG7lo1

