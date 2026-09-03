<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($account) && $account !== null;
$pageTitle = $isEdit ? 'Editar Conta: ' . esc($account->name) : 'Adicionar Nova Conta';
?>

<?= $this->section('title') ?><?= $pageTitle ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="mb-4">
            <a href="<?= site_url('accounts') ?>" class="text-secondary text-decoration-none small">
                <i class="bi bi-arrow-left"></i> Voltar às Contas
            </a>
            <div class="text-uppercase small fw-bold text-success mt-2" style="letter-spacing: 0.08em;">
                <?= $isEdit ? 'Editar conta' : 'Nova conta' ?>
            </div>
            <h1 class="h2 fw-bold text-white"><?= $pageTitle ?></h1>
        </div>

        <div class="app-card">
            <form action="<?= $isEdit ? site_url('accounts/' . $account->id) : site_url('accounts') ?>" method="post">
                <?= csrf_field() ?>
                <?php if ($isEdit) : ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="mb-4">
                    <label class="form-label" for="name">
                        <i class="bi bi-tag me-1"></i> Nome da Conta
                    </label>
                    <input type="text" class="form-control form-control-lg" id="name" name="name" 
                           placeholder="ex: Millennium BCP, Revolut, Dinheiro..." 
                           value="<?= old('name', $account->name ?? '') ?>" required>
                    <div class="form-text text-secondary small">Exemplo: Conta à ordem.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="type">
                        <i class="bi bi-collection me-1"></i> Tipo de Conta
                    </label>
                    <select class="form-select" id="type" name="type" required>
                        <?php
                        $types = [
                            'bank' => 'Conta Bancária / À Ordem',
                            'cash' => 'Dinheiro Físico / Carteira',
                            'savings' => 'Conta Poupança / Depósito a Prazo',
                            'credit' => 'Cartão de Crédito'
                        ];
                        $selectedType = old('type', $account->type ?? 'bank');
                        ?>
                        <?php foreach ($types as $val => $label) : ?>
                            <option value="<?= $val ?>" <?= $selectedType === $val ? 'selected' : '' ?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label" for="initial_balance">
                        <i class="bi bi-currency-euro me-1"></i> Saldo Inicial (em cêntimos)
                    </label>
                    <input type="number" class="form-control" id="initial_balance" name="initial_balance" min="0" step="1"
                           placeholder="ex: 200000" 
                           value="<?= old('initial_balance', $account->initial_balance ?? 0) ?>" required>
                    
                    <div class="mt-2 p-3 rounded-3 bg-dark border border-secondary border-opacity-20 d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">
                            <i class="bi bi-calculator me-1 text-info"></i> Equivalente:
                        </span>
                        <strong id="euro_preview" class="text-success fs-5" style="font-family: var(--font-mono);">
                            € 0,00
                        </strong>
                    </div>
                    <div class="form-text text-secondary small mt-1">
                        Exemplo: <code>€ 1.000,00 = 100000 cêntimos</code>.
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 pt-3 border-top border-secondary border-opacity-10">
                    <button type="submit" class="btn-brand-primary px-4 py-2">
                        <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Conta' ?>
                    </button>
                    <a href="<?= site_url('accounts') ?>" class="btn-brand-outline">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('initial_balance');
        const preview = document.getElementById('euro_preview');
        function updateEuro() {
            const cents = parseInt(input.value, 10) || 0;
            preview.textContent = '€ ' + (cents / 100).toLocaleString('pt-PT', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }
        input.addEventListener('input', updateEuro);
        updateEuro();
    });
</script>
<?= $this->endSection() ?>
