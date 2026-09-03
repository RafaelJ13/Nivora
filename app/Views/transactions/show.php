<?= $this->extend('app_layout') ?>

<?php
$transaction = $transaction ?? (object)[
    'id' => 1,
    'description' => 'Salário Mensal',
    'type' => 'income',
    'amount' => 280000,
    'account_id' => 1,
    'account_name' => 'Millennium BCP',
    'category_id' => 1,
    'category_name' => 'Salário',
    'date' => date('Y-m-01 10:30:00')
];

$isIncome = $transaction->type === 'income';
?>

<?= $this->section('title') ?>Recibo de Transação #<?= esc($transaction->id) ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-4">
            <a href="<?= site_url('transactions') ?>" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Voltar às Transações
            </a>
            <div class="text-uppercase small fw-bold text-success mt-2" style="letter-spacing: 0.08em;">
                Detalhe do movimento
            </div>
            <h1 class="h2 fw-bold text-white">Transação #<?= esc($transaction->id) ?></h1>
        </div>

        <div class="app-card position-relative overflow-hidden">
            <!-- Amount Banner -->
            <div class="text-center py-4 border-bottom border-secondary border-opacity-10 mb-4">
                <span class="<?= $isIncome ? 'badge-income' : 'badge-expense' ?> p-2 px-3 rounded-pill text-uppercase fw-bold small">
                    <i class="bi <?= $isIncome ? 'bi-plus-circle' : 'bi-dash-circle' ?> me-1"></i>
                    <?= $isIncome ? 'Rendimento' : 'Despesa' ?>
                </span>
                <div class="display-5 fw-bold my-3 <?= $isIncome ? 'text-success' : 'text-danger' ?>" style="font-family: var(--font-mono);">
                    <?= $isIncome ? '+' : '-' ?> € <?= number_format($transaction->amount / 100, 2, ',', '.') ?>
                </div>
                <div class="h5 text-white fw-semibold mb-0"><?= esc($transaction->description) ?></div>
            </div>

            <!-- Receipt Metadata List -->
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Conta</span>
                    <span class="badge-account"><?= esc($transaction->account_name ?? 'Millennium BCP') ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Categoria</span>
                    <span class="badge bg-dark border border-secondary border-opacity-25 text-white px-2 py-1">
                        <?= esc($transaction->category_name ?? 'Geral') ?>
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Data</span>
                    <span class="text-white fw-semibold"><?= date('d/m/Y', strtotime($transaction->date ?? 'now')) ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Valor guardado</span>
                    <code class="text-success"><?= number_format($transaction->amount, 0, '', '') ?> cêntimos (BIGINT)</code>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="d-flex justify-content-between align-items-center pt-2">
                <a href="<?= site_url('transactions/' . $transaction->id . '/edit') ?>" class="btn-brand-primary">
                    <i class="bi bi-pencil"></i> Editar Movimento
                </a>
                <form action="<?= site_url('transactions/' . $transaction->id) ?>" method="post" onsubmit="return confirm('Eliminar este movimento?');">
                    <?= csrf_field() ?>
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-dark border border-danger border-opacity-50 text-danger">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
