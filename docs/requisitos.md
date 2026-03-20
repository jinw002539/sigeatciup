# Requisitos Funcionais (RF)

| ID | Nome do Requisito | Requisito |
| :--- | :--- | :--- |
| RF1 | Registo de Funcionários | O sistema deve permitir o registo de funcionários através de nome, BI, departamento e cargo, garantindo que o BI não seja duplicado e gerando credenciais automáticas. |
| RF2 | Autenticação e Sessão | O sistema deve validar o acesso de utilizadores e proteger rotas administrativas, garantindo que o Diretor e Técnicos acedam apenas às áreas permitidas. |
| RF3 | Plano de Atividades | O sistema deve permitir que o Diretor registe atividades detalhando a descrição, os objetivos e o resultado esperado para cada tarefa. |
| RF4 | Agendamento Trimestral | O sistema deve permitir a definição de prazos de execução e a filtragem das atividades por 1º Trimestre, 2º Trimestre ou 1º Semestre. |
| RF5 | Atribuição Dinâmica | O sistema deve permitir ao Diretor selecionar um funcionário da base de dados e vinculá-lo a uma tarefa específica que esteja no estado "Por Atribuir". |
| RF6 | Atualização de Estados | O sistema deve permitir a alteração do estado da tarefa (Pendente, Em Curso, Concluída) e refletir essas mudanças instantaneamente na interface. |

# Requisitos Não Funcionais (RNF)

| ID | Nome do Requisito | Requisito |
| :--- | :--- | :--- |
| RNF1 | Segurança de Acesso | O sistema deve utilizar sessões PHP e controlo de cache para impedir que dados sensíveis sejam visualizados após o logout. |
| RNF2 | Tempo de Resposta | A filtragem de tarefas e o carregamento da lista de funcionários devem ser processados via Fetch API em menos de 3 segundos. |
| RNF3 | Integridade de Dados | O sistema deve utilizar PDO e Prepared Statements para mitigar ataques de SQL Injection e garantir a persistência correta no PostgreSQL. |
| RNF4 | Interface Responsiva | O layout desenvolvido em SCSS deve ser adaptável, garantindo a usabilidade tanto em computadores desktop como em dispositivos móveis. |
| RNF5 | Persistência de Dados | Em caso de falha na rede, o sistema deve garantir que o registo na base de dados PostgreSQL seja concluído ou revertido integralmente (Atomicidade). |
