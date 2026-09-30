<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Nova Transferência<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$accounts = $accounts ?? [];
?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                Novo movimento
            </div>
            <h1>Nova Transferência</h1>
            <p>Move dinheiro entre as tuas contas.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('transfers') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <form action="<?= site_url('transfers') ?>" method="post" id="transferForm">
        <?= csrf_field() ?>

        <div class="row g-3">

            <!-- COLUNA ESQUERDA: Formulário -->
            <div class="col-lg-6">
                <div class="n-card" style="height: auto; padding: 1.75rem;">

                    <!-- Conta de Origem -->
                    <div class="mb-4">
                        <label class="form-label" for="account_from_id">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Conta de Origem
                        </label>
                        <select name="account_from_id" id="account_from_id" class="form-select" required onchange="updatePreview()">
                            <option value="">Seleciona a conta de origem…</option>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->id ?>"
                                        data-name="<?= esc($account->name) ?>"
                                        data-balance="<?= (int) ($account->current_balance ?? $account->initial_balance ?? 0) ?>"
                                        data-type="<?= esc($account->type) ?>"
                                        <?= old('account_from_id') == $account->id ? 'selected' : '' ?>>
                                    <?= esc($account->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Conta de Destino -->
                    <div class="mb-4">
                        <label class="form-label" for="account_to_id">
                            <i class="bi bi-box-arrow-in-down-left me-1"></i> Conta de Destino
                        </label>
                        <select name="account_to_id" id="account_to_id" class="form-select" required onchange="updatePreview()">
                            <option value="">Seleciona a conta de destino…</option>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->id ?>"
                                        data-name="<?= esc($account->name) ?>"
                                        data-balance="<?= (int) ($account->current_balance ?? $account->initial_balance ?? 0) ?>"
                                        data-type="<?= esc($account->type) ?>"
                                        <?= old('account_to_id') == $account->id ? 'selected' : '' ?>>
                                    <?= esc($account->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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
                                   value="<?= old('amount', isset($transfer) ? number_format($transfer['amount'] / 100, 2, '.', '') : '') ?>" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3" style="pointer-events:none; z-index:5; color: rgba(245,245,245,0.5); font-weight:600; font-size:1rem;">€</span>
                        </div>
                    </div>

                    <!-- Data -->
                    <div class="mb-4">
                        <label class="form-label" for="transfer_date">
                            <i class="bi bi-calendar3 me-1"></i> Data
                        </label>
                        <input type="datetime-local" name="transfer_date" id="transfer_date" class="form-control"
                               value="<?= old('transfer_date', date('Y-m-d\TH:i')) ?>" required>
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

                    <div style="flex: 1; display: flex; flex-direction: column; justify-content: center;">

                        <div class="badge-transfer d-inline-block mb-3" style="align-self: flex-start;">
                            <i class="bi bi-arrow-left-right me-1"></i> Transferência
                        </div>

                        <div class="n-metric-value" id="previewAmount" style="font-size:2.2rem; color:#F5F5F5; margin:0 0 0.5rem;">
                            € 0,00
                        </div>

                        <div id="previewFlow" style="color: rgba(245,245,245,0.5); font-size:0.85rem; margin-bottom:1.5rem;">
                            Origem → Destino
                        </div>

                        <div style="border-top: 1px solid rgba(245,245,245,0.05); padding-top:1rem;">
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">De</span>
                                <span id="previewFrom" style="color:#F5F5F5; font-size:0.85rem; font-weight:500;">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo origem</span>
                                <span id="previewFromBalance" style="color:#F5F5F5; font-size:0.85rem; font-weight:500; font-family: var(--font-mono);">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo origem após</span>
                                <span id="previewFromBalanceAfter" style="color:#FF5C5C; font-size:0.85rem; font-weight:600; font-family: var(--font-mono);">—</span>
                            </div>

                            <div class="d-flex justify-content-between py-2" style="border-top: 1px solid rgba(245,245,245,0.05); margin-top:0.5rem; padding-top:1rem;">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Para</span>
                                <span id="previewTo" style="color:#F5F5F5; font-size:0.85rem; font-weight:500;">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo destino</span>
                                <span id="previewToBalance" style="color:#F5F5F5; font-size:0.85rem; font-weight:500; font-family: var(--font-mono);">—</span>
                            </div>
                            <div class="d-flex justify-content-between py-2">
                                <span style="color: rgba(245,245,245,0.45); font-size:0.82rem;">Saldo destino após</span>
                                <span id="previewToBalanceAfter" style="color:#43B790; font-size:0.85rem; font-weight:600; font-family: var(--font-mono);">—</span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Ações -->
                <div class="n-card" style="height: auto; padding: 1.25rem;">
                    <button type="submit" class="btn-brand-primary w-100 justify-content-center mb-2" style="padding: 0.75rem;">
                        <i class="bi bi-check-lg"></i> Efetuar Transferência
                    </button>
                    <a href="<?= site_url('transfers') ?>" class="btn-brand-outline w-100 justify-content-center" style="padding: 0.65rem;">
                        Cancelar
                    </a>
                    <div style="color: rgba(245,245,245,0.4); font-size:0.72rem; text-align:center; margin-top:1rem;">
                        <i class="bi bi-shield-check me-1" style="color:#43B790;"></i>
                        Transferência imediata entre contas
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>

<style>
    .badge-transfer {
        background: rgba(245,245,245,0.08);
        color: rgba(245,245,245,0.8);
        border: 1px solid rgba(245,245,245,0.12);
        font-weight: 600;
        border-radius: 6px;
        padding: 0.3rem 0.65rem;
        font-size: 0.75rem;
    }
</style>

<script>
    function formatEuro(cents) {
        return '€ ' + (cents / 100).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updatePreview() {
        const fromSelect = document.getElementById('account_from_id');
        const toSelect = document.getElementById('account_to_id');
        const amountInput = document.getElementById('amount');

        const fromOpt = fromSelect.options[fromSelect.selectedIndex];
        const toOpt = toSelect.options[toSelect.selectedIndex];

        const fromName = fromOpt && fromOpt.dataset.name ? fromOpt.dataset.name : '—';
        const toName = toOpt && toOpt.dataset.name ? toOpt.dataset.name : '—';
        const fromBalance = fromOpt ? parseInt(fromOpt.dataset.balance || 0) : 0;
        const toBalance = toOpt ? parseInt(toOpt.dataset.balance || 0) : 0;

        const amountCents = Math.round(parseFloat(amountInput.value || 0) * 100);

        document.getElementById('previewAmount').textContent = formatEuro(amountCents);
        document.getElementById('previewFlow').textContent = fromName + ' → ' + toName;
        document.getElementById('previewFrom').textContent = fromName;
        document.getElementById('previewTo').textContent = toName;
        document.getElementById('previewFromBalance').textContent = formatEuro(fromBalance);
        document.getElementById('previewToBalance').textContent = formatEuro(toBalance);

        const fromAfter = fromBalance - amountCents;
        const toAfter = toBalance + amountCents;

        const fromAfterEl = document.getElementById('previewFromBalanceAfter');
        fromAfterEl.textContent = formatEuro(fromAfter);
        fromAfterEl.style.color = fromAfter >= 0 ? '#F5F5F5' : '#FF5C5C';

        const toAfterEl = document.getElementById('previewToBalanceAfter');
        toAfterEl.textContent = formatEuro(toAfter);
        toAfterEl.style.color = '#43B790';
    }

    document.addEventListener('DOMContentLoaded', function () {
        ['account_from_id', 'account_to_id', 'amount'].forEach(id => {
            document.getElementById(id).addEventListener('input', updatePreview);
            document.getElementById(id).addEventListener('change', updatePreview);
        });
        updatePreview();
    });
</script>
<?= $this->endSection() ?>