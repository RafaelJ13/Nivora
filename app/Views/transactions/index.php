<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Transações<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$transactions = $transactions ?? [];

$totalIncome = 0;
$totalExpenses = 0;
foreach ($transactions as $tx) {
    if (($tx->row_type ?? 'TRANSACTION') === 'TRANSFER') {
        continue;
    }
    if (strtoupper($tx->type) === 'INCOME') {
        $totalIncome += $tx->amount;
    } else {
        $totalExpenses += $tx->amount;
    }
}
$netPeriod = $totalIncome - $totalExpenses;
?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h1>Transações</h1>
            <p>Consulta e gere os teus movimentos.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('transfers/new') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left-right"></i> Nova Transferência
        </a>
        <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">
            <i class="bi bi-plus-lg"></i> Nova Transação
        </a>
    </div>
</div>

<div class="app-content">

    <!-- Resumo -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Total entradas</div>
                <div class="n-metric-value" style="color:#43B790;">+ € <?= number_format($totalIncome / 100, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Total saídas</div>
                <div class="n-metric-value" style="color:#FF5C5C;">- € <?= number_format($totalExpenses / 100, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Saldo do período</div>
                <div class="n-metric-value">€ <?= number_format($netPeriod / 100, 2, ',', '.') ?></div>
            </div>
        </div>
    </div>

    <!-- Card principal -->
    <div class="n-card">

        <!-- Filtros -->
<form method="get" action="<?= site_url('transactions') ?>" id="filterForm" class="filter-bar mb-3 pb-3">

    <div class="filter-bar-left">
        <span class="badge-account">
            <i class="bi bi-list-check me-1" style="color:#43B790;"></i>
            <?= count($transactions) ?> movimentos
        </span>
    </div>

    <div class="filter-bar-right">

        <!-- Tipo -->
        <div class="filter-select-wrap">
            <i class="bi bi-funnel filter-select-icon"></i>
            <select class="filter-select" name="type" onchange="document.getElementById('filterForm').submit()">
                <option value="">Todos os tipos</option>
                <option value="income"  <?= service('request')->getGet('type') === 'income'  ? 'selected' : '' ?>>Rendimentos</option>
                <option value="expense" <?= service('request')->getGet('type') === 'expense' ? 'selected' : '' ?>>Despesas</option>
                <option value="transfer" <?= service('request')->getGet('type') === 'transfer' ? 'selected' : '' ?>>Transferências</option>
            </select>
            <i class="bi bi-chevron-down filter-select-caret"></i>
        </div>

        <!-- Conta -->
        <div class="filter-select-wrap">
            <i class="bi bi-bank filter-select-icon"></i>
            <select class="filter-select" name="account_id" onchange="document.getElementById('filterForm').submit()">
                <option value="">Todas as contas</option>
                <?php if (!empty($accounts)) : ?>
                    <?php foreach ($accounts as $acc) : ?>
                        <option value="<?= $acc->id ?>" <?= service('request')->getGet('account_id') == $acc->id ? 'selected' : '' ?>>
                            <?= esc($acc->name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <i class="bi bi-chevron-down filter-select-caret"></i>
        </div>

        <!-- Categoria -->
        <div class="filter-select-wrap">
            <i class="bi bi-tag filter-select-icon"></i>
            <select class="filter-select" name="category_id" onchange="document.getElementById('filterForm').submit()">
                <option value="">Todas as categorias</option>
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $cat) : ?>
                        <option value="<?= $cat->id ?>" <?= service('request')->getGet('category_id') == $cat->id ? 'selected' : '' ?>>
                            <?= esc($cat->name) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <i class="bi bi-chevron-down filter-select-caret"></i>
        </div>

        <!-- Pesquisa -->
        <div class="filter-search">
            <i class="bi bi-search filter-search-icon"></i>
            <input type="text"
                   class="filter-search-input"
                   name="search"
                   placeholder="Pesquisar descrição…"
                   value="<?= esc(service('request')->getGet('search')) ?>">
            <button type="submit" class="filter-search-submit" title="Pesquisar">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>

        <!-- Limpar -->
        <?php if (!empty(array_filter(service('request')->getGet()))) : ?>
            <a href="<?= site_url('transactions') ?>" class="filter-clear" title="Limpar filtros">
                <i class="bi bi-x-lg"></i>
            </a>
        <?php endif; ?>

    </div>
</form>

        <?php if ($transactions === []) : ?>
            <div class="text-center py-5">
                <i class="bi bi-receipt fs-1 d-block mb-3" style="color: rgba(245,245,245,0.2);"></i>
                <h3 class="h5 fw-bold mb-2" style="color:#F5F5F5;">Ainda não tens movimentos</h3>
                <p class="small mb-4" style="color: rgba(245,245,245,0.5);">Adiciona o primeiro rendimento ou despesa.</p>
                <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">Adicionar Transação</a>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="n-table" id="transactionsTable">
                    <thead>
                        <tr>
                            <?php
                                $currentSort = service('request')->getGet('sort') ?? 'transactions.transaction_date';
                                $currentDir  = service('request')->getGet('direction') ?? 'DESC';

                                if (!function_exists('sortUrl')) {
                                    function sortUrl($column, $currentSort, $currentDir) {
                                        $params = service('request')->getGet();
                                        $params['sort'] = $column;
                                        $params['direction'] = ($currentSort === $column && $currentDir === 'ASC') ? 'DESC' : 'ASC';
                                        unset($params['page']);
                                        return site_url('transactions') . '?' . http_build_query($params);
                                    }
                                }
                            ?>
                            <th>
                                <a href="<?= sortUrl('transactions.description', $currentSort, $currentDir) ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color:#F5F5F5;">
                                    Transação
                                    <?= $currentSort === 'transactions.description' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small" style="color: rgba(245,245,245,0.4);"></i>' ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?= sortUrl('accounts.name', $currentSort, $currentDir) ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color:#F5F5F5;">
                                    Conta
                                    <?= $currentSort === 'accounts.name' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small" style="color: rgba(245,245,245,0.4);"></i>' ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?= sortUrl('categories.name', $currentSort, $currentDir) ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color:#F5F5F5;">
                                    Categoria
                                    <?= $currentSort === 'categories.name' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small" style="color: rgba(245,245,245,0.4);"></i>' ?>
                                </a>
                            </th>
                            <th>
                                <a href="<?= sortUrl('transactions.transaction_date', $currentSort, $currentDir) ?>" class="text-decoration-none d-flex align-items-center gap-1" style="color:#F5F5F5;">
                                    Data
                                    <?= $currentSort === 'transactions.transaction_date' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small" style="color: rgba(245,245,245,0.4);"></i>' ?>
                                </a>
                            </th>
                            <th class="text-end">
                                <a href="<?= sortUrl('transactions.amount', $currentSort, $currentDir) ?>" class="text-decoration-none d-flex align-items-center justify-content-end gap-1" style="color:#F5F5F5;">
                                    Valor
                                    <?= $currentSort === 'transactions.amount' ? ($currentDir === 'ASC' ? '<i class="bi bi-caret-up-fill small"></i>' : '<i class="bi bi-caret-down-fill small"></i>') : '<i class="bi bi-chevron-expand small" style="color: rgba(245,245,245,0.4);"></i>' ?>
                                </a>
                            </th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $tx) : ?>
                            <?php if (($tx->row_type ?? 'TRANSACTION') === 'TRANSFER') : ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="tx-icon"><i class="bi bi-arrow-left-right"></i></span>
                                            <div>
                                                <div style="font-weight:600; color:#F5F5F5;">Transferência</div>
                                                <div class="d-md-none" style="font-size:0.72rem; color: rgba(245,245,245,0.4);">
                                                    <?= esc($tx->account_from_name) ?> → <?= esc($tx->account_to_name) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge-account"><?= esc($tx->account_from_name) ?> → <?= esc($tx->account_to_name) ?></span></td>
                                    <td style="color: rgba(245,245,245,0.55);">Transferência</td>
                                    <td style="color: rgba(245,245,245,0.55); font-size:0.8rem; font-family: var(--font-mono);">
                                        <?= date('d/m/Y', strtotime($tx->transfer_date)) ?>
                                    </td>
                                    <td class="amount">€ <?= number_format($tx->amount / 100, 2, ',', '.') ?></td>
                                    <td class="text-end">
                                        <a href="<?= site_url('transfers/' . $tx->id) ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size: 0.75rem;" title="Ver transferência">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php $isIncome = strtoupper($tx->type) === 'INCOME'; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="tx-icon">
                                                <i class="bi <?= $isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right' ?>"></i>
                                            </span>
                                            <div>
                                                <div style="font-weight:600; color:#F5F5F5;"><?= esc($tx->description) ?></div>
                                                <div class="d-md-none" style="font-size:0.72rem; color: rgba(245,245,245,0.4);"><?= esc($tx->account_name) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge-account"><?= esc($tx->account_name) ?></span></td>
                                    <td style="color: rgba(245,245,245,0.55);"><?= esc($tx->category_name ?? '—') ?></td>
                                    <td style="color: rgba(245,245,245,0.55); font-size:0.8rem; font-family: var(--font-mono);"><?= date('d/m/Y', strtotime($tx->transaction_date)) ?></td>
                                    <td class="amount <?= $isIncome ? 'pos' : 'neg' ?>">
                                        <?= $isIncome ? '+' : '−' ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <a href="<?= site_url('transactions/' . $tx->id) ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size: 0.75rem;" title="Ver">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="<?= site_url('transactions/' . $tx->id . '/edit') ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size: 0.75rem;" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="<?= site_url('transactions/' . $tx->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar o movimento '<?= esc($tx->description) ?>'?" class="d-inline">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size: 0.75rem; color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
</div>
<?= $this->endSection() ?>