<?= $this->extend('app_layout') ?>

<?= $this->section('title') ?>Contas<?= $this->endSection() ?>

<?= $this->section('main') ?>
<?php
$accounts = $accounts ?? [];

$totalBalanceCents = 0;
foreach ($accounts as $acc) {
    $totalBalanceCents += $acc->current_balance ?? 0;
}

// Categorias fixas (mostra sempre as 4, mesmo que vazias)
$categories = [
    'bank'    => ['label' => 'Contas Bancárias',   'icon' => 'bi-bank',        'color' => '#43B790', 'items' => []],
    'savings' => ['label' => 'Poupanças',          'icon' => 'bi-safe',        'color' => '#60A5FA', 'items' => []],
    'cash'    => ['label' => 'Dinheiro Físico',    'icon' => 'bi-cash-coin',   'color' => '#F3B562', 'items' => []],
    'credit'  => ['label' => 'Cartões de Crédito', 'icon' => 'bi-credit-card', 'color' => '#FF5C5C', 'items' => []],
];

foreach ($accounts as $acc) {
    $type = strtolower((string) $acc->type);
    if (isset($categories[$type])) {
        $categories[$type]['items'][] = $acc;
    }
}
?>

<!-- Topbar -->
<div class="app-topbar">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle" data-sidebar-toggle aria-label="Abrir menu">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div style="font-size:0.68rem; text-transform:uppercase; letter-spacing:0.1em; color:#43B790; font-weight:600; margin-bottom:0.25rem;">
                Contas
            </div>
            <h1>As Tuas Contas</h1>
            <p>Consulta e gere as tuas contas por categoria.</p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('accounts/create') ?>" class="btn-brand-primary">
            <i class="bi bi-plus-lg"></i> Nova Conta
        </a>
    </div>
</div>

<div class="app-content">

    <!-- Resumo do saldo total -->
    <div class="n-card mb-4" style="height: auto;">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="n-metric-label">Saldo total</div>
                <div class="n-metric-value">€ <?= number_format($totalBalanceCents / 100, 2, ',', '.') ?></div>
                <div class="n-metric-meta">Os valores são guardados em cêntimos.</div>
            </div>
            <div class="col-md-5 text-md-end mt-3 mt-md-0">
                <span class="badge-account">
                    <i class="bi bi-collection me-1" style="color:#43B790;"></i>
                    <?= count($accounts) ?> conta<?= count($accounts) === 1 ? '' : 's' ?> configurada<?= count($accounts) === 1 ? '' : 's' ?>
                </span>
            </div>
        </div>
    </div>

    <!-- Grelha 2x2 com categorias -->
    <div class="row g-3">

        <?php foreach ($categories as $key => $cat) : ?>
            <?php
            $count = count($cat['items']);
            $catTotal = 0;
            foreach ($cat['items'] as $acc) {
                $catTotal += $acc->current_balance ?? 0;
            }
            ?>

            <div class="col-lg-6">
                <div class="cat-panel" style="--cat-color: <?= $cat['color'] ?>;">

                    <!-- Cabeçalho da categoria -->
                    <div class="cat-panel-head">
                        <div class="d-flex align-items-center gap-2">
                            <span class="cat-panel-icon">
                                <i class="bi <?= $cat['icon'] ?>"></i>
                            </span>
                            <div>
                                <div class="cat-panel-title"><?= $cat['label'] ?></div>
                                <div class="cat-panel-sub">
                                    <?= $count ?> conta<?= $count === 1 ? '' : 's' ?>
                                    <?php if ($count > 0) : ?>
                                        • € <?= number_format($catTotal / 100, 2, ',', '.') ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($count > 1) : ?>
                            <div class="cat-panel-nav">
                                <button type="button" class="cat-nav-btn" data-cat-prev="<?= $key ?>" aria-label="Anterior">
                                    <i class="bi bi-chevron-left"></i>
                                </button>
                                <span class="cat-nav-counter" id="counter-<?= $key ?>">1 / <?= $count ?></span>
                                <button type="button" class="cat-nav-btn" data-cat-next="<?= $key ?>" aria-label="Próxima">
                                    <i class="bi bi-chevron-right"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Carrossel de contas -->
                    <div class="cat-panel-body">
                        <?php if ($count === 0) : ?>
                            <div class="cat-empty">
                                <i class="bi <?= $cat['icon'] ?>"></i>
                                <div>Sem contas nesta categoria</div>
                                <a href="<?= site_url('accounts/create') ?>" class="cat-empty-link">+ Adicionar</a>
                            </div>
                        <?php else : ?>
                            <div class="cat-carousel" data-cat-carousel="<?= $key ?>">
                                <?php foreach ($cat['items'] as $i => $account) : ?>
                                    <?php
                                    $balance = $account->current_balance ?? $account->initial_balance ?? 0;
                                    $initial = $account->initial_balance ?? 0;
                                    $diff    = $balance - $initial;
                                    ?>
                                    <div class="cat-slide <?= $i === 0 ? 'is-active' : '' ?>" data-slide-index="<?= $i ?>">
                                        <div class="cat-slide-inner">

                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <span class="cat-slide-icon">
                                                    <i class="bi <?= $cat['icon'] ?>"></i>
                                                </span>
                                                <span class="cat-slide-id">#<?= esc($account->id) ?></span>
                                            </div>

                                            <div class="cat-slide-name"><?= esc($account->name) ?></div>
                                            <div class="cat-slide-type"><?= esc($account->type) ?></div>

                                            <div class="cat-slide-balance-label">Saldo atual</div>
                                            <div class="cat-slide-balance">€ <?= number_format($balance / 100, 2, ',', '.') ?></div>

                                            <div class="d-flex justify-content-between cat-slide-meta">
                                                <span>Inicial: € <?= number_format($initial / 100, 2, ',', '.') ?></span>
                                                <span style="color: <?= $diff >= 0 ? '#43B790' : '#FF5C5C' ?>; font-family: var(--font-mono); font-weight:600;">
                                                    <?= $diff >= 0 ? '+' : '−' ?> € <?= number_format(abs($diff) / 100, 2, ',', '.') ?>
                                                </span>
                                            </div>

                                            <div class="cat-slide-actions">
                                                <a href="<?= site_url('accounts/' . $account->id) ?>" class="btn-brand-outline" style="padding: 0.3rem 0.6rem; font-size:0.75rem;">
                                                    <i class="bi bi-eye"></i> Detalhes
                                                </a>
                                                <div class="d-flex gap-1">
                                                    <a href="<?= site_url('accounts/' . $account->id . '/edit') ?>" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem;" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="<?= site_url('accounts/' . $account->id) ?>" method="post" data-confirm="Tens a certeza que pretendes eliminar a conta '<?= esc($account->name) ?>'?" class="d-inline">
                                                        <?= csrf_field() ?>
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button type="submit" class="btn-brand-outline" style="padding: 0.3rem 0.55rem; font-size:0.75rem; color:#FF5C5C !important; border-color: rgba(255,92,92,0.3) !important;" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        <?php endforeach; ?>

    </div>

</div>

<style>
    /* ============================================
       CATEGORY PANEL
       ============================================ */
    .cat-panel {
        background: #141414;
        border: 1px solid rgba(245,245,245,0.06);
        border-radius: 14px;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: border-color 0.2s ease;
    }
    .cat-panel:hover {
        border-color: color-mix(in srgb, var(--cat-color) 30%, transparent);
    }

    .cat-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.1rem 1.35rem;
        border-bottom: 1px solid rgba(245,245,245,0.05);
        background: color-mix(in srgb, var(--cat-color) 5%, transparent);
    }

    .cat-panel-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        background: color-mix(in srgb, var(--cat-color) 15%, transparent);
        color: var(--cat-color);
        flex-shrink: 0;
    }

    .cat-panel-title {
        color: #F5F5F5;
        font-size: 0.92rem;
        font-weight: 600;
    }
    .cat-panel-sub {
        color: rgba(245,245,245,0.45);
        font-size: 0.72rem;
        margin-top: 0.1rem;
    }

    .cat-panel-nav {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }
    .cat-nav-btn {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        background: rgba(245,245,245,0.04);
        border: 1px solid rgba(245,245,245,0.08);
        color: rgba(245,245,245,0.6);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.7rem;
        transition: all 0.15s ease;
    }
    .cat-nav-btn:hover:not(:disabled) {
        color: var(--cat-color);
        border-color: color-mix(in srgb, var(--cat-color) 40%, transparent);
        background: color-mix(in srgb, var(--cat-color) 8%, transparent);
    }
    .cat-nav-btn:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    .cat-nav-counter {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: rgba(245,245,245,0.5);
        min-width: 32px;
        text-align: center;
    }

    .cat-panel-body {
        flex: 1;
        padding: 1.1rem 1.35rem 1.35rem;
        display: flex;
        flex-direction: column;
    }

    /* Carrossel */
    .cat-carousel {
        position: relative;
        flex: 1;
    }

    .cat-slide {
        display: none;
        animation: catFade 0.25s ease;
    }
    .cat-slide.is-active {
        display: block;
    }

    @keyframes catFade {
        from { opacity: 0; transform: translateY(4px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .cat-slide-inner {
        padding: 1rem 1.1rem;
        border-radius: 10px;
        background: #0f0f0f;
        border: 1px solid rgba(245,245,245,0.05);
    }

    .cat-slide-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        background: color-mix(in srgb, var(--cat-color) 15%, transparent);
        color: var(--cat-color);
    }
    .cat-slide-id {
        font-family: var(--font-mono);
        font-size: 0.7rem;
        color: rgba(245,245,245,0.35);
    }

    .cat-slide-name {
        color: #F5F5F5;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 0.15rem;
    }
    .cat-slide-type {
        color: rgba(245,245,245,0.4);
        font-size: 0.72rem;
        text-transform: capitalize;
        margin-bottom: 1rem;
    }

    .cat-slide-balance-label {
        font-size: 0.62rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: rgba(245,245,245,0.4);
        font-weight: 600;
    }
    .cat-slide-balance {
        font-family: var(--font-mono);
        font-size: 1.5rem;
        font-weight: 700;
        color: #F5F5F5;
        margin: 0.25rem 0 0.6rem;
    }

    .cat-slide-meta {
        font-size: 0.75rem;
        color: rgba(245,245,245,0.45);
        margin-bottom: 1rem;
    }

    .cat-slide-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 0.85rem;
        border-top: 1px solid rgba(245,245,245,0.05);
    }

    /* Estado vazio */
    .cat-empty {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem 0;
        color: rgba(245,245,245,0.35);
        gap: 0.5rem;
    }
    .cat-empty i {
        font-size: 1.8rem;
        color: color-mix(in srgb, var(--cat-color) 40%, transparent);
    }
    .cat-empty div {
        font-size: 0.82rem;
    }
    .cat-empty-link {
        font-size: 0.78rem;
        color: var(--cat-color);
        text-decoration: none;
        font-weight: 500;
        margin-top: 0.25rem;
    }
    .cat-empty-link:hover {
        color: var(--cat-color);
        opacity: 0.8;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializa cada carrossel
        document.querySelectorAll('[data-cat-carousel]').forEach(carousel => {
            const key = carousel.dataset.catCarousel;
            const slides = carousel.querySelectorAll('.cat-slide');
            const counter = document.getElementById('counter-' + key);
            const prevBtn = document.querySelector('[data-cat-prev="' + key + '"]');
            const nextBtn = document.querySelector('[data-cat-next="' + key + '"]');

            if (!slides.length) return;

            let current = 0;

            function show(index) {
                current = (index + slides.length) % slides.length;
                slides.forEach((s, i) => s.classList.toggle('is-active', i === current));
                if (counter) counter.textContent = (current + 1) + ' / ' + slides.length;
                if (prevBtn) prevBtn.disabled = current === 0;
                if (nextBtn) nextBtn.disabled = current === slides.length - 1;
            }

            if (prevBtn) prevBtn.addEventListener('click', () => show(current - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => show(current + 1));

            show(0);
        });
    });
</script>
<?= $this->endSection() ?>