<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$accounts     = $accounts ?? [];
$transactions = $transactions ?? [];
$expensesByCategory = $expensesByCategory ?? [];
$totalIncomeCents   = $totalIncome ?? 0;
$totalExpensesCents = $totalExpenses ?? 0;
$totalBalanceCents  = $totalBalance ?? 0;
$netSavingsCents    = $totalIncomeCents - $totalExpensesCents;
$monthNames = [
    1 => 'janeiro',
    2 => 'fevereiro',
    3 => 'março',
    4 => 'abril',
    5 => 'maio',
    6 => 'junho',
    7 => 'julho',
    8 => 'agosto',
    9 => 'setembro',
    10 => 'outubro',
    11 => 'novembro',
    12 => 'dezembro',
];
?>

<!-- Wallet header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-end gap-3 mb-4">
    <div>
        <div class="text-uppercase small fw-bold text-success mb-2" style="letter-spacing: 0.1em;">Resumo de <?= $monthNames[(int) date('n')] ?> <?= date('Y') ?></div>
        <h1 class="h2 fw-bold text-white mb-1">Olá, <?= esc((function_exists('auth') && auth()->loggedIn()) ? (auth()->user()->name ?: auth()->user()->username) : 'Rafael') ?></h1>
        <p class="text-secondary small mb-0">O teu dinheiro, visto sem ruído.</p>
    </div>
    <span class="text-secondary small"><i class="bi bi-shield-check text-success me-1"></i> Dados privados por defeito</span>
</div>

<!-- Balance card and quick actions -->
<div class="row g-3 mb-4 align-items-stretch dashboard-balance-row">
    <div class="col-lg-7">
        <section class="wallet-balance h-100">
            <div class="d-flex justify-content-between align-items-start">
                <span class="wallet-label">Saldo total</span>
                <i class="bi bi-wallet2 wallet-mark" aria-hidden="true"></i>
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
                <a href="<?= site_url('accounts/create') ?>" class="quick-action"><span><i class="bi bi-wallet2"></i></span><small>Conta</small></a>
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

    <div class="accounts-carousel-shell">
        <button type="button" class="accounts-carousel-arrow accounts-carousel-arrow-prev" data-accounts-direction="prev" aria-label="Conta anterior">
            <i class="bi bi-chevron-left"></i>
        </button>
        <div class="accounts-carousel">
            <div class="row g-3 accounts-carousel-track">
            <?php if (empty($accounts)) : ?>
                <div class="col-12">
                    <a href="<?= site_url('accounts/create') ?>" class="text-decoration-none">
                        <div class="app-card p-4 text-center" style="border: 1.5px dashed rgba(139, 92, 246, 0.25); background: rgba(139, 92, 246, 0.03);">
                            <i class="bi bi-bank fs-2 d-block mb-2" style="color: rgba(139, 92, 246, 0.4);"></i>
                            <p class="text-secondary small mb-2">Ainda não tens contas registadas.</p>
                            <span class="small fw-semibold" style="color: #a78bfa;">+ Adicionar primeira conta</span>
                        </div>
                    </a>
                </div>
            <?php else : ?>
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
                                    € <?= number_format(($acc->current_balance ?? $acc->initial_balance) / 100, 2, ',', '.') ?>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
        <button type="button" class="accounts-carousel-arrow accounts-carousel-arrow-next" data-accounts-direction="next" aria-label="Conta seguinte">
            <i class="bi bi-chevron-right"></i>
        </button>
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
                                            <span class="<?= strtoupper($tx->type) === 'INCOME' ? 'badge-income' : 'badge-expense' ?> p-2 rounded-2">
                                                <i class="bi <?= strtoupper($tx->type) === 'INCOME' ? 'bi-plus-lg' : 'bi-dash-lg' ?>"></i>
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
                                        <?= date('d/m/Y', strtotime($tx->transaction_date)) ?>
                                    </td>
                                    <td class="text-end fw-bold" style="font-family: var(--font-mono);">
                                        <span class="<?= strtoupper($tx->type) === 'INCOME' ? 'text-success' : 'text-danger' ?>">
                                            <?= strtoupper($tx->type) === 'INCOME' ? '+' : '-' ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
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

            <?php if ($expensesByCategory === []) : ?>
                <div class="text-center py-4 text-secondary">
                    <i class="bi bi-pie-chart fs-2 d-block mb-2 opacity-50"></i>
                    <p class="small mb-3">Ainda não existem despesas neste mês.</p>
                    <a href="<?= site_url('transactions/new') ?>" class="text-decoration-none small text-success">
                        Registar uma despesa &rarr;
                    </a>
                </div>
            <?php else : ?>
                <?php foreach ($expensesByCategory as $categoryName => $amountCents) : ?>
                    <?php $percentage = $totalExpensesCents > 0 ? ($amountCents / $totalExpensesCents) * 100 : 0; ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-white small"><?= esc($categoryName) ?></span>
                            <span class="text-secondary small">
                                € <?= number_format($amountCents / 100, 2, ',', '.') ?>
                            </span>
                        </div>
                        <div class="progress" style="height: 6px; background: rgba(148, 163, 184, 0.15);">
                            <div class="progress-bar bg-warning" role="progressbar"
                                 style="width: <?= min(100, $percentage) ?>%;"
                                 aria-valuenow="<?= round($percentage, 1) ?>"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <div class="text-secondary mt-1" style="font-size: 0.72rem;">
                            <?= number_format($percentage, 1, ',', '.') ?>% das despesas
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Product principle card -->
        <div class="app-card" style="background: radial-gradient(circle at top left, rgba(16, 185, 129, 0.1) 0%, rgba(14, 23, 30, 0.95) 100%);">
            <div class="d-flex align-items-center gap-2 mb-2 text-success small fw-bold">
                <i class="bi bi-lightbulb"></i> Filosofia Nivora
            </div>
            <blockquote class="mb-2 text-white small fst-italic">
                "Clareza hoje. Mais controlo amanhã."
            </blockquote>
            <p class="text-secondary small mb-0">
                O Nivora ajuda-te a perceber para onde vai o teu dinheiro e a
                tomar decisões com mais confiança.
            </p>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.accounts-carousel');
    const track = document.querySelector('.accounts-carousel-track');
    const arrows = document.querySelectorAll('.accounts-carousel-arrow');
    if (!carousel || !track || !arrows.length) return;

    const updateArrows = () => {
        const maxScroll = track.scrollWidth - carousel.clientWidth;
        arrows.forEach(arrow => {
            const isPrevious = arrow.dataset.accountsDirection === 'prev';
            arrow.disabled = isPrevious ? carousel.scrollLeft <= 2 : carousel.scrollLeft >= maxScroll - 2;
        });
    };

    arrows.forEach(arrow => {
        arrow.addEventListener('click', () => {
            const distance = carousel.clientWidth;
            carousel.scrollBy({
                left: arrow.dataset.accountsDirection === 'prev' ? -distance : distance,
                behavior: 'smooth'
            });
        });
    });

    carousel.addEventListener('scroll', updateArrows, { passive: true });
    window.addEventListener('resize', updateArrows);
    updateArrows();
});
</script>
<?= $this->endSection() ?>
