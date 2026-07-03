# MonolithPHP – Sistema Fullstack Monolítico

## Sobre o Projeto

MonolithPHP é um sistema web fullstack construído em PHP puro, seguindo o padrão arquitetural MVC (Model-View-Controller). Todo o código – back-end, front-end, rotas, modelos, controladores e visões – reside em um único repositório e é executado como uma única aplicação. Essa abordagem monolítica simplifica o deploy, a manutenção e o desenvolvimento inicial, sendo perfeita para aplicações de pequeno e médio porte, MVPs ou sistemas internos.

**Principais características:**
- Roteamento personalizado (URLs amigáveis)
- Controladores com injeção de dependência básica
- Modelos Active Record ou Data Mapper (escolha sua abstração)
- Sistema de templates com layouts e partials
- Autenticação e controle de acesso (RBAC)
- Validação de dados server-side
- Integração com MySQL/PostgreSQL via PDO
- Assets gerenciados (CSS, JS, imagens)
- Suporte a ambiente de desenvolvimento e produção
- Logs e tratamento de erros centralizado

## Requisitos

- PHP 8.0 ou superior
- Composer (para gerenciar dependências)
- Banco de dados: MySQL 5.7+ ou PostgreSQL 12+
- Servidor Web (Apache/Nginx) com mod_rewrite ou equivalente