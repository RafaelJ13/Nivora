<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Categorias<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$categories = $categories ?? [];

$expenseCategories = array_filter($categories, fn($c) => strtoupper($c->type) === 'EXPENSE');
$incomeCategories  = array_filter($categories, fn($c) => strtoupper($c->type) === 'INCOME');
?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                Organização
            </div>
            <h1>Categorias</h1>
            <p>Escolhe uma categoria para cada movimento.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('categories/new') ?>" class="btn-brand-primary">
            <i class="bi bi-plus-lg"></i> Nova Categoria
        </a>
    </div>
</div>

<div class="app-content">

    <!-- Despesas -->
    <div class="mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="tx-icon" style="background: rgba(255,92,92,0.12); color:#FF5C5C;">
                <i class="bi bi-arrow-up-right"></i>
            </span>
            <h2 style="font-size:1rem; font-weight:700; color:#F5F5F5; margin:0;">Despesas</h2>
            <span class="badge-account"><?= count($expenseCategories) ?></span>
        </div>

        <?php if (empty($expenseCategories)) : ?>
            <div class="n-card" style="height:auto; padding: 1.5rem; text-align:center;">
                <p style="color: rgba(245,245,245,0.45); font-size:0.85rem; margin:0 0 0.75rem;">Ainda não tens categorias de despesa.</p>
                <a href="<?= site_url('categories/new') ?>" class="n-link">+ Criar categoria</a>
            </div>
        <?php else : ?>
            <div class="row g-3">
                <?php foreach ($expenseCategories as $cat) : ?>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="n-card" style="height:auto; padding: 1.15rem;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="tx-icon" style="background: rgba(255,92,92,0.12); color:#FF5C5C;">
                                    <i class="bi <?= esc($cat->icon ?? 'bi-tag') ?>"></i>
                                </span>
                                <span style="color: rgba(245,245,245,0.35); font-size:0.72rem; font-family: var(--font-mono);">#<?= esc($cat->id) ?></span>
                            </div>
                            <h3 style="font-size:0.95rem; font-weight:600; color:#F5F5F5; margin:0 0 0.25rem;"><?= esc($cat->name) ?></h3>
                            <div style="color: rgba(245,245,245,0.4); font-size:0.75rem;"><?= $cat->tx_count ?? 0 ?> transações</div>

                            <div class="d-flex justify-content-end gap-2 mt-3 pt-3" style="border-top: 1px solid rgba(245,245,245,0.05);">
                                <a href="<?= site_url('categories/' . $cat->id . '/edit') ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem;" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?= site_url('categories/' . $cat->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar a categoria '<?= esc($cat->name) ?>'?" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem; color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Rendimentos -->
    <div>
        <div class="d-flex align-items-center gap-2 mb-3">
            <span class="tx-icon" style="background: rgba(67,183,144,0.12); color:#43B790;">
                <i class="bi bi-arrow-down-left"></i>
            </span>
            <h2 style="font-size:1rem; font-weight:700; color:#F5F5F5; margin:0;">Rendimentos</h2>
            <span class="badge-account"><?= count($incomeCategories) ?></span>
        </div>

        <?php if (empty($incomeCategories)) : ?>
            <div class="n-card" style="height:auto; padding: 1.5rem; text-align:center;">
                <p style="color: rgba(245,245,245,0.45); font-size:0.85rem; margin:0 0 0.75rem;">Ainda não tens categorias de rendimento.</p>
                <a href="<?= site_url('categories/new') ?>" class="n-link">+ Criar categoria</a>
            </div>
        <?php else : ?>
            <div class="row g-3">
                <?php foreach ($incomeCategories as $cat) : ?>
                    <div class="col-sm-6 col-lg-4 col-xl-3">
                        <div class="n-card" style="height:auto; padding: 1.15rem;">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <span class="tx-icon" style="background: rgba(67,183,144,0.12); color:#43B790;">
                                    <i class="bi <?= esc($cat->icon ?? 'bi-wallet2') ?>"></i>
                                </span>
                                <span style="color: rgba(245,245,245,0.35); font-size:0.72rem; font-family: var(--font-mono);">#<?= esc($cat->id) ?></span>
                            </div>
                            <h3 style="font-size:0.95rem; font-weight:600; color:#F5F5F5; margin:0 0 0.25rem;"><?= esc($cat->name) ?></h3>
                            <div style="color: rgba(245,245,245,0.4); font-size:0.75rem;"><?= $cat->tx_count ?? 0 ?> transações</div>

                            <div class="d-flex justify-content-end gap-2 mt-3 pt-3" style="border-top: 1px solid rgba(245,245,245,0.05);">
                                <a href="<?= site_url('categories/' . $cat->id . '/edit') ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem;" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="<?= site_url('categories/' . $cat->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar a categoria '<?= esc($cat->name) ?>'?" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem; color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

</div>
<?= $this->endSection() ?>