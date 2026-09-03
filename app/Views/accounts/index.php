<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Contas Financeiras<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$accounts = $accounts ?? [
    (object)[
        'id' => 1,
        'name' => 'Millennium BCP',
        'type' => 'bank',
        'initial_balance' => 200000, // 2000.00 €
        'created_at' => date('Y-01-15 10:00:00')
    ],
    (object)[
        'id' => 2,
        'name' => 'Revolut',
        'type' => 'bank',
        'initial_balance' => 50000, // 500.00 €
        'created_at' => date('Y-02-01 14:30:00')
    ],
    (object)[
        'id' => 3,
        'name' => 'Dinheiro em Carteira',
        'type' => 'cash',
        'initial_balance' => 15050, // 150.50 €
        'created_at' => date('Y-02-10 09:15:00')
    ]
];

$totalBalanceCents = 0;
foreach ($accounts as $acc) {
    $totalBalanceCents += $acc->initial_balance;
}
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom border-secondary border-opacity-10">
    <div>
        <div class="text-uppercase small fw-bold text-success mb-1" style="letter-spacing: 0.08em;">
            <i class="bi bi-wallet2"></i> Contas
        </div>
        <h1 class="h2 fw-bold text-white mb-1">As Tuas Contas</h1>
        <p class="text-secondary small mb-0">Consulta e gere as tuas contas.</p>
    </div>
    <a class="btn-brand-primary" href="<?= site_url('accounts/new') ?>">
        <i class="bi bi-plus-lg"></i> Adicionar Nova Conta
    </a>
</div>

<!-- Total Balance Summary Banner -->
<div class="app-card mb-4" style="background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.12) 0%, rgba(14, 23, 30, 0.95) 70%);">
    <div class="row align-items-center">
        <div class="col-md-7">
            <span class="text-secondary small text-uppercase fw-bold" style="letter-spacing: 0.06em;">Saldo total</span>
            <div class="display-6 fw-bold text-white my-1" style="font-family: var(--font-mono);">
                € <?= number_format($totalBalanceCents / 100, 2, ',', '.') ?>
            </div>
            <p class="text-secondary small mb-0">Os valores são guardados em cêntimos.</p>
        </div>
        <div class="col-md-5 text-md-end mt-3 mt-md-0">
            <span class="badge bg-dark border border-secondary border-opacity-25 px-3 py-2 text-secondary">
                <i class="bi bi-collection me-1 text-success"></i> <?= count($accounts) ?> contas configuradas
            </span>
        </div>
    </div>
</div>

<!-- Accounts Grid -->
<div class="row g-4">
    <?php if ($accounts === []) : ?>
        <div class="col-12">
            <div class="app-card text-center py-5">
                <i class="bi bi-bank fs-1 text-secondary opacity-50 d-block mb-3"></i>
                <h3 class="h5 fw-bold text-white mb-2">Ainda não tens contas</h3>
                <p class="text-secondary small mb-4">Adiciona uma conta para começares.</p>
                <a class="btn-brand-primary" href="<?= site_url('accounts/new') ?>">
                    <i class="bi bi-plus-lg"></i> Criar Primeira Conta
                </a>
            </div>
        </div>
    <?php else : ?>
        <?php foreach ($accounts as $account) : ?>
            <div class="col-md-6 col-lg-4">
                <div class="app-card h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-secondary bg-opacity-15 text-light border border-secondary border-opacity-20 px-2 py-1 small text-uppercase" style="letter-spacing: 0.05em;">
                                <i class="bi <?= $account->type === 'bank' ? 'bi-bank' : ($account->type === 'cash' ? 'bi-cash-coin' : 'bi-safe') ?> me-1 text-success"></i>
                                <?= esc($account->type) ?>
                            </span>
                            <span class="text-secondary small">#<?= esc($account->id) ?></span>
                        </div>

                        <h2 class="h5 fw-bold text-white mb-2"><?= esc($account->name) ?></h2>
                        <div class="h3 fw-bold text-white mb-3" style="font-family: var(--font-mono);">
                            € <?= number_format($account->initial_balance / 100, 2, ',', '.') ?>
                        </div>
                        <div class="text-secondary small mb-4">
                            Saldo Inicial: <span class="text-white"><?= number_format($account->initial_balance, 0, '', ' ') ?> cêntimos</span>
                        </div>
                    </div>

                    <div class="pt-3 border-top border-secondary border-opacity-10 d-flex justify-content-between align-items-center">
                        <a class="btn-brand-outline py-1 px-3" style="font-size: 0.8rem;" href="<?= site_url('accounts/' . $account->id) ?>">
                            <i class="bi bi-eye"></i> Detalhes
                        </a>
                        <div class="d-flex gap-2">
                            <a class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary" href="<?= site_url('accounts/' . $account->id . '/edit') ?>" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= site_url('accounts/' . $account->id) ?>" method="post" onsubmit="return confirm('Eliminar esta conta?');" class="d-inline">
                                <?= csrf_field() ?>
                                <input type="hidden" name="_method" value="DELETE">
                                <button class="btn btn-sm btn-dark border border-danger border-opacity-50 text-danger" type="submit" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
