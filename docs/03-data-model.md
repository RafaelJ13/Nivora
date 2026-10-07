# Modelo de dados

## Tabelas confirmadas

### `users` `[IMPLEMENTADO via Shield]`

Tabela criada pelo Shield, com `id` e identidades de autenticação. A
migration local [AddNameToUsers.php](/Users/shopkit/Documents/personal/Nivora/app/Database/Migrations/2026-09-03-194909_AddNameToUsers.php:8)
adiciona `name` nullable. A estrutura completa depende da versão instalada do
Shield e deve ser obtida da migration vendor em ambiente de build.

### `accounts` `[IMPLEMENTADO]`

`id INT unsigned PK`, `user_id INT unsigned NOT NULL`, `name VARCHAR(100)`,
`type VARCHAR(20)`, `initial_balance BIGINT NOT NULL DEFAULT 0`,
`created_at`, `updated_at`, `deleted_at`. Tem índice e FK de `user_id` para
`users` com cascade. Unique `(user_id, name)`.

### `categories` `[IMPLEMENTADO]`

`id`, `user_id`, `name VARCHAR(100)`, `type ENUM('INCOME','EXPENSE')`,
timestamps e `deleted_at`; unique `(user_id,name,type)`, FK para users.

### `transactions` `[IMPLEMENTADO]`

`id`, `user_id`, `account_id`, `category_id`, `type ENUM('INCOME','EXPENSE')`,
`amount BIGINT`, `description VARCHAR(255) NULL`,
`transaction_date DATETIME`, timestamps e `deleted_at`; índices e FKs para
users/accounts/categories com cascade. Fonte:
[CreateTransactionsTable.php](/Users/shopkit/Documents/personal/Nivora/app/Database/Migrations/2026-08-30-183938_CreateTransactionsTable.php:8).

### `transfers` `[IMPLEMENTADO]` (schema incompleto)

`id`, `user_id`, `account_from_id`, `account_to_id`, `amount BIGINT`,
`transfer_date DATETIME`, `created_at`, `deleted_at`, índices e FKs com
cascade. Falta `updated_at`, constraint `from <> to`, positividade do
montante e uma política de deleção coerente com histórico:
[CreateTransfer.php](/Users/shopkit/Documents/personal/Nivora/app/Database/Migrations/2026-09-19-230518_CreateTransfer.php:8).

## Invariantes obrigatórias

- Todo registo financeiro ativo pertence ao utilizador autenticado.
- `amount > 0` para transações e transferências; valores monetários são
  inteiros em cêntimos.
- `account_from_id <> account_to_id`.
- Conta e categoria referenciadas pertencem ao mesmo utilizador do movimento.
- Categoria de transação coincide com `type`.
- Saldo atual nunca é escrito como fonte de verdade: é a soma reproduzível do
  ledger ativo.
- Transferência aplicada a uma conta reduz a origem e aumenta o destino pelo
  mesmo valor; o saldo total mantém-se.
- Eliminação lógica exclui o movimento dos saldos, mas preserva consulta
  histórica conforme decisão de produto.

## Riscos do schema atual

As FKs `ON DELETE CASCADE` entram em conflito com a decisão de manter
movimentos após remoção lógica: uma operação física de conta pode eliminar
histórico. A próxima migration deve impedir deleção física acidental ou
alterar a estratégia de FK. Também falta unicidade/índice composto para
consultas de saldo por conta e data; medir antes de otimizar.
