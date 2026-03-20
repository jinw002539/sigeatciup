# Arquitetura do Sistema - SIGATCIUP

## Requisitos Principais do MVP

| ID | Requisito Funcional | Mitigação |
| :--- | :--- | :--- |
| RF01 | Níveis de Acesso | Essencial para isolar as funções de Direção Geral, Departamental, Secretaria e Técnicos. |
| RF02 | Gestão de Memorandos | Garante o registo e rastreabilidade documental de toda a correspondência do CIUP. |
| RF03 | Planeamento Trimestral | Permite a organização temporal das metas e a medição de desempenho por períodos. |
| RF04 | Atribuição de Responsáveis | Garante a integridade da agenda, impedindo que tarefas fiquem órfãs de um técnico. |
| RF05 | Controlo de Estados | Permite a atualização em tempo real do progresso das atividades para consulta da direção. |

## Arquitetura do Sistema

O sistema foi desenhado combinando duas abordagens fundamentais de Engenharia de Software:

1. **Arquitetura MVC (Model-View-Controller):** Utilizada para separar a interface da lógica de negócio e do acesso aos dados. O **Model** gere a persistência no Postgres, a **View** apresenta os painéis (Dashboards) e o **Controller** processa os pedidos do utilizador.
2. **Arquitetura em Camadas:** Separa o Front-End (apresentação) do Back-End (regras de negócio). Esta divisão garante que as validações críticas e os dados sensíveis dos funcionários e memorandos fiquem protegidos no servidor, inacessíveis por manipulação direta no navegador.

## Escolha de Tecnologia

* **Front-End:** HTML5, SCSS, CSS e JavaScript (ES6+);
* **Back-End:** PHP e APACHE2;
* **Base de Dados:** PostgreSQL (PSQL).

## Justificação Técnica

A escolha destas arquiteturas deve-se à necessidade de manter um sistema leve e funcional para o parque informático da instituição, sem abdicar da segurança. O **MVC** facilita a manutenção do código, permitindo corrigir erros na interface sem afetar a integridade dos dados.

Sobre a segurança e fecho de requisitos, o uso do **PHP** no lado do servidor é crucial. Ele funciona como um filtro que assegura que um Funcionário veja apenas a sua agenda, enquanto a Secretaria gere apenas os memorandos. O uso de **Sessions PHP** garante que utilizadores sem privilégios não acedam a funções da Direção Geral.

Para a interatividade, a combinação de **JavaScript** com a **Fetch API** permite que estados de tarefas sejam atualizados instantaneamente. Isto resolve o requisito de agilidade, pois o Diretor recebe feedback visual imediato sobre o progresso dos departamentos sem necessidade de recarregar a página.

## Ambiente de Desenvolvimento

O sistema é desenvolvido em ambiente Linux (Fedora/Ubuntu), utilizando o servidor Apache. A comunicação com o PostgreSQL é feita via **PDO (PHP Data Objects)**, utilizando *Prepared Statements* para mitigar riscos de SQL Injection e garantir a robustez necessária para o ambiente universitário.
