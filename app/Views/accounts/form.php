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
                <div class="n-card h-100 w-100 d-flex flex-column" style="padding: 1.75rem;">

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

                    <div class="mt-auto pt-4" style="border-top: 1px solid rgba(245,245,245,0.05);">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn-brand-primary flex-fill justify-content-center" style="padding: 0.75rem;">
                                <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Conta' ?>
                            </button>
                            <a href="<?= site_url('accounts') ?>" class="btn-brand-outline flex-fill justify-content-center" style="padding: 0.65rem;">
                                Cancelar
                            </a>
                        </div>
                        <div style="color: rgba(245,245,245,0.4); font-size:0.72rem; text-align:center; margin-top:1rem;">
                            <i class="bi bi-shield-check me-1" style="color:#43B790;"></i>
                            Os movimentos desta conta ficam organizados num só lugar
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

                    <div class="account-preview-card">
                        <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                            <div id="previewIcon" class="tx-icon account-preview-icon" style="background: rgba(67,183,144,0.12); color:#43B790;">
                                <i class="bi bi-bank"></i>
                            </div>
                            <span id="previewTypeBadge" class="badge-account">Conta bancária</span>
                        </div>

                        <div id="previewName" class="account-preview-name">Sem nome</div>
                        <div class="account-preview-label">Saldo inicial</div>
                        <div id="previewBalance" class="account-preview-balance">€ 0,00</div>

                        <div class="account-preview-footer">
                            <i class="bi bi-wallet2" aria-hidden="true"></i>
                            <span id="previewDescription">Esta conta vai agrupar os teus movimentos e contribuir para o saldo total.</span>
                        </div>
                    </div>

                    <div class="account-preview-note">
                        <i class="bi bi-info-circle" aria-hidden="true"></i>
                        O saldo inicial é usado como ponto de partida para acompanhar os movimentos desta conta.
                    </div>
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
    const accountLabels = {
        bank: 'Conta bancária',
        cash: 'Dinheiro físico',
        savings: 'Conta poupança',
        credit: 'Cartão de crédito',
    };
    const accountDescriptions = {
        bank: 'Acompanha os movimentos associados a uma conta bancária.',
        cash: 'Acompanha o dinheiro físico que tens disponível.',
        savings: 'Acompanha o saldo reservado numa conta poupança.',
        credit: 'Acompanha os movimentos associados ao teu cartão de crédito.',
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
        document.getElementById('previewTypeBadge').textContent = accountLabels[type] || accountLabels.bank;
        document.getElementById('previewBalance').textContent = formatEuro(cents);
        document.getElementById('previewDescription').textContent = accountDescriptions[type] || accountDescriptions.bank;
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('name').addEventListener('input', updatePreview);
        document.getElementById('type').addEventListener('change', updatePreview);
        document.getElementById('initial_balance').addEventListener('input', updatePreview);
        updatePreview();
    });
</script>
<style>
    .account-preview-card {
        flex: 1;
        min-height: 270px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid rgba(67,183,144,0.18);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(67,183,144,0.1), rgba(245,245,245,0.02));
    }

    .account-preview-icon {
        width: 54px;
        height: 54px;
        font-size: 1.35rem;
    }

    .account-preview-name {
        color: #F5F5F5;
        font-size: 1.35rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .account-preview-label {
        margin-top: 1.5rem;
        color: rgba(245,245,245,0.45);
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .account-preview-balance {
        margin-top: 0.25rem;
        color: #F5F5F5;
        font-family: var(--font-mono);
        font-size: 2rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .account-preview-footer,
    .account-preview-note {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        color: rgba(245,245,245,0.5);
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .account-preview-footer {
        margin-top: auto;
        padding-top: 1.25rem;
        border-top: 1px solid rgba(245,245,245,0.07);
    }

    .account-preview-footer i,
    .account-preview-note i {
        color: #43B790;
        flex-shrink: 0;
    }

    .account-preview-note {
        padding-top: 1rem;
    }
</style>
<?= $this->endSection() ?>