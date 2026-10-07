# Convenções do Nivora

## Comandos

- Instalar: `composer install`
- Migrar: `php spark migrate --all`
- Servir localmente: `php spark serve`
- Testar: `vendor/bin/phpunit` (ou `./phpunit` se o symlink existir)
- Testes dirigidos: `vendor/bin/phpunit tests/path/to/Test.php`

## Convenções obrigatórias

- Nunca usar `float` para dinheiro; persistir inteiros em cêntimos (`BIGINT`).
- Nunca consultar ou alterar dados financeiros sem filtrar por `user_id`.
- Nunca confiar em `id` enviado pelo browser para provar ownership.
- Toda rota mutável autenticada exige CSRF.
- Validar referências de conta/categoria antes de inserir ou editar.
- Não alterar saldo derivado fora de uma transação de BD; a fonte de verdade
  é o ledger, não uma coluna cache.
- Transferências devem ser atómicas e ter efeito líquido zero no saldo total.
- Eliminações de histórico são lógicas; alinhar FKs para não apagar dados
  contra a decisão de produto.
- Erros devem ser explícitos e testáveis; não esconder falhas em defaults.
- Usar `esc()` nas saídas de texto e seguir os padrões existentes de views.

## Definição de feito

Uma alteração financeira só está pronta com validação, ownership, CSRF,
tratamento de erro, teste de sucesso, teste de falha e teste cross-user.
Migrations novas devem ter caminho `down()` seguro e ser verificadas num banco
de teste isolado.

## Fonte de verdade

O comportamento implementado está nos ficheiros PHP e migrations. README e
`Nivora-Development-Guide.md` são contexto/roadmap e não substituem o código.
Em caso de divergência, atualizar a especificação com `[IMPLEMENTADO]`,
`[PLANEADO]` ou `[ASSUNÇÃO]` antes de mudar o produto.
