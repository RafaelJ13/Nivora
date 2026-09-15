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
    <a class="btn-brand-primary" href="<?= site_url('transactions/new') ?>">
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

<!-- Ledger Card -->
<div class="app-card">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4 pb-3 border-bottom border-secondary border-opacity-10">
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary bg-opacity-25 text-light px-3 py-2">
                <i class="bi bi-list-check me-1 text-success"></i> <?= count($transactions) ?> Movimentos
            </span>
        </div>

        <!-- Filter Controls (Visual Mockup for MVP / V1) -->
        <div class="d-flex flex-wrap gap-2">
            <select class="form-select form-select-sm" style="width: auto;" id="typeFilter">
                <option value="">Todos os Tipos</option>
                <option value="income">Rendimentos (+)</option>
                <option value="expense">Despesas (-)</option>
            </select>
            <input type="text" class="form-control form-control-sm" placeholder="Pesquisar descrição..." id="searchInput" style="width: 200px;">
        </div>
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
                <thead>
                    <tr>
                        <th>Transação</th>
                        <th>Conta</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th class="text-end">Valor</th>
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
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const rows = document.querySelectorAll('.tx-row');

        function filterRows() {
            const query = (searchInput ? searchInput.value : '').toLowerCase();
            const type = typeFilter ? typeFilter.value : '';

            rows.forEach(row => {
                const desc = row.getAttribute('data-desc') || '';
                const rowType = row.getAttribute('data-type') || '';
                const matchesQuery = desc.includes(query);
                const matchesType = !type || rowType === type;

                row.style.display = (matchesQuery && matchesType) ? '' : 'none';
            });
        }

        if (searchInput) searchInput.addEventListener('input', filterRows);
        if (typeFilter) typeFilter.addEventListener('change', filterRows);
    });
</script>
<?= $this->endSection() ?>
