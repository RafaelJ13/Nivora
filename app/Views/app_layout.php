<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ? $this->renderSection('title') . ' — Nivora' : 'Nivora — Gestão Financeira Pessoal' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --nivora-dark: #080f14;
            --nivora-dark-card: #0e171e;
            --nivora-dark-card-border: rgba(255, 255, 255, 0.08);
            --nivora-teal: #10b981;
            --nivora-teal-dark: #0e4b49;
            --nivora-teal-glow: rgba(16, 185, 129, 0.2);
            --nivora-coral: #f43f5e;
            --nivora-peach: #fb923c;
            --nivora-amber: #f59e0b;
            --nivora-light: #f8fafc;
            --nivora-slate: #94a3b8;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'Space Grotesk', monospace;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            background-color: #070d11;
            color: var(--nivora-light);
            font-family: var(--font-main);
            display: flex;
            flex-direction: column;
        }

        /* Top Navigation Header */
        .app-header {
            background: rgba(14, 23, 30, 0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--nivora-dark-card-border);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .brand-logo {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1.25rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .brand-icon-box {
            width: 30px;
            height: 30px;
            background: linear-gradient(135deg, var(--nivora-teal) 0%, var(--nivora-teal-dark) 100%);
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 0.9rem;
            box-shadow: 0 0 12px var(--nivora-teal-glow);
        }

        .nav-pill-link {
            color: var(--nivora-slate);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.45rem 0.85rem;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.45rem;
        }

        .nav-pill-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-pill-link.active {
            color: var(--nivora-teal);
            background: rgba(16, 185, 129, 0.12);
            font-weight: 600;
        }

        .btn-brand-primary {
            background: linear-gradient(135deg, var(--nivora-teal) 0%, #059669 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.5rem 1.15rem;
            border-radius: 8px;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            box-shadow: 0 4px 14px var(--nivora-teal-glow);
            transition: all 0.2s ease;
        }

        .btn-brand-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.35);
            color: #ffffff;
        }

        .btn-brand-outline {
            background: rgba(255, 255, 255, 0.04);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-weight: 600;
            font-size: 0.88rem;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-brand-outline:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* App Cards */
        .app-card {
            background: var(--nivora-dark-card);
            border: 1px solid var(--nivora-dark-card-border);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
            transition: border-color 0.2s ease, transform 0.2s ease;
        }

        .app-card:hover {
            border-color: rgba(255, 255, 255, 0.15);
        }

        /* Forms */
        .form-control, .form-select {
            background-color: #060b0e !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 0.65rem 1rem;
            font-size: 0.92rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--nivora-teal) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18) !important;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--nivora-slate);
            margin-bottom: 0.4rem;
        }

        /* Tables */
        .custom-table {
            color: var(--nivora-light);
            vertical-align: middle;
            margin-bottom: 0;
        }

        .custom-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--nivora-slate);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding: 0.9rem 1rem;
            font-weight: 600;
            background: transparent;
        }

        .custom-table td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.04);
            padding: 1rem;
            font-size: 0.9rem;
            background: transparent;
            color: var(--nivora-light);
        }

        .custom-table tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* Badges */
        .badge-income {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
            font-weight: 600;
            border-radius: 6px;
            padding: 0.3rem 0.65rem;
            font-size: 0.75rem;
        }

        .badge-expense {
            background: rgba(244, 63, 94, 0.15);
            color: #fb7185;
            border: 1px solid rgba(244, 63, 94, 0.3);
            font-weight: 600;
            border-radius: 6px;
            padding: 0.3rem 0.65rem;
            font-size: 0.75rem;
        }

        .badge-account {
            background: rgba(255, 255, 255, 0.06);
            color: var(--nivora-slate);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            padding: 0.25rem 0.6rem;
            font-size: 0.78rem;
        }

        .footer-app {
            margin-top: auto;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 1.5rem 0;
            color: var(--nivora-slate);
            font-size: 0.82rem;
        }

        /* Open Design: tech-utility direction, adapted for Nivora. */
        :root {
            --nivora-dark: #09100f;
            --nivora-dark-card: #111a17;
            --nivora-dark-card-border: rgba(214, 232, 221, 0.12);
            --nivora-teal: #70e0b0;
            --nivora-teal-dark: #164d3d;
            --nivora-teal-glow: rgba(112, 224, 176, 0.16);
            --nivora-coral: #ff806f;
            --nivora-peach: #f3b562;
            --nivora-amber: #f3b562;
            --nivora-light: #edf5ef;
            --nivora-slate: #9caf9f;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'Space Grotesk', monospace;
        }

        body {
            background-color: var(--nivora-dark);
            background-image: linear-gradient(rgba(112, 224, 176, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(112, 224, 176, 0.025) 1px, transparent 1px);
            background-size: 32px 32px;
            letter-spacing: 0;
        }

        .app-header {
            background: rgba(9, 16, 15, 0.92);
            border-bottom-color: rgba(214, 232, 221, 0.14);
        }

        .brand-icon-box {
            background: var(--nivora-teal);
            color: #092019;
            border-radius: 5px;
            box-shadow: none;
        }

        .nav-pill-link {
            border-radius: 5px;
            color: #9caf9f;
        }

        .nav-pill-link:hover {
            background: rgba(112, 224, 176, 0.08);
        }

        .nav-pill-link.active {
            color: var(--nivora-teal);
            background: rgba(112, 224, 176, 0.12);
        }

        .btn-brand-primary {
            background: var(--nivora-teal);
            color: #092019;
            border-radius: 5px;
            box-shadow: none;
        }

        .btn-brand-primary:hover {
            background: #a0edc9;
            color: #092019;
            box-shadow: 0 6px 18px rgba(112, 224, 176, 0.16);
        }

        .btn-brand-outline {
            border-radius: 5px;
            border-color: rgba(214, 232, 221, 0.18);
        }

        .app-card {
            border-radius: 8px;
            border-color: rgba(214, 232, 221, 0.12);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        }

        .app-card:hover {
            border-color: rgba(112, 224, 176, 0.34);
        }

        .form-control, .form-select {
            background-color: #0b1311 !important;
            border-radius: 5px;
            border-color: rgba(214, 232, 221, 0.16) !important;
        }

        .custom-table th {
            color: #82988a;
            letter-spacing: 0.08em;
        }

        .custom-table td {
            border-bottom-color: rgba(214, 232, 221, 0.07);
        }

        .footer-app {
            border-top-color: rgba(214, 232, 221, 0.1);
        }

        @media (max-width: 767.98px) {
            main.container {
                padding-top: 1.25rem !important;
            }

            .app-card {
                padding: 1.15rem;
            }
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/nivora.css') ?>">
</head>
<body>

    <?php
        $uri = service('uri');
        $currentSegment = $uri->getSegment(1) ?? 'dashboard';
        $isLoggedIn = function_exists('auth') && auth()->loggedIn();
        $user = $isLoggedIn ? auth()->user() : null;
        $displayName = $user ? ($user->name ?: $user->username) : 'Rafael Januário';
        $userInitial = strtoupper(substr($displayName, 0, 1));
    ?>

    <!-- App Navigation Header -->
    <header class="app-header py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-4">
                <a href="<?= site_url('/') ?>" class="brand-logo">
                    <span class="brand-icon-box"><i class="bi bi-wallet2"></i></span>
                    <span>NIVORA</span>
                </a>

                <nav class="d-none d-md-flex align-items-center gap-1">
                    <a href="<?= site_url('dashboard') ?>" class="nav-pill-link <?= $currentSegment === 'dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="<?= site_url('accounts') ?>" class="nav-pill-link <?= $currentSegment === 'accounts' ? 'active' : '' ?>">
                        <i class="bi bi-bank"></i> Contas
                    </a>
                    <a href="<?= site_url('categories') ?>" class="nav-pill-link <?= $currentSegment === 'categories' ? 'active' : '' ?>">
                        <i class="bi bi-tags"></i> Categorias
                    </a>
                    <a href="<?= site_url('transactions') ?>" class="nav-pill-link <?= $currentSegment === 'transactions' ? 'active' : '' ?>">
                        <i class="bi bi-arrow-left-right"></i> Transações
                    </a>
                </nav>
            </div>

            <div class="d-flex align-items-center gap-3">
                <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary d-none d-sm-inline-flex">
                    <i class="bi bi-plus-lg"></i> Nova Transação
                </a>

                <!-- User dropdown -->
                <div class="dropdown">
                    <button class="btn btn-dark border border-secondary border-opacity-25 d-flex align-items-center gap-2 py-1 px-2 rounded-3 text-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="badge bg-success rounded-circle p-2 fw-bold" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem;">
                            <?= esc($userInitial) ?>
                        </span>
                        <span class="small fw-semibold d-none d-sm-inline"><?= esc($displayName) ?></span>
                        <i class="bi bi-chevron-down small text-secondary"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark border-secondary border-opacity-25 shadow py-2" style="min-width: 200px; font-size: 0.88rem;">
                        <li class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                            <div class="fw-bold text-white"><?= esc($displayName) ?></div>
                            <div class="small text-secondary"><?= $user ? esc($user->email ?? 'utilizador@nivora.pt') : 'rafael@nivora.pt' ?></div>
                        </li>
                        <li><a class="dropdown-item py-2" href="<?= site_url('dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a></li>
                        <li><a class="dropdown-item py-2" href="<?= site_url('accounts') ?>"><i class="bi bi-bank me-2"></i> As Minhas Contas</a></li>
                        <li><a class="dropdown-item py-2" href="<?= site_url('transactions') ?>"><i class="bi bi-receipt me-2"></i> Histórico de Transações</a></li>
                        <li><hr class="dropdown-divider border-secondary border-opacity-25"></li>
                        <?php if ($isLoggedIn) : ?>
                            <li><a class="dropdown-item py-2 text-danger" href="<?= url_to('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Terminar Sessão</a></li>
                        <?php else : ?>
                            <li><a class="dropdown-item py-2" href="<?= url_to('login') ?>"><i class="bi bi-box-arrow-in-right me-2"></i> Iniciar Sessão</a></li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Mobile Menu Button -->
                <button class="btn btn-dark border border-secondary border-opacity-25 d-md-none p-2 rounded-3 text-light" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileOffcanvas" aria-controls="mobileOffcanvas">
                    <i class="bi bi-list fs-5"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Offcanvas Menu -->
    <div class="offcanvas offcanvas-start bg-dark text-light border-secondary border-opacity-25" tabindex="-1" id="mobileOffcanvas" style="background-color: #0d171d !important;">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <a href="<?= site_url('/') ?>" class="brand-logo">
                <span class="brand-icon-box"><i class="bi bi-wallet2"></i></span>
                <span>NIVORA</span>
            </a>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column gap-2">
            <a href="<?= site_url('dashboard') ?>" class="nav-pill-link <?= $currentSegment === 'dashboard' ? 'active' : '' ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="<?= site_url('accounts') ?>" class="nav-pill-link <?= $currentSegment === 'accounts' ? 'active' : '' ?>">
                <i class="bi bi-bank"></i> Contas
            </a>
            <a href="<?= site_url('categories') ?>" class="nav-pill-link <?= $currentSegment === 'categories' ? 'active' : '' ?>">
                <i class="bi bi-tags"></i> Categorias
            </a>
            <a href="<?= site_url('transactions') ?>" class="nav-pill-link <?= $currentSegment === 'transactions' ? 'active' : '' ?>">
                <i class="bi bi-arrow-left-right"></i> Transações
            </a>

            <div class="mt-4 pt-3 border-top border-secondary border-opacity-25">
                <a href="<?= site_url('transactions/new') ?>" class="btn-brand-primary w-100 justify-content-center mb-2">
                    <i class="bi bi-plus-lg"></i> Nova Transação
                </a>
                <?php if ($isLoggedIn) : ?>
                    <a href="<?= url_to('logout') ?>" class="btn-brand-outline w-100 justify-content-center text-danger border-danger border-opacity-50">
                        <i class="bi bi-box-arrow-right"></i> Terminar Sessão
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content Container -->
    <main class="container py-4 flex-grow-1">
        <!-- Flash Messages Container -->
        <?php if (session('message')) : ?>
            <div class="alert alert-success alert-dismissible fade show bg-success bg-opacity-20 border-success border-opacity-50 text-white mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <div><?= esc(session('message')) ?></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (session('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-20 border-danger border-opacity-50 text-white mb-4 d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i>
                <div><?= esc(session('error')) ?></div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?php if (session('errors')) : ?>
            <div class="alert alert-danger alert-dismissible fade show bg-danger bg-opacity-20 border-danger border-opacity-50 text-white mb-4" role="alert">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    <strong>Por favor, verifica os erros abaixo:</strong>
                </div>
                <ul class="mb-0 small ps-4">
                    <?php foreach ((array) session('errors') as $err) : ?>
                        <li><?= esc($err) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('main') ?>
    </main>

    <!-- App Footer -->
    <footer class="footer-app">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
            <div>
                <span class="text-white fw-bold">Nivora</span> &mdash; Gestão Financeira Pessoal (MVP V1.0)
            </div>
            <div>
                Licença Open Source MIT &bull; Precisão ao Cêntimo
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5.3 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
