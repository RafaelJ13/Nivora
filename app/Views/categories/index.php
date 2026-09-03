<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Categorias<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$categories = $categories ?? [
    (object)['id' => 1, 'name' => 'Salário', 'type' => 'income', 'icon' => 'bi-wallet2', 'tx_count' => 1],
    (object)['id' => 2, 'name' => 'Freelance / Extras', 'type' => 'income', 'icon' => 'bi-laptop', 'tx_count' => 0],
    (object)['id' => 3, 'name' => 'Alimentação & Supermercado', 'type' => 'expense', 'icon' => 'bi-cart', 'tx_count' => 2],
    (object)['id' => 4, 'name' => 'Habitação & Renda', 'type' => 'expense', 'icon' => 'bi-house', 'tx_count' => 1],
    (object)['id' => 5, 'name' => 'Transporte & Combustível', 'type' => 'expense', 'icon' => 'bi-fuel-pump', 'tx_count' => 1],
    (object)['id' => 6, 'name' => 'Lazer & Restaurantes', 'type' => 'expense', 'icon' => 'bi-cup-hot', 'tx_count' => 1],
    (object)['id' => 7, 'name' => 'Saúde & Farmácia', 'type' => 'expense', 'icon' => 'bi-heart-pulse', 'tx_count' => 0],
    (object)['id' => 8, 'name' => 'Subscrições & Serviços', 'type' => 'expense', 'icon' => 'bi-tv', 'tx_count' => 0]
];

$expenseCategories = array_filter($categories, fn($c) => $c->type === 'expense');
$incomeCategories = array_filter($categories, fn($c) => $c->type === 'income');
?>

<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4 pb-2 border-bottom border-secondary border-opacity-10">
    <div>
        <div class="text-uppercase small fw-bold text-success mb-1" style="letter-spacing: 0.08em;">
            <i class="bi bi-tags"></i> Organização
        </div>
        <h1 class="h2 fw-bold text-white mb-1">Categorias</h1>
        <p class="text-secondary small mb-0">Escolhe uma categoria para cada movimento.</p>
    </div>
    <a class="btn-brand-primary" href="<?= site_url('categories/new') ?>">
        <i class="bi bi-plus-lg"></i> Nova Categoria
    </a>
</div>

<!-- Expense Categories -->
<div class="mb-5">
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-danger bg-opacity-20 text-danger p-2 rounded-circle">
            <i class="bi bi-arrow-down-left"></i>
        </span>
        <h2 class="h5 fw-bold text-white mb-0">Despesas</h2>
        <span class="badge bg-secondary bg-opacity-25 text-secondary small"><?= count($expenseCategories) ?></span>
    </div>

    <div class="row g-3">
        <?php foreach ($expenseCategories as $cat) : ?>
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="app-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-expense p-2 rounded-2 fs-6">
                                <i class="bi <?= esc($cat->icon ?? 'bi-tag') ?>"></i>
                            </span>
                            <span class="text-secondary small">#<?= esc($cat->id) ?></span>
                        </div>
                        <h3 class="h6 fw-bold text-white mb-1"><?= esc($cat->name) ?></h3>
                        <div class="text-secondary small"><?= $cat->tx_count ?? 0 ?> transações</div>
                    </div>

                    <div class="pt-2 mt-3 border-top border-secondary border-opacity-10 d-flex justify-content-end gap-2">
                        <a href="<?= site_url('categories/' . $cat->id . '/edit') ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?= site_url('categories/' . $cat->id) ?>" method="post" onsubmit="return confirm('Pretendes eliminar esta categoria?');" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-dark border border-danger border-opacity-50 text-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Income Categories -->
<div>
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="badge bg-success bg-opacity-20 text-success p-2 rounded-circle">
            <i class="bi bi-arrow-up-right"></i>
        </span>
        <h2 class="h5 fw-bold text-white mb-0">Rendimentos</h2>
        <span class="badge bg-secondary bg-opacity-25 text-secondary small"><?= count($incomeCategories) ?></span>
    </div>

    <div class="row g-3">
        <?php foreach ($incomeCategories as $cat) : ?>
            <div class="col-sm-6 col-lg-4 col-xl-3">
                <div class="app-card p-3 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge-income p-2 rounded-2 fs-6">
                                <i class="bi <?= esc($cat->icon ?? 'bi-wallet2') ?>"></i>
                            </span>
                            <span class="text-secondary small">#<?= esc($cat->id) ?></span>
                        </div>
                        <h3 class="h6 fw-bold text-white mb-1"><?= esc($cat->name) ?></h3>
                        <div class="text-secondary small"><?= $cat->tx_count ?? 0 ?> transações</div>
                    </div>

                    <div class="pt-2 mt-3 border-top border-secondary border-opacity-10 d-flex justify-content-end gap-2">
                        <a href="<?= site_url('categories/' . $cat->id . '/edit') ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary" title="Editar">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="<?= site_url('categories/' . $cat->id) ?>" method="post" onsubmit="return confirm('Pretendes eliminar esta categoria?');" class="d-inline">
                            <?= csrf_field() ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-sm btn-dark border border-danger border-opacity-50 text-danger" title="Eliminar">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?= $this->endSection() ?>
