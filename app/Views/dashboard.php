<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
// Mock Data Fallbacks (Guia de Desenvolvimento e README)
$accounts = $accounts ?? [
    (object)[
        'id' => 1,
        'name' => 'Millennium BCP',
        'type' => 'bank',
        'initial_balance' => 200000,
        'current_balance' => 200000,
    ],
    (object)[
        'id' => 2,
        'name' => 'Revolut',
        'type' => 'bank',
        'initial_balance' => 50000,
        'current_balance' => 50000,
    ],
    (object)[
        'id' => 3,
        'name' => 'Dinheiro em Carteira',
        'type' => 'cash',
        'initial_balance' => 15050,
        'current_balance' => 15050,
    ]
];

$transactions = $transactions ?? [
    (object)[
        'id' => 1,
        'description' => 'Salário Mensal',
        'type' => 'income',
        'amount' => 280000, // 2800.00 €
        'category_name' => 'Salário',
        'account_name' => 'Millennium BCP',
        'date' => date('Y-m-01')
    ],
    (object)[
        'id' => 2,
        'description' => 'Supermercado Continente',
        'type' => 'expense',
        'amount' => 4500, // 45.00 €
        'category_name' => 'Alimentação',
        'account_name' => 'Revolut',
        'date' => date('Y-m-02')
    ],
    (object)[
        'id' => 3,
        'description' => 'Jantar Restaurante',
        'type' => 'expense',
        'amount' => 2000, // 20.00 €
        'category_name' => 'Lazer',
        'account_name' => 'Revolut',
        'date' => date('Y-m-02')
    ],
    (object)[
        'id' => 4,
        'description' => 'Combustível Repsol',
        'type' => 'expense',
        'amount' => 5000, // 50.00 €
        'category_name' => 'Transporte',
        'account_name' => 'Millennium BCP',
        'date' => date('Y-m-03')
    ],
    (object)[
        'id' => 5,
        'description' => 'Café e Padaria',
        'type' => 'expense',
        'amount' => 450, // 4.50 €
        'category_name' => 'Alimentação',
        'account_name' => 'Dinheiro em Carteira',
        'date' => date('Y-m-03')
    ]
];

$totalIncomeCents = $totalIncome ?? 280000;
$totalExpensesCents = $totalExpenses ?? 45950;
$totalBalanceCents = $totalBalance ?? 265050;
$netSavingsCents = $totalIncomeCents - $totalExpensesCents;
?>

<!-- Wallet header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3 mb-4">
    <div>
        <div class="text-uppercase small fw-bold text-success mb-2" style="letter-spacing: 0.1em;">Resumo de <?= date('F Y') ?></div>
        <h1 class="h2 fw-bold text-white mb-1">Olá, <?= esc((function_exists('auth') && auth()->loggedIn()) ? (auth()->user()->name ?: auth()->user()->username) : 'Rafael') ?></h1>
        <p class="text-secondary small mb-0">O teu dinheiro, visto sem ruído.</p>
    </div>
    <span class="text-secondary small"><i class="bi bi-shield-check text-success me-1"></i> Dados privados por defeito</span>
</div>

<!-- Balance card and quick actions -->
<div class="row g-3 mb-4 align-items-stretch">
    <div class="col-lg-7">
        <section class="wallet-balance h-100">
            <div class="d-flex justify-content-between align-items-start">
                <span class="wallet-label">Saldo total</span>
                <i class="bi bi-wallet2 wallet-mark"></i>
            </div>
            <div class="wallet-amount">€ <?= number_format($totalBalanceCents / 100, 2, ',', '.') ?></div>
            <div class="d-flex justify-content-between align-items-end gap-3">
                <span class="wallet-meta"><?= count($accounts) ?> contas ligadas</span>
                <span class="wallet-meta">Atualizado hoje</span>
            </div>
        </section>
    </div>
    <div class="col-lg-5">
        <div class="quick-actions h-100">
            <div class="quick-heading">Ações rápidas</div>
            <div class="quick-grid">
                <a href="<?= site_url('transactions/new') ?>" class="quick-action"><span><i class="bi bi-plus-lg"></i></span><small>Transação</small></a>
                <a href="<?= site_url('accounts/new') ?>" class="quick-action"><span><i class="bi bi-wallet2"></i></span><small>Conta</small></a>
                <a href="<?= site_url('categories/new') ?>" class="quick-action"><span><i class="bi bi-tag"></i></span><small>Categoria</small></a>
                <a href="<?= site_url('transactions') ?>" class="quick-action"><span><i class="bi bi-list-ul"></i></span><small>Histórico</small></a>
            </div>
        </div>
    </div>
</div>

<!-- Monthly pulse -->
<div class="row g-3 mb-5 wallet-pulse">
    <div class="col-sm-4"><div class="pulse-item"><span>Entradas</span><strong class="text-success">+ € <?= number_format($totalIncomeCents / 100, 2, ',', '.') ?></strong><small>este mês</small></div></div>
    <div class="col-sm-4"><div class="pulse-item"><span>Saídas</span><strong class="text-danger">- € <?= number_format($totalExpensesCents / 100, 2, ',', '.') ?></strong><small>este mês</small></div></div>
    <div class="col-sm-4"><div class="pulse-item"><span>Poupança</span><strong class="text-white">€ <?= number_format($netSavingsCents / 100, 2, ',', '.') ?></strong><small><?= $totalIncomeCents > 0 ? round(($netSavingsCents / $totalIncomeCents) * 100, 1) : 0 ?>% da entrada</small></div></div>
</div>

<!-- Accounts Strip -->
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="h5 fw-bold text-white mb-0">
            <i class="bi bi-bank me-2 text-success"></i> As Tuas Contas
        </h2>
        <a href="<?= site_url('accounts') ?>" class="text-decoration-none small text-success">
            Gerir todas as contas &rarr;
        </a>
    </div>

    <div class="row g-3">
        <?php foreach ($accounts as $acc) : ?>
            <div class="col-md-4">
                <a href="<?= site_url('accounts/' . $acc->id) ?>" class="text-decoration-none">
                    <div class="app-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary bg-opacity-25 text-light p-2 rounded-2">
                                    <i class="bi <?= $acc->type === 'bank' ? 'bi-bank' : ($acc->type === 'cash' ? 'bi-cash-coin' : 'bi-safe') ?>"></i>
                                </span>
                                <div>
                                    <div class="fw-bold text-white small"><?= esc($acc->name) ?></div>
                                    <div class="text-secondary" style="font-size: 0.72rem; text-transform: uppercase;"><?= esc($acc->type) ?></div>
                                </div>
                            </div>
                            <i class="bi bi-chevron-right text-secondary small"></i>
                        </div>
                        <div class="h4 fw-bold text-white mb-0" style="font-family: var(--font-mono);">
                            € <?= number_format($acc->initial_balance / 100, 2, ',', '.') ?>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Main 2-Column Section: Transactions & Category Breakdown -->
<div class="row g-4">
    <!-- Recent Transactions Ledger (8 Cols) -->
    <div class="col-lg-8">
        <div class="app-card">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                <h2 class="h5 fw-bold text-white mb-0">
                    <i class="bi bi-clock-history me-2 text-success"></i> Transações Recentes
                </h2>
                <a href="<?= site_url('transactions') ?>" class="text-decoration-none small text-success">
                    Ver histórico completo &rarr;
                </a>
            </div>

            <?php if ($transactions === []) : ?>
                <div class="text-center py-5 text-secondary">
                    <i class="bi bi-receipt fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <p class="mb-3">Ainda não tens transações registadas.</p>
                    <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">Adicionar Primeira Transação</a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table custom-table">
                        <thead>
                            <tr>
                                <th>Transação</th>
                                <th>Conta</th>
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
                                        <span class="badge-account"><?= esc($tx->account_name ?? 'Millennium') ?></span>
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
    </div>

    <!-- Category Breakdown & Insights (4 Cols) -->
    <div class="col-lg-4">
        <!-- Expenses by Category -->
        <div class="app-card mb-4">
            <h2 class="h5 fw-bold text-white mb-3 pb-2 border-bottom border-secondary border-opacity-10">
                <i class="bi bi-pie-chart me-2 text-warning"></i> Despesas por Categoria
            </h2>

            <div class="d-flex flex-column gap-3">
                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white"><i class="bi bi-cart me-1 text-peach"></i> Alimentação & Supermercado</span>
                        <span class="text-secondary fw-bold">€ 165,00 (36%)</span>
                    </div>
                    <div class="progress bg-dark" style="height: 6px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 36%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white"><i class="bi bi-fuel-pump me-1 text-danger"></i> Transporte & Combustível</span>
                        <span class="text-secondary fw-bold">€ 95,00 (21%)</span>
                    </div>
                    <div class="progress bg-dark" style="height: 6px;">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 21%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white"><i class="bi bi-house me-1 text-info"></i> Habitação & Renda</span>
                        <span class="text-secondary fw-bold">€ 120,00 (26%)</span>
                    </div>
                    <div class="progress bg-dark" style="height: 6px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 26%;"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="text-white"><i class="bi bi-cup-hot me-1 text-success"></i> Lazer & Restaurantes</span>
                        <span class="text-secondary fw-bold">€ 79,50 (17%)</span>
                    </div>
                    <div class="progress bg-dark" style="height: 6px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 17%;"></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-10 text-center">
                <a href="<?= site_url('categories') ?>" class="text-decoration-none small text-success">
                    Configurar Categorias &rarr;
                </a>
            </div>
        </div>

        <!-- Architecture & Financial Principle Card -->
        <div class="app-card" style="background: radial-gradient(circle at top left, rgba(16, 185, 129, 0.1) 0%, rgba(14, 23, 30, 0.95) 100%);">
            <div class="d-flex align-items-center gap-2 mb-2 text-success small fw-bold">
                <i class="bi bi-lightbulb"></i> Filosofia Nivora
            </div>
            <blockquote class="mb-2 text-white small fst-italic">
                "Start simple. Earn the complexity."
            </blockquote>
            <p class="text-secondary small mb-0">
                Os valores são guardados em cêntimos inteiros, sem floats.
            </p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
