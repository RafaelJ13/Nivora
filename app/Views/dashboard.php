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
$savingsRate        = $totalIncomeCents > 0 ? round(($netSavingsCents / $totalIncomeCents) * 100, 1) : 0;
$budgetUsed         = $totalIncomeCents > 0 ? round(($totalExpensesCents / $totalIncomeCents) * 100) : 0;

$monthNames = [
    1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
    7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro',
];
$currentMonth = $monthNames[(int) date('n')] . ' ' . date('Y');
$displayName = (function_exists('auth') && auth()->loggedIn())
    ? (auth()->user()->name ?: auth()->user()->username)
    : 'Visitante';
?>

<!-- ============ TOPBAR ============ -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <h1>Olá, <?= esc($displayName) ?></h1>
            <p>O teu resumo financeiro de <?= esc($currentMonth) ?></p>
        </div>
    </div>
    <div class="topbar-actions">
        <span class="topbar-pill"><i class="bi bi-calendar3"></i> <?= esc(ucfirst($currentMonth)) ?></span>
        <span class="topbar-pill d-none d-md-inline-flex"><i class="bi bi-shield-check" style="color:#43B790;"></i> Privado</span>
    </div>
</div>

<!-- ============ GRID PRINCIPAL ============ -->
<div class="row g-3 mb-3">

    <!-- Saldo total -->
    <div class="col-lg-5 col-xl-4">
        <div class="n-card h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <span class="n-metric-label">Saldo total</span>
                <i class="bi bi-wallet2" style="color: rgba(245,245,245,0.3); font-size: 0.9rem;"></i>
            </div>
            <div class="n-metric-value">€ <?= number_format($totalBalanceCents / 100, 2, ',', '.') ?></div>
            <div class="n-metric-meta">
                <?= count($accounts) ?> conta<?= count($accounts) === 1 ? '' : 's' ?> ligada<?= count($accounts) === 1 ? '' : 's' ?>
            </div>
        </div>
    </div>

    <!-- Lista de contas -->
    <div class="col-lg-7 col-xl-5">
        <div class="n-card h-100">
            <div class="n-card-head">
                <div>
                    <p class="n-card-title">Contas</p>
                    <p class="n-card-sub">As tuas contas registadas</p>
                </div>
                <a href="<?= site_url('accounts') ?>" class="n-link">Ver todas</a>
            </div>

            <?php if (empty($accounts)) : ?>
                <p class="small mb-3" style="color: rgba(245,245,245,0.4);">Ainda não tens contas registadas.</p>
                <a href="<?= site_url('accounts/create') ?>" class="n-link">+ Adicionar conta</a>
            <?php else : ?>
                <div class="d-flex flex-column">
                    <?php foreach (array_slice($accounts, 0, 3) as $i => $acc) : ?>
                        <a href="<?= site_url('accounts/' . $acc->id) ?>" class="d-flex align-items-center justify-content-between text-decoration-none py-2 <?= $i > 0 ? 'border-top' : '' ?>" style="border-color: rgba(245,245,245,0.04) !important;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="tx-icon">
                                    <i class="bi <?= $acc->type === 'bank' ? 'bi-bank' : ($acc->type === 'cash' ? 'bi-cash-coin' : 'bi-safe') ?>"></i>
                                </span>
                                <div>
                                    <div style="font-size: 0.85rem; font-weight: 600; color: #F5F5F5;"><?= esc($acc->name) ?></div>
                                    <div style="font-size: 0.7rem; color: rgba(245,245,245,0.4); text-transform: capitalize;"><?= esc($acc->type) ?></div>
                                </div>
                            </div>
                            <span style="font-family: var(--font-mono); font-size: 0.85rem; font-weight: 600; color: #F5F5F5;">
                                € <?= number_format(($acc->current_balance ?? $acc->initial_balance) / 100, 2, ',', '.') ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Ações rápidas -->
    <div class="col-xl-3">
        <div class="n-card h-100">
            <p class="n-card-title mb-3">Ações rápidas</p>
            <a href="<?= site_url('transactions/new') ?>" class="qa-btn primary">
                <span><i class="bi bi-arrow-left-right me-2"></i> Transferir</span>
                <i class="bi bi-arrow-right"></i>
            </a>
            <a href="<?= site_url('accounts/create') ?>" class="qa-btn">
                <span><i class="bi bi-plus-lg me-2"></i> Adicionar conta</span>
                <i class="bi bi-arrow-right"></i>
            </a>
            <a href="<?= site_url('transactions') ?>" class="qa-btn">
                <span><i class="bi bi-list-ul me-2"></i> Histórico</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- ============ MÉTRICAS DO MÊS ============ -->
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="n-card">
            <div class="n-metric-label">Entradas do mês</div>
            <div class="n-metric-value" style="color: #43B790;">€ <?= number_format($totalIncomeCents / 100, 2, ',', '.') ?></div>
            <div class="n-metric-meta pos">Total recebido este mês</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="n-card">
            <div class="n-metric-label">Saídas do mês</div>
            <div class="n-metric-value" style="color: #FF5C5C;">€ <?= number_format($totalExpensesCents / 100, 2, ',', '.') ?></div>
            <div class="n-metric-meta neg">Total gasto este mês</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="n-card">
            <div class="n-metric-label">Poupança</div>
            <div class="n-metric-value">€ <?= number_format($netSavingsCents / 100, 2, ',', '.') ?></div>
            <div class="n-metric-meta <?= $savingsRate >= 0 ? 'pos' : 'neg' ?>">
                <?= $savingsRate ?>% do rendimento
            </div>
        </div>
    </div>
</div>

<!-- ============ TRANSAÇÕES + CATEGORIAS ============ -->
<div class="row g-3">

    <!-- Transações recentes -->
    <div class="col-lg-7">
        <div class="n-card h-100">
            <div class="n-card-head">
                <div>
                    <p class="n-card-title">Transações recentes</p>
                    <p class="n-card-sub">Últimos movimentos em todas as contas</p>
                </div>
                <a href="<?= site_url('transactions') ?>" class="n-link">Ver todas</a>
            </div>

            <?php if ($transactions === []) : ?>
                <div class="text-center py-4">
                    <i class="bi bi-receipt fs-2 d-block mb-2" style="color: rgba(245,245,245,0.2);"></i>
                    <p class="small mb-3" style="color: rgba(245,245,245,0.4);">Ainda não tens transações registadas.</p>
                    <a href="<?= site_url('transactions/new') ?>" class="n-link">+ Adicionar primeira transação</a>
                </div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="n-table">
                        <thead>
                            <tr>
                                <th>Descrição</th>
                                <th>Categoria</th>
                                <th>Data</th>
                                <th class="text-end">Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($transactions, 0, 6) as $tx) : ?>
                                <?php $isIncome = strtoupper($tx->type) === 'INCOME'; ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="tx-icon">
                                                <i class="bi <?= $isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right' ?>"></i>
                                            </span>
                                            <span style="font-weight: 600; color: #F5F5F5;"><?= esc($tx->description) ?></span>
                                        </div>
                                    </td>
                                    <td style="color: rgba(245,245,245,0.5);"><?= esc($tx->category_name ?? 'Geral') ?></td>
                                    <td style="color: rgba(245,245,245,0.5); font-size: 0.8rem; font-family: var(--font-mono);"><?= date('d/m', strtotime($tx->transaction_date)) ?></td>
                                    <td class="amount <?= $isIncome ? 'pos' : 'neg' ?>">
                                        <?= $isIncome ? '+' : '−' ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Gastos por categoria -->
    <div class="col-lg-5">
        <div class="n-card h-100">
            <div class="n-card-head">
                <div>
                    <p class="n-card-title">Gastos por categoria</p>
                    <p class="n-card-sub"><?= esc(ucfirst($currentMonth)) ?></p>
                </div>
            </div>

            <?php if ($expensesByCategory === []) : ?>
                <div class="text-center py-4">
                    <i class="bi bi-pie-chart fs-2 d-block mb-2" style="color: rgba(245,245,245,0.2);"></i>
                    <p class="small mb-3" style="color: rgba(245,245,245,0.4);">Ainda não existem despesas este mês.</p>
                    <a href="<?= site_url('transactions/new') ?>" class="n-link">+ Registar despesa</a>
                </div>
            <?php else : ?>
                <?php arsort($expensesByCategory); ?>
                <div class="d-flex justify-content-between align-items-end mb-3 pb-3" style="border-bottom: 1px solid rgba(245,245,245,0.05);">
                    <div>
                        <div class="n-metric-label">Total gasto</div>
                        <div class="n-metric-value" style="font-size: 1.5rem; margin: 0.4rem 0 0;">€ <?= number_format($totalExpensesCents / 100, 2, ',', '.') ?></div>
                    </div>
                    <div class="text-end">
                        <div style="font-size: 0.68rem; color: rgba(245,245,245,0.4);">do rendimento</div>
                        <div style="font-family: var(--font-mono); font-weight: 700; color: #43B790; font-size: 0.9rem;"><?= $budgetUsed ?>%</div>
                    </div>
                </div>

                <?php foreach ($expensesByCategory as $categoryName => $amountCents) : ?>
                    <?php $percentage = $totalExpensesCents > 0 ? ($amountCents / $totalExpensesCents) * 100 : 0; ?>
                    <div class="cat-row">
                        <div class="cat-row-head">
                            <span class="name">
                                <?= esc($categoryName) ?>
                                <span class="pct"><?= number_format($percentage, 0) ?>%</span>
                            </span>
                            <span class="val">€ <?= number_format($amountCents / 100, 2, ',', '.') ?></span>
                        </div>
                        <div class="cat-bar">
                            <div style="width: <?= min(100, $percentage) ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>