# Próximas tarefas

As tarefas são pequenas, ordenadas e começam por Transferências. Cada tarefa
deve incluir testes automatizados antes de ser considerada concluída.

## T-001 — fechar ledger de transferências

- **RF:** RF-006, RF-002, RF-005.
- **Dependências:** RF-001 e schema existente; nenhuma tarefa anterior.
- **Trabalho:** validar `amount > 0`, contas próprias/diferentes, inserir numa
  transação de BD, incluir débito/crédito nas queries de saldo, dashboard,
  conta e histórico.
- **Testes:** sucesso entre duas contas; rollback forçado; cross-user;
  origem=destino; zero/negativo; saldo total invariável; transferência
  eliminada logicamente deixa de contar.
- **Feito quando:** todos os invariantes de [03-data-model.md](03-data-model.md)
  passam e não existe caminho de sucesso para inserção parcial.

## T-002 — normalizar parsing monetário

- **RF/RNF:** RF-004, RNF-003.
- **Dependências:** T-001.
- **Trabalho:** substituir conversões `float` por parser decimal de cêntimos,
  com formato EUR explícito e mensagens de erro.
- **Testes:** `0,01`, `19,99`, separadores inválidos, zero, negativo,
  overflow e repetição idempotente.
- **Feito quando:** nenhum controller de dinheiro usa `float` e os valores
  persistidos são inteiros esperados.

## T-003 — tornar remoção histórica coerente

- **RF:** RF-002, RF-003, RF-004.
- **Dependências:** T-001.
- **Trabalho:** alinhar soft delete, FKs e UI para que conta/categoria
  removida logicamente mantenha histórico e não possa ser usada em novos
  movimentos.
- **Testes:** saldo antes/depois; histórico consultável; nova referência
  rejeitada; deleção física protegida.
- **Feito quando:** a decisão de retenção não é contrariada por cascade.

## T-004 — consolidar cálculo de saldo

- **RF:** RF-005, RNF-004.
- **Dependências:** T-001, T-003.
- **Trabalho:** extrair uma consulta/serviço de leitura única para contas,
  dashboard e detalhes; documentar limites temporais do resumo mensal.
- **Testes:** saldo inicial negativo, mistura de tipos, transferências,
  soft-deletes e várias contas.
- **Feito quando:** as três superfícies devolvem o mesmo resultado para a
  mesma carteira.

## T-005 — completar contrato de edição/remoção

- **RF:** RF-004, RF-006.
- **Dependências:** T-004.
- **Trabalho:** mensagens e redirects corretos, edição de transação/
  transferência, remoção lógica e recalculo reproduzível.
- **Testes:** alterar valor/tipo/conta/data; apagar e restaurar se previsto;
  ownership e CSRF.
- **Feito quando:** cada mutação tem teste de sucesso, validação e isolamento.

## T-006 — construir suite de regressão

- **RF/RNF:** todos.
- **Dependências:** T-001 a T-005.
- **Trabalho:** substituir testes exemplo por testes de domínio, mantendo
  smoke tests de framework.
- **Testes:** integração HTTP, migrations, models, autorização, ledger e
  invariantes.
- **Feito quando:** `vendor/bin/phpunit` passa num banco de teste isolado e
  cobre cada critério crítico.

## Lacunas e bugs encontrados na leitura

- **BUG-001:** transferências são guardadas mas não entram nos saldos
  calculados; o saldo da origem não baixa nem o destino sobe.
- **BUG-002:** controllers usam `float` ao converter dinheiro, contrariando
  a regra de cêntimos.
- **BUG-003:** `DashboardController` percorre `paginate(5)` para calcular
  saldo, logo o saldo depende da página e não de todas as transações.
- **BUG-004:** `AccountController` e dashboard não incorporam transferências
  nos saldos.
- **BUG-005:** migrations usam cascade físico, incompatível com retenção
  histórica se ocorrer deleção física.
- **BUG-006:** não há testes de domínio; os existentes são exemplos de
  health/session/database.
- **BUG-007:** `TransferController::index()` está vazio e não existem rotas
  de edição/remoção de transferências.

## Divergências código vs documentação

- **D-001:** README diz que Transfers estão concluídas e marca-as como
  roadmap concluído; o código tem schema/formulário/insert, mas não aplica o
  efeito nos saldos nem oferece edição/remoção.
- **D-002:** README diz que os saldos são atualizados pelas transações; o
  código calcula-os em controllers/views e o dashboard usa apenas a página
  paginada para parte do cálculo.
- **D-003:** README/guide exigem evitar `float`; controllers convertem
  entradas monetárias com `float`.
- **D-004:** guide descreve apenas `initial + income - expense`; a decisão
  atual exige também transferências como débito/crédito compensado.
- **D-005:** guide lista edição de transações como V1/futuro, mas o código já
  expõe `GET/PUT` e a view de edição.
- **D-006:** guide propõe rotas POST de update/delete como exemplo; o código
  usa PUT/DELETE simulados por `_method`.
- **D-007:** a promessa de retenção histórica conflita com FKs `ON DELETE
  CASCADE` se houver deleção física.

## Assunções restantes

- **A-001:** `users` e tabelas do Shield têm a estrutura da versão instalada;
  a migration vendor não foi copiada para o repositório.
- **A-002:** timezone da aplicação/servidor é a fonte de interpretação de
  `datetime-local`; Europe/Lisbon não foi imposto.
- **A-003:** “remoção lógica” de conta/categoria implica manter linhas
  consultáveis, embora o fluxo de restauração não tenha sido pedido.
- **A-004:** limites de tamanho/overflow para cêntimos serão definidos pelo
  parser e pelo banco durante T-002.

## Riscos transversais e lacunas de testes

- **Consistência:** cálculo em várias controllers pode divergir; centralizar
  em T-004.
- **Atomicidade:** transferências não usam transação de BD; rollback não é
  garantido até T-001.
- **Autorização:** há filtros por utilizador, mas não há testes negativos
  para cada endpoint nem testes de joins com IDs de outro utilizador.
- **Dados:** cascade físico pode destruir histórico; resolver em T-003.
- **Testes:** não existem testes para autenticação real, ownership,
  parsing monetário, saldo, transferências, validações de domínio,
  CSRF/HTTP, migrations da aplicação, filtros/paginação ou regressão de
  edição/remoção. Os testes presentes são exemplos de framework.
