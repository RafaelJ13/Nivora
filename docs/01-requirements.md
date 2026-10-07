# Requisitos

IDs `RF` são funcionais e `RNF` não funcionais. O estado é factual:
`[IMPLEMENTADO]` só quando confirmado no código, `[PLANEADO]` quando aparece
apenas em README/guide, e `[ASSUNÇÃO]` quando não está confirmado.

## Funcionais

### RF-001 — autenticação `[IMPLEMENTADO]`

Registar, iniciar e terminar sessão através do Shield.

**Aceitação:** utilizador válido consegue registar-se e entrar; utilizador
não autenticado não acede ao grupo protegido; logout termina a sessão.
Referências: [Routes.php](/Users/shopkit/Documents/personal/Nivora/app/Config/Routes.php:9)
e [RegisterController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/RegisterController.php:7).

### RF-002 — contas `[IMPLEMENTADO]` / `[ASSUNÇÃO]`

Criar, consultar, editar e remover logicamente contas próprias. O saldo atual
deve ser derivado, incluindo movimentos ativos e transferências.

**Aceitação:** conta de outro utilizador nunca é devolvida; o saldo exibido
é igual à fórmula do glossário; remover uma conta não elimina
automaticamente o histórico. A política confirmada de retenção exige uma
alteração de constraints/código ainda não implementada.

### RF-003 — categorias `[IMPLEMENTADO]`

Criar, consultar, editar e remover logicamente categorias próprias.

**Aceitação:** `type` é `INCOME` ou `EXPENSE`; uma transação só aceita
categoria do mesmo tipo e do mesmo utilizador. Referência:
[CategoryController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/CategoryController.php:8).

### RF-004 — transações `[IMPLEMENTADO]` (com lacunas)

Criar, consultar, editar e remover logicamente rendimentos/despesas, com
montante inteiro em cêntimos e data válida.

**Aceitação:** montante positivo introduzido em euros é convertido sem
float na regra final; `type` é válido; referências pertencem ao utilizador;
editar/apagar altera o saldo derivado; uma tentativa cross-user falha sem
revelar dados. O código atual converte via `float` em
[TransactionController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/TransactionController.php:168),
logo o critério de “sem float” ainda é `[PLANEADO]`.

### RF-005 — dashboard `[IMPLEMENTADO]` (parcial)

Mostrar saldo total, entradas/saídas do mês, movimentos recentes e gastos por
categoria.

**Aceitação:** saldo total coincide com a soma dos saldos derivados; uma
transferência não aumenta rendimento/despesa; filtros temporais têm limites
documentados. O dashboard atual calcula apenas transações na soma principal
em [DashboardController.php](/Users/shopkit/Documents/personal/Nivora/app/Controllers/DashboardController.php:113).

### RF-006 — transferências `[IMPLEMENTADO]` (incompleto; próximo trabalho)

Registar uma transferência atómica entre duas contas próprias, debitando uma
e creditando outra, sem categoria.

**Aceitação:** origem e destino são diferentes e do mesmo utilizador; valor
é positivo em cêntimos; ou ambas as alterações ficam persistidas, ou nenhuma
fica; o saldo total não muda; apagar/editar segue a mesma regra de
reconstrução. O código atual apenas insere `transfers`, sem transação de BD,
edição ou remoção.

## Não funcionais

- **RNF-001 — isolamento `[IMPLEMENTADO]`**: queries de domínio filtram por
  utilizador em controllers; deve existir teste negativo por cada recurso.
- **RNF-002 — CSRF `[IMPLEMENTADO]`**: filtro global e `csrf_field()` nos
  formulários de alteração.
- **RNF-003 — dinheiro `[IMPLEMENTADO]` / `[PLANEADO]`**: schema usa `BIGINT`,
  mas controllers ainda usam `float` na conversão; o requisito final é nunca
  usar float no parsing.
- **RNF-004 — atomicidade `[PLANEADO]`**: criação/alteração de transferência
  e qualquer atualização derivada devem usar uma transação MySQL.
- **RNF-005 — testabilidade `[PLANEADO]`**: regras de saldo, ownership,
  validação e transferências terão testes automatizados de integração.
- **RNF-006 — erros explícitos `[IMPLEMENTADO]` (parcial)**: controllers
  redirecionam com erros de validação, mas há mensagens inconsistentes e
  falhas sem contrato HTTP documentado.
