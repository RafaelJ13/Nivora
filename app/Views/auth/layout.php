<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ? $this->renderSection('title') . ' — Nivora' : 'Autenticação — Nivora' ?></title>
    <link rel="icon" type="image/png" href="<?= base_url('/favicon.png') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&family=Geist+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="<?= base_url('assets/theme.js?v=' . filemtime(FCPATH . 'assets/theme.js')) ?>"></script>
    <link rel="stylesheet" href="<?= base_url('assets/nivora.css?v=' . filemtime(FCPATH . 'assets/nivora.css')) ?>">
    <link rel="stylesheet" href="<?= base_url('assets/theme.css?v=' . filemtime(FCPATH . 'assets/theme.css')) ?>">
    <style>
        body:has(.auth-panel) { --auth-bg: #0d1117; --auth-surface: #161b22; --auth-surface-2: #1c232c; --auth-text: #f0f6fc; --auth-muted: #9ba7b4; --auth-green: #4ade80; --auth-line: rgba(154, 167, 180, .2); min-height: 100vh; margin: 0; background: var(--auth-bg) !important; color: var(--auth-text); font-family: 'Geist', sans-serif; }
        .auth-page { min-height: 100vh; display: grid; }
        .auth-layout { min-height: 100vh; width: 100%; display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); overflow: hidden; background: var(--auth-surface); border: 1px solid rgba(255,255,255,.04); }
        .auth-aside { --auth-aside-gap-horizontal: clamp(1.5rem, 2.5vw, 2.25rem); min-height: 100%; padding: 1.5rem var(--auth-aside-gap-horizontal); background: transparent; color: #fff; position: relative; overflow: visible; display: flex; align-items: center; justify-content: center; }
        .auth-aside { grid-column: 2; grid-row: 1; flex-direction: column; align-items: stretch; }
        .auth-aside-shell { width: 100%; height: 100%; min-height: 100%; display: flex; flex-direction: column; }
        .auth-aside-inner { width: 100%; height: auto; min-height: 0; flex: 1; padding: clamp(1.5rem, 3vw, 3rem); background-color: #1f8f68; border-radius: 18px; position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; }
        .auth-aside-inner::before { content: ''; position: absolute; inset: -48%; background-image: url('<?= base_url('assets/nivora-mark.svg') ?>'); background-repeat: no-repeat; background-size: cover; background-position: center; opacity: .2; mix-blend-mode: multiply; pointer-events: none; }
        .auth-aside-inner::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,.07), transparent 42%, rgba(4,62,45,.12)); pointer-events: none; }
        .auth-brand, .auth-brand:hover { display: inline-flex; align-items: center; align-self: flex-start; gap: .55rem; color: var(--auth-text); text-decoration: none; font-family: 'Geist Mono', monospace; font-size: .95rem; font-weight: 800; letter-spacing: .08em; }
        .auth-brand img { width: 30px; height: 30px; display: block; }
        .auth-eyebrow { margin: 0 0 .8rem; padding-top: 0; color: #b6f2d8; font: 600 .68rem/1 'Geist Mono', monospace; letter-spacing: .14em; text-transform: uppercase; position: relative; z-index: 1; }
        .auth-aside h1 { max-width: 430px; margin: 0; color: #f5fff9; font-size: clamp(2.2rem, 4vw, 3.5rem); font-weight: 700; letter-spacing: -.06em; line-height: 1.04; position: relative; z-index: 1; }
        .auth-aside h1 span { color: #b6f2d8; }
        .auth-aside-copy { max-width: 410px; margin: 1.1rem 0 0; color: rgba(232,255,243,.7); font-size: .95rem; line-height: 1.6; position: relative; z-index: 1; }
        .auth-dashboard-images { width: 100%; margin: 0; padding-left: clamp(1rem, 2vw, 1.5rem); position: relative; z-index: 1; }
        .auth-dashboard-images img { display: block; height: auto; object-fit: contain; border: 0; border-radius: 14px; box-shadow: 0 18px 34px rgba(0,0,0,.28); filter: none; }
        .auth-dashboard-images .auth-dashboard-desktop { width: 78%; }
        .auth-dashboard-images .auth-dashboard-mobile { position: absolute; top: 18%; right: 12%; z-index: 2; width: 18%; border-radius: 16px; box-shadow: 0 20px 38px rgba(0,0,0,.38); }
        .auth-points { display: flex; flex-wrap: wrap; gap: .6rem; margin: 1.8rem 0 0; padding: 0; list-style: none; position: relative; z-index: 1; }
        .auth-points li { display: inline-flex; align-items: center; gap: .45rem; padding: .5rem .7rem; border: 1px solid rgba(182,242,216,.24); border-radius: 999px; color: rgba(232,255,243,.72); font-size: .75rem; background: rgba(4,62,45,.14); }
        .auth-points li:first-child { border-color: rgba(182,242,216,.5); color: #d8ffea; background: rgba(182,242,216,.13); }
        .auth-points i { color: #b6f2d8; }
        .auth-panel { grid-column: 1; grid-row: 1; width: 100% !important; max-width: none !important; min-width: 0; min-height: 100%; position: relative; align-self: stretch; justify-self: stretch; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: clamp(4.5rem, 9vh, 7rem) clamp(2rem, 4vw, 4rem); }
        .auth-panel-header { position: absolute; inset: 2rem clamp(4rem, 5vw, 5rem) auto; display: flex; align-items: center; justify-content: space-between; pointer-events: none; }
        .auth-panel-header .auth-brand { pointer-events: auto; }
        .auth-card, .auth-footer { width: 38%; max-width: 500px; }
        .auth-card form { width: 100%; }
        .auth-card.auth-card-register { width: min(100%, 680px); }
        .auth-panel:has(.auth-card-register) .auth-footer { width: min(100%, 680px); }
        .auth-card { padding: 0 !important; background: transparent !important; border: 0 !important; border-radius: 0 !important; box-shadow: none !important; }
        .auth-card h2 { color: var(--auth-text); letter-spacing: -.045em; font-size: clamp(1.8rem, 2.35vw, 2.5rem); line-height: 1.12; margin-bottom: .75rem !important; }
        .auth-card > p { color: var(--auth-muted) !important; line-height: 1.55; }
        .auth-card .form-label { margin-bottom: .55rem; color: var(--auth-text); font-size: .78rem; font-weight: 600; letter-spacing: .01em; }
        .auth-card .form-label i { color: var(--auth-green); opacity: .9; }
        .auth-card .form-control { min-height: 50px; background: var(--auth-bg) !important; border: 1px solid var(--auth-line) !important; border-radius: 8px !important; color: var(--auth-text) !important; box-shadow: inset 0 1px 0 rgba(255,255,255,.02); transition: border-color .18s ease, box-shadow .18s ease, background-color .18s ease; }
        .auth-card .form-control:hover { border-color: color-mix(in srgb, var(--auth-green) 38%, var(--auth-line)) !important; }
        .auth-card .form-control:focus { border-color: var(--auth-green) !important; box-shadow: 0 0 0 3px color-mix(in srgb, var(--auth-green) 16%, transparent) !important; }
        .auth-card .form-control::placeholder { color: var(--auth-muted) !important; opacity: .62; }
        .auth-card .btn-brand-primary { width: 100% !important; min-height: 50px; border-radius: 8px !important; justify-content: center; font-size: .9rem; font-weight: 650; letter-spacing: .01em; box-shadow: 0 8px 18px color-mix(in srgb, var(--auth-green) 12%, transparent); }
        .auth-register-form { display: grid; grid-template-columns: 1fr 1fr; column-gap: 1rem; }
        .auth-register-form .auth-field { margin-bottom: 1.1rem !important; }
        .auth-register-form .auth-field-confirm { margin-bottom: 1.5rem !important; }
        .auth-register-form .auth-submit, .auth-register-form .auth-terms { grid-column: 1 / -1; }
        .auth-register-form .auth-terms { margin: 0 0 1rem !important; color: var(--auth-muted) !important; font-size: .76rem; line-height: 1.55; }
        .auth-register-form .auth-terms a { font-weight: 600; }
        .auth-card .form-check { background: transparent !important; border: 0 !important; }
        .auth-card .form-check-input { background-color: var(--auth-bg); border-color: var(--auth-line); }
        .auth-card .form-check-input:checked { background-color: var(--auth-green); border-color: var(--auth-green); }
        .auth-card .form-check-label { color: var(--auth-muted) !important; }
        .auth-card a, .auth-footer a { color: var(--auth-green); font-weight: 600; text-underline-offset: 3px; }
        .auth-card a:hover, .auth-footer a:hover { color: color-mix(in srgb, var(--auth-green) 75%, white); }
        .auth-footer { color: var(--auth-muted); font-size: .84rem; }
        .auth-footer .back-link { color: var(--auth-muted); font-weight: 500; }
        .auth-footer .back-link:hover { color: var(--auth-text); }
        .auth-kicker { display: flex; align-items: center; gap: .5rem; margin-bottom: 1.25rem; color: var(--auth-green); font: 650 .68rem/1 'Geist Mono', monospace; letter-spacing: .12em; text-transform: uppercase; }
        .auth-kicker::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: var(--auth-green); box-shadow: 0 0 0 4px color-mix(in srgb, var(--auth-green) 12%, transparent); }
        .auth-card .alert { border-radius: 9px; line-height: 1.45; }
        .auth-card .alert-danger { color: #ffb4b4 !important; background: rgba(239, 68, 68, .1) !important; border-color: rgba(239, 68, 68, .28) !important; }
        .auth-card .alert-success { color: #a7e8c5 !important; background: rgba(34, 197, 94, .1) !important; border-color: rgba(34, 197, 94, .28) !important; }
        .theme-toggle--auth { position: absolute !important; top: auto !important; right: auto !important; left: clamp(4rem, 5vw, 5rem); bottom: 2.5rem; display: inline-flex !important; width: max-content !important; height: auto !important; max-width: max-content; flex: 0 0 auto; margin: 0; }
        html:not([data-theme="light"]) .theme-toggle--auth { border-color: rgba(154,167,180,.22); background: rgba(22,27,34,.88); color: #c9d4df; backdrop-filter: blur(12px); }
        html:not([data-theme="light"]) .theme-toggle--auth:hover, html:not([data-theme="light"]) .theme-toggle--auth:focus-visible { background: #1c232c; color: #f0f6fc; }
        html:not([data-theme="light"]) .auth-card .form-control { background: #0b100d !important; }
        html:not([data-theme="light"]) .auth-footer .back-link { color: #768393; }
        html[data-theme="light"] body:has(.auth-panel) { --auth-bg: #f1f6f3; --auth-surface: #fff; --auth-surface-2: #e7f0eb; --auth-text: #183126; --auth-muted: #5d7066; --auth-green: #147a56; --auth-line: rgba(24, 49, 38, .16); background: var(--auth-bg) !important; color: var(--auth-text); }
        html[data-theme="light"] .auth-layout { background: var(--auth-surface); border-color: rgba(24,49,38,.08); box-shadow: 0 18px 60px rgba(38,77,58,.08); }
        html[data-theme="light"] .auth-aside-inner { background-color: #2b9b72; }
        html[data-theme="light"] .auth-aside-inner::before { opacity: .11; mix-blend-mode: multiply; }
        html[data-theme="light"] .auth-aside-inner::after { background: linear-gradient(135deg, rgba(255,255,255,.16), transparent 45%, rgba(7,78,53,.1)); }
        html[data-theme="light"] .auth-aside h1 { color: #f8fffb; }
        html[data-theme="light"] .auth-aside h1 span, html[data-theme="light"] .auth-eyebrow { color: #d5ffea; }
        html[data-theme="light"] .auth-aside-copy { color: rgba(247,255,250,.78); }
        html[data-theme="light"] .auth-points li { border-color: rgba(213,255,234,.35); color: rgba(247,255,250,.8); background: rgba(7,78,53,.12); }
        html[data-theme="light"] .auth-points li:first-child { border-color: rgba(213,255,234,.62); background: rgba(213,255,234,.16); color: #f1fff7; }
        html[data-theme="light"] .theme-toggle--auth { box-shadow: 0 8px 24px rgba(38,77,58,.12); }
        html[data-theme="light"] .theme-toggle--auth { border-color: rgba(24,49,38,.14); background: rgba(255,255,255,.9); color: #365448; }
        html[data-theme="light"] .theme-toggle--auth:hover, html[data-theme="light"] .theme-toggle--auth:focus-visible { background: #fff; color: #147a56; }
        @media (max-width: 767.98px) {
            .auth-page { display: block; padding: 0; }
            .auth-layout { min-height: 100vh; display: block; border: 0; border-radius: 0; }
            .auth-aside { grid-column: auto; grid-row: auto; min-height: auto; padding: 1.5rem; }
            .auth-aside-shell { min-height: 560px; }
            .auth-aside-inner { width: 100%; height: auto; min-height: 560px; padding: 2rem 1.5rem 2.25rem; }
            .auth-eyebrow { margin-top: 0; padding-top: 0; }
            .auth-aside h1 { font-size: 2rem; }
            .auth-aside-copy { margin-top: .8rem; font-size: .9rem; }
            .auth-dashboard-images { width: 100%; margin: 0; padding-left: 1rem; }
            .auth-dashboard-images .auth-dashboard-desktop { width: 78%; }
            .auth-dashboard-images .auth-dashboard-mobile { top: 18%; right: 12%; width: 18%; }
            .auth-points { display: none; }
            .auth-panel { grid-column: auto; grid-row: auto; min-height: auto; padding: 5.5rem 1.5rem 2.25rem; }
            .auth-panel-header { inset: 1.5rem 2rem auto; }
            .theme-toggle--auth { left: 2rem; bottom: 1.5rem; }
            .auth-card, .auth-card form, .auth-footer, .auth-card.auth-card-register, .auth-panel:has(.auth-card-register) .auth-footer { width: 100%; max-width: none; }
            .auth-register-form { display: block; }
        }
    </style>
</head>
<body>
    <div class="auth-page">
        <div class="auth-layout">
            <aside class="auth-aside">
                <div class="auth-aside-shell">
                    <div class="auth-aside-inner">
                        <div class="auth-dashboard-images" aria-label="Painel de Controlo Nivora — versão web e mobile">
                            <img class="auth-dashboard-desktop" src="<?= base_url('assets/landing/dashboard-laptop.png') ?>" alt="Dashboard Nivora no computador" loading="lazy">
                            <img class="auth-dashboard-mobile" src="<?= base_url('assets/landing/dashboard-iphone-14-pro-max.png') ?>" alt="Dashboard Nivora no telemóvel" loading="lazy">
                        </div>
                        <div class="auth-aside-content">
                            <p class="auth-eyebrow">Finanças sob controlo</p>
                            <h1>Gestão financeira, <span>sem complicações.</span></h1>
                            <p class="auth-aside-copy">Regista contas, rendimentos e despesas num só lugar.</p>
                            <ul class="auth-points"><li><i class="bi bi-shield-check"></i> Privado por defeito</li><li><i class="bi bi-lightning-charge"></i> Simples e manual</li></ul>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="auth-panel">
                <div class="auth-panel-header">
                    <a href="<?= site_url('/') ?>" class="auth-brand"><img src="<?= base_url('/favicon.png') ?>" alt="Nivora"><span>NIVORA</span></a>
                </div>
                <?= $this->renderSection('main') ?>
                <button type="button" class="theme-toggle theme-toggle--auth" data-theme-toggle aria-label="Ativar modo claro" title="Ativar modo claro">
                    <i class="bi bi-sun-fill" data-theme-icon aria-hidden="true"></i>
                    <span data-theme-label>Modo claro</span>
                </button>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('pageScripts') ?>
</body>
</html>
