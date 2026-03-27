-- migracao.sql — executar uma única vez no PostgreSQL
-- Expandir tarefas com departamento e autorização
ALTER TABLE tarefas
    ADD COLUMN IF NOT EXISTS departamento_origem VARCHAR(50),
    ADD COLUMN IF NOT EXISTS autorizada_por_direcao BOOLEAN DEFAULT FALSE,
    ADD COLUMN IF NOT EXISTS bloqueada BOOLEAN DEFAULT FALSE;

-- Expandir directores com departamento (para Directores de Departamento)
ALTER TABLE directores
    ADD COLUMN IF NOT EXISTS departamento VARCHAR(50),
    ADD COLUMN IF NOT EXISTS nivel VARCHAR(30) DEFAULT 'geral';
-- nivel: 'geral' = Director Geral, 'departamento' = Director de Departamento

-- Atualizar director existente como geral
UPDATE directores SET nivel = 'geral' WHERE nivel IS NULL OR nivel = 'geral';
