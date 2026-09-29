<?= $this->extend('app_layout') ?>

<?= $this->section('main') ?> <!-- <-- ALTERADO DE 'content' PARA 'main' -->
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h1 class="h4 text-white fw-bold mb-0">Nova Transferência</h1>
                <a href="<?= site_url('transactions') ?>" class="btn btn-sm btn-dark border border-secondary border-opacity-25 text-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <!-- Exibição de Erros de Validação -->
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger border-danger border-opacity-50 bg-dark text-danger mb-4">
                    <ul class="mb-0 small">
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endif ?>

            <div class="app-card p-4 shadow-sm">
                <form action="<?= site_url('transfers') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Conta de Origem -->
                    <div class="mb-3">
                        <label for="account_from_id" class="form-label">Conta de Origem</label>
                        <select name="account_from_id" id="account_from_id" class="form-select" required>
                            <option value="">Seleciona a conta de origem...</option>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->id ?>" <?= old('account_from_id') == $account->id ? 'selected' : '' ?>>
                                    <?= esc($account->name) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Conta de Destino -->
                    <div class="mb-3">
                        <label for="account_to_id" class="form-label">Conta de Destino</label>
                        <select name="account_to_id" id="account_to_id" class="form-select" required>
                            <option value="">Seleciona a conta de destino...</option>
                            <?php foreach ($accounts as $account) : ?>
                                <option value="<?= $account->id ?>" <?= old('account_to_id') == $account->id ? 'selected' : '' ?>>
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
                                style="padding-right: 2.3rem !important;"
                                placeholder="0.00"
                                value="<?= old('amount', isset($transfer) ? number_format($transfer['amount'] / 100, 2, '.', '') : '') ?>" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3 text-secondary fw-semibold" style="pointer-events: none; z-index: 5;">
                                €
                            </span>
                        </div>
                    </div>
                    <!-- Data da Transferência -->
                    <div class="mb-4">
                        <label for="transfer_date" class="form-label">Data</label>
                        <input type="datetime-local" name="transfer_date" id="transfer_date" class="form-control" value="<?= old('transfer_date', date('Y-m-d\TH:i')) ?>" required>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="<?= site_url('transactions') ?>" class="btn btn-brand-outline">Cancelar</a>
                        <button type="submit" class="btn btn-brand-primary">Efetuar Transferência</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>