<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Detalhes da Transferência #<?= esc($transfer->id ?? '') ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="mb-4">
            <div class="text-uppercase small fw-bold text-success mt-2" style="letter-spacing: 0.08em;">
                Detalhe do movimento
            </div>
            <h1 class="h2 fw-bold text-white">Transferência #<?= esc($transfer->id ?? '') ?></h1>
        </div>

        <div class="app-card position-relative overflow-hidden">
            <!-- Amount Banner -->
            <div class="text-center py-4 border-bottom border-secondary border-opacity-10 mb-4">
                <span class="badge-account p-2 px-3 rounded-pill text-uppercase fw-bold small">
                    <i class="bi bi-arrow-left-right me-1"></i>
                    Transferência
                </span>
                <div class="display-5 fw-bold my-3 text-white" style="font-family: var(--font-mono);">
                    € <?= number_format(($transfer->amount ?? 0) / 100, 2, ',', '.') ?>
                </div>
                <div class="h6 text-secondary fw-semibold mb-0">
                    <?= esc($transfer->account_from_name ?? '—') ?> <i class="bi bi-arrow-right mx-1 text-success"></i> <?= esc($transfer->account_to_name ?? '—') ?>
                </div>
            </div>

            <!-- Transfer Metadata List -->
            <div class="d-flex flex-column gap-3 mb-4">
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Conta Origem</span>
                    <span class="badge-account"><?= esc($transfer->account_from_name ?? '—') ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Conta Destino</span>
                    <span class="badge-account"><?= esc($transfer->account_to_name ?? '—') ?></span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Categoria</span>
                    <span class="badge bg-dark border border-secondary border-opacity-25 text-white px-2 py-1">
                        Transferência
                    </span>
                </div>

                <div class="d-flex justify-content-between align-items-center py-2 border-bottom border-secondary border-opacity-10">
                    <span class="text-secondary small">Data</span>
                    <span class="text-white fw-semibold"><?= !empty($transfer->transfer_date) ? date('d/m/Y H:i', strtotime($transfer->transfer_date)) : '—' ?></span>
                </div>
            </div>

            <!-- Actions Bar -->
            <div class="d-flex justify-content-start align-items-center pt-2">
                <a href="<?= site_url('transactions') ?>" class="btn btn-brand-outline">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>