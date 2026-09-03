<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($transaction) && $transaction !== null;
$pageTitle = $isEdit ? 'Editar Transação: ' . esc($transaction->description) : 'Registar Nova Transação';

$accounts = $accounts ?? [
    (object)['id' => 1, 'name' => 'Millennium BCP'],
    (object)['id' => 2, 'name' => 'Revolut'],
    (object)['id' => 3, 'name' => 'Dinheiro em Carteira']
];

$categories = $categories ?? [
    (object)['id' => 1, 'name' => 'Salário', 'type' => 'income'],
    (object)['id' => 2, 'name' => 'Freelance / Extras', 'type' => 'income'],
    (object)['id' => 3, 'name' => 'Alimentação & Supermercado', 'type' => 'expense'],
    (object)['id' => 4, 'name' => 'Habitação & Renda', 'type' => 'expense'],
    (object)['id' => 5, 'name' => 'Transporte & Combustível', 'type' => 'expense'],
    (object)['id' => 6, 'name' => 'Lazer & Restaurantes', 'type' => 'expense'],
    (object)['id' => 7, 'name' => 'Subscrições & Serviços', 'type' => 'expense']
];

$currentType = old('type', $transaction->type ?? 'expense');
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

                <!-- Type Selector (Income vs Expense) -->
                <div class="mb-4">
                    <label class="form-label d-block">Tipo de Transação</label>
                    <div class="d-flex gap-3">
                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_expense" value="expense" <?= $currentType === 'expense' ? 'checked' : '' ?> onchange="updateFormTheme()">
                            <label class="form-check-label text-white fw-bold d-block" for="type_expense">
                                <i class="bi bi-dash-circle-fill text-danger me-1"></i> Despesa (EXPENSE)
                            </label>
                            <span class="text-secondary small">Dinheiro gasto ou debitado</span>
                        </div>

                        <div class="form-check p-3 rounded-3 border border-secondary border-opacity-25 flex-grow-1 bg-dark">
                            <input class="form-check-input" type="radio" name="type" id="type_income" value="income" <?= $currentType === 'income' ? 'checked' : '' ?> onchange="updateFormTheme()">
                            <label class="form-check-label text-white fw-bold d-block" for="type_income">
                                <i class="bi bi-plus-circle-fill text-success me-1"></i> Rendimento (INCOME)
                            </label>
                            <span class="text-secondary small">Dinheiro recebido ou creditado</span>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label class="form-label" for="description">
                        <i class="bi bi-pencil-square me-1"></i> Descrição
                    </label>
                    <input type="text" class="form-control form-control-lg" id="description" name="description"
                           placeholder="ex: Supermercado Continente, Jantar de Aniversário, Salário..."
                           value="<?= old('description', $transaction->description ?? '') ?>" required>
                </div>

                <!-- Amount in Cents with Live Converter -->
                <div class="mb-4">
                    <label class="form-label" for="amount">
                        <i class="bi bi-currency-euro me-1"></i> Montante (em cêntimos)
                    </label>
                    <input type="number" class="form-control form-control-lg" id="amount" name="amount" min="1" step="1"
                           placeholder="ex: 4500 (para 45,00 €)"
                           value="<?= old('amount', $transaction->amount ?? '') ?>" required>

                    <div class="mt-2 p-3 rounded-3 bg-dark border border-secondary border-opacity-20 d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">
                            <i class="bi bi-calculator me-1 text-info"></i> Equivalente:
                        </span>
                        <strong id="amount_preview" class="text-white fs-4" style="font-family: var(--font-mono);">
                            € 0,00
                        </strong>
                    </div>
                    <div class="form-text text-secondary small mt-1">
                        Regra: valores guardados em unidades mínimas (ex: <code>€ 19.99 &rarr; 1999</code>).
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
                                <option value="<?= $cat->id ?>" <?= $selectedCat == $cat->id ? 'selected' : '' ?>>
                                    <?= esc($cat->name) ?> (<?= $cat->type === 'income' ? '+' : '-' ?>)
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
                    <input type="date" class="form-control" id="transaction_date" name="transaction_date"
                           value="<?= old('transaction_date', isset($transaction->date) ? date('Y-m-d', strtotime($transaction->date)) : date('Y-m-d')) ?>" required>
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
        const amountInput = document.getElementById('amount');
        const amountPreview = document.getElementById('amount_preview');

        function updatePreview() {
            const cents = parseInt(amountInput.value, 10) || 0;
            const isExpense = document.getElementById('type_expense').checked;
            const sign = isExpense ? '- ' : '+ ';
            amountPreview.className = (isExpense ? 'text-danger' : 'text-success') + ' fs-4';
            amountPreview.textContent = sign + '€ ' + (cents / 100).toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        window.updateFormTheme = updatePreview;
        amountInput.addEventListener('input', updatePreview);
        updatePreview();
    });
</script>
<?= $this->endSection() ?>
