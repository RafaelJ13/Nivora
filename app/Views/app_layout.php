<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $pageTitle = trim($this->renderSection('title')); ?>
    <title><?= $pageTitle !== '' ? esc($pageTitle) . ' — Nivora' : 'Nivora — Gestão Financeira Pessoal' ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Geist+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        try {
            if (window.matchMedia('(min-width: 992px)').matches && localStorage.getItem('nivora-sidebar-collapsed') === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        } catch (error) {
            console.warn('Não foi possível carregar a preferência da barra lateral.', error);
        }
    </script>
    <style>
        @font-face {
            font-family: 'Geist';
            src: url('<?= base_url('/Geist.woff2') ?>') format('woff2-variations');
            font-weight: 100 900;
            font-style: normal;
            font-display: swap;
            font-feature-settings: "ss02" 1, "ss03" 1;
        }

        :root {
            --n-bg: #0a0a0a;
            --n-surface: #141414;
            --n-surface-2: #161616;
            --n-surface-3: #1f1f1f;
            --n-border: rgba(245, 245, 245, 0.06);
            --n-border-strong: rgba(245, 245, 245, 0.12);
            --n-text: #F5F5F5;
            --n-text-muted: rgba(245, 245, 245, 0.55);
            --n-text-dim: rgba(245, 245, 245, 0.35);
            --n-accent: #43B790;
            --n-accent-hover: #56c9a1;
            --n-accent-dim: rgba(67, 183, 144, 0.12);
            --n-error: #FF5C5C;
            --n-error-dim: rgba(255, 92, 92, 0.12);
            --font-main: 'Geist', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            --font-mono: 'Geist Mono', 'SF Mono', 'JetBrains Mono', monospace;
        }

        html, body, button, input, select, textarea, .btn, .form-control, .form-select, .dropdown-item {
            font-family: 'Geist', -apple-system, BlinkMacSystemFont, sans-serif !important;
        }

        .amount, code, pre, .font-mono, [style*="font-mono"] {
            font-family: 'Geist Mono', monospace !important;
        }

        * { box-sizing: border-box; }

        html, body {
            background: #0a0a0a !important;
            color: #F5F5F5 !important;
            font-family: var(--font-main);
            font-size: 16px;
            margin: 0;
            min-height: 100vh;
        }

        /* ============ APP SHELL ============ */
        .app-shell {
            display: flex;
            min-height: 100vh;
            background: #0a0a0a !important;
        }

        /* ============ SIDEBAR ============ */
        .app-sidebar {
            width: 230px;
            flex-shrink: 0;
            background: #0a0a0a !important;
            border-right: 1px solid rgba(245, 245, 245, 0.05) !important;
            display: flex;
            flex-direction: column;
            padding: 1.5rem 1rem;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: width 0.3s ease-in-out, padding 0.3s ease-in-out;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin: 2rem 0;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.6rem;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1rem;
            color: #F5F5F5 !important;
            text-decoration: none;
            padding: 0 0.5rem;
            min-width: 0;
            width: 100%;
        }

        @media (max-width: 991.98px) {
            .sidebar-collapse-toggle { display: none; }
        }

        .sidebar-brand-icon {
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #43B790;
            font-size: 1.2rem;
        }

        .sidebar-brand-icon img { width: 22px; height: 22px; }

        .sidebar-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.14em;
            color: rgba(245, 245, 245, 0.3) !important;
            font-weight: 600;
            padding: 0 0.75rem;
            margin-bottom: 0.7rem;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            margin-bottom: auto;
        }

        /* Item base — fundo igual para todos, borda transparente a reservar espaço */
    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.95rem 1rem 0.95rem 1.5rem;               /* padding atual */
        border-radius: 8px;
        color: rgba(245, 245, 245, 0.55) !important;
        text-decoration: none;
        font-size: 0.82rem;
        font-weight: 500;
        transition: width 0.3s ease-in-out, height 0.3s ease-in-out, padding 0.3s ease-in-out, color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease;
        position: relative;
        background: #161616;
        border: 1px solid transparent;
        box-sizing: border-box;
        width: 100%;
    }

        .sidebar-link.sidebar-collapse-toggle {
            cursor: pointer;
            text-align: left;
            margin: 0 0 0.45rem;
        }

        .sidebar-collapse-toggle i {
            transition: transform 0.3s ease-in-out;
        }

        .sidebar-collapse-toggle[aria-expanded="false"] i {
            transform: rotate(180deg);
        }

        .sidebar-collapse-toggle:focus-visible {
            outline: 2px solid #43B790;
            outline-offset: 2px;
        }

        .sidebar-link i {
            padding: 0;
            font-size: 1rem;
            width: 18px;
            text-align: center;
            color: rgba(245, 245, 245, 0.55);
        }

        .sidebar-link:hover {
            background: #1c1c1c;
            color: #F5F5F5 !important;
        }
        .sidebar-link:hover i {
            color: #F5F5F5;
        }

        /* Item ativo — mesmo fundo, só muda a borda + cor do ícone */
        .sidebar-link.active {
            background: transparent !important;
            color: #F5F5F5 !important;
            font-weight: 600;
            border-color: rgba(245, 245, 245, 0.18) !important;

        }

        .sidebar-link.active i {
            color: #43B790 !important;
        }

        /* Barra verde à esquerda */
    .sidebar-link.active::before {
        content: '';
        position: absolute;
        left: 9px;                                /* espaço entre a borda e a barra */
        top: 50%;
        transform: translateY(-50%);
        width: 3px;
        height: 20px;
        background: #43B790;
        border-radius: 2px;
    }

        .sidebar-footer {
            border-top: 1px solid rgba(245, 245, 245, 0.05) !important;
            padding-top: 1rem;
            margin-top: 1rem;
        }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem;
            border-radius: 8px;
            text-decoration: none;
            color: #F5F5F5 !important;
            transition: background 0.15s;
        }

        .sidebar-user:hover { background: rgba(245, 245, 245, 0.03) !important; color: #F5F5F5 !important; }

        .sidebar-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(67, 183, 144, 0.15) !important;
            color: #43B790 !important;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .sidebar-user-info { flex: 1; min-width: 0; }
        .sidebar-user-name { font-size: 0.8rem; font-weight: 600; color: #F5F5F5 !important; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user-role { font-size: 0.68rem; color: rgba(245, 245, 245, 0.4) !important; }

        @media (min-width: 992px) {
            html.sidebar-collapsed .app-sidebar {
                width: 76px;
                padding-right: 0.6rem;
                padding-left: 0.6rem;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-header {
                flex-direction: column;
                gap: 0;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-brand {
                padding: 0;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-brand > span:last-child,
            html.sidebar-collapsed .app-sidebar .sidebar-label,
            html.sidebar-collapsed .app-sidebar .sidebar-link > span,
            html.sidebar-collapsed .app-sidebar .sidebar-user-info,
            html.sidebar-collapsed .app-sidebar .sidebar-user > .bi-three-dots {
                display: none !important;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-collapse-toggle {
                margin: 0 auto 0.4rem;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-nav {
                align-items: center;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-link {
                width: 48px;
                height: 48px;
                justify-content: center;
                padding: 0;
                overflow: hidden;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-link i {
                padding: 0;
                font-size: 1.1rem;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-link.active::before {
                left: 3px;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-footer > .qa-btn {
                width: 48px;
                height: 42px;
                margin-right: auto;
                margin-left: auto;
                padding: 0;
                font-size: 0;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-footer > .qa-btn i {
                margin: 0 !important;
                font-size: 1rem;
            }

            html.sidebar-collapsed .app-sidebar .sidebar-user {
                justify-content: center;
                padding: 0.5rem 0;
            }
        }

        /* ============ MAIN ============ */
        .app-main {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #0a0a0a !important;
        }

        .app-topbar {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.75rem 2rem 1.25rem;
            background: #0a0a0a !important;
        }

        .app-topbar h1 {
            font-size: 1.8rem;
            font-weight: 500;
            color: #F5F5F5 !important;
            margin: 0;
            letter-spacing: -0.04em;
        }

        .app-topbar p {
            font-size: 0.85rem;
            color: rgba(245, 245, 245, 0.5) !important;
            margin: 0.2rem 0 0;
        }

        .topbar-actions { display: flex; align-items: center; gap: 0.6rem; }

        .topbar-pill {
            background: #141414 !important;
            border: 1px solid rgba(245, 245, 245, 0.06) !important;
            border-radius: 8px;
            padding: 0.5rem 0.9rem;
            font-size: 0.8rem;
            color: #F5F5F5 !important;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
        }

        .app-content { padding: 0 2rem 2rem; flex: 1; }

        /* ============ CARDS ============ */
        .n-card {
            background: #141414 !important;
            border: 1px solid rgba(245, 245, 245, 0.05) !important;
            border-radius: 14px;
            padding: 1.25rem 1.35rem;
            height: 100%;
        }

        .n-card-title {
            font-size: 0.95rem;
            font-weight: 500;
            color: #F5F5F5 !important;
            margin: 0;
        }

        .n-card-sub {
            font-size: 0.76rem;
            color: rgba(245, 245, 245, 0.4) !important;
            margin: 0.2rem 0 0;
        }

        .n-card-head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .n-link {
            font-size: 0.78rem;
            color: #43B790 !important;
            text-decoration: none;
            font-weight: 500;
        }
        .n-link:hover { color: #56c9a1 !important; }

        .n-metric-label {
            font-size: 0.75rem;
            color: rgba(245, 245, 245, 0.65) !important;
            text-transform: none;
            letter-spacing: 0;
            font-weight: 400;
        }

        .n-metric-value {
            font-family: var(--font-main);
            font-size: 2.5rem;
            font-weight: 500;
            color: #F5F5F5 !important;
            margin: 0.6rem 0 0.4rem;
            line-height: 1.1;
            letter-spacing: -0.04em;
        }
        .n-metric-value.expense { color: #FF5C5C !important; }

        .n-metric-meta {
            font-size: 0.78rem;
            color: rgba(245, 245, 245, 0.4) !important;
        }

        .n-metric-meta.pos { color: #43B790 !important; }
        .n-metric-meta.neg { color: #FF5C5C !important; }

        body:not(.landing-page) .app-shell [style*="text-transform:uppercase"],
        body:not(.landing-page) .app-shell [style*="text-transform: uppercase"] {
            text-transform: none !important;
            letter-spacing: 0 !important;
        }

        body:not(.landing-page) .account-preview-label,
        body:not(.landing-page) .category-preview-example span,
        body:not(.landing-page) .cat-slide-balance-label,
        body:not(.landing-page) .transfer-preview-account > span {
            text-transform: none !important;
            letter-spacing: 0 !important;
        }

        /* ============ TABLES ============ */
        .n-table { width: 100%; border-collapse: collapse; }
        .n-table th {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(245, 245, 245, 0.35) !important;
            font-weight: 600;
            text-align: left;
            padding: 0.6rem 0.5rem;
            border-bottom: 1px solid rgba(245, 245, 245, 0.05) !important;
        }
        .n-table td {
            padding: 0.85rem 0.5rem;
            font-size: 0.9rem;
            border-bottom: 1px solid rgba(245, 245, 245, 0.03) !important;
            color: #F5F5F5 !important;
        }
        .n-table tr:last-child td { border-bottom: none; }
        .n-table tr:hover td { background: rgba(245, 245, 245, 0.015) !important; }

        .n-table .amount { font-family: var(--font-mono); font-weight: 600; text-align: right; }
        .n-table .amount.pos { color: #43B790 !important; }
        .n-table .amount.neg { color: #FF5C5C !important; }

        /* ============ TX ICON ============ */
        .tx-icon {
            width: 30px; height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            flex-shrink: 0;
            background: rgba(245, 245, 245, 0.05);
            color: rgba(245, 245, 245, 0.7);
        }

        /* ============ CATEGORY BARS ============ */
        .cat-row { margin-bottom: 1.1rem; }
        .cat-row:last-child { margin-bottom: 0; }
        .cat-row-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.88rem;
            margin-bottom: 0.45rem;
        }
        .cat-row-head .name { color: #F5F5F5 !important; font-weight: 500; }
        .cat-row-head .name .pct { color: var(--category-color, #FF5C5C) !important; font-size: 0.76rem; margin-left: 0.4rem; font-weight: 600; }
        .cat-row-head .val { color: var(--category-color, #FF5C5C) !important; font-family: var(--font-mono); font-size: 0.84rem; font-weight: 600; }
        .cat-row-icon {
            width: 28px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.6rem;
            border-radius: 8px;
            color: var(--category-color, #FF5C5C);
            background: color-mix(in srgb, var(--category-color, #FF5C5C) 14%, transparent);
            font-size: 0.7rem;
            flex-shrink: 0;
        }
        .cat-bar { height: 5px; background: rgba(245, 245, 245, 0.06) !important; border-radius: 4px; overflow: hidden; }
        .cat-bar > div { height: 100%; background: var(--category-color, #FF5C5C) !important; border-radius: 4px; }

        /* ============ QUICK ACTIONS ============ */
        .qa-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.7rem 0.9rem;
            border-radius: 8px;
            background: #1a1a1a !important;
            border: 1px solid rgba(245, 245, 245, 0.06) !important;
            color: #F5F5F5 !important;
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            transition: all 0.15s ease;
            margin-bottom: 0.5rem;
        }
        .qa-btn:last-child { margin-bottom: 0; }
        .qa-btn:hover {
            border-color: rgba(67, 183, 144, 0.4) !important;
            color: #43B790 !important;
            background: rgba(67, 183, 144, 0.04) !important;
        }
        .qa-btn.primary {
            background: #43B790 !important;
            color: #0F1A16 !important;
            border-color: #43B790 !important;
            font-weight: 600;
        }
        .qa-btn.primary:hover { background: #56c9a1 !important; color: #0F1A16 !important; }

        /* ============ SIDEBAR BUDGET ============ */
        .sidebar-budget {
            background: #141414;
            border: 1px solid rgba(245, 245, 245, 0.05);
            border-radius: 10px;
            padding: 0.85rem;
            margin-bottom: 0.85rem;
        }
        .sidebar-budget-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.78rem;
            margin-bottom: 0.55rem;
        }
        .sidebar-budget-head span:first-child { color: #F5F5F5; font-weight: 500; }
        .sidebar-budget-head span:last-child { color: #43B790; font-weight: 700; font-family: var(--font-mono); font-size: 0.82rem; }
        .sidebar-budget-bar {
            height: 3px;
            background: rgba(245, 245, 245, 0.06);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.55rem;
        }
        .sidebar-budget-bar > div { height: 100%; background: #43B790; border-radius: 4px; }
        .sidebar-budget-meta { font-size: 0.76rem; color: rgba(245, 245, 245, 0.4); }

        /* ============ ALERTS ============ */
        .n-alert {
            border-radius: 10px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            border: 1px solid;
        }
        .n-alert.success { background: rgba(67,183,144,0.1) !important; border-color: rgba(67,183,144,0.25) !important; color: #b8e6d3 !important; }
        .n-alert.error { background: rgba(255,92,92,0.1) !important; border-color: rgba(255,92,92,0.25) !important; color: #ffc9c9 !important; }

        /* ============ FORM CONTROLS ============ */
        .form-control, .form-select {
            background-color: #0f0f0f !important;
            border: 1px solid rgba(245, 245, 245, 0.1) !important;
            color: #F5F5F5 !important;
            border-radius: 8px;
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: #43B790 !important;
            box-shadow: 0 0 0 3px rgba(67, 183, 144, 0.15) !important;
            background-color: #0f0f0f !important;
        }

        .form-control::placeholder { color: rgba(245, 245, 245, 0.3); }

        .form-label {
            font-weight: 500;
            font-size: 0.86rem;
            color: rgba(245, 245, 245, 0.6);
            margin-bottom: 0.4rem;
        }

        .input-group-text {
            background-color: #0f0f0f !important;
            border: 1px solid rgba(245, 245, 245, 0.1) !important;
            color: rgba(245, 245, 245, 0.5) !important;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* ============ BUTTONS ============ */
        .btn-brand-primary {
            background: #43B790 !important;
            color: #0F1A16 !important;
            border: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.55rem 1.1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .btn-brand-primary:hover { background: #56c9a1 !important; color: #0F1A16 !important; }

        .btn-brand-outline {
            background: rgba(245, 245, 245, 0.03) !important;
            color: #F5F5F5 !important;
            border: 1px solid rgba(245, 245, 245, 0.1) !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.55rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: background 0.15s ease;
        }
        .btn-brand-outline:hover { background: rgba(245, 245, 245, 0.06) !important; color: #F5F5F5 !important; }

        /* ============ BADGES ============ */
        .badge-income {
            background: rgba(67,183,144,0.15); color: #43B790;
            border: 1px solid rgba(67,183,144,0.3);
            font-weight: 600; border-radius: 6px;
            padding: 0.3rem 0.65rem; font-size: 0.8rem;
        }
        .badge-expense {
            background: rgba(255,92,92,0.15); color: #FF5C5C;
            border: 1px solid rgba(255,92,92,0.3);
            font-weight: 600; border-radius: 6px;
            padding: 0.3rem 0.65rem; font-size: 0.8rem;
        }
        .badge-account {
            background: rgba(245,245,245,0.06); color: rgba(245,245,245,0.7);
            border: 1px solid rgba(245,245,245,0.1);
            border-radius: 6px; padding: 0.25rem 0.6rem; font-size: 0.85rem;
        }

        /* ============ FOOTER ============ */
        .footer-app {
            margin-top: auto;
            border-top: 1px solid rgba(245,245,245,0.05);
            padding: 1.25rem 2rem;
            color: rgba(245,245,245,0.4);
            font-size: 0.78rem;
        }

        /* ============ MOBILE ============ */
        .sidebar-toggle {
            display: none;
            background: #141414 !important;
            border: 1px solid rgba(245, 245, 245, 0.06) !important;
            color: #F5F5F5 !important;
            border-radius: 8px;
            padding: 0.4rem 0.6rem;
            font-size: 1.1rem;
            cursor: pointer;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                position: fixed;
                top: 0; left: 0;
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                width: 260px;
                transition: transform 0.25s ease;
            }
            .app-sidebar.open { transform: translateX(0); }
            .sidebar-toggle { display: inline-flex; align-items: center; justify-content: center; }
            .app-topbar { padding: 1rem 1.25rem; }
            .app-content { padding: 0 1.25rem 1.5rem; }
            .footer-app { padding: 1rem 1.25rem; }
            .sidebar-backdrop {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                z-index: 1040;
            }
            .sidebar-backdrop.open { display: block; }
        }

        /* ============ FILTER BAR ============ */
        .filter-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            border-bottom: 1px solid rgba(245,245,245,0.05);
        }
        .filter-bar-left { display: flex; align-items: center; gap: 0.5rem; }
        .filter-bar-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.filter-select-wrap {
    position: relative;
    display: inline-flex;
    align-items: center;
    background: #0f0f0f;
    border: 1px solid rgba(245,245,245,0.08);
    border-radius: 8px;
    padding: 0 0.5rem;
    height: 34px;
    transition: border-color 0.15s ease;
    overflow: hidden;                       /* ← garante que nada sai fora */
}
.filter-select-wrap:hover { border-color: rgba(245,245,245,0.16); }
.filter-select-wrap:focus-within { border-color: #43B790; }

.filter-select-icon {
    color: rgba(245,245,245,0.4);
    font-size: 0.78rem;
    margin-right: 0.35rem;
    pointer-events: none;
    flex-shrink: 0;
}

.filter-select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;                  /* ← Firefox */
    background: transparent !important;      /* ← força transparente */
    border: none;
    color: #F5F5F5;
    font-size: 0.8rem;
    font-weight: 500;
    padding: 0 1.6rem 0 0;                   /* ← espaço à direita para o caret */
    height: 100%;
    cursor: pointer;
    outline: none;
    text-overflow: ellipsis;                 /* ← corta o texto se for longo */
    overflow: hidden;
    white-space: nowrap;
    min-width: 0;
    background-image: none !important;
    background-repeat: no-repeat;
    padding-right: 10rem;
}

/* Remove a seta nativa em browsers WebKit */
.filter-select::-ms-expand { display: none; }

.filter-select option {
    background: #141414;
    color: #F5F5F5;
}

.filter-select-caret {
    position: absolute;
    right: 0.55rem;
    top: 50%;
    transform: translateY(-50%);             /* ← centra verticalmente */
    color: rgba(245,245,245,0.4);
    font-size: 0.7rem;
    pointer-events: none;
    line-height: 1;
}

        .filter-search {
            position: relative;
            display: inline-flex;
            align-items: center;
            background: #0f0f0f;
            border: 1px solid rgba(245,245,245,0.08);
            border-radius: 8px;
            height: 34px;
            width: 240px;
            transition: border-color 0.15s ease;
        }
        .filter-search:hover { border-color: rgba(245,245,245,0.16); }
        .filter-search:focus-within { border-color: #43B790; }

        .filter-search-icon {
            position: absolute;
            left: 0.7rem;
            color: rgba(245,245,245,0.4);
            font-size: 0.78rem;
            pointer-events: none;
        }
        .filter-search-input {
            flex: 1;
            background: transparent;
            border: none;
            color: #F5F5F5;
            font-size: 0.8rem;
            padding: 0 2rem 0 2.1rem;
            height: 100%;
            outline: none;
            min-width: 0;
        }
        .filter-search-input::placeholder { color: rgba(245,245,245,0.3); }
        .filter-search-submit {
            position: absolute;
            right: 0.35rem;
            width: 26px; height: 26px;
            border-radius: 6px;
            background: transparent;
            border: none;
            color: rgba(245,245,245,0.5);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .filter-search-submit:hover { color: #43B790; background: rgba(67,183,144,0.1); }

        .filter-clear {
            width: 34px; height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #0f0f0f;
            border: 1px solid rgba(245,245,245,0.08);
            border-radius: 8px;
            color: rgba(245,245,245,0.5);
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .filter-clear:hover {
            border-color: rgba(255,92,92,0.4);
            color: #FF5C5C;
            background: rgba(255,92,92,0.05);
        }

        /* ============ TRANSACTION TYPE CARDS ============ */
        .transaction-type-card {
            cursor: pointer;
            display: block;
        }
        .transaction-type-card input[type="radio"] {
            display: none;
        }
        .type-box {
            padding: 1rem;
            border-radius: 10px;
            border: 1px solid rgba(245,245,245,0.08);
            background: #0f0f0f;
            transition: all 0.15s ease;
        }
        .transaction-type-card:hover .type-box {
            border-color: rgba(245,245,245,0.18);
        }
        .transaction-type-card input[type="radio"]:checked + .type-box {
            border-color: #43B790 !important;
            background: rgba(67,183,144,0.08) !important;
            box-shadow: inset 0 0 0 1px #43B790;
        }
        /* ============ PAGER ============ */
.n-pager {
    display: flex;
    justify-content: center;
    margin: 2rem 0 0;
}

.n-pager-list {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    list-style: none;
    margin: 0;
    padding: 0;
}

.n-pager-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 34px;
    height: 34px;
    padding: 0 0.65rem;
    border-radius: 8px;
    background: #161616;
    border: 1px solid rgba(245,245,245,0.06);
    color: rgba(245,245,245,0.6);
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.15s ease;
    font-family: var(--font-mono);
    line-height: 1;
}

.n-pager-link:hover {
    background: #1c1c1c;
    border-color: rgba(245,245,245,0.14);
    color: #F5F5F5;
}

.n-pager-link.n-pager-active {
    background: rgba(67,183,144,0.12) !important;
    border-color: #43B790 !important;
    color: #43B790 !important;
    font-weight: 700;
}

.n-pager-link.n-pager-arrow {
    color: rgba(245,245,245,0.5);
    padding: 0;
    min-width: 34px;
}

.n-pager-link.n-pager-arrow i {
    font-size: 0.75rem;
    line-height: 1;
}

.n-pager-link.n-pager-arrow:hover {
    color: #43B790;
    border-color: rgba(67,183,144,0.4);
    background: rgba(67,183,144,0.05);
}

.n-pager-link.n-pager-disabled {
    opacity: 0.35;
    cursor: not-allowed;
    pointer-events: none;
    color: rgba(245,245,245,0.3);
    background: #131313;
}
    </style>
</head>
<body>
<?php
    $uri = service('uri');
    $currentSegment = $uri->getSegment(1) ?? 'dashboard';
    $isLoggedIn = function_exists('auth') && auth()->loggedIn();
    $user = $isLoggedIn ? auth()->user() : null;
    $displayName = $user ? ($user->name ?: $user->username) : '';
    $userInitial = $displayName ? strtoupper(substr($displayName, 0, 1)) : '?';
?>

<div class="app-shell">

    <!-- ============ SIDEBAR ============ -->
    <aside class="app-sidebar" id="appSidebar">
        <div class="sidebar-header">
            <a href="<?= $isLoggedIn ? site_url('dashboard') : site_url('/') ?>" class="sidebar-brand">
                <span class="sidebar-brand-icon">
                    <img src="<?= base_url('favicon.png') ?>" alt="Nivora">
                </span>
                <span>Nivora</span>
            </a>
        </div>

        <div class="sidebar-label">Workspace</div>
        <nav class="sidebar-nav">
            <a href="<?= site_url('dashboard') ?>" class="sidebar-link <?= $currentSegment === 'dashboard' ? 'active' : '' ?>" title="Dashboard">
                <i class="bi bi-grid-1x2"></i><span>Dashboard</span>
            </a>
            <a href="<?= site_url('accounts') ?>" class="sidebar-link <?= $currentSegment === 'accounts' ? 'active' : '' ?>" title="Accounts">
                <i class="bi bi-wallet2"></i><span>Accounts</span>
            </a>
            <a href="<?= site_url('categories') ?>" class="sidebar-link <?= $currentSegment === 'categories' ? 'active' : '' ?>" title="Categories">
                <i class="bi bi-tag"></i><span>Categories</span>
            </a>
            <a href="<?= site_url('transactions') ?>" class="sidebar-link <?= $currentSegment === 'transactions' ? 'active' : '' ?>" title="Transactions">
                <i class="bi bi-arrow-left-right"></i><span>Transactions</span>
            </a>
        </nav>
        <button type="button" class="sidebar-link sidebar-collapse-toggle" id="sidebarCollapseToggle" aria-label="Recolher menu lateral" aria-expanded="true" title="Recolher menu lateral">
            <i class="bi bi-layout-sidebar-inset" aria-hidden="true"></i><span>Recolher menu</span>
        </button>

        <div class="sidebar-footer">
            <a href="<?= site_url('transactions/new') ?>" class="qa-btn primary" style="margin-bottom: 0.85rem; justify-content: center;" title="Nova Transação">
                <i class="bi bi-plus-lg me-1"></i><span>Nova Transação</span>
            </a>

            <div class="dropdown">
                <a href="#" class="sidebar-user" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="sidebar-user-avatar"><?= esc($userInitial) ?></span>
                    <span class="sidebar-user-info">
                        <span class="sidebar-user-name d-block"><?= esc($displayName ?: 'Visitante') ?></span>
                        <span class="sidebar-user-role"><?= $isLoggedIn ? 'Conta pessoal' : 'Sessão não iniciada' ?></span>
                    </span>
                    <i class="bi bi-three-dots text-secondary"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary border-opacity-25 shadow py-2" style="min-width: 200px; font-size: 0.85rem;">
                    <?php if ($isLoggedIn) : ?>
                        <li><a class="dropdown-item py-2" href="<?= url_to('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Terminar Sessão</a></li>
                    <?php else : ?>
                        <li><a class="dropdown-item py-2" href="<?= url_to('login') ?>"><i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sessão</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- ============ MAIN ============ -->
    <div class="app-main">
        <main class="app-content">
            <?php if (session('success')) : ?>
                <div class="n-alert success">
                    <i class="bi bi-check-circle-fill"></i>
                    <div><?= esc(session('success')) ?></div>
                </div>
            <?php endif; ?>

            <?php if (session('error')) : ?>
                <div class="n-alert error">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div>
                        <strong>Por favor, verifica os erros abaixo:</strong>
                        <ul class="mb-0 small ps-4 mt-1">
                            <?php foreach ((array) session('error') as $err) : ?>
                                <li><?= esc($err) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('main') ?>
        </main>

        <footer class="footer-app">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span style="width:6px;height:6px;border-radius:50%;background:#43B790;display:inline-block;"></span>
                    <span style="color:#F5F5F5;font-weight:600;">Nivora</span>
                    <span>• Gestão Financeira Pessoal V1.0</span>
                </div>
                <div class="d-flex gap-3">
                    <a href="<?= site_url('privacidade') ?>" class="text-decoration-none" style="color: rgba(245,245,245,0.4);">Privacidade</a>
                    <a href="<?= site_url('cookies') ?>" class="text-decoration-none" style="color: rgba(245,245,245,0.4);">Cookies</a>
                    <a href="<?= site_url('termos') ?>" class="text-decoration-none" style="color: rgba(245,245,245,0.4);">Termos</a>
                    <a href="<?= site_url('contacto') ?>" class="text-decoration-none" style="color: rgba(245,245,245,0.4);">Contacto</a>
                </div>
            </div>
        </footer>
    </div>
</div>

<!-- Global Delete Confirmation Modal -->
<div class="modal fade" id="nivoraDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="background:#141414;border:1px solid rgba(245,245,245,0.08);border-radius:14px;">
            <div class="modal-body p-0">
                <div class="text-center px-5 pt-5 pb-4">
                    <div style="width:60px;height:60px;border-radius:16px;background:rgba(255,92,92,0.12);border:1px solid rgba(255,92,92,0.25);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;">
                        <i class="bi bi-trash3" style="font-size:1.4rem;color:#FF5C5C;"></i>
                    </div>
                    <p style="font-size:1.05rem;font-weight:700;color:#F5F5F5;margin-bottom:0.5rem;">Confirmar Eliminação</p>
                    <p id="nivoraDeleteModalMessage" style="font-size:0.82rem;color:rgba(245,245,245,0.55);margin:0;line-height:1.6;">
                        Tens a certeza que pretendes eliminar este registo? Esta ação não pode ser desfeita.
                    </p>
                </div>
                <hr style="border:none;height:1px;background:rgba(245,245,245,0.06);margin:0;">
                <div class="d-flex gap-2 px-4 py-3">
                    <button type="button" class="btn-brand-outline flex-fill justify-content-center" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-brand-primary flex-fill justify-content-center" id="nivoraConfirmDeleteBtn" style="background:#FF5C5C !important;color:#fff !important;">
                        <i class="bi bi-trash3-fill"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('appSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');
        const collapseToggle = document.getElementById('sidebarCollapseToggle');

        if (sidebar && collapseToggle && window.matchMedia('(min-width: 992px)').matches) {
            const isCollapsed = document.documentElement.classList.contains('sidebar-collapsed');
            collapseToggle.setAttribute('aria-expanded', String(!isCollapsed));
            collapseToggle.setAttribute('aria-label', isCollapsed ? 'Expandir menu lateral' : 'Recolher menu lateral');
            collapseToggle.title = isCollapsed ? 'Expandir menu lateral' : 'Recolher menu lateral';
        }

        collapseToggle?.addEventListener('click', function () {
            if (!sidebar || !window.matchMedia('(min-width: 992px)').matches) return;
            const isCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');
            try {
                localStorage.setItem('nivora-sidebar-collapsed', String(isCollapsed));
            } catch (error) {
                console.warn('Não foi possível guardar a preferência da barra lateral.', error);
            }
            collapseToggle.setAttribute('aria-expanded', String(!isCollapsed));
            collapseToggle.setAttribute('aria-label', isCollapsed ? 'Expandir menu lateral' : 'Recolher menu lateral');
            collapseToggle.title = isCollapsed ? 'Expandir menu lateral' : 'Recolher menu lateral';
        });

        document.querySelectorAll('[data-sidebar-toggle]').forEach(btn => {
            btn.addEventListener('click', () => {
                sidebar?.classList.toggle('open');
                backdrop?.classList.toggle('open');
            });
        });
        backdrop?.addEventListener('click', () => {
            sidebar?.classList.remove('open');
            backdrop?.classList.remove('open');
        });

        const deleteModalEl = document.getElementById('nivoraDeleteModal');
        if (!deleteModalEl) return;
        const deleteModal = new bootstrap.Modal(deleteModalEl);
        const confirmBtn = document.getElementById('nivoraConfirmDeleteBtn');
        const modalMessage = document.getElementById('nivoraDeleteModalMessage');
        let formToSubmit = null;

        document.addEventListener('submit', function (e) {
            const form = e.target;
            const methodInput = form.querySelector('input[name="_method"][value="DELETE"]');
            const hasDataConfirm = form.hasAttribute('data-confirm');
            if ((methodInput || hasDataConfirm) && !form.dataset.confirmed) {
                e.preventDefault();
                formToSubmit = form;
                const message = form.getAttribute('data-confirm') || 'Tens a certeza que pretendes eliminar este registo? Esta ação não pode ser desfeita.';
                if (modalMessage) modalMessage.textContent = message;
                deleteModal.show();
            }
        });

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function () {
                if (formToSubmit) {
                    formToSubmit.dataset.confirmed = 'true';
                    formToSubmit.submit();
                }
            });
        }
    });
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>