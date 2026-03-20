# Gestão de Riscos (RISKS.md)

| ID | Risco | Probabilidade | Impacto | Prioridade | Mitigação |
| :--- | :--- | :--- | :--- | :--- | :--- |
| R01 | Falha na ligação ao PostgreSQL | Baixa | Muito Alto | Alta | Implementação de logs de erro e redundância de base de dados. |
| R02 | Incompatibilidade de Navegador | Baixa | Baixo | Baixa | Testes contínuos em Chrome, Firefox e Edge durante o dev. |
| R03 | Falha de Segurança (SQL Injection) | Média | Muito Alto | Alta | Uso obrigatório de PDO com Prepared Statements em todo o sistema. |
| R04 | Mudança de Requisitos pelo CIUP | Alta | Médio | Alta | Manter uma comunicação semanal com o Diretor para validação de módulos. |
