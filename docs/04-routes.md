# Rotas, validação e autorização

## Públicas

`GET /`, `GET /privacidade`, `GET /termos`, `GET /cookies`,
`GET /contacto`; autenticação Shield adiciona `/login`, `/register` e
`/logout`. Fonte: [Routes.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Routes.php:9).

## Protegidas por sessão

O grupo iniciado em [Routes.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Routes.php:21)
usa `filter => session`.

| Recurso | Rotas atuais | Estado |
|---|---|---|
| Dashboard | `GET /dashboard` | `[IMPLEMENTADO]` |
| Contas | `GET /accounts`, `GET /accounts/create`, `GET /accounts/{id}`, `GET /accounts/{id}/edit`, `POST /accounts`, `PUT /accounts/{id}`, `DELETE /accounts/{id}` | `[IMPLEMENTADO]` |
| Categorias | `GET /categories`, `GET /categories/new`, `GET /categories/{id}/edit`, `POST`, `PUT`, `DELETE` | `[IMPLEMENTADO]` |
| Transações | `GET /transactions`, `GET /transactions/new`, `GET /transactions/{id}`, `GET /transactions/{id}/edit`, `POST`, `PUT`, `DELETE` | `[IMPLEMENTADO]` |
| Transferências | `GET /transfers/new`, `POST /transfers`, `GET /transfers/{id}` | `[IMPLEMENTADO]` parcial |

## Regras de entrada

Controllers validam ownership e referências antes do insert/update:
[TransactionController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/TransactionController.php:271)
e [TransferController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/TransferController.php:85).
Models restringem `allowedFields` e regras de tipo/data. O contrato final
deve rejeitar montantes zero/negativos, parsear cêntimos sem float e aceitar
saldos iniciais negativos.

## CSRF

**[IMPLEMENTADO]** `csrf` está no filtro global antes de cada request e os
formulários HTML incluem `csrf_field()`. A configuração usa token em sessão,
regeneração e redirect apenas em produção:
[Security.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Security.php:15).

## Ownership e respostas

Cada rota `{id}` deve consultar com `user_id` antes de revelar ou alterar.
ID inexistente e ID de outro utilizador devem produzir a mesma resposta
funcional (redirect para a coleção, sem confirmar existência). Todas as
rotas mutáveis exigem sessão e CSRF. A futura transferência deve validar
ambas as contas dentro da mesma unidade transacional.
