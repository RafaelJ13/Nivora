<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Transação #<?= esc($transaction->id) ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php $isIncome = strtoupper($transaction->type) === 'INCOME'; ?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                Detalhe do movimento
            </div>
            <h1>Transação #<?= esc($transaction->id) ?></h1>
            <p>Visualiza os detalhes e gere este movimento.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('transactions') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="n-card" style="height: auto;">
                <div class="row g-5 align-items-center">

                    <!-- Coluna esquerda: valor + descrição -->
                    <div class="col-md-5" style="border-right: 1px solid rgba(245,245,245,0.06);">
                        <span class="<?= $isIncome ? 'badge-income' : 'badge-expense' ?> d-inline-block mb-3">
                            <i class="bi <?= $isIncome ? 'bi-plus-circle' : 'bi-dash-circle' ?> me-1"></i>
                            <?= $isIncome ? 'Rendimento' : 'Despesa' ?>
                        </span>
                        <div class="n-metric-value" style="font-size: 3rem; color: <?= $isIncome ? '#43B790' : '#FF5C5C' ?>; margin: 0.5rem 0 0.75rem;">
                            <?= $isIncome ? '+' : '−' ?> € <?= number_format($transaction->amount / 100, 2, ',', '.') ?>
                        </div>
                        <div style="color:#F5F5F5; font-weight:600; font-size:1.15rem;">
                            <?= esc($transaction->description) ?>
                        </div>
                    </div>

                    <!-- Coluna direita: detalhes + ações -->
                    <div class="col-md-7">
                        <div class="d-flex justify-content-between py-3" style="border-bottom: 1px solid rgba(245,245,245,0.05);">
                            <span style="color: rgba(245,245,245,0.5); font-size:0.85rem;">Conta</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500;"><?= esc($transaction->account_name ?? '—') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-3" style="border-bottom: 1px solid rgba(245,245,245,0.05);">
                            <span style="color: rgba(245,245,245,0.5); font-size:0.85rem;">Categoria</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500;"><?= esc($transaction->category_name ?? '—') ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-3 mb-4" style="border-bottom: 1px solid rgba(245,245,245,0.05);">
                            <span style="color: rgba(245,245,245,0.5); font-size:0.85rem;">Data</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500; font-family: var(--font-mono);">
                                <?= date('d/m/Y H:i', strtotime($transaction->transaction_date)) ?>
                            </span>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="<?= site_url('transactions/' . $transaction->id . '/edit') ?>" class="btn-brand-primary">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <form action="<?= site_url('transactions/' . $transaction->id) ?>" method="post" data-confirm="Eliminar esta transação?">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn-brand-outline" style="color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>