# Arquitetura real e decisões

## Fluxo

**[IMPLEMENTADO]** Browser → rotas → controller → model/query builder →
MySQL → view. A estrutura é MVC simples; não há camada Service ou Repository.
Isto corresponde ao guia original e continua adequado enquanto a lógica
financeira permanecer pequena.

As rotas estão em [Routes.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Routes.php:13).
Controllers constroem queries e chamam models. Models definem campos
permitidos, timestamps, soft deletes e regras básicas; por exemplo
[TransactionModel.php](/Users/shopkit/Documents/personal/Nivora/app/Models/TransactionModel.php:6).
As views PHP escapam texto apresentado com `esc()` em pontos de saída.

## Autorização

**[IMPLEMENTADO]** O filtro `session` protege o grupo autenticado. Ownership é
aplicado por `where('user_id', $authUserId)` antes de `find`, e referências
de conta/categoria são validadas no controller. Isto deve evoluir para uma
política testada e centralizada antes de expor API.

## Dinheiro e saldo

**[IMPLEMENTADO]** A persistência usa inteiros (`BIGINT`) e views formatam
`/ 100`. **[PLANEADO]** O parser final deve aceitar uma representação decimal
controlada e convertê-la para cêntimos sem `float`.

**Decisão:** não existe saldo derivado persistido. Uma consulta/serviço de
leitura calcula:

```text
saldo_conta = saldo_inicial
  + SUM(transações INCOME)
  - SUM(transações EXPENSE)
  + SUM(transferências destino)
  - SUM(transferências origem)
```

O resultado deve ignorar registos com `deleted_at` preenchido. Uma
transferência é a unidade atómica que grava uma intenção; não se duplicam
rendimentos/despesas artificiais.

## Atomicidade

**[IMPLEMENTADO]** transações comuns são inseridas/alteradas numa chamada de
model. **[PLANEADO]** transferência e qualquer operação multi-registo deve
usar `transStart()`/`transComplete()` ou equivalente e confirmar o resultado
antes de responder sucesso. Sem coluna de saldo, não há risco de divergência
entre cache e ledger, mas há risco de inserção parcial enquanto a transferência
não for atomicamente validada.

## Consolidação do Development Guide

- **Reaproveitar:** MVC simples, migrations, seeders, ownership, CSRF,
  cêntimos e a ordem incremental de evolução.
- **Substituir:** exemplos genéricos de rotas e entidades pelos contratos
  reais de `Routes.php`, migrations e controllers; substituir o saldo
  meramente `initial + income - expense` pela fórmula com transferências.
- **Apagar/arquivar:** listas de funcionalidades futuras e diagramas que
  pareçam requisitos implementados; mantê-los apenas como roadmap no README,
  referenciado por esta especificação.
