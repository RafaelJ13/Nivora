<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($transaction) && $transaction !== null;
$pageTitle = $isEdit ? 'Editar Transação: ' . esc($transaction->description) : 'Registar Nova Transação';

$accounts = $accounts ?? [];

$categories = $categories ?? [];

$currentType = strtoupper((string) old('type', $transaction->type ?? 'EXPENSE'));
?>

<?= $this->section('title') ?><?= $pageTitle ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-4">
            <a href="<?= site_url('transactions') ?>" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Voltar às Transações
            </a>
            <div class="text-uppercase small fw-bold text-success mt-2" style="letter-spacing: 0.08em;">
                <?= $isEdit ? 'Editar movimento' : 'Novo movimento' ?>
            </div>
            <h1 class="h2 fw-bold text-white"><?= $pageTitle ?></h1>
        </div>

        <div class="app-card">
            <form action="<?= $isEdit ? site_url('transactions/' . $transaction->id) : site_url('transactions') ?>" method="post">
                <?= csrf_field() ?>
                <?php if ($isEdit) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <!-- Escolhe se o dinheiro entra ou sai. -->
                <div class="mb-4">
                    <label class="form-label d-block">Tipo</label>
                    <div class="d-flex gap-3">
                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_expense" value="EXPENSE" <?= $currentType === 'EXPENSE' ? 'checked' : '' ?> onchange="updateFormTheme()">
                            <label class="form-check-label text-white fw-bold d-block" for="type_expense">
                                <i class="bi bi-dash-circle-fill text-danger me-1"></i> Despesa
                            </label>
                            <span class="text-secondary small">Dinheiro que sai.</span>
                        </div>

                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_income" value="INCOME" <?= $currentType === 'INCOME' ? 'checked' : '' ?> onchange="updateFormTheme()">
                            <label class="form-check-label text-white fw-bold d-block" for="type_income">
                                <i class="bi bi-plus-circle-fill text-success me-1"></i> Rendimento
                            </label>
                            <span class="text-secondary small">Dinheiro que entra.</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label" for="description">
                        <i class="bi bi-pencil-square me-1"></i> Descrição
                    </label>
                    <input type="text" class="form-control form-control-lg" id="description" name="description"
                           placeholder="ex: Supermercado ou salário"
                           value="<?= old('description', $transaction->description ?? '') ?>" required>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="amount">
                        <i class="bi bi-currency-euro me-1"></i> Valor
                    </label>
                    <div class="position-relative">
                        <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01"
                               style="padding-right: 2.3rem !important;"
                               placeholder="0.00"
                               value="<?= old('amount', isset($transaction) ? number_format($transaction->amount / 100, 2, '.', '') : '') ?>" required>
                        <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-secondary fw-semibold" style="pointer-events: none; z-index: 5;">
                            €
                        </span>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <!-- Account -->
                    <div class="col-md-6">
                        <label class="form-label" for="account_id">
                            <i class="bi bi-bank me-1"></i> Conta
                        </label>
                        <select class="form-select" id="account_id" name="account_id" required>
                            <?php $selectedAccount = old('account_id', $transaction->account_id ?? ''); ?>
                            <?php foreach ($accounts as $acc) : ?>
                                <option value="<?= $acc->id ?>" <?= $selectedAccount == $acc->id ? 'selected' : '' ?>>
                                    <?= esc($acc->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label class="form-label" for="category_id">
                            <i class="bi bi-tag me-1"></i> Categoria
                        </label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <?php $selectedCat = old('category_id', $transaction->category_id ?? ''); ?>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?= $cat->id ?>" data-category-type="<?= esc(strtoupper($cat->type)) ?>" <?= $selectedCat == $cat->id ? 'selected' : '' ?>>
                                    <?= esc($cat->name) ?> (<?= strtoupper($cat->type) === 'INCOME' ? '+' : '-' ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Date -->
                <div class="mb-4">
                    <label class="form-label" for="transaction_date">
                            <i class="bi bi-calendar3 me-1"></i> Data
                    </label>
                    <input type="datetime-local" class="form-control" id="transaction_date" name="transaction_date"
                           value="<?= old('transaction_date', isset($transaction->transaction_date) ? date('Y-m-d\TH:i', strtotime($transaction->transaction_date)) : date('Y-m-d\TH:i')) ?>" required>
                </div>

                <div class="d-flex align-items-center gap-3 pt-3 border-top border-secondary border-opacity-10">
                    <button type="submit" class="btn-brand-primary px-4 py-2">
                        <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Registar Transação' ?>
                    </button>
                    <a href="<?= site_url('transactions') ?>" class="btn-brand-outline">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categoryInput = document.getElementById('category_id');
        const categoryOptions = Array.from(categoryInput.options);

        function filterCategories() {
            const selectedType = document.querySelector('input[name="type"]:checked').value.toUpperCase();
            let selectedVisible = false;

            categoryOptions.forEach(option => {
                const visible = option.dataset.categoryType === selectedType;
                option.hidden = !visible;
                if (visible && option.selected) {
                    selectedVisible = true;
                }
            });

            if (!selectedVisible) {
                const firstVisible = categoryOptions.find(option => !option.hidden);
                categoryInput.value = firstVisible ? firstVisible.value : '';
            }
        }

        window.updateFormTheme = filterCategories;
        document.querySelectorAll('input[name="type"]').forEach(input => {
            input.addEventListener('change', filterCategories);
        });
        filterCategories();
    });
</script>
<?= $this->endSection() ?>
