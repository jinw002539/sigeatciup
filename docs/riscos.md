# Gestão de Riscos

| ID | Risco | Probabilidade | Impacto | Prioridade | Mitigação |
| :--- | :--- | :--- | :--- | :--- | :--- |
| R01 | Indisponibilidade do Dev Único | Baixa | Muito Alto | Alta | Manter o código bem comentado e o repositório GitHub atualizado diariamente para facilitar retomas. |
| R02 | Falha na Conexão PostgreSQL | Média | Muito Alto | Alta | Utilizar Try-Catch no PHP (PDO) para exibir mensagens de erro amigáveis e logs de sistema. |
| R03 | Quebra de Hierarquia (Acesso) | Baixa | Alto | Alta | Testar rigorosamente as variáveis de sessão para impedir que um Funcionário aceda ao painel do Diretor Geral. |
| R04 | Inconsistência de Dados (Memorandos) | Média | Médio | Média | Implementar validações de formulário (JS e PHP) para garantir que nenhum memorando seja registado sem número. |
| R05 | Falha de Backup Local | Média | Muito Alto | Alta | Realizar "git push" frequente para o GitHub e exportar o dump (.sql) da base de dados semanalmente. |
| R06 | Sobrecarga de Requisitos | Alta | Médio | Média | Seguir rigorosamente o Backlog definido e focar primeiro no MVP (Mínimo Produto Viável). |
| R07 | Conflito de Prazos (T1/T2) | Baixa | Médio | Baixa | Criar lógica de base de dados que impeça a sobreposição de datas em trimestres diferentes. |

## Matriz de Prioridade
- **Alta:** Exige ação imediata e monitorização constante.
- **Média:** Exige planeamento de contingência.
- **Baixa:** Observação periódica.
