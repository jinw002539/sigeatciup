--
-- PostgreSQL database dump
--

\restrict 9hPLrbeaRwg9kl7DN2yGglh8SZCNelxTOhEan4gtKQYuv7HyhpID24InvPmFDB4

-- Dumped from database version 18.1
-- Dumped by pg_dump version 18.1

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
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP
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
    data_nascimento date,
    departamento character varying(50),
    cargo character varying(50),
    codigo_acesso character varying(15) NOT NULL,
    senha_hash text NOT NULL,
    status boolean DEFAULT true,
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP
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
    criado_em timestamp without time zone DEFAULT CURRENT_TIMESTAMP
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
-- Data for Name: directores; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.directores (id, nome, genero, data_nascimento, bi, cargo, codigo_acesso, senha_hash, status, criado_em) FROM stdin;
1	Claudia Jovo	F	\N	199783991089F	Director Geral CIUP	00.1234.2026	drgeral	t	2026-03-11 23:02:02.245567
\.


--
-- Data for Name: funcionarios; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.funcionarios (id, nome, genero, bi, data_nascimento, departamento, cargo, codigo_acesso, senha_hash, status, criado_em) FROM stdin;
1	Herold Fintch	M	778985678754K	1998-09-07	Sistemas	Desenvolvedor	01.4659.2026	01.4659.2026	t	2026-03-12 21:34:17.714367
2	Carlos Barbosa	M	441982674987D	1977-02-10	Redes	Instalador	01.5070.2026	01.5070.2026	t	2026-03-13 18:52:30.81535
3	Natasha Miranda	F	123456780987F	1989-09-08	Recursos Humanos	Secretaria	01.1952.2026	01.1952.2026	t	2026-03-13 20:02:07.176117
4	Miguel Souza	M	776678765443A	1987-06-29	Redes	Intalador LAN	01.7485.2026	01.7485.2026	t	2026-03-13 20:57:49.120077
5	Johnny Reese	M	725272626282G	1977-05-12	Sistemas	Analista 	01.6445.2026	01.6445.2026	t	2026-03-14 16:41:06.418685
6	Gerson Tovele	M	177253835733I	2002-01-03	Sistemas	Analista de sistemas	01.5985.2026	01.5985.2026	t	2026-03-19 08:37:56.792939
\.


--
-- Data for Name: tarefas; Type: TABLE DATA; Schema: public; Owner: kaly
--

COPY public.tarefas (id, actividade, objectivos, resultado_esperado, prazo_execucao, estado, id_funcionario, criado_em) FROM stdin;
1	Inventário Geral de Hardware	Levantar todos os equipamentos activos e obsoletos	Relatório detalhado com números de série e estado	2026-01-30	Pendente	\N	2026-03-19 13:15:36.867908
2	Actualização de Servidores	Aplicar patches de segurança críticos em Windows/Linux	Servidores protegidos e sem vulnerabilidades	2026-02-05	Pendente	\N	2026-03-19 13:15:36.867908
3	Revisão da Infraestrutura de Rede	Identificar pontos de rede danificados nos laboratórios	Substituição de conectores e organização de racks	2026-02-15	Pendente	\N	2026-03-19 13:15:36.867908
4	Configuração de Backups Externos	Garantir a redundância dos dados do SIGATCIUP	Backup semanal configurado em nuvem	2026-01-20	Pendente	\N	2026-03-19 13:15:36.867908
5	Formação em Cibersegurança	Instruir funcionários sobre Phishing e senhas	Redução de incidentes por erro humano	2026-03-10	Pendente	\N	2026-03-19 13:15:36.867908
6	Limpeza Física de Computadores	Remover poeira e trocar pasta térmica (Lab 1)	Aumento da vida útil dos equipamentos	2026-03-05	Pendente	\N	2026-03-19 13:15:36.867908
7	Auditoria de Licenças	Verificar activação legal de SO e Softwares	Lista de softwares para renovação	2026-02-28	Pendente	\N	2026-03-19 13:15:36.867908
8	Optimização do PostgreSQL	Criar índices e limpar logs antigos	Consultas ao sistema 30% mais rápidas	2026-03-20	Pendente	\N	2026-03-19 13:15:36.867908
9	Monitorização de Tráfego	Visualizar consumo de banda larga em tempo real	Gráficos de consumo disponíveis no dashboard	2026-01-15	Pendente	\N	2026-03-19 13:15:36.867908
10	Migração de Emails	Padronizar contas institucionais de novos docentes	50 novos emails criados e configurados	2026-03-31	Pendente	\N	2026-03-19 13:15:36.867908
11	Desenvolvimento do Módulo IA	Criar leitura automática de relatórios (RAG)	IA capaz de responder sobre custos e prazos	2026-05-15	Pendente	\N	2026-03-19 13:15:36.95793
12	Instalação de Wi-Fi (Piso 2)	Eliminar zonas mortas no bloco administrativo	Cobertura de sinal estável em 100% da área	2026-04-10	Pendente	\N	2026-03-19 13:15:36.95793
13	Pentest no Portal	Encontrar falhas de segurança no sistema de login	Relatório técnico com correcções aplicadas	2026-06-05	Pendente	\N	2026-03-19 13:15:36.95793
14	Reestruturação do Active Directory	Organizar permissões por departamentos	Acesso restrito a pastas sensíveis	2026-04-25	Pendente	\N	2026-03-19 13:15:36.95793
15	Upgrade de RAM nos Servidores	Suportar aumento de utilizadores simultâneos	Fim dos travamentos em horários de pico	2026-05-20	Pendente	\N	2026-03-19 13:15:36.95793
16	Implementação de Helpdesk	Organizar pedidos de reparação via tickets	Tempo de resposta reduzido para < 4 horas	2026-06-15	Pendente	\N	2026-03-19 13:15:36.95793
17	Padronização de Imagens SO	Criar imagem padrão com softwares base	Formatação de PC em menos de 15 minutos	2026-05-30	Pendente	\N	2026-03-19 13:15:36.95793
18	Revisão de No-Breaks	Garantir autonomia do Data Center	Baterias viciadas substituídas	2026-04-05	Pendente	\N	2026-03-19 13:15:36.95793
19	Documentação Técnica SIGATCIUP	Mapear estrutura do código e base de dados	Manual técnico em PDF disponível	2026-06-30	Pendente	\N	2026-03-19 13:15:36.95793
20	Workshop Drive/Teams	Ensinar uso de ferramentas colaborativas	Redução do uso de papel e anexos pesados	2026-05-10	Pendente	\N	2026-03-19 13:15:36.95793
\.


--
-- Name: directores_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.directores_id_seq', 1, true);


--
-- Name: funcionarios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.funcionarios_id_seq', 6, true);


--
-- Name: tarefas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: kaly
--

SELECT pg_catalog.setval('public.tarefas_id_seq', 20, true);


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
-- Name: tarefas tarefas_id_funcionario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: kaly
--

ALTER TABLE ONLY public.tarefas
    ADD CONSTRAINT tarefas_id_funcionario_fkey FOREIGN KEY (id_funcionario) REFERENCES public.funcionarios(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict 9hPLrbeaRwg9kl7DN2yGglh8SZCNelxTOhEan4gtKQYuv7HyhpID24InvPmFDB4

