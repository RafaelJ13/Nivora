<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Transferência #<?= esc($transfer->id) ?><?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                Detalhe do movimento
            </div>
            <h1>Transferência #<?= esc($transfer->id) ?></h1>
            <p>Consulta os detalhes desta transferência.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('transactions') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="n-card h-100" style="height:auto;">
                <div class="row g-4 align-items-stretch">
                    <div class="col-md-6 d-flex transfer-show-summary">
                        <div class="w-100">
                            <span class="badge-account d-inline-block mb-3">
                                <i class="bi bi-arrow-left-right me-1"></i> Transferência
                            </span>
                            <div class="n-metric-value" style="font-size:3rem; margin:0.5rem 0 0.75rem;">
                                € <?= number_format($transfer->amount / 100, 2, ',', '.') ?>
                            </div>
                            <div style="color:#F5F5F5; font-weight:600; font-size:1.05rem;">
                                <?= esc($transfer->account_from_name) ?>
                                <i class="bi bi-arrow-right mx-1" style="color:#43B790;"></i>
                                <?= esc($transfer->account_to_name) ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between py-3" style="border-bottom:1px solid rgba(245,245,245,0.05);">
                            <span style="color:rgba(245,245,245,0.5); font-size:0.85rem;">Conta de origem</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500;"><?= esc($transfer->account_from_name) ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-3" style="border-bottom:1px solid rgba(245,245,245,0.05);">
                            <span style="color:rgba(245,245,245,0.5); font-size:0.85rem;">Conta de destino</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500;"><?= esc($transfer->account_to_name) ?></span>
                        </div>
                        <div class="d-flex justify-content-between py-3" style="border-bottom:1px solid rgba(245,245,245,0.05);">
                            <span style="color:rgba(245,245,245,0.5); font-size:0.85rem;">Data</span>
                            <span style="color:#F5F5F5; font-size:0.9rem; font-weight:500; font-family:var(--font-mono);">
                                <?= date('d/m/Y H:i', strtotime($transfer->transfer_date)) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .transfer-show-summary {
        border-right: 1px solid rgba(245,245,245,0.06);
    }

    @media (max-width: 767.98px) {
        .transfer-show-summary {
            border-right: 0;
            border-bottom: 1px solid rgba(245,245,245,0.06);
            padding-bottom: 1.5rem;
        }
    }
</style>
<?= $this->endSection() ?>
