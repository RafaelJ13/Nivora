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
                <div class="n-card" style="flex: 1; padding: 1.75rem; display: flex; flex-direction: column; justify-content: space-between;">

                    <div>
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

                    <!-- Info em baixo (preenche o vazio) -->
                    <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid rgba(245,245,245,0.05);">
                        <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                            <div class="tx-icon" style="width: 32px; height: 32px; font-size: 0.85rem; background: rgba(67,183,144,0.12); color: #43B790; flex-shrink: 0;">
                                <i class="bi bi-lightbulb"></i>
                            </div>
                            <div>
                                <div style="color: #F5F5F5; font-size: 0.82rem; font-weight: 600; margin-bottom: 0.15rem;">
                                    Dica
                                </div>
                                <div style="color: rgba(245,245,245,0.45); font-size: 0.78rem; line-height: 1.5;">
                                    Escolhe um nome claro e curto. Categorias bem definidas tornam os teus relatórios mais úteis.
                                </div>
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
                            <div id="previewIcon" class="tx-icon" style="width:56px; height:56px; font-size:1.4rem; background: rgba(255,92,92,0.12); color:#FF5C5C;">
                                <i class="bi bi-tag"></i>
                            </div>
                            <span id="previewTypeBadge" class="badge-expense">
                                <i class="bi bi-dash-circle me-1"></i> Despesa
                            </span>
                        </div>
                        <div style="min-width: 0; text-align: left;">
                            <div id="previewName" style="color:#F5F5F5; font-weight:600; font-size:1.4rem; margin-bottom:0.5rem;">
                                Sem nome
                            </div>
                            <div style="color: rgba(245,245,245,0.45); font-size:0.85rem; line-height: 1.5;">
                                Esta categoria será usada para agrupar os teus movimentos de
                                <span id="previewTypeText" style="color:#F5F5F5;">despesa</span>.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="n-card" style="height: auto; padding: 1.25rem;">
                    <button type="submit" class="btn-brand-primary w-100 justify-content-center mb-2" style="padding: 0.75rem;">
                        <i class="bi bi-check-lg"></i> <?= $isEdit ? 'Guardar Alterações' : 'Criar Categoria' ?>
                    </button>
                    <a href="<?= site_url('categories') ?>" class="btn-brand-outline w-100 justify-content-center" style="padding: 0.65rem;">
                        Cancelar
                    </a>
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
</style>


<?= $this->endSection() ?>