<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?><?= esc($account->name) ?> — Detalhes<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$transactions = $transactions ?? [];

$accountIncome   = 0;
$accountExpenses = 0;
foreach ($transactions as $tx) {
    if (strtoupper((string) $tx->type) === 'INCOME') {
        $accountIncome += $tx->amount;
    } else {
        $accountExpenses += $tx->amount;
    }
}
$calculatedBalance = ($account->initial_balance ?? 0) + $accountIncome - $accountExpenses;

$icon = $account->type === 'bank' ? 'bi-bank'
       : ($account->type === 'cash' ? 'bi-cash-coin'
       : ($account->type === 'credit' ? 'bi-credit-card' : 'bi-safe'));
?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                <i class="bi <?= $icon ?> me-1"></i> <?= esc($account->type) ?>
            </div>
            <h1><?= esc($account->name) ?></h1>
            <p>Conta criada a <?= date('d/m/Y', strtotime($account->created_at ?? 'now')) ?> • ID #<?= esc($account->id) ?></p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('accounts/' . $account->id . '/edit') ?>" class="btn-brand-primary">
            <i class="bi bi-pencil"></i> Editar
        </a>
        <a href="<?= site_url('accounts') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">

    <!-- Sumário da conta -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Saldo atual</div>
                <div class="n-metric-value">€ <?= number_format($calculatedBalance / 100, 2, ',', '.') ?></div>
                <div class="n-metric-meta">Inicial: € <?= number_format(($account->initial_balance ?? 0) / 100, 2, ',', '.') ?></div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Entradas</div>
                <div class="n-metric-value" style="color:#43B790;">+ € <?= number_format($accountIncome / 100, 2, ',', '.') ?></div>
                <div class="n-metric-meta pos">Créditos nesta conta</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="n-card">
                <div class="n-metric-label">Saídas</div>
                <div class="n-metric-value" style="color:#FF5C5C;">− € <?= number_format($accountExpenses / 100, 2, ',', '.') ?></div>
                <div class="n-metric-meta neg">Débitos nesta conta</div>
            </div>
        </div>
    </div>

    <!-- Movimentos -->
    <div class="n-card" style="height: auto;">
        <div class="n-card-head">
            <div>
                <p class="n-card-title">Movimentos desta Conta</p>
                <p class="n-card-sub"><?= count($transactions) ?> movimento<?= count($transactions) === 1 ? '' : 's' ?></p>
            </div>
            <a href="<?= site_url('transactions/new') ?>" class="n-link">+ Novo movimento</a>
        </div>

        <?php if ($transactions === []) : ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 d-block mb-3" style="color: rgba(245,245,245,0.2);"></i>
                <p style="color: rgba(245,245,245,0.5); font-size:0.9rem; margin-bottom:1rem;">Ainda não existem movimentos associados a esta conta.</p>
                <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary">
                    <i class="bi bi-plus-lg"></i> Criar primeira transação
                </a>
            </div>
        <?php else : ?>
            <div class="table-responsive">
                <table class="n-table">
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
                            <?php $isIncome = strtoupper((string) $tx->type) === 'INCOME'; ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="tx-icon">
                                            <i class="bi <?= $isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right' ?>"></i>
                                        </span>
                                        <span style="font-weight:500; color:#F5F5F5;"><?= esc($tx->description) ?></span>
                                    </div>
                                </td>
                                <td style="color: rgba(245,245,245,0.5);"><?= esc($tx->category_name ?? 'Geral') ?></td>
                                <td style="color: rgba(245,245,245,0.5); font-size:0.8rem; font-family: var(--font-mono);"><?= date('d/m/Y', strtotime($tx->transaction_date)) ?></td>
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

    <!-- Zona de perigo -->
    <div class="d-flex justify-content-end mt-3">
        <form action="<?= site_url('accounts/' . $account->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar a conta '<?= esc($account->name) ?>'?">
            <?= csrf_field() ?>
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit" class="btn-brand-outline" style="color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;">
                <i class="bi bi-trash"></i> Eliminar conta
            </button>
        </form>
    </div>

</div>
<?= $this->endSection() ?>