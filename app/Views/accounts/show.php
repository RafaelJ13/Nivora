<?= $this->extend('app_layout') ?>

<?php
$account = $account ?? (object)[
    'id' => 1,
    'name' => 'Millennium BCP',
    'type' => 'bank',
    'initial_balance' => 200000,
    'created_at' => date('Y-01-15 10:00:00')
];

$transactions = $transactions ?? [
    (object)[
        'id' => 1,
        'description' => 'Salário Mensal',
        'type' => 'income',
        'amount' => 280000,
        'category_name' => 'Salário',
        'date' => date('Y-m-01')
    ],
    (object)[
        'id' => 4,
        'description' => 'Combustível Repsol',
        'type' => 'expense',
        'amount' => 5000,
        'category_name' => 'Transporte',
        'date' => date('Y-m-03')
    ]
];

$accountIncome = 0;
$accountExpenses = 0;
foreach ($transactions as $tx) {
    if ($tx->type === 'income') {
        $accountIncome += $tx->amount;
    } else {
        $accountExpenses += $tx->amount;
    }
}
$calculatedBalance = $account->initial_balance + $accountIncome - $accountExpenses;
?>

<?= $this->section('title') ?><?= esc($account->name) ?> — Detalhes<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="mb-4">
    <a href="<?= site_url('accounts') ?>" class="text-secondary text-decoration-none small">
        <i class="bi bi-arrow-left"></i> Voltar às Contas
    </a>
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mt-2 pb-2 border-bottom border-secondary border-opacity-10">
        <div>
            <div class="text-uppercase small fw-bold text-success" style="letter-spacing: 0.08em;">
                <i class="bi <?= $account->type === 'bank' ? 'bi-bank' : ($account->type === 'cash' ? 'bi-cash-coin' : 'bi-safe') ?> me-1"></i>
                <?= esc($account->type) ?>
            </div>
            <h1 class="h2 fw-bold text-white mb-1"><?= esc($account->name) ?></h1>
            <div class="text-secondary small">Conta criada a <?= date('d/m/Y', strtotime($account->created_at ?? 'now')) ?> &bull; ID #<?= esc($account->id) ?></div>
        </div>

        <div class="d-flex gap-2">
            <a href="<?= site_url('accounts/' . $account->id . '/edit') ?>" class="btn-brand-outline">
                <i class="bi bi-pencil"></i> Editar
            </a>
            <form action="<?= site_url('accounts/' . $account->id) ?>" method="post" onsubmit="return confirm('Eliminar esta conta?');" class="d-inline">
                <?= csrf_field() ?>
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-dark border border-danger border-opacity-50 text-danger rounded-3 px-3 py-2">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Account Balance Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="app-card">
            <span class="text-secondary small text-uppercase fw-bold">Saldo Calculado</span>
            <div class="h3 fw-bold text-white my-2" style="font-family: var(--font-mono);">
                € <?= number_format($calculatedBalance / 100, 2, ',', '.') ?>
            </div>
            <div class="text-secondary small">Saldo Inicial: € <?= number_format($account->initial_balance / 100, 2, ',', '.') ?></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card">
            <span class="text-secondary small text-uppercase fw-bold">Entradas Registadas</span>
            <div class="h3 fw-bold text-success my-2" style="font-family: var(--font-mono);">
                + € <?= number_format($accountIncome / 100, 2, ',', '.') ?>
            </div>
            <div class="text-secondary small">Créditos nesta conta</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="app-card">
            <span class="text-secondary small text-uppercase fw-bold">Saídas Registadas</span>
            <div class="h3 fw-bold text-danger my-2" style="font-family: var(--font-mono);">
                - € <?= number_format($accountExpenses / 100, 2, ',', '.') ?>
            </div>
            <div class="text-secondary small">Débitos nesta conta</div>
        </div>
    </div>
</div>

<!-- Transactions of This Account -->
<div class="app-card">
    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-10">
        <h2 class="h5 fw-bold text-white mb-0">
            <i class="bi bi-receipt me-2 text-success"></i> Movimentos desta Conta
        </h2>
        <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary py-1 px-3" style="font-size: 0.85rem;">
            <i class="bi bi-plus-lg"></i> Novo Movimento
        </a>
    </div>

    <?php if ($transactions === []) : ?>
        <div class="text-center py-5 text-secondary">
            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
            <p class="mb-3">Ainda não existem movimentos associados a esta conta.</p>
            <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">Criar Primeira Transação</a>
        </div>
    <?php else : ?>
        <div class="table-responsive">
            <table class="table custom-table">
                <thead>
                    <tr>
                        <th>Descrição</th>
                        <th>Categoria</th>
                        <th>Data</th>
                        <th class="text-end">Montante</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $tx) : ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="<?= $tx->type === 'income' ? 'badge-income' : 'badge-expense' ?> p-2 rounded-2">
                                        <i class="bi <?= $tx->type === 'income' ? 'bi-plus-lg' : 'bi-dash-lg' ?>"></i>
                                    </span>
                                    <span class="fw-semibold text-white"><?= esc($tx->description) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-dark border border-secondary border-opacity-25 text-secondary px-2 py-1 small">
                                    <?= esc($tx->category_name ?? 'Geral') ?>
                                </span>
                            </td>
                            <td class="text-secondary small">
                                <?= date('d/m/Y', strtotime($tx->date ?? 'now')) ?>
                            </td>
                            <td class="text-end fw-bold" style="font-family: var(--font-mono);">
                                <span class="<?= $tx->type === 'income' ? 'text-success' : 'text-danger' ?>">
                                    <?= $tx->type === 'income' ? '+' : '-' ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
