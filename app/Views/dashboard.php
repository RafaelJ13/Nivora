<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$accounts     = $accounts ?? [];
$transactions = $transactions ?? [];
$recentMovements = $recentMovements ?? $transactions;
$expensesByCategory = $expensesByCategory ?? [];
$totalIncomeCents   = $totalIncome ?? 0;
$totalExpensesCents = $totalExpenses ?? 0;
$totalBalanceCents  = $totalBalance ?? 0;
$netSavingsCents    = $totalIncomeCents - $totalExpensesCents;
$savingsRate        = $totalIncomeCents > 0 ? round(($netSavingsCents / $totalIncomeCents) * 100, 1) : 0;
$budgetUsed         = $totalIncomeCents > 0 ? round(($totalExpensesCents / $totalIncomeCents) * 100) : 0;
$cashFlowWeeks = $cashFlowWeeks ?? [];

$cashFlowMaximum = 0;
$recentCashIncome = 0;
$recentCashExpenses = 0;
foreach ($cashFlowWeeks as $week) {
    $cashFlowMaximum = max($cashFlowMaximum, $week['income'], $week['expenses']);
    $recentCashIncome += $week['income'];
    $recentCashExpenses += $week['expenses'];
}
$cashFlowMaximum = max($cashFlowMaximum, 1);
$recentCashNet = $recentCashIncome - $recentCashExpenses;
$incomeSparkMaximum = max(array_column($cashFlowWeeks, 'income') ?: [0]) ?: 1;
$expenseSparkMaximum = max(array_column($cashFlowWeeks, 'expenses') ?: [0]) ?: 1;
$savingsSparkMaximum = max(array_map(
    static fn (array $week): int => abs($week['income'] - $week['expenses']),
    $cashFlowWeeks ?: [['income' => 0, 'expenses' => 0]]
)) ?: 1;

$monthNames = [
    1=>'janeiro',2=>'fevereiro',3=>'março',4=>'abril',5=>'maio',6=>'junho',
    7=>'julho',8=>'agosto',9=>'setembro',10=>'outubro',11=>'novembro',12=>'dezembro',
];
$currentMonth = $monthNames[(int) date('n')] . ' ' . date('Y');
$displayName = (function_exists('auth') && auth()->loggedIn())
    ? (auth()->user()->name ?: auth()->user()->username)
    : 'Visitante';
?>

<div class="dashboard-page">
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
    <div class="col-lg-5 col-xl-5">
        <div class="n-card dashboard-balance-card h-100">
            <div class="dashboard-balance-head">
                <span class="n-metric-label">Saldo total</span>
                <span class="dashboard-balance-status"><i class="bi bi-circle-fill"></i> Atualizado agora</span>
            </div>
            <div class="dashboard-balance-value">€ <?= number_format($totalBalanceCents / 100, 2, ',', '.') ?></div>
            <div class="dashboard-balance-meta">
                <?php if ($balanceChangePercent === null) : ?>
                    <span class="dashboard-balance-change neutral">—%</span>
                    <span class="dashboard-balance-change-label">sem referência do mês anterior</span>
                <?php else : ?>
                    <span class="dashboard-balance-change <?= $balanceChangePercent >= 0 ? 'positive' : 'negative' ?>">
                        <i class="bi <?= $balanceChangePercent >= 0 ? 'bi-arrow-up-right' : 'bi-arrow-down-right' ?>" aria-hidden="true"></i>
                        <?= $balanceChangePercent >= 0 ? '+' : '' ?><?= number_format($balanceChangePercent, 1, ',', '.') ?>%
                    </span>
                    <span class="dashboard-balance-change-label">vs mês anterior</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Lista de contas -->
    <div class="col-lg-7 col-xl-4">
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
                <?php
                $accountVisuals = [
                    'bank' => ['icon' => 'bi-bank', 'color' => '#43B790', 'background' => 'rgba(67,183,144,0.12)'],
                    'savings' => ['icon' => 'bi-safe', 'color' => '#60A5FA', 'background' => 'rgba(96,165,250,0.12)'],
                    'cash' => ['icon' => 'bi-cash-coin', 'color' => '#F3B562', 'background' => 'rgba(243,181,98,0.12)'],
                    'credit' => ['icon' => 'bi-credit-card', 'color' => '#FF5C5C', 'background' => 'rgba(255,92,92,0.12)'],
                ];
                ?>
                <div class="d-flex flex-column">
                    <?php foreach (array_slice($accounts, 0, 3) as $i => $acc) : ?>
                        <?php $accountVisual = $accountVisuals[strtolower((string) $acc->type)] ?? $accountVisuals['bank']; ?>
                        <a href="<?= site_url('accounts/' . $acc->id) ?>" class="d-flex align-items-center justify-content-between text-decoration-none py-2 <?= $i > 0 ? 'border-top' : '' ?>" style="border-color: rgba(245,245,245,0.04) !important;">
                            <div class="d-flex align-items-center gap-3">
                                <span class="tx-icon" style="background: <?= $accountVisual['background'] ?>; color: <?= $accountVisual['color'] ?>;">
                                    <i class="bi <?= $accountVisual['icon'] ?>"></i>
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
            <div class="dashboard-metric-head">
                <div class="n-metric-label">Entradas do mês</div>
                <span class="dashboard-metric-icon income" aria-hidden="true"><i class="bi bi-arrow-down-left"></i></span>
            </div>
            <div class="dashboard-metric-body">
                <div>
                    <div class="n-metric-value" style="color: #43B790;">€ <?= number_format($totalIncomeCents / 100, 2, ',', '.') ?></div>
                    <div class="n-metric-meta pos">Total recebido este mês</div>
                </div>
                <div class="dashboard-sparkline income" role="img" aria-label="Entradas das últimas cinco semanas">
                    <?php foreach ($cashFlowWeeks as $weekIndex => $week) : ?>
                        <?php $height = $week['income'] > 0 ? max(10, (int) round($week['income'] / $incomeSparkMaximum * 100)) : 0; ?>
                        <?php $barOpacity = count($cashFlowWeeks) > 1 ? 0.3 + 0.7 * ($weekIndex / (count($cashFlowWeeks) - 1)) : 1; ?>
                        <?php if ($height > 0) : ?>
                            <span style="height: <?= $height ?>%; opacity: <?= number_format($barOpacity, 2) ?>;" title="<?= esc($week['label']) ?>: € <?= number_format($week['income'] / 100, 2, ',', '.') ?>"></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="n-card">
            <div class="dashboard-metric-head">
                <div class="n-metric-label">Saídas do mês</div>
                <span class="dashboard-metric-icon expense" aria-hidden="true"><i class="bi bi-arrow-up-right"></i></span>
            </div>
            <div class="dashboard-metric-body">
                <div>
                    <div class="n-metric-value" style="color: #FF5C5C;">€ <?= number_format($totalExpensesCents / 100, 2, ',', '.') ?></div>
                    <div class="n-metric-meta neg">Total gasto este mês</div>
                </div>
                <div class="dashboard-sparkline expense" role="img" aria-label="Saídas das últimas cinco semanas">
                    <?php foreach ($cashFlowWeeks as $weekIndex => $week) : ?>
                        <?php $height = $week['expenses'] > 0 ? max(10, (int) round($week['expenses'] / $expenseSparkMaximum * 100)) : 0; ?>
                        <?php $barOpacity = count($cashFlowWeeks) > 1 ? 0.3 + 0.7 * ($weekIndex / (count($cashFlowWeeks) - 1)) : 1; ?>
                        <?php if ($height > 0) : ?>
                            <span style="height: <?= $height ?>%; opacity: <?= number_format($barOpacity, 2) ?>;" title="<?= esc($week['label']) ?>: € <?= number_format($week['expenses'] / 100, 2, ',', '.') ?>"></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="n-card">
            <div class="dashboard-metric-head">
                <div class="n-metric-label">Poupança</div>
                <span class="dashboard-metric-icon savings" aria-hidden="true"><i class="bi bi-safe"></i></span>
            </div>
            <div class="dashboard-metric-body">
                <div>
                    <div class="n-metric-value">€ <?= number_format($netSavingsCents / 100, 2, ',', '.') ?></div>
                    <div class="n-metric-meta <?= $savingsRate >= 0 ? 'pos' : 'neg' ?>">
                        <?= $savingsRate ?>% do rendimento
                    </div>
                </div>
                <div class="dashboard-sparkline savings" role="img" aria-label="Poupança líquida das últimas cinco semanas">
                    <?php foreach ($cashFlowWeeks as $weekIndex => $week) : ?>
                        <?php
                        $weeklySavings = $week['income'] - $week['expenses'];
                        $height = $weeklySavings !== 0 ? max(10, (int) round(abs($weeklySavings) / $savingsSparkMaximum * 100)) : 0;
                        $barOpacity = count($cashFlowWeeks) > 1 ? 0.3 + 0.7 * ($weekIndex / (count($cashFlowWeeks) - 1)) : 1;
                        ?>
                        <?php if ($height > 0) : ?>
                            <span class="<?= $weeklySavings < 0 ? 'negative' : '' ?>" style="height: <?= $height ?>%; opacity: <?= number_format($barOpacity, 2) ?>;" title="<?= esc($week['label']) ?>: € <?= number_format($weeklySavings / 100, 2, ',', '.') ?>"></span>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ FLUXO DE CAIXA RECENTE ============ -->
<div class="n-card dashboard-cashflow-card mb-3">
    <div class="dashboard-cashflow-chart">
        <div class="n-card-head">
            <div>
                <p class="n-card-title">Fluxo de caixa</p>
                <p class="n-card-sub">Entradas e saídas nas últimas cinco semanas</p>
            </div>
            <div class="dashboard-chart-legend">
                <span><i class="dashboard-legend-income"></i> Entradas</span>
                <span><i class="dashboard-legend-expense"></i> Saídas</span>
            </div>
        </div>

        <?php if ($cashFlowWeeks === []) : ?>
            <div class="dashboard-chart-empty">Ainda não existem movimentos para apresentar.</div>
        <?php else : ?>
            <div class="dashboard-chart" role="img" aria-label="Gráfico de barras agrupadas com entradas e saídas nas últimas cinco semanas">
                <div class="dashboard-chart-y-axis" aria-hidden="true">
                    <span>€ <?= number_format($cashFlowMaximum / 100, 0, ',', '.') ?></span>
                    <span>€ <?= number_format($cashFlowMaximum / 200, 0, ',', '.') ?></span>
                    <span>€ 0</span>
                </div>
                <div class="dashboard-chart-main">
                    <div class="dashboard-chart-plot">
                        <div class="dashboard-chart-grid" aria-hidden="true">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="dashboard-chart-columns">
                            <?php foreach ($cashFlowWeeks as $week) : ?>
                                <div class="dashboard-chart-group">
                                    <div class="dashboard-chart-bars">
                                        <span class="dashboard-chart-bar income" style="height: <?= $week['income'] > 0 ? max(3, round($week['income'] / $cashFlowMaximum * 100, 2)) : 0 ?>%;" title="Entradas <?= esc($week['label']) ?>: € <?= number_format($week['income'] / 100, 2, ',', '.') ?>"></span>
                                        <span class="dashboard-chart-bar expense" style="height: <?= $week['expenses'] > 0 ? max(3, round($week['expenses'] / $cashFlowMaximum * 100, 2)) : 0 ?>%;" title="Saídas <?= esc($week['label']) ?>: € <?= number_format($week['expenses'] / 100, 2, ',', '.') ?>"></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="dashboard-chart-dates">
                        <?php foreach ($cashFlowWeeks as $week) : ?>
                            <span><?= esc($week['label']) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <aside class="dashboard-cashflow-summary">
        <div class="n-metric-label">Saldo líquido em 5 semanas</div>
        <div class="dashboard-cashflow-net <?= $recentCashNet >= 0 ? 'positive' : 'negative' ?>">
            <?= $recentCashNet >= 0 ? '+' : '−' ?> € <?= number_format(abs($recentCashNet) / 100, 2, ',', '.') ?>
        </div>
        <p>Resultado acumulado das últimas cinco semanas</p>
        <a href="<?= site_url('transactions') ?>" class="btn-brand-outline justify-content-center">Ver movimentos <i class="bi bi-arrow-right"></i></a>
    </aside>
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

            <?php if ($recentMovements === []) : ?>
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
                            <?php foreach ($recentMovements as $tx) : ?>
                                <?php
                                $isTransfer = ($tx->row_type ?? '') === 'TRANSFER' || strtoupper((string) $tx->type) === 'TRANSFER';
                                $isIncome = strtoupper((string) $tx->type) === 'INCOME';
                                $movementColor = $isTransfer ? '#F3B562' : ($isIncome ? '#43B790' : '#FF5C5C');
                                $movementBackground = $isTransfer
                                    ? 'rgba(243,181,98,0.12)'
                                    : ($isIncome ? 'rgba(67,183,144,0.12)' : 'rgba(255,92,92,0.12)');
                                $movementIcon = $isTransfer
                                    ? 'bi-arrow-left-right'
                                    : ($isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right');
                                $movementDate = $isTransfer ? $tx->transfer_date : $tx->transaction_date;
                                ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="tx-icon" style="color: <?= $movementColor ?>; background: <?= $movementBackground ?>;">
                                                <i class="bi <?= $movementIcon ?>"></i>
                                            </span>
                                            <span style="font-weight: 600; color: #F5F5F5;"><?= esc($isTransfer ? 'Transferência' : $tx->description) ?></span>
                                        </div>
                                    </td>
                                    <td style="color: rgba(245,245,245,0.5);">
                                        <?= esc($isTransfer ? $tx->account_from_name . ' → ' . $tx->account_to_name : ($tx->category_name ?? 'Geral')) ?>
                                    </td>
                                    <td style="color: rgba(245,245,245,0.5); font-size: 0.8rem; font-family: var(--font-mono);"><?= date('d/m', strtotime($movementDate)) ?></td>
                                    <td class="amount <?= $isTransfer ? '' : ($isIncome ? 'pos' : 'neg') ?>">
                                        <?= $isTransfer ? '' : ($isIncome ? '+' : '−') ?> € <?= number_format($tx->amount / 100, 2, ',', '.') ?>
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
                        <div class="n-metric-value expense" style="font-size: 1.5rem; margin: 0.4rem 0 0;">€ <?= number_format($totalExpensesCents / 100, 2, ',', '.') ?></div>
                    </div>
                    <div class="text-end">
                        <div style="font-size: 0.68rem; color: rgba(245,245,245,0.4);">do rendimento</div>
                        <div style="font-family: var(--font-mono); font-weight: 700; color: #FF5C5C; font-size: 0.9rem;"><?= $budgetUsed ?>%</div>
                    </div>
                </div>

                <?php
                $expenseColors = ['#FF5C5C', '#F87171', '#EF4444', '#FB7185', '#E11D48'];
                ?>
                <?php foreach ($expensesByCategory as $categoryName => $amountCents) :
                    $percentage = $totalExpensesCents > 0 ? ($amountCents / $totalExpensesCents) * 100 : 0;
                    $categoryColor = $expenseColors[crc32((string) $categoryName) % count($expenseColors)];
                ?>
                    <div class="cat-row" style="--category-color: <?= $categoryColor ?>;">
                        <div class="cat-row-head">
                            <span class="name d-flex align-items-center">
                                <span class="cat-row-icon" aria-hidden="true"><i class="bi bi-tag-fill"></i></span>
                                <span>
                                    <?= esc($categoryName) ?>
                                    <span class="pct"><?= number_format($percentage, 0) ?>%</span>
                                </span>
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

</div>

<style>
    .dashboard-metric-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 28px;
        margin-bottom: 0.35rem;
    }

    .dashboard-metric-icon {
        display: inline-flex;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        border-radius: 7px;
        font-size: 0.85rem;
    }

    .dashboard-metric-icon.income {
        color: #43B790;
        background: rgba(67,183,144,0.12);
    }

    .dashboard-metric-icon.expense {
        color: #FF5C5C;
        background: rgba(255,92,92,0.12);
    }

    .dashboard-metric-icon.savings {
        color: #60A5FA;
        background: rgba(96,165,250,0.12);
    }

    .dashboard-balance-card {
        container-type: inline-size;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.35rem;
        background: linear-gradient(135deg, #181a19 0%, #141414 70%) !important;
    }

    .dashboard-balance-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 0.55rem;
    }

    .dashboard-balance-head .n-metric-label {
        color: rgba(245,245,245,0.7) !important;
        font-size: 0.75rem;
        font-weight: 400;
        letter-spacing: 0;
        text-transform: none;
    }

    .dashboard-balance-status {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        color: rgba(245,245,245,0.48);
        font-size: 0.7rem;
        white-space: nowrap;
    }

    .dashboard-balance-status i {
        color: #43B790;
        font-size: 0.4rem;
    }

    .dashboard-balance-value {
        color: #F5F5F5;
        font-family: var(--font-main);
        font-size: clamp(2.6rem, 10cqi, 5rem);
        font-weight: 500;
        letter-spacing: -0.045em;
        line-height: 1;
        white-space: nowrap;
    }

    .dashboard-balance-meta {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        margin-top: 0.55rem;
    }

    .dashboard-balance-change {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.32rem 0.65rem;
        border-radius: 999px;
        background: rgba(245,245,245,0.06);
        font-size: 0.9rem;
        font-weight: 500;
        white-space: nowrap;
    }

    .dashboard-balance-change.positive { color: #43B790; background: rgba(67,183,144,0.1); }
    .dashboard-balance-change.negative { color: #FF5C5C; background: rgba(255,92,92,0.1); }
    .dashboard-balance-change.neutral { color: rgba(245,245,245,0.48); }

    .dashboard-balance-change-label {
        color: rgba(245,245,245,0.58);
        font-size: 0.9rem;
    }

    .dashboard-metric-body {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 2rem;
    }

    .dashboard-sparkline {
        height: 38px;
        flex: 0 0 28%;
        min-width: 54px;
        display: flex;
        align-items: flex-end;
        gap: 2px;
        padding-bottom: 2px;
    }

    .dashboard-sparkline span {
        flex: 1 1 0;
        width: auto;
        min-height: 0;
        border-radius: 2px 2px 0 0;
        background: #43B790;
    }

    .dashboard-sparkline.expense span { background: #FF5C5C; }
    .dashboard-sparkline.savings span { background: #43B790; }
    .dashboard-sparkline.savings span.negative { background: #FF5C5C; }

    .dashboard-cashflow-card {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 275px;
        align-items: stretch;
        width: 100%;
        height: auto;
        min-height: 0;
        padding: 1.25rem;
    }

    .dashboard-cashflow-chart {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        min-width: 0;
        padding-right: 1.25rem;
    }

    .dashboard-cashflow-chart .n-card-head {
        margin-bottom: 0.75rem;
    }

    .dashboard-chart-legend {
        display: flex;
        align-items: center;
        gap: 0.9rem;
        color: rgba(245,245,245,0.5);
        font-size: 0.62rem;
    }

    .dashboard-chart-legend span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .dashboard-chart-legend i {
        width: 7px;
        height: 7px;
        border-radius: 2px;
    }

    .dashboard-legend-income { background: #43B790; }
    .dashboard-legend-expense { background: #FF5C5C; }

    .dashboard-chart {
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 0.55rem;
        height: 160px;
        min-width: 0;
    }

    .dashboard-chart-y-axis {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 0 0 20px;
        color: rgba(245,245,245,0.35);
        font-family: var(--font-mono);
        font-size: 0.68rem;
        text-align: right;
    }

    .dashboard-chart-main {
        display: flex;
        flex-direction: column;
        min-width: 0;
        min-height: 0;
    }

    .dashboard-chart-plot {
        position: relative;
        flex: 1 1 auto;
        min-height: 0;
    }

    .dashboard-chart-grid {
        position: absolute;
        inset: 0 0 1px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        pointer-events: none;
    }

    .dashboard-chart-grid span {
        border-top: 1px dashed rgba(245,245,245,0.08);
    }

    .dashboard-chart-columns {
        position: absolute;
        inset: 0;
        z-index: 1;
        display: flex;
        align-items: flex-end;
        justify-content: space-around;
    }

    .dashboard-chart-group {
        display: flex;
        flex: 1;
        min-width: 0;
        height: 100%;
        align-items: flex-end;
        justify-content: center;
    }

    .dashboard-chart-bars {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 10px;
        width: 90%;
        height: 90%;
    }

    .dashboard-chart-bar {
        width: 100%;
        min-height: 0;
        border-radius: 3px 3px 0 0;
        transition: height 0.25s ease;
    }

    .dashboard-chart-bar.income { background: #43B790; }
    .dashboard-chart-bar.expense { background: #FF5C5C; }

    .dashboard-chart-dates {
        display: flex;
        flex: 0 0 20px;
        justify-content: space-around;
        min-width: 0;
    }

    .dashboard-chart-dates span {
        flex: 1;
        color: rgba(245,245,245,0.4);
        font-family: var(--font-mono);
        font-size: 0.65rem;
        text-align: center;
    }

    .dashboard-cashflow-summary {
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding-left: 1.25rem;
        border-left: 1px solid rgba(245,245,245,0.08);
    }

    .dashboard-cashflow-net {
        margin-top: 0.4rem;
        font-family: var(--font-mono);
        font-size: 1.85rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .dashboard-cashflow-net.positive { color: #43B790; }
    .dashboard-cashflow-net.negative { color: #FF5C5C; }

    .dashboard-cashflow-summary p {
        margin: 0.5rem 0 1rem;
        color: rgba(245,245,245,0.45);
        font-size: 0.78rem;
    }

    @media (max-width: 991.98px) {
        .dashboard-cashflow-card { grid-template-columns: minmax(0, 1fr); }
        .dashboard-cashflow-chart { padding: 0 0 1rem; }
        .dashboard-cashflow-summary {
            padding: 1rem 0 0;
            border-top: 1px solid rgba(245,245,245,0.08);
            border-left: 0;
        }
        .dashboard-cashflow-summary .btn-brand-outline { align-self: flex-start; }
        .dashboard-metric-body { gap: 0.75rem; min-width: 0; }
        .dashboard-metric-body > div:first-child { min-width: 0; }
        .dashboard-metric-body .n-metric-value {
            font-size: clamp(1.2rem, 4vw, 2.1rem) !important;
            white-space: nowrap;
        }
        .dashboard-sparkline { flex-basis: 24%; min-width: 38px; }
        .dashboard-balance-value { font-size: clamp(1.75rem, 9cqi, 4rem); }
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {
        .dashboard-page .row > [class*="col-"],
        .dashboard-page .n-card { min-width: 0; }
        .dashboard-balance-card { padding: 1rem; }
        .dashboard-balance-head { flex-wrap: wrap; gap: 0.5rem; }
        .dashboard-balance-value { font-size: clamp(1.6rem, 3.2vw, 2.6rem); }
        .dashboard-balance-change,
        .dashboard-balance-change-label { font-size: 0.8rem; }
        .dashboard-balance-change { padding: 0.25rem 0.5rem; }
        .dashboard-metric-body { gap: 0.6rem; min-width: 0; }
        .dashboard-metric-body > div:first-child { min-width: 0; }
        .dashboard-metric-body .n-metric-value { font-size: clamp(1.35rem, 2.6vw, 2rem) !important; }
        .dashboard-sparkline { flex-basis: 24%; min-width: 36px; }
    }

    .dashboard-chart-empty {
        display: grid;
        min-height: 130px;
        place-items: center;
        color: rgba(245,245,245,0.4);
        font-size: 0.85rem;
    }

    @media (max-width: 420px) {
        .dashboard-metric-body { align-items: flex-end; }
        .dashboard-sparkline { min-width: 42px; gap: 2px; }
        .dashboard-chart { grid-template-columns: 34px minmax(0, 1fr); }
        .dashboard-chart-dates span { font-size: 0.55rem; }
    }

    @media (min-width: 1200px) and (min-height: 850px) {
        .app-main { height: 100vh; }
        .app-content {
            display: flex;
            flex-direction: column;
            min-height: 0;
            padding: 0 1.5rem 0.85rem;
        }
        .dashboard-page {
            display: grid;
            flex: 1;
            min-height: 0;
            grid-template-rows: auto minmax(145px, 0.75fr) minmax(110px, 0.55fr) minmax(180px, 1fr) minmax(230px, 1.25fr);
            gap: 0.65rem;
        }
        .app-topbar { padding: 1rem 0 0.65rem; }
        .app-topbar h1 { font-size: 1.55rem; }
        .dashboard-page > .row.g-3.mb-3 {
            --bs-gutter-y: 0.65rem;
            min-height: 0;
            margin-bottom: 0 !important;
        }
        .dashboard-page > .dashboard-cashflow-card.mb-3 {
            min-height: 0;
            margin-bottom: 0 !important;
            padding: 0.9rem;
        }
        .dashboard-page > .row > [class*="col-"] { min-height: 0; }
        .dashboard-balance-card { padding: 1rem; }
        .dashboard-page .n-card:not(.dashboard-balance-card) { padding: 0.8rem 0.9rem; }
        .dashboard-page .n-card-head { margin-bottom: 0.55rem; }
        .dashboard-balance-value { font-size: clamp(2rem, 8cqi, 4rem); }
        .dashboard-metric-head { margin-bottom: 0.2rem; }
        .dashboard-metric-body { gap: 1rem; }
        .dashboard-metric-body .n-metric-value { font-size: 2rem !important; }
        .dashboard-sparkline { height: 26px; }
        .dashboard-chart { height: 100%; min-height: 0; }
        .dashboard-cashflow-chart .n-card-head { margin-bottom: 0.45rem; }
        .dashboard-cashflow-net { font-size: 1.55rem; }
        .dashboard-cashflow-summary p { margin: 0.25rem 0 0.45rem; }
        .dashboard-page .n-table th { padding: 0.3rem 0.4rem; }
        .dashboard-page .n-table td { padding: 0.3rem 0.4rem; font-size: 0.8rem; }
        .dashboard-page .cat-row { margin-bottom: 0.35rem; }
        .dashboard-page .cat-row-head { margin-bottom: 0.25rem; font-size: 0.78rem; }
        .dashboard-page .cat-row-icon { width: 24px; height: 24px; }
        .dashboard-page .qa-btn { padding: 0.5rem 0.7rem; margin-bottom: 0.3rem; }
    }
</style>

<?= $this->endSection() ?>