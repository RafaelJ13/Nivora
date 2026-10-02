<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($transaction) && $transaction !== null;
$pageTitle = $isEdit ? 'Editar Transação' : 'Registar Nova Transação';

$accounts   = $accounts ?? [];
$categories = $categories ?? [];

$currentType = strtoupper((string) old('type', $transaction->type ?? 'EXPENSE'));
?>

<?= $this->section('title') ?><?= $pageTitle ?><?= $this->endSection() ?>

<?= $this->section('main') ?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                <?= $isEdit ? 'Editar movimento' : 'Novo movimento' ?>
            </div>
            <h1><?= $pageTitle ?></h1>
            <p><?= $isEdit ? 'Atualiza os dados da transação.' : 'Preenche os dados para registar um novo movimento.' ?></p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('transactions') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <form action="<?= $isEdit ? site_url('transactions/' . $transaction->id) : site_url('transactions') ?>" method="post" id="transactionForm">
        <?= csrf_field() ?>
        <?php if ($isEdit) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="row g-3 align-items-stretch">

            <!-- COLUNA ESQUERDA: Formulário -->
            <div class="col-lg-6">
                <div class="n-card h-100 d-flex flex-column" style="padding: 1.75rem;">

                    <!-- Tipo -->
                    <div class="mb-4">
                        <label class="form-label d-block mb-3">Tipo de Transação</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="type-card type-card-expense" for="type_expense">
                                    <input type="radio" name="type" id="type_expense" value="EXPENSE" <?= $currentType === 'EXPENSE' ? 'checked' : '' ?> onchange="updateFormTheme()">
                                    <div class="type-box">
                                        <div class="type-icon" style="color:#FF5C5C; background: rgba(255,92,92,0.12);">
                                            <i class="bi bi-dash-circle-fill"></i>
                                        </div>
                                        <div>
                                            <div style="color:#F5F5F5; font-weight:600; font-size:0.9rem;">Despesa</div>
                                            <div style="font-size:0.72rem; color: rgba(245,245,245,0.4);">Dinheiro que sai</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="type-card type-card-income" for="type_income">
                                    <input type="radio" name="type" id="type_income" value="INCOME" <?= $currentType === 'INCOME' ? 'checked' : '' ?> onchange="updateFormTheme()">
                                    <div class="type-box">
                                        <div class="type-icon" style="color:#43B790; background: rgba(67,183,144,0.12);">
                                            <i class="bi bi-plus-circle-fill"></i>
                                        </div>
                                        <div>
                                            <div style="color:#F5F5F5; font-weight:600; font-size:0.9rem;">Rendimento</div>
                                            <div style="font-size:0.72rem; color: rgba(245,245,245,0.4);">Dinheiro que entra</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Descrição -->
                    <div class="mb-4">
                        <label class="form-label" for="description">
                            <i class="bi bi-pencil-square me-1"></i> Descrição
                        </label>
                        <input type="text" class="form-control" id="description" name="description"
                               placeholder="ex: Supermercado, salário, renda…"
                               value="<?= old('description', $transaction->description ?? '') ?>" required>
                    </div>

                    <!-- Valor -->
                    <div class="mb-4">
                        <label class="form-label" for="amount">
                            <i class="bi bi-currency-euro me-1"></i> Valor
                        </label>
                        <div class="position-relative">
                            <input type="number" class="form-control" id="amount" name="amount" min="0.01" step="0.01"
                                   style="padding-right: 2.5rem !important; font-size:1.1rem; font-weight:600;"
                                   placeholder="0.00"
                                   value="<?= old('amount', isset($transaction) ? number_format($transaction->amount / 100, 2, '.', '') : '') ?>" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events:none; z-index:5; color: rgba(245,245,245,0.5); font-weight:600; font-size:1rem;">€</span>
                        </div>
                    </div>

                    <!-- Conta + Categoria -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="account_id">
                                <i class="bi bi-bank me-1"></i> Conta
                            </label>
                            <select class="form-select" id="account_id" name="account_id" required onchange="updatePreview()">
                                <?php $selectedAccount = old('account_id', $transaction->account_id ?? ''); ?>
                                <?php foreach ($accounts as $acc) : ?>
                                    <option value="<?= $acc->id ?>"
                                            data-name="<?= esc($acc->name) ?>"
                                            data-balance="<?= (int) ($acc->current_balance ?? $acc->initial_balance ?? 0) ?>"
                                            data-type="<?= esc($acc->type) ?>"
                                            <?= $selectedAccount == $acc->id ? 'selected' : '' ?>>
                                        <?= esc($acc->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="category_id">
                                <i class="bi bi-tag me-1"></i> Categoria
                            </label>
                            <select class="form-select" id="category_id" name="category_id" required onchange="updatePreview()">
                                <?php $selectedCat = old('category_id', $transaction->category_id ?? ''); ?>
                                <?php foreach ($categories as $cat) : ?>
                                    <option value="<?= $cat->id ?>" data-category-type="<?= esc(strtoupper($cat->type)) ?>" <?= $selectedCat == $cat->id ? 'selected' : '' ?>>
                                        <?= esc($cat->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Data -->
                    <div class="mb-4">
                        <label class="form-label" for="transaction_date">
                            <i class="bi bi-calendar3 me-1"></i> Data
                        </label>
                        <input type="datetime-local" class="form-control" id="transaction_date" name="transaction_date"
                               value="<?= old('transaction_date', isset($transaction->transaction_date) ? date('Y-m-d\TH:i', strtotime($transaction->transaction_date)) : date('Y-m-d\TH:i')) ?>" required>
                    </div>

                    <!-- Ações -->
                    <div class="mt-auto pt-4" style="border-top: 1px solid rgba(245,245,245,0.05);">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn-brand-primary flex-fill justify-content-center" style="padding: 0.75rem;">
                                <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Registar Transação' ?>
                            </button>
                            <a href="<?= site_url('transactions') ?>" class="btn-brand-outline flex-fill justify-content-center" style="padding: 0.65rem;">
                                Cancelar
                            </a>
                        </div>
                        <div style="color: rgba(245,245,245,0.4); font-size:0.72rem; text-align:center; margin-top:1rem;">
                            <i class="bi bi-shield-check me-1" style="color:#43B790;"></i>
                            Dados guardados de forma privada
                        </div>
                    </div>
                </div>
            </div>

            <!-- COLUNA DIREITA: Pré-visualização -->
            <div class="col-lg-6 d-flex">

                <div class="n-card h-100 w-100 d-flex flex-column" style="padding: 1.75rem;">

                    <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color: rgba(245,245,245,0.4); font-weight:600; margin-bottom:1rem;">
                        Pré-visualização
                    </div>

                    <div class="transaction-preview-card" id="transactionPreviewCard">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span id="previewTypeBadge" class="badge-expense d-inline-block">
                                <i class="bi bi-dash-circle me-1"></i> Despesa
                            </span>
                            <i id="previewTypeIcon" class="bi bi-arrow-up-right transaction-preview-icon" aria-hidden="true"></i>
                        </div>

                        <div class="n-metric-value text-center mb-3" id="previewAmount" style="font-size:2.2rem; color:#FF5C5C;">
                            − € 0,00
                        </div>

                        <div id="previewDescription" class="transaction-preview-description">
                            Sem descrição
                        </div>

                        <div class="transaction-preview-meta">
                            <div>
                                <i class="bi bi-wallet2" aria-hidden="true"></i>
                                <span id="previewAccountCard">—</span>
                            </div>
                            <div>
                                <i class="bi bi-tag" aria-hidden="true"></i>
                                <span id="previewCategory">—</span>
                            </div>
                        </div>
                    </div>

                    <div class="transaction-preview-details mt-4">
                        <div style="border-top: 1px solid rgba(245,245,245,0.05); padding-top:1rem;">
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Conta</span>
                                <span id="previewAccount" style="color:#F5F5F5; font-size:0.85rem; font-weight:500;">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo atual</span>
                                <span id="previewBalance" style="color:#F5F5F5; font-size:0.85rem; font-weight:500; font-family: var(--font-mono);">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo após</span>
                                <span id="previewBalanceAfter" style="color:#43B790; font-size:0.85rem; font-weight:600; font-family: var(--font-mono);">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Data</span>
                                <span id="previewDate" style="color:#F5F5F5; font-size:0.85rem; font-weight:500; font-family: var(--font-mono);">—</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .type-card { cursor: pointer; display: block; }
    .type-card input[type="radio"] { display: none; }

    .type-card .type-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem;
        border-radius: 10px;
        border: 1px solid rgba(245,245,245,0.08);
        background: #0f0f0f;
        transition: all 0.15s ease;
    }
    .type-card:hover .type-box {
        border-color: rgba(245,245,245,0.18);
    }
    .type-card-expense input[type="radio"]:checked + .type-box {
        border-color: rgba(255,92,92,0.5) !important;
        background: rgba(255,92,92,0.08) !important;
        box-shadow: inset 0 0 0 1px rgba(255,92,92,0.5);
    }
    .type-card-expense input[type="radio"]:checked + .type-box .type-title {
        color: #FF5C5C !important;
    }
    .type-card-expense input[type="radio"]:checked + .type-box .type-icon {
        background: rgba(255,92,92,0.18) !important;
        color: #FF5C5C !important;
    }
    .type-card-income input[type="radio"]:checked + .type-box {
        border-color: rgba(67,183,144,0.5) !important;
        background: rgba(67,183,144,0.08) !important;
        box-shadow: inset 0 0 0 1px rgba(67,183,144,0.5);
    }
    .type-card-income input[type="radio"]:checked + .type-box .type-title {
        color: #43B790 !important;
    }
    .type-card-income input[type="radio"]:checked + .type-box .type-icon {
        background: rgba(67,183,144,0.18) !important;
        color: #43B790 !important;
    }

    .type-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .transaction-preview-card {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 280px;
        padding: 1.25rem;
        border: 1px solid rgba(255,92,92,0.18);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(255,92,92,0.08), rgba(245,245,245,0.02));
        transition: border-color 160ms ease, background 160ms ease;
    }

    .transaction-preview-card.is-income {
        border-color: rgba(67,183,144,0.2);
        background: linear-gradient(145deg, rgba(67,183,144,0.1), rgba(245,245,245,0.02));
    }

    .transaction-preview-icon {
        color: #FF5C5C;
        font-size: 1.2rem;
    }

    .transaction-preview-card.is-income .transaction-preview-icon {
        color: #43B790;
    }

    .transaction-preview-description {
        min-height: 1.4rem;
        margin-bottom: 1.25rem;
        color: rgba(245,245,245,0.65);
        font-size: 0.85rem;
        text-align: center;
        overflow-wrap: anywhere;
    }

    .transaction-preview-meta {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 0.75rem;
    }

    .transaction-preview-details {
        display: flex;
        flex-direction: column;
    }

    .transaction-preview-details > div {
        display: block;
    }

    .transaction-preview-details .d-flex {
        min-height: 2.5rem;
        align-items: center;
    }

    .transaction-preview-meta > div {
        min-width: 0;
        min-height: 58px;
        padding: 0.75rem;
        display: grid;
        grid-template-columns: 1.25rem minmax(0, 1fr);
        align-items: center;
        gap: 0.65rem;
        border: 1px solid rgba(245,245,245,0.07);
        border-radius: 10px;
        background: rgba(10,10,10,0.5);
    }

    .transaction-preview-meta i {
        color: #43B790;
        font-size: 1rem;
    }

    .transaction-preview-meta span {
        color: #F5F5F5;
        font-size: 0.8rem;
        font-weight: 600;
        overflow-wrap: anywhere;
    }
</style>

<script>
    function formatEuro(cents) {
        return '€ ' + (cents / 100).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePreview() {
        const typeInput = document.querySelector('input[name="type"]:checked');
        const isIncome = typeInput && typeInput.value === 'INCOME';

        const amountInput = document.getElementById('amount');
        const amountCents = Math.round(parseFloat(amountInput.value || 0) * 100);

        const descInput = document.getElementById('description');
        const dateInput = document.getElementById('transaction_date');
        const accSelect = document.getElementById('account_id');
        const selectedOption = accSelect.options[accSelect.selectedIndex];
        const categorySelect = document.getElementById('category_id');
        const selectedCategory = categorySelect.options[categorySelect.selectedIndex];

        const accName = selectedOption ? selectedOption.dataset.name : '—';
        const accBalance = selectedOption ? parseInt(selectedOption.dataset.balance || 0) : 0;

        const badge = document.getElementById('previewTypeBadge');
        badge.className = (isIncome ? 'badge-income' : 'badge-expense') + ' d-inline-block';
        badge.innerHTML = '<i class="bi ' + (isIncome ? 'bi-plus-circle' : 'bi-dash-circle') + ' me-1"></i> ' + (isIncome ? 'Rendimento' : 'Despesa');

        const previewCard = document.getElementById('transactionPreviewCard');
        previewCard.classList.toggle('is-income', isIncome);
        document.getElementById('previewTypeIcon').className = 'bi ' + (isIncome ? 'bi-arrow-down-left' : 'bi-arrow-up-right') + ' transaction-preview-icon';

        const amountEl = document.getElementById('previewAmount');
        amountEl.style.color = isIncome ? '#43B790' : '#FF5C5C';
        amountEl.textContent = (isIncome ? '+ ' : '− ') + formatEuro(amountCents);

        document.getElementById('previewDescription').textContent = descInput.value || 'Sem descrição';
        const transactionDate = dateInput.value ? new Date(dateInput.value) : null;
        document.getElementById('previewDate').textContent = transactionDate && !Number.isNaN(transactionDate.getTime())
            ? transactionDate.toLocaleString('pt-PT', { dateStyle: 'medium', timeStyle: 'short' })
            : '—';
        document.getElementById('previewAccount').textContent = accName;
        document.getElementById('previewAccountCard').textContent = accName;
        document.getElementById('previewCategory').textContent = selectedCategory ? selectedCategory.textContent.trim() : '—';
        document.getElementById('previewBalance').textContent = formatEuro(accBalance);

        const balanceAfter = isIncome ? accBalance + amountCents : accBalance - amountCents;
        const afterEl = document.getElementById('previewBalanceAfter');
        afterEl.textContent = formatEuro(balanceAfter);
        afterEl.style.color = balanceAfter >= 0 ? '#43B790' : '#FF5C5C';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const categoryInput = document.getElementById('category_id');
        const categoryOptions = Array.from(categoryInput.options);

        function filterCategories() {
            const selectedType = document.querySelector('input[name="type"]:checked').value.toUpperCase();
            let selectedVisible = false;

            categoryOptions.forEach(option => {
                const visible = option.dataset.categoryType === selectedType;
                option.hidden = !visible;
                if (visible && option.selected) selectedVisible = true;
            });

            if (!selectedVisible) {
                const firstVisible = categoryOptions.find(option => !option.hidden);
                categoryInput.value = firstVisible ? firstVisible.value : '';
            }
            updatePreview();
        }

        window.updateFormTheme = filterCategories;

        document.querySelectorAll('input[name="type"]').forEach(input => {
            input.addEventListener('change', filterCategories);
        });
        document.getElementById('amount').addEventListener('input', updatePreview);
        document.getElementById('description').addEventListener('input', updatePreview);
        document.getElementById('account_id').addEventListener('change', updatePreview);
        categoryInput.addEventListener('change', updatePreview);
        document.getElementById('transaction_date').addEventListener('input', updatePreview);
        document.getElementById('transaction_date').addEventListener('change', updatePreview);

        filterCategories();
        updatePreview();
    });
</script>
<?= $this->endSection() ?>