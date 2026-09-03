<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($category) && $category !== null;
$pageTitle = $isEdit ? 'Editar Categoria: ' . esc($category->name) : 'Adicionar Nova Categoria';
?>

<?= $this->section('title') ?><?= $pageTitle ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-4">
            <a href="<?= site_url('categories') ?>" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Voltar às Categorias
            </a>
            <div class="text-uppercase small fw-bold text-success mt-2" style="letter-spacing: 0.08em;">
                <?= $isEdit ? 'Atualizar Classificação' : 'Nova Classificação' ?>
            </div>
            <h1 class="h2 fw-bold text-white"><?= $pageTitle ?></h1>
        </div>

        <div class="app-card">
            <form action="<?= $isEdit ? site_url('categories/' . $category->id) : site_url('categories') ?>" method="post">
                <?= csrf_field() ?>
                <?php if ($isEdit) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-4">
                    <label class="form-label" for="name">
                        <i class="bi bi-tag me-1"></i> Nome da Categoria
                    </label>
                    <input type="text" class="form-control form-control-lg" id="name" name="name" 
                           placeholder="ex: Alimentação, Combustível, Salário..." 
                           value="<?= old('name', $category->name ?? '') ?>" required>
                    <div class="form-text text-secondary small">Nome descritivo para agrupar as tuas transações.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label d-block">
                        <i class="bi bi-arrow-left-right me-1"></i> Tipo
                    </label>
                    <?php $selectedType = old('type', $category->type ?? 'expense'); ?>
                    <div class="d-flex gap-3">
                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" <?= $selectedType === 'expense' ? 'checked' : '' ?>>
                            <label class="form-check-label text-white fw-bold d-block" for="type_expense">
                                <i class="bi bi-arrow-down-left text-danger me-1"></i> Despesa
                            </label>
                            <span class="text-secondary small">Dinheiro que sai.</span>
                        </div>

                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_income" value="income" <?= $selectedType === 'income' ? 'checked' : '' ?>>
                            <label class="form-check-label text-white fw-bold d-block" for="type_income">
                                <i class="bi bi-arrow-up-right text-success me-1"></i> Rendimento
                            </label>
                            <span class="text-secondary small">Dinheiro que entra.</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 pt-3 border-top border-secondary border-opacity-10">
                    <button type="submit" class="btn-brand-primary px-4 py-2">
                        <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Categoria' ?>
                    </button>
                    <a href="<?= site_url('categories') ?>" class="btn-brand-outline">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
