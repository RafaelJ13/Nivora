<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($account) && $account !== null;
$pageTitle = $isEdit ? 'Editar Conta' : 'Nova Conta';

$types = [
    'bank'    => 'Conta Bancária / À Ordem',
    'cash'    => 'Dinheiro Físico / Carteira',
    'savings' => 'Conta Poupança / Depósito a Prazo',
    'credit'  => 'Cartão de Crédito',
];
$selectedType = old('type', $account->type ?? 'bank');
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
                <?= $isEdit ? 'Editar conta' : 'Nova conta' ?>
            </div>
            <h1><?= $pageTitle ?></h1>
            <p><?= $isEdit ? 'Atualiza os dados desta conta.' : 'Cria uma nova conta para agrupar os teus movimentos.' ?></p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('accounts') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <form action="<?= $isEdit ? site_url('accounts/' . $account->id) : site_url('accounts') ?>" method="post" id="accountForm">
        <?= csrf_field() ?>
        <?php if ($isEdit) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="row g-3 align-items-stretch">

            <!-- COLUNA ESQUERDA: Formulário -->
            <div class="col-lg-6 d-flex">
                <div class="n-card" style="flex: 1; padding: 1.75rem; display: flex; flex-direction: column; justify-content: space-between;">

                    <div>
                        <!-- Nome -->
                        <div class="mb-4">
                            <label class="form-label" for="name">
                                <i class="bi bi-tag me-1"></i> Nome da Conta
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                   placeholder="ex: Millennium BCP, Revolut, Dinheiro…"
                                   value="<?= old('name', $account->name ?? '') ?>" required oninput="updatePreview()">
                            <div style="color: rgba(245,245,245,0.35); font-size:0.72rem; margin-top:0.4rem;">
                                Um nome para identificares rapidamente esta conta.
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div class="mb-4">
                            <label class="form-label" for="type">
                                <i class="bi bi-collection me-1"></i> Tipo de Conta
                            </label>
                            <select class="form-select" id="type" name="type" required onchange="updatePreview()">
                                <?php foreach ($types as $val => $label) : ?>
                                    <option value="<?= $val ?>" <?= $selectedType === $val ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Saldo inicial -->
                        <div class="mb-0">
                            <label class="form-label" for="initial_balance">
                                <i class="bi bi-currency-euro me-1"></i> Saldo Inicial
                            </label>
                            <div class="position-relative">
                                <input type="number" class="form-control" id="initial_balance" name="initial_balance" min="0" step="0.01"
                                       style="padding-right: 2.5rem !important; font-size:1.05rem; font-weight:600;"
                                       placeholder="0.00"
                                       value="<?= old('initial_balance', isset($account) ? number_format($account->initial_balance / 100, 2, '.', '') : '') ?>"
                                       oninput="updatePreview()">
                                <span class="position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events:none; z-index:5; color: rgba(245,245,245,0.5); font-weight:600; font-size:1rem;">€</span>
                            </div>
                            <div style="color: rgba(245,245,245,0.35); font-size:0.72rem; margin-top:0.4rem;">
                                Opcional. Se deixares vazio, assume <code style="background: rgba(245,245,245,0.06); padding: 0.1rem 0.3rem; border-radius: 4px;">0,00 €</code>.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- COLUNA DIREITA: Preview + Ações -->
            <div class="col-lg-6 d-flex flex-column">

                <!-- Preview -->
                <div class="n-card mb-3" style="flex: 1; padding: 1.75rem; display: flex; flex-direction: column;">
                    <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color: rgba(245,245,245,0.4); font-weight:600; margin-bottom:1rem;">
                        Pré-visualização
                    </div>

                    <div style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 1.5rem;">

                        <div style="flex-shrink: 0; display: flex; flex-direction: column; align-items: center; gap: 0.75rem;">
                            <div id="previewIcon" class="tx-icon" style="width:56px; height:56px; font-size:1.4rem; background: rgba(67,183,144,0.12); color:#43B790;">
                                <i class="bi bi-bank"></i>
                            </div>
                            <span id="previewTypeBadge" class="badge-account">
                                bank
                            </span>
                        </div>

                        <div style="min-width: 0; text-align: left;">
                            <div id="previewName" style="color:#F5F5F5; font-weight:600; font-size:1.4rem; margin-bottom:0.5rem;">
                                Sem nome
                            </div>
                            <div id="previewBalance" style="color:#F5F5F5; font-weight:600; font-family: var(--font-mono); font-size:1.1rem; margin-bottom:0.5rem;">
                                € 0,00
                            </div>
                            <div style="color: rgba(245,245,245,0.45); font-size:0.85rem; line-height: 1.5;">
                                Esta conta vai agrupar os teus movimentos e contribuir para o saldo total.
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Ações -->
                <div class="n-card" style="height: auto; padding: 1.25rem;">
                    <button type="submit" class="btn-brand-primary w-100 justify-content-center mb-2" style="padding: 0.75rem;">
                        <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Conta' ?>
                    </button>
                    <a href="<?= site_url('accounts') ?>" class="btn-brand-outline w-100 justify-content-center" style="padding: 0.65rem;">
                        Cancelar
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
    const accountIcons = {
        bank:    { icon: 'bi-bank',      color: '#43B790', bg: 'rgba(67,183,144,0.12)' },
        cash:    { icon: 'bi-cash-coin', color: '#F3B562', bg: 'rgba(243,181,98,0.12)' },
        savings: { icon: 'bi-safe',      color: '#43B790', bg: 'rgba(67,183,144,0.12)' },
        credit:  { icon: 'bi-credit-card', color: '#FF5C5C', bg: 'rgba(255,92,92,0.12)' },
    };

    function formatEuro(cents) {
        return '€ ' + (cents / 100).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePreview() {
        const nameInput  = document.getElementById('name');
        const typeSelect = document.getElementById('type');
        const balanceInput = document.getElementById('initial_balance');

        const name = nameInput.value.trim() || 'Sem nome';
        const type = typeSelect.value;
        const cents = Math.round(parseFloat(balanceInput.value || 0) * 100);

        const meta = accountIcons[type] || accountIcons.bank;

        const icon = document.getElementById('previewIcon');
        icon.style.background = meta.bg;
        icon.style.color = meta.color;
        icon.innerHTML = '<i class="bi ' + meta.icon + '"></i>';

        document.getElementById('previewName').textContent = name;
        document.getElementById('previewTypeBadge').textContent = type;
        document.getElementById('previewBalance').textContent = formatEuro(cents);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('type').addEventListener('change', updatePreview);
        document.getElementById('initial_balance').addEventListener('input', updatePreview);
        updatePreview();
    });
</script>
<?= $this->endSection() ?>