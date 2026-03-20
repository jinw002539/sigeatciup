# SIGATCIUP - Sistema de Gestão de Agendamento e Tarefas

## Requisitos Principais do MVP

| ID | Requisito Funcional | Mitigação |
| :--- | :--- | :--- |
| RF01 | Registo de Funcionários | Permite a criação da base de dados técnica e geração automática de códigos de acesso. |
| RF02 | Plano de Atividades | Interface para o Diretor registar atividades, objetivos e os resultados pretendidos. |
| RF03 | Filtro por Período | Capacidade de segmentar a visualização das tarefas por Trimestres (T1, T2) ou Semestres. |
| RF04 | Atribuição Dinâmica | Permite vincular um técnico específico a uma atividade pendente no sistema. |
| RF05 | Controlo de Estados | Monitorização em tempo real se a tarefa está "Pendente", "Em Curso" ou "Concluída". |

## Escolha de Tecnologia

* **Front-End:** HTML5, SCSS (Sassy CSS) e JavaScript (ES6+);
* **Back-End:** PHP estruturado para lógica de servidor e gestão de sessões;
* **Base de Dados:** PostgreSQL (PSQL) para armazenamento persistente;
* **Comunicação:** Fetch API para pedidos assíncronos (AJAX).

## Arquitetura do Sistema

O projeto adota uma combinação de duas abordagens estruturais para garantir a escalabilidade:

1. **Arquitetura MVC (Model-View-Controller):** Utilizada para separar a interface (View) da lógica de negócio (Controller) e do acesso aos dados (Model). Esta separação facilita a manutenção e permite correções na interface sem comprometer as regras de processamento.
2. **Arquitetura em Camadas:** Implementada para isolar o Front-End do Back-End. Esta divisão assegura que as regras de negócio e os dados sensíveis fiquem protegidos no servidor, longe do acesso direto pelo navegador, aumentando a segurança do sistema.

## Justificação Técnica

A escolha destas arquiteturas deve-se à necessidade de organização e segurança. O uso do **MVC** permite que o sistema seja leve e funcional, ideal para os computadores da instituição, enquanto a **Arquitetura em Camadas** mitiga riscos de acesso indevido por parte de utilizadores sem privilégios de administrador.

O uso do **PHP** no lado do servidor (Server-side) é fundamental para o fecho de requisitos de segurança, permitindo filtrar os dados para que cada funcionário visualize apenas as suas tarefas. Graças ao PHP, conseguimos isolar informações sensíveis em modais, garantindo que o carregamento seja seguro.

Para a gestão de formulários e notificações, a combinação de **HTML** e **PHP** oferece uma base robusta, enquanto o **JavaScript** assegura que as atualizações de estado das tarefas (como o cancelamento ou reagendamento) sejam refletidas instantaneamente na interface sem necessidade de recarregar a página.

## Ambiente de Desenvolvimento

O sistema foi desenvolvido e testado em ambiente Linux (Fedora/Kali), utilizando servidores Apache. A ligação à base de dados é efetuada via **PDO (PHP Data Objects)** para garantir proteção contra SQL Injection e assegurar a integridade dos dados do CIUP.
