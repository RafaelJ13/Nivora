<?= $this->extend('app_layout') ?>

<?php
$isEdit = isset($category) && $category !== null;
$pageTitle = $isEdit ? 'Editar Categoria' : 'Nova Categoria';
$selectedType = strtoupper((string) old('type', $category->type ?? 'EXPENSE'));
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
                <?= $isEdit ? 'Editar categoria' : 'Nova categoria' ?>
            </div>
            <h1><?= $pageTitle ?></h1>
            <p><?= $isEdit ? 'Atualiza os dados desta categoria.' : 'Cria uma nova categoria para organizar os teus movimentos.' ?></p>
        </div>
    </div>
    <div class="topbar-actions">
        <a href="<?= site_url('categories') ?>" class="btn-brand-outline">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
    </div>
</div>

<div class="app-content">
    <form action="<?= $isEdit ? site_url('categories/' . $category->id) : site_url('categories') ?>" method="post" id="categoryForm">
        <?= csrf_field() ?>
        <?php if ($isEdit) : ?>
            <input type="hidden" name="_method" value="PUT">
        <?php endif; ?>

        <div class="row g-3 align-items-stretch">

            <!-- COLUNA ESQUERDA: Formulário -->
            <div class="col-lg-6 d-flex">
                <div class="n-card h-100 w-100 d-flex flex-column" style="padding: 1.75rem;">

                    <div class="category-form-fields">
                        <!-- Nome -->
                        <div class="mb-4">
                            <label class="form-label" for="name">
                                <i class="bi bi-tag me-1"></i> Nome da Categoria
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="ex: Alimentação, Combustível, Salário…"
                                value="<?= old('name', $category->name ?? '') ?>" required oninput="updatePreview()">
                            <div style="color: rgba(245,245,245,0.35); font-size:0.72rem; margin-top:0.4rem;">
                                Nome descritivo para agrupar as tuas transações.
                            </div>
                        </div>

                        <!-- Tipo -->
                        <div>
                            <label class="form-label d-block mb-3">Tipo</label>
                            <div class="d-flex justify-content-center gap-3">
                                <div style="flex: 1; max-width: 220px;">
                                    <label class="type-card type-card-expense" for="type_expense">
                                        <input type="radio" name="type" id="type_expense" value="EXPENSE" <?= $selectedType === 'EXPENSE' ? 'checked' : '' ?> onchange="updatePreview()">
                                        <div class="type-box">
                                            <div class="type-icon" style="color:#FF5C5C; background: rgba(255,92,92,0.12);">
                                                <i class="bi bi-dash-circle-fill"></i>
                                            </div>
                                            <div>
                                                <div class="type-title">Despesa</div>
                                                <div class="type-sub">Dinheiro que sai</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                                <div style="flex: 1; max-width: 220px;">
                                    <label class="type-card type-card-income" for="type_income">
                                        <input type="radio" name="type" id="type_income" value="INCOME" <?= $selectedType === 'INCOME' ? 'checked' : '' ?> onchange="updatePreview()">
                                        <div class="type-box">
                                            <div class="type-icon" style="color:#43B790; background: rgba(67,183,144,0.12);">
                                                <i class="bi bi-plus-circle-fill"></i>
                                            </div>
                                            <div>
                                                <div class="type-title">Rendimento</div>
                                                <div class="type-sub">Dinheiro que entra</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-auto pt-4" style="border-top: 1px solid rgba(245,245,245,0.05);">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <button type="submit" class="btn-brand-primary flex-fill justify-content-center" style="padding: 0.75rem;">
                                <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Categoria' ?>
                            </button>
                            <a href="<?= site_url('categories') ?>" class="btn-brand-outline flex-fill justify-content-center" style="padding: 0.65rem;">
                                Cancelar
                            </a>
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

                    <div class="category-preview-card" id="categoryPreviewCard">
                        <div class="d-flex justify-content-between align-items-start mb-4">
                            <div id="previewIcon" class="tx-icon category-preview-icon">
                                <i class="bi bi-tag"></i>
                            </div>
                            <span id="previewTypeBadge" class="badge-expense">
                                <i class="bi bi-dash-circle me-1"></i> Despesa
                            </span>
                        </div>

                        <div id="previewName" class="category-preview-name">Sem nome</div>
                        <div class="category-preview-example">
                            <i class="bi bi-receipt" aria-hidden="true"></i>
                            <div>
                                <span>Categoria do movimento</span>
                                <strong id="previewExampleName">Sem nome</strong>
                            </div>
                        </div>
                    </div>

                    <div class="category-preview-note">
                        <i class="bi bi-lightbulb" aria-hidden="true"></i>
                        <span>As categorias ajudam a organizar e analisar os teus <span id="previewCategoryTypeDescription"><?= $selectedType === 'INCOME' ? 'rendimentos' : 'despesas' ?></span>.</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    /* ============================================
       TYPE CARDS — LIQUID GLASS
       ============================================ */

    .type-card { cursor: pointer; display: block; }

    /* Esconde o radio nativo */
    .type-card input[type="radio"],
    .type-card input.form-check-input {
        display: none !important;
        appearance: none !important;
        -webkit-appearance: none !important;
        opacity: 0 !important;
        position: absolute !important;
        width: 0 !important;
        height: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        pointer-events: none !important;
    }

    /* --- Base: vidro --- */
    .type-box {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 1rem 1.1rem;
        border-radius: 12px;
        border: 1px solid rgba(245,245,245,0.08);
        background: rgba(245,245,245,0.03);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        transition: all 0.2s ease;
        overflow: hidden;
    }

    /* Realce no topo (highlight de vidro) */
    .type-box::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: inherit;
        padding: 1px;
        background: linear-gradient(180deg, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 100%);
        -webkit-mask: linear-gradient(#000 0 0) content-box, linear-gradient(#000 0 0);
        -webkit-mask-composite: xor;
                mask-composite: exclude;
        pointer-events: none;
    }

    /* Hover (vidro ligeiramente mais visível) */
    .type-card:hover .type-box {
        background: rgba(245,245,245,0.06);
        border-color: rgba(245,245,245,0.15);
    }

    /* --- Ícone --- */
    .type-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }

    /* --- Texto --- */
    .type-title {
        color: #F5F5F5;
        font-weight: 600;
        font-size: 1rem;
    }

    .type-sub {
        font-size: 0.8rem;
        color: rgba(245,245,245,0.5);
        margin-top: 0.15rem;
    }

    /* ============================================
       DESPESA ATIVA — Liquid Glass Vermelho
       ============================================ */
    .type-card-expense input[type="radio"]:checked + .type-box {
        border-color: rgba(255,92,92,0.5) !important;
        background: rgba(255,92,92,0.08) !important;
        backdrop-filter: blur(16px) saturate(140%);
        -webkit-backdrop-filter: blur(16px) saturate(140%);
    }

    .type-card-expense input[type="radio"]:checked + .type-box::before {
        background: linear-gradient(180deg, rgba(255,92,92,0.25) 0%, rgba(255,92,92,0) 100%);
    }

    .type-card-expense input[type="radio"]:checked + .type-box .type-title {
        color: #FF5C5C;
    }

    .type-card-expense input[type="radio"]:checked + .type-box .type-icon {
        background: rgba(255,92,92,0.18) !important;
        color: #FF5C5C !important;
    }

    /* ============================================
       RENDIMENTO ATIVO — Liquid Glass Verde
       ============================================ */
    .type-card-income input[type="radio"]:checked + .type-box {
        border-color: rgba(67,183,144,0.5) !important;
        background: rgba(67,183,144,0.08) !important;
        backdrop-filter: blur(16px) saturate(140%);
        -webkit-backdrop-filter: blur(16px) saturate(140%);
    }

    .type-card-income input[type="radio"]:checked + .type-box::before {
        background: linear-gradient(180deg, rgba(67,183,144,0.25) 0%, rgba(67,183,144,0) 100%);
    }

    .type-card-income input[type="radio"]:checked + .type-box .type-title {
        color: #43B790;
    }

    .type-card-income input[type="radio"]:checked + .type-box .type-icon {
        background: rgba(67,183,144,0.18) !important;
        color: #43B790 !important;
    }

    .category-form-fields {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .category-preview-card {
        flex: 1;
        min-height: 270px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        border: 1px solid rgba(255,92,92,0.2);
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(255,92,92,0.09), rgba(245,245,245,0.02));
        transition: border-color 160ms ease, background 160ms ease;
    }

    .category-preview-card.is-income {
        border-color: rgba(67,183,144,0.2);
        background: linear-gradient(145deg, rgba(67,183,144,0.1), rgba(245,245,245,0.02));
    }

    .category-preview-icon {
        width: 54px;
        height: 54px;
        font-size: 1.35rem;
        background: rgba(255,92,92,0.12);
        color: #FF5C5C;
    }

    .category-preview-name {
        color: #F5F5F5;
        font-size: 1.4rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .category-preview-example {
        margin-top: 1.5rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        border: 1px solid rgba(245,245,245,0.07);
        border-radius: 10px;
        background: rgba(10,10,10,0.5);
    }

    .category-preview-example > i,
    .category-preview-note > i {
        color: #43B790;
        font-size: 1.1rem;
    }

    .category-preview-example > div {
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .category-preview-example span {
        color: rgba(245,245,245,0.45);
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
    }

    .category-preview-example strong {
        color: #F5F5F5;
        font-size: 0.85rem;
        overflow-wrap: anywhere;
    }

    .category-preview-note {
        margin-top: auto;
        padding-top: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
        color: rgba(245,245,245,0.5);
        font-size: 0.8rem;
        line-height: 1.5;
    }
</style>

<script>
    function updatePreview() {
        const name = document.getElementById('name').value.trim() || 'Sem nome';
        const isIncome = document.querySelector('input[name="type"]:checked').value === 'INCOME';
        const color = isIncome ? '#43B790' : '#FF5C5C';

        document.getElementById('previewName').textContent = name;
        document.getElementById('previewExampleName').textContent = name;
        document.getElementById('previewCategoryTypeDescription').textContent = isIncome ? 'rendimentos' : 'despesas';

        const badge = document.getElementById('previewTypeBadge');
        badge.className = isIncome ? 'badge-income' : 'badge-expense';
        badge.innerHTML = '<i class="bi ' + (isIncome ? 'bi-plus-circle' : 'bi-dash-circle') + ' me-1"></i> ' + (isIncome ? 'Rendimento' : 'Despesa');

        const icon = document.getElementById('previewIcon');
        icon.style.color = color;
        icon.style.background = isIncome ? 'rgba(67,183,144,0.12)' : 'rgba(255,92,92,0.12)';
        document.getElementById('categoryPreviewCard').classList.toggle('is-income', isIncome);
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('name').addEventListener('input', updatePreview);
        document.querySelectorAll('input[name="type"]').forEach(input => input.addEventListener('change', updatePreview));
        updatePreview();
    });
</script>

<?= $this->endSection() ?>