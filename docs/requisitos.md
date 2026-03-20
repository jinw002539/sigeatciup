# Requisitos Funcionais (RF)

| ID | Nome do Requisito | Requisito |
| :--- | :--- | :--- |
| RF01 | Níveis de Acesso | O sistema deve distinguir permissões entre Diretor Geral, Diretores de Departamento, Secretaria e Funcionários. |
| RF02 | Gestão de Memorandos | A Secretaria deve conseguir registar, editar e listar memorandos institucionais com data e número de protocolo. |
| RF03 | Planeamento por Departamento | O sistema deve permitir que cada Diretor de Departamento gira o seu próprio plano de atividades trimestral. |
| RF04 | Atribuição de Responsáveis | O Diretor deve vincular obrigatoriamente um funcionário a uma tarefa para que o prazo comece a contar. |
| RF05 | Filtro de Períodos | O sistema deve permitir filtrar todas as atividades e documentos por 1º Trimestre, 2º Trimestre ou 1º Semestre. |
| RF06 | Atualização de Progresso | O funcionário deve conseguir alterar o estado da tarefa para "Em curso" ou "Concluída", enviando uma notificação ao Diretor. |
| RF07 | Repositório de Resultados | O sistema deve permitir o upload de evidências (ficheiros) que comprovem a conclusão do "Resultado Esperado". |

# Requisitos Não Funcionais (RNF)

| ID | Nome do Requisito | Requisito |
| :--- | :--- | :--- |
| RNF01 | Segurança de Perfil | O sistema deve utilizar variáveis de sessão para garantir que um funcionário nunca aceda ao painel da Direção Geral. |
| RNF02 | Integridade Multitenant | A base de dados PostgreSQL deve isolar os dados de cada departamento para evitar fugas de informação interna. |
| RNF03 | Performance de Pesquisa | A pesquisa por memorandos ou tarefas deve retornar resultados em menos de 2 segundos utilizando índices no Postgres. |
| RNF04 | Interface Unificada | O sistema deve utilizar uma folha de estilos SCSS global, mantendo a identidade visual do CIUP em todos os módulos. |
| RNF05 | Robustez de Sessão | O sistema deve encerrar a sessão automaticamente após 30 minutos de inatividade para proteção dos dados no servidor. |
