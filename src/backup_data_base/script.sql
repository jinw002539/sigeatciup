-- 1. LIMPEZA DE DADOS ANTIGOS (Cuidado: apaga todos os funcionários e tarefas atuais)
TRUNCATE TABLE public.tarefas CASCADE;
TRUNCATE TABLE public.funcionarios CASCADE;

-- 2. ESTRUTURA DE DEPARTAMENTOS
CREATE TABLE IF NOT EXISTS public.departamentos (
    id SERIAL PRIMARY KEY,
    nome_departamento VARCHAR(100) NOT NULL UNIQUE
);

-- Inserção dos departamentos conforme solicitado
INSERT INTO public.departamentos (nome_departamento)
VALUES ('Redes'), ('Sistemas'), ('Recursos Humanos'), ('Administrativo')
ON CONFLICT (nome_departamento) DO NOTHING;

-- 3. ESTRUTURA DE FUNCIONÁRIOS (Ajustada com Email e Departamento)
-- Se a tabela já existir, o script apenas adiciona as colunas que faltam
ALTER TABLE public.funcionarios ADD COLUMN IF NOT EXISTS email VARCHAR(150) UNIQUE;
ALTER TABLE public.funcionarios ADD COLUMN IF NOT EXISTS id_departamento INTEGER REFERENCES public.departamentos(id);
ALTER TABLE public.funcionarios ADD COLUMN IF NOT EXISTS nivel_acesso VARCHAR(20) DEFAULT 'Tecnico'; -- 'Tecnico' ou 'Secretaria'
ALTER TABLE public.funcionarios ADD COLUMN IF NOT EXISTS status BOOLEAN DEFAULT TRUE;

-- 4. CONTROLO DE SEGURANÇA (Tokens para definição de senha via E-mail)
CREATE TABLE IF NOT EXISTS public.tokens_acesso (
    id SERIAL PRIMARY KEY,
    email VARCHAR(150) NOT NULL,
    token VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    usado BOOLEAN DEFAULT FALSE
);

-- 5. ESTRUTURA DE TAREFAS (Organizada com relações e estados)
-- Aqui garantimos que a tarefa pertence a um departamento e a um funcionário específico
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS id_departamento INTEGER REFERENCES public.departamentos(id);
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS autorizada_por_direcao BOOLEAN DEFAULT FALSE;
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS relatorio_entregue BOOLEAN DEFAULT FALSE;
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS bloqueada BOOLEAN DEFAULT FALSE;
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS caminho_arquivo TEXT;
ALTER TABLE public.tarefas ADD COLUMN IF NOT EXISTS observacoes_falha TEXT;

-- Nota: O campo 'id_funcionario' já deve existir na tua tabela.
-- Caso queiras garantir a integridade:
ALTER TABLE public.tarefas
ADD CONSTRAINT fk_tarefa_funcionario
FOREIGN KEY (id_funcionario) REFERENCES public.funcionarios(id) ON DELETE SET NULL;
