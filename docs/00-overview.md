# Nivora — visão e âmbito

## Objetivo

O Nivora é uma aplicação web MVC para registar contas, categorias e
movimentos financeiros pessoais. Esta especificação descreve o estado real
do código e orienta a próxima fase sem transformar intenções futuras em
comportamento já existente.

## Âmbito atual

- **[IMPLEMENTADO]** Autenticação por sessão via Shield; as rotas públicas
  são carregadas em [Routes.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Routes.php:9).
- **[IMPLEMENTADO]** CRUD de contas, categorias e transações com ownership por
  `user_id`; ver [AccountsController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/AccountsController.php:16)
  e [TransactionController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/TransactionController.php:17).
- **[IMPLEMENTADO]** Dashboard com saldo inicial, rendimentos, despesas,
  resumo mensal e movimentos recentes; ver
  [DashboardController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/DashboardController.php:35).
- **[IMPLEMENTADO]** Transferências têm formulário, persistência e consulta,
  mas ainda não atualizam o cálculo dos saldos; ver
  [TransferController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/TransferController.php:42).
- **[IMPLEMENTADO]** Valores monetários são convertidos para inteiros de
  cêntimos em controllers e armazenados em `BIGINT`.
- **[IMPLEMENTADO]** CSRF global está configurado em
  [Filters.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Filters.php:75).

## Fora de âmbito nesta especificação

Multi-moeda, API REST, budgets, recorrências, notificações, integrações
bancárias, OAuth, Redis, filas, Docker e microserviços continuam fora de
âmbito, salvo como roadmap. O README e o Development Guide descrevem-nos
como futuro, não como requisito atual.

## Glossário

- **Conta**: local financeiro do utilizador; contém `initial_balance`.
- **Saldo atual**: `initial_balance + rendimentos ativos - despesas ativas +
  créditos de transferências - débitos de transferências`.
- **Saldo total**: soma dos saldos atuais de todas as contas do utilizador.
- **Movimento**: transação ou transferência apresentada no histórico.
- **Transação**: rendimento ou despesa ligada a uma conta e categoria.
- **Transferência**: movimento atómico entre duas contas do mesmo utilizador;
  não é rendimento nem despesa.
- **Ativo**: registo não eliminado logicamente (`deleted_at IS NULL`).
- **Cêntimo**: unidade monetária inteira; €19,99 é `1999`.
- **Ownership**: cada leitura/escrita de dados financeiros é limitada ao
  `user_id` autenticado.

## Decisões de produto confirmadas

O saldo é sempre derivado, não persistido; transferências têm efeito líquido
zero no saldo total; transações podem ser editadas e removidas logicamente;
contas/categorias removidas logicamente mantêm movimentos consultáveis; são
aceites contas negativas; categorias são obrigatórias e devem corresponder ao
tipo; a moeda é EUR e não há conversão nesta fase.
