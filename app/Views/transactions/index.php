<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Transações<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$transactions = $transactions ?? [];

$totalIncome = 0;
$totalExpenses = 0;
foreach ($transactions as $tx) {
    if (strtoupper($tx->type) === 'INCOME') {
        $totalIncome += $tx->amount;
    } else {
        $totalExpenses += $tx->amount;
    }
}
$netPeriod = $totalIncome - $totalExpenses;
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom border-secondary border-opacity-10">
    <div>
        <div class="text-uppercase small fw-bold text-success mb-1" style="letter-spacing: 0.08em;">
            <i class="bi bi-journal-text"></i> Movimentos
        </div>
        <h1 class="h2 fw-bold text-white mb-1">Transações</h1>
        <p class="text-secondary small mb-0">Consulta e gere os teus movimentos.</p>
    </div>
    <a class="btn-brand-primary desktop-page-action" href="<?= site_url('transactions/new') ?>">
        <i class="bi bi-plus-lg"></i> Registar Nova Transação
    </a>
</div>

<!-- Financial Summary Bar -->
<div class="row g-3 mb-4">
    <div class="col-sm-4">
        <div class="app-card py-3">
            <span class="text-secondary small text-uppercase fw-bold">Total Entradas</span>
            <div class="h3 fw-bold text-success my-1" style="font-family: var(--font-mono);">
                + € <?= number_format($totalIncome / 100, 2, ',', '.') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="app-card py-3">
            <span class="text-secondary small text-uppercase fw-bold">Total Saídas</span>
            <div class="h3 fw-bold text-danger my-1" style="font-family: var(--font-mono);">
                - € <?= number_format($totalExpenses / 100, 2, ',', '.') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="app-card py-3">
            <span class="text-secondary small text-uppercase fw-bold">Saldo do período</span>
            <div class="h3 fw-bold text-white my-1" style="font-family: var(--font-mono);">
                € <?= number_format($netPeriod / 100, 2, ',', '.') ?>
            </div>
        </div>
    </div>
</div>

<div class="mobile-page-action">
    <a class="btn-brand-primary w-100 justify-content-center" href="<?= site_url('transactions/new') ?>">
        <i class="bi bi-plus-lg"></i> Registar Nova Transação
    </a>
</div>

<!-- Ledger Card -->
<div class="app-card">

        <!-- Filter Controls & Reset -->
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4 pb-3 border-bottom border-secondary border-opacity-10">
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-secondary bg-opacity-25 text-light px-3 py-2">
                    <i class="bi bi-list-check me-1 text-success"></i> <?= count($transactions) ?> Movimentos
                </span>
            </div>

            <!-- Formulário de Filtros Integrado -->
            <form method="get" action="<?= site_url('transactions') ?>" class="d-flex flex-wrap gap-2 align-items-center" id="filterForm">
                <!-- Filtro por Tipo -->
                <select class="form-select form-select-sm" style="width: auto;" name="type" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Todos os Tipos</option>
                    <option value="income" <?= service('request')->getGet('type') === 'income' ? 'selected' : '' ?>>Rendimentos (+)</option>
                    <option value="expense" <?= service('request')->getGet('type') === 'expense' ? 'selected' : '' ?>>Despesas (-)</option>
                </select>

                <!-- Filtro por Conta -->
                <select class="form-select form-select-sm" style="width: auto;" name="account_id" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Todas as Contas</option>
                    <?php if (!empty($accounts)) : ?>
                        <?php foreach ($accounts as $acc) : ?>
                            <option value="<?= $acc->id ?>" <?= service('request')->getGet('account_id') == $acc->id ? 'selected' : '' ?>><?= esc($acc->name) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <!-- Filtro por Categoria -->
                <select class="form-select form-select-sm" style="width: auto;" name="category_id" onchange="document.getElementById('filterForm').submit()">
                    <option value="">Todas as Categorias</option>
                    <?php if (!empty($categories)) : ?>
                        <?php foreach ($categories as $cat) : ?>
                            <option value="<?= $cat->id ?>" <?= service('request')->getGet('category_id') == $cat->id ? 'selected' : '' ?>><?= esc($cat->name) ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <!-- Search Box com Elementos Posicionados Inteiramente por Dentro do Input -->
                <div class="position-relative" style="width: 250px;">
                    <!-- Ícone de Lupa Colado à Esquerda por Dentro -->
                    <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-secondary" style="pointer-events: none; z-index: 5;">
                        <i class="bi bi-search"></i>
                    </span>

                    <!-- Campo de Texto com padding ajustado para acolher os ícones internos -->
                    <input type="text" class="form-control form-control-sm bg-dark text-white border-secondary border-opacity-25 rounded-pill ps-5 pe-5" placeholder="Pesquisar descrição..." name="search" value="<?= esc(service('request')->getGet('search')) ?>">

                    <!-- Botão de Submissão Colado à Direita por Dentro -->
                    <button type="submit" class="position-absolute top-50 end-0 translate-middle-y me-2 btn btn-link btn-sm text-success p-0 text-decoration-none" title="Pesquisar" style="z-index: 5; width: 24px; height: 24px; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </div>


                <!-- Botão de Limpar Filtros (Dentro do Form para alinhar perfeitamente) -->
                <?php if (!empty(array_filter(service('request')->getGet()))) : ?>
                    <a href="<?= site_url('transactions') ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary px-3" title="Limpar Filtros" style="height: 31px; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="bi bi-x-lg"></i>
                    </a>
                <?php endif; ?>
            </form>
        </div>

    <?php if ($transactions === []) : ?>
        <div class="text-center py-5 text-secondary">
            <i class="bi bi-receipt fs-1 d-block mb-3 opacity-50"></i>
                <h3 class="h5 fw-bold text-white mb-2">Ainda não tens movimentos</h3>
            <p class="small mb-4">Adiciona o primeiro rendimento ou despesa.</p>
            <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">Adicionar Transação</a>
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table custom-table" id="transactionsTable">
 <tr>
                        <?php 
                            // Obter parâmetros atuais do GET para manter os filtros ativos ao ordenar
                            $currentSort = service('request')->getGet('sort') ?? 'transactions.transaction_date';
                            $currentDir  = service('request')->getGet('direction') ?? 'DESC';
                            
                            // Função auxiliar para calcular a direção oposta do link
                            function sortUrl($column, $currentSort, $currentDir) {
                                $params = service('request')->getGet();
                                $params['sort'] = $column;
                                $params['direction'] = ($currentSort === $column && $currentDir === 'ASC') ? 'DESC' : 'ASC';
                                // Omitir a página atual para voltar à página 1 ao ordenar
                                unset($params['page']); 
                                return site_url('transactions') . '?' . http_build_query($params);
                            }
                        ?>
                        
                        <th>
                            <a href="<?= sortUrl('transactions.description', $currentSort, $currentDir) ?>" class="text-decoration-none text-white d-flex align-items-center gap-1">
                                Transação 
                                <?= $currentSort === 'transactions.description' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small text-secondary"></i>' ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?= sortUrl('accounts.name', $currentSort, $currentDir) ?>" class="text-decoration-none text-white d-flex align-items-center gap-1">
                                Conta 
                                <?= $currentSort === 'accounts.name' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small text-secondary"></i>' ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?= sortUrl('categories.name', $currentSort, $currentDir) ?>" class="text-decoration-none text-white d-flex align-items-center gap-1">
                                Categoria 
                                <?= $currentSort === 'categories.name' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small text-secondary"></i>' ?>
                            </a>
                        </th>
                        <th>
                            <a href="<?= sortUrl('transactions.transaction_date', $currentSort, $currentDir) ?>" class="text-decoration-none text-white d-flex align-items-center gap-1">
                                Data 
                                <?= $currentSort === 'transactions.transaction_date' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small text-secondary"></i>' ?>
                            </a>
                        </th>
                        <th class="text-end">
                            <a href="<?= sortUrl('transactions.amount', $currentSort, $currentDir) ?>" class="text-decoration-none text-white d-flex align-items-center justify-content-end gap-1">
                                Valor 
                                <?= $currentSort === 'transactions.amount' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small text-secondary"></i>' ?>
                            </a>
                        </th>
                        <th class="text-end">Ações</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach ($transactions as $tx) : ?>
                        <tr class="tx-row" data-type="<?= esc($tx->type) ?>" data-desc="<?= esc(strtolower($tx->description)) ?>">
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="<?= strtoupper($tx->type) === 'INCOME' ? 'badge-income' : 'badge-expense' ?> p-2 rounded-2">
                                        <i class="bi <?= strtoupper($tx->type) === 'INCOME' ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i>
                                    </span>
                                    <div>
                                        <div class="fw-bold text-white"><?= esc($tx->description) ?></div>
                                        <div class="text-secondary small d-md-none"><?= esc($tx->account_name) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge-account"><?= esc($tx->account_name) ?></span>
                            </td>
                            <td>
                                <span class="badge bg-dark border border-secondary border-opacity-25 text-secondary px-2 py-1 small">
                                    <?= esc($tx->category_name ?? '—') ?>
                                </span>
                            </td>
                            <td class="text-secondary small">
                                <?= date('d/m/Y', strtotime($tx->transaction_date)) ?>
                            </td>
                            <td class="text-end fw-bold fs-6" style="font-family: var(--font-mono);">
                                <span class="<?= strtoupper($tx->type) === 'INCOME' ? 'text-success' : 'text-danger' ?>">
                                    <?= strtoupper($tx->type) === 'INCOME' ? '+' : '-' ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="<?= site_url('transactions/' . $tx->id) ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary" title="Ver Recibo">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?= site_url('transactions/' . $tx->id . '/edit') ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= site_url('transactions/' . $tx->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar o movimento '<?= esc($tx->description) ?>'?" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-dark border border-danger border-opacity-50 text-danger" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pager)) : ?>
            <div class="mt-4">
                <?= $pager->links('default', 'nivora_pager') ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
