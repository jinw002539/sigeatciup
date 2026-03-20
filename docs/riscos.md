# Gestão de Riscos (RISKS.md)

| ID | Risco | Probabilidade | Impacto | Prioridade | Mitigação |
| :--- | :--- | :--- | :--- | :--- | :--- |
| R01 | Saída de membro da equipa | Média | Alto | Alta | Documentação rigorosa do código e backups semanais. |
| R02 | Falha na ligação ao PostgreSQL | Baixa | Muito Alto | Alta | Implementação de logs de erro e redundância de base de dados. |
| R03 | Atraso na entrega dos requisitos | Média | Médio | Média | Definição de marcos semanais (Sprints) para validação rápida. |
| R04 | Incompatibilidade de Navegador | Baixa | Baixo | Baixa | Testes contínuos em Chrome, Firefox e Edge durante o dev. |
| R05 | Falha de Segurança (SQL Injection) | Média | Muito Alto | Alta | Uso obrigatório de PDO com Prepared Statements em todo o sistema. |
| R06 | Mudança de Requisitos pelo CIUP | Alta | Médio | Alta | Manter uma comunicação semanal com o Diretor para validação de módulos. |
