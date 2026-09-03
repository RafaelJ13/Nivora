<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nivora — Gestão de Finanças Pessoais</title>
    <meta name="description" content="Regista contas, rendimentos e despesas.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --bg-body: #080c0f;
            --bg-surface: #0e1419;
            --bg-surface-elevated: #131b22;
            --border-subtle: rgba(255, 255, 255, 0.08);
            --border-hover: rgba(255, 255, 255, 0.18);
            --emerald: #10b981;
            --emerald-soft: rgba(16, 185, 129, 0.12);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --text-tertiary: #64748b;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background-color: var(--bg-body);
            color: var(--text-primary);
            font-family: var(--font-sans);
            overflow-x: hidden;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* Fundo da página. */
        .ambient-radial {
            position: absolute;
            top: -200px;
            right: 15%;
            width: 700px;
            height: 700px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.08) 0%, transparent 65%);
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        /* Navigation */
        .site-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(8, 12, 15, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border-subtle);
        }

        .brand-text {
            font-weight: 800;
            font-size: 1.25rem;
            letter-spacing: -0.03em;
            color: #ffffff;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-dot {
            width: 8px;
            height: 8px;
            background: var(--emerald);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--emerald);
        }

        .nav-link-item {
            color: var(--text-secondary);
            font-size: 0.9rem;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.15s ease;
            padding: 0.4rem 0.8rem;
        }

        .nav-link-item:hover { color: #ffffff; }

        .btn-action-primary {
            background: #ffffff;
            color: #080c0f;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.9);
        }

        .btn-action-primary:hover {
            background: #e2e8f0;
            color: #080c0f;
            transform: translateY(-1px);
        }

        .btn-action-secondary {
            background: rgba(255, 255, 255, 0.04);
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid var(--border-subtle);
            transition: all 0.2s ease;
        }

        .btn-action-secondary:hover {
            background: rgba(255, 255, 255, 0.08);
            border-color: var(--border-hover);
            color: #ffffff;
        }

        /* Hero */
        .hero {
            position: relative;
            padding: 4.5rem 0 5rem;
            min-height: 85vh;
            display: flex;
            align-items: center;
            z-index: 1;
        }

        .hero-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            background: var(--emerald-soft);
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 9999px;
            color: var(--emerald);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .hero-headline {
            font-size: clamp(2.75rem, 5.2vw, 4.5rem);
            font-weight: 800;
            line-height: 1.06;
            letter-spacing: -0.035em;
            margin-bottom: 1.25rem;
        }

        .hero-subhead {
            font-size: 1.15rem;
            color: var(--text-secondary);
            max-width: 520px;
            line-height: 1.65;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        /* 3D Canvas Showcase */
        .card-stage {
            position: relative;
            width: 100%;
            height: 480px;
            border-radius: 20px;
            background: radial-gradient(circle at 50% 50%, rgba(20, 28, 35, 0.6) 0%, rgba(8, 12, 15, 0.95) 85%);
            border: 1px solid var(--border-subtle);
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #orbital-canvas {
            width: 100%;
            height: 100%;
            display: block;
            cursor: grab;
        }

        #orbital-canvas:active { cursor: crosshair; }

        .stage-indicator {
            position: absolute;
            bottom: 14px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.75rem;
            color: var(--text-tertiary);
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
            padding: 4px 12px;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            pointer-events: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Cartões de funcionalidades. */
        .feature-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 14px;
            padding: 1.75rem;
            height: 100%;
            transition: border-color 0.2s ease, transform 0.2s ease;
        }

        .feature-card:hover {
            border-color: var(--border-hover);
            transform: translateY(-2px);
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border-subtle);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: #ffffff;
            margin-bottom: 1.25rem;
        }

        /* Interactive Interface Showcase */
        .preview-wrapper {
            background: var(--bg-surface);
            border: 1px solid var(--border-subtle);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
        }

        .preview-header {
            background: rgba(255, 255, 255, 0.02);
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tab-btn {
            background: transparent;
            border: none;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 500;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .tab-btn.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
            font-weight: 600;
        }

        .preview-body {
            padding: 2rem;
            min-height: 380px;
        }

        /* Tabela de movimentos. */
        .preview-table {
            width: 100%;
            color: var(--text-primary);
            border-collapse: collapse;
        }

        .preview-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-tertiary);
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--border-subtle);
            text-align: left;
        }

        .preview-table td {
            padding: 0.9rem 1rem;
            font-size: 0.88rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        }

        /* Pill Badges */
        .pill-tag {
            font-size: 0.75rem;
            padding: 3px 8px;
            border-radius: 6px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: var(--text-secondary);
        }

        /* Technical Specification Box */
        .spec-box {
            background: #05080a;
            border: 1px solid var(--border-subtle);
            border-radius: 12px;
            padding: 1.5rem;
            font-family: var(--font-mono);
            font-size: 0.85rem;
        }

        /* Minimal FAQ */
        .faq-item {
            border-bottom: 1px solid var(--border-subtle);
            padding: 1.5rem 0;
        }

        .faq-item:first-child { border-top: 1px solid var(--border-subtle); }

        .faq-q {
            font-size: 1.05rem;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .faq-a {
            color: var(--text-secondary);
            font-size: 0.92rem;
            margin-bottom: 0;
            line-height: 1.6;
        }

        /* Site Footer */
        .site-footer {
            border-top: 1px solid var(--border-subtle);
            padding: 3rem 0;
            color: var(--text-tertiary);
            font-size: 0.88rem;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/nivora.css') ?>">
</head>
<body class="landing-page">

    <div class="ambient-radial"></div>

    <!-- Navigation -->
    <nav class="site-nav py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?= site_url('/') ?>" class="brand-text">
                <span class="brand-dot"></span>
                <span>Nivora</span>
            </a>

            <div class="d-none d-md-flex align-items-center gap-1">
                <a href="#produto" class="nav-link-item">Produto</a>
                <a href="#precisao" class="nav-link-item">Valores</a>
                <a href="#faq" class="nav-link-item">Perguntas</a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php if (function_exists('auth') && auth()->loggedIn()) : ?>
                    <?php $user = auth()->user(); ?>
                    <a href="<?= site_url('dashboard') ?>" class="btn-action-primary">
                        Abrir Dashboard <i class="bi bi-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <a href="<?= url_to('login') ?>" class="nav-link-item">Entrar</a>
                    <a href="<?= url_to('register') ?>" class="btn-action-primary">Criar conta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero" data-od-id="landing-hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="hero-tag">
                        <i class="bi bi-wallet2"></i> Finanças pessoais
                    </div>
                    <h1 class="hero-headline">
                        O teu dinheiro, <br><span style="color: #ffffff;">num só lugar.</span>
                    </h1>
                    <p class="hero-subhead">
                        Regista contas bancárias, despesas e rendimentos. Os valores monetários ficam guardados em cêntimos inteiros.
                    </p>

                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                        <?php if (function_exists('auth') && auth()->loggedIn()) : ?>
                            <a href="<?= site_url('dashboard') ?>" class="btn-action-primary px-4 py-3">
                                Ir para o Painel <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php else : ?>
                            <a href="<?= url_to('register') ?>" class="btn-action-primary px-4 py-3">
                                Começar agora <i class="bi bi-arrow-right"></i>
                            </a>
                            <a href="#produto" class="btn-action-secondary px-4 py-3">
                                Ver interface
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-4 text-secondary small pt-3 border-top border-secondary border-opacity-10">
                        <span><i class="bi bi-check2 text-success me-1"></i> Sem floats</span>
                        <span><i class="bi bi-check2 text-success me-1"></i> Dados 100% teus</span>
                        <span><i class="bi bi-check2 text-success me-1"></i> Código Aberto MIT</span>
                    </div>
                </div>

                <!-- 3D financial flow -->
                <div class="col-lg-6">
                    <canvas id="orbital-canvas" aria-label="Visualização 3D do fluxo financeiro"></canvas>
                </div>
            </div>
        </div>
    </header>

    <!-- Exemplo do painel. -->
    <section id="produto" class="py-5" data-od-id="product-preview">
        <div class="container py-4">
            <div class="mb-4">
                <p class="text-secondary small fw-bold text-uppercase mb-1" style="letter-spacing: 0.08em;">Exemplo do painel</p>
                <h2 class="h3 fw-bold text-white mb-0">Contas e movimentos numa só vista</h2>
            </div>

            <div class="preview-wrapper">
                <div class="preview-header">
                    <div class="d-flex align-items-center gap-2">
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                        <span style="width: 10px; height: 10px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                        <span class="text-secondary small ms-2 d-none d-sm-inline">nivora.app &bull; painel financeiro</span>
                    </div>

                    <div class="d-flex gap-1" id="preview-tabs">
                        <button class="tab-btn active" onclick="switchTab('tab-overview', this)">Visão Geral</button>
                        <button class="tab-btn" onclick="switchTab('tab-ledger', this)">Transações</button>
                        <button class="tab-btn" onclick="switchTab('tab-accounts', this)">Contas</button>
                    </div>
                </div>

                <div class="preview-body">
                    <!-- Tab 1: Overview -->
                    <div id="tab-overview" class="tab-content-panel">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="text-secondary small">Saldo Total Consolidado</div>
                                    <div class="h3 fw-bold text-white mt-1 mb-0" style="font-family: var(--font-mono);">€ 2.650,50</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="text-secondary small">Rendimentos do Mês</div>
                                    <div class="h3 fw-bold text-success mt-1 mb-0" style="font-family: var(--font-mono);">+ € 2.800,00</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="text-secondary small">Despesas do Mês</div>
                                    <div class="h3 fw-bold text-danger mt-1 mb-0" style="font-family: var(--font-mono);">- € 459,50</div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="preview-table">
                                <thead>
                                    <tr>
                                        <th>Descrição</th>
                                        <th>Conta</th>
                                        <th>Categoria</th>
                                        <th class="text-end">Montante</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-semibold">Salário Líquido Mensal</td>
                                        <td><span class="pill-tag">Millennium BCP</span></td>
                                        <td><span class="text-secondary">Salário</span></td>
                                        <td class="text-end fw-bold text-success" style="font-family: var(--font-mono);">+ € 2.800,00</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Renda Habitação</td>
                                        <td><span class="pill-tag">Millennium BCP</span></td>
                                        <td><span class="text-secondary">Habitação</span></td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 650,00</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Supermercado Continente</td>
                                        <td><span class="pill-tag">Revolut</span></td>
                                        <td><span class="text-secondary">Alimentação</span></td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 85,40</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold">Combustível Repsol</td>
                                        <td><span class="pill-tag">Millennium BCP</span></td>
                                        <td><span class="text-secondary">Transporte</span></td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 50,00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 2: Ledger -->
                    <div id="tab-ledger" class="tab-content-panel d-none">
                        <div class="table-responsive">
                            <table class="preview-table">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Registo</th>
                                        <th>Conta</th>
                                        <th>Categoria</th>
                                        <th class="text-end">Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-secondary small">01/09/2026</td>
                                        <td class="fw-semibold">Salário Líquido Mensal</td>
                                        <td><span class="pill-tag">Millennium BCP</span></td>
                                        <td>Salário</td>
                                        <td class="text-end fw-bold text-success" style="font-family: var(--font-mono);">+ € 2.800,00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary small">02/09/2026</td>
                                        <td class="fw-semibold">Supermercado Continente</td>
                                        <td><span class="pill-tag">Revolut</span></td>
                                        <td>Alimentação</td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 45,00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary small">02/09/2026</td>
                                        <td class="fw-semibold">Restaurante Jantar</td>
                                        <td><span class="pill-tag">Revolut</span></td>
                                        <td>Lazer</td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 20,00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary small">03/09/2026</td>
                                        <td class="fw-semibold">Combustível Repsol</td>
                                        <td><span class="pill-tag">Millennium BCP</span></td>
                                        <td>Transporte</td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 50,00</td>
                                    </tr>
                                    <tr>
                                        <td class="text-secondary small">03/09/2026</td>
                                        <td class="fw-semibold">Café e Padaria</td>
                                        <td><span class="pill-tag">Dinheiro</span></td>
                                        <td>Alimentação</td>
                                        <td class="text-end fw-bold text-danger" style="font-family: var(--font-mono);">- € 4,50</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Tab 3: Accounts -->
                    <div id="tab-accounts" class="tab-content-panel d-none">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary small text-uppercase">Banco</span>
                                        <span class="badge bg-secondary bg-opacity-25 text-white small">Principal</span>
                                    </div>
                                    <div class="fw-bold text-white fs-5">Millennium BCP</div>
                                    <div class="h4 fw-bold text-white mt-2 mb-0" style="font-family: var(--font-mono);">€ 2.000,00</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary small text-uppercase">Digital</span>
                                        <span class="badge bg-secondary bg-opacity-25 text-white small">Cartão</span>
                                    </div>
                                    <div class="fw-bold text-white fs-5">Revolut</div>
                                    <div class="h4 fw-bold text-white mt-2 mb-0" style="font-family: var(--font-mono);">€ 500,00</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 rounded-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-subtle);">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary small text-uppercase">Dinheiro</span>
                                        <span class="badge bg-secondary bg-opacity-25 text-white small">Físico</span>
                                    </div>
                                    <div class="fw-bold text-white fs-5">Dinheiro em Carteira</div>
                                    <div class="h4 fw-bold text-white mt-2 mb-0" style="font-family: var(--font-mono);">€ 150,50</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Valores monetários. -->
    <section id="precisao" class="py-5" style="border-top: 1px solid var(--border-subtle);" data-od-id="money-precision">
        <div class="container py-4">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <p class="text-secondary small fw-bold text-uppercase mb-1" style="letter-spacing: 0.08em;">Valores</p>
                    <h2 class="h3 fw-bold text-white mb-3">Armazenamento em cêntimos inteiros</h2>
                    <p class="text-secondary mb-3">
                        Números em vírgula flutuante (como <code>19.99</code>) sofrem de imprecisão de arredondamento binário nativo da CPU. No Nivora, cada valor monetário é armazenado como unidade mínima inteira (<code>BIGINT</code>).
                    </p>
                    <p class="text-secondary mb-0">
                        Por exemplo, <code>1999 + 500 = 2499</code>. A aplicação guarda estes valores como inteiros.
                    </p>
                </div>

                <div class="col-lg-6">
                    <div class="spec-box">
                        <div class="text-secondary mb-2">// Como os dados são persistidos:</div>
                        <div class="text-white">€ 19,99 &rarr; <code>1999 cêntimos</code></div>
                        <div class="text-white">€ 5,00  &rarr; <code>500 cêntimos</code></div>
                        <div class="text-white mb-3">€ 0,50  &rarr; <code>50 cêntimos</code></div>
                        <div class="text-secondary mb-1">// Schema MySQL limpo:</div>
                        <div class="text-success">amount BIGINT NOT NULL DEFAULT 0</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Funcionalidades. -->
    <section class="py-5" style="border-top: 1px solid var(--border-subtle);" data-od-id="product-capabilities">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-wallet2"></i></div>
                        <h3 class="h6 fw-bold text-white mb-2">Contas</h3>
                        <p class="text-secondary small mb-0">
                            Regista o teu banco de dia a dia, contas digitais e dinheiro em notas. Cada conta tem o seu saldo e histórico dedicados.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-tags"></i></div>
                        <h3 class="h6 fw-bold text-white mb-2">Categorias</h3>
                        <p class="text-secondary small mb-0">
                            Agrupa despesas e rendimentos por categorias como Alimentação, Renda, Transportes, Lazer ou Salário.
                        </p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="feature-card">
                        <div class="feature-icon"><i class="bi bi-lock"></i></div>
                        <h3 class="h6 fw-bold text-white mb-2">Dados separados</h3>
                        <p class="text-secondary small mb-0">
                            Cada utilizador consulta apenas os seus próprios registos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-5" style="border-top: 1px solid var(--border-subtle);" data-od-id="faq">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="h4 fw-bold text-white mb-4">Perguntas Frequentes</h2>

                    <div class="faq-item">
                        <div class="faq-q">Como é calculado o saldo final?</div>
                        <p class="faq-a">
                            O saldo segue esta regra: <code>Saldo = Saldo Inicial + Rendimentos - Despesas</code>. O valor apresentado soma as tuas contas.
                        </p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-q">Preciso de partilhar credenciais bancárias?</div>
                        <p class="faq-a">
                            Não. O Nivora não faz ligações diretas a APIs de bancos para aceder à tua conta bancária. Todos os registos são mantidos de forma autónoma e sob o teu controlo direto.
                        </p>
                    </div>

                    <div class="faq-item">
                        <div class="faq-q">Qual a licença de utilização?</div>
                        <p class="faq-a">
                            O Nivora é distribuído sob a Licença MIT. É software de código aberto, livre para utilização pessoal ou adaptação técnica.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Footer -->
    <footer class="site-footer">
        <div class="container d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <span class="brand-dot"></span>
                <span class="text-white fw-bold">Nivora</span>
                <span>&bull; Licença MIT</span>
            </div>
            <div class="d-flex gap-4">
                <a href="<?= site_url('dashboard') ?>" class="text-secondary text-decoration-none small">Dashboard</a>
                <a href="<?= url_to('login') ?>" class="text-secondary text-decoration-none small">Entrar</a>
                <a href="<?= url_to('register') ?>" class="text-secondary text-decoration-none small">Registo</a>
            </div>
        </div>
    </footer>

    <!-- Three.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

    <!-- Tab Switching Logic -->
    <script>
        function switchTab(tabId, btn) {
            document.querySelectorAll('.tab-content-panel').forEach(p => p.classList.add('d-none'));
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            const panel = document.getElementById(tabId);
            if (panel) panel.classList.remove('d-none');
            if (btn) btn.classList.add('active');
        }
    </script>

    <!-- Elemento visual da página. -->
    <script>
        (function initTitaniumCard() {
            const canvas = document.getElementById('orbital-canvas');
            if (!canvas) return;
            return;

            const container = canvas.parentElement;
            let width = container.clientWidth;
            let height = container.clientHeight;

            // Scene, Camera, Renderer
            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(40, width / height, 0.1, 100);
            camera.position.set(0, 0, 6.8);

            const renderer = new THREE.WebGLRenderer({
                canvas: canvas,
                alpha: true,
                antialias: true,
                powerPreference: "high-performance"
            });
            renderer.setSize(width, height);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            // Lighting Setup - Sleek Studio Key & Rim Lights
            const ambientLight = new THREE.AmbientLight(0xffffff, 0.65);
            scene.add(ambientLight);

            // Dynamic Point/Spotlight that tracks cursor
            const keySpotlight = new THREE.SpotLight(0xffffff, 3.2, 20, Math.PI / 3.5, 0.4, 1.2);
            keySpotlight.position.set(0, 2, 5);
            scene.add(keySpotlight);

            // Emerald Rim Light (Accentuating the card edges)
            const emeraldRim = new THREE.DirectionalLight(0x10b981, 2.0);
            emeraldRim.position.set(5, -2, -2);
            scene.add(emeraldRim);

            // Subtle Cool Fill Light
            const coolFill = new THREE.DirectionalLight(0x60a5fa, 0.8);
            coolFill.position.set(-5, 4, -1);
            scene.add(coolFill);

            // Card Object Group
            const cardGroup = new THREE.Group();
            scene.add(cardGroup);

            // True credit card ratio: 85.6mm x 53.98mm = ~1.586 ratio
            const cardW = 3.8;
            const cardH = 2.4;
            const cardD = 0.04;

            // Geometry: beveled box
            const cardGeometry = new THREE.BoxGeometry(cardW, cardH, cardD, 32, 32, 2);

            // High-Resolution Procedural Texture for Card Front
            const frontCanvas = document.createElement('canvas');
            frontCanvas.width = 2048;
            frontCanvas.height = 1290;
            const ctx = frontCanvas.getContext('2d');

            // 1. Base Dark Slate/Obsidian Gradient
            const bgGrad = ctx.createLinearGradient(0, 0, 2048, 1290);
            bgGrad.addColorStop(0, '#10171d');
            bgGrad.addColorStop(0.5, '#0b1014');
            bgGrad.addColorStop(1, '#06090c');
            ctx.fillStyle = bgGrad;
            ctx.fillRect(0, 0, 2048, 1290);

            // 2. Subtle Brushed Metal Texture Lines
            ctx.fillStyle = 'rgba(255, 255, 255, 0.015)';
            for (let i = 0; i < 1290; i += 3) {
                if (Math.random() > 0.4) {
                    ctx.fillRect(0, i, 2048, 1.5);
                }
            }

            // 3. Ultra-fine geometric accent watermark
            ctx.strokeStyle = 'rgba(16, 185, 129, 0.1)';
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.arc(1700, 300, 450, 0, Math.PI * 2);
            ctx.stroke();
            ctx.beginPath();
            ctx.arc(1700, 300, 520, 0, Math.PI * 2);
            ctx.stroke();

            // 4. EMV Gold Contact Microchip
            const chipX = 220;
            const chipY = 480;
            const chipW = 260;
            const chipH = 190;
            const chipRadius = 24;

            // Chip Gold Metallic Body
            const chipGrad = ctx.createLinearGradient(chipX, chipY, chipX + chipW, chipY + chipH);
            chipGrad.addColorStop(0, '#d97706');
            chipGrad.addColorStop(0.3, '#fde68a');
            chipGrad.addColorStop(0.6, '#b45309');
            chipGrad.addColorStop(1, '#fef3c7');
            ctx.fillStyle = chipGrad;
            ctx.beginPath();
            ctx.roundRect(chipX, chipY, chipW, chipH, chipRadius);
            ctx.fill();

            // Chip Contact Wire Lines
            ctx.strokeStyle = '#78350f';
            ctx.lineWidth = 4;
            ctx.beginPath();
            ctx.moveTo(chipX, chipY + chipH * 0.45);
            ctx.lineTo(chipX + chipW, chipY + chipH * 0.45);
            ctx.moveTo(chipX + chipW * 0.4, chipY);
            ctx.lineTo(chipX + chipW * 0.4, chipY + chipH);
            ctx.moveTo(chipX + chipW * 0.65, chipY);
            ctx.lineTo(chipX + chipW * 0.65, chipY + chipH);
            ctx.stroke();

            // Contactless Payment Icon
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.5)';
            ctx.lineWidth = 5;
            const cx = 560;
            const cy = 575;
            for (let r = 18; r <= 42; r += 12) {
                ctx.beginPath();
                ctx.arc(cx, cy, r, -Math.PI * 0.35, Math.PI * 0.35);
                ctx.stroke();
            }

            // 5. Brand Identity: NIVORA
            ctx.fillStyle = '#ffffff';
            ctx.font = '800 68px "Plus Jakarta Sans", sans-serif';
            ctx.letterSpacing = '-2px';
            ctx.fillText('NIVORA', 220, 240);

            // Subtle Green Dot on logo
            ctx.fillStyle = '#10b981';
            ctx.beginPath();
            ctx.arc(525, 220, 10, 0, Math.PI * 2);
            ctx.fill();

            // 6. Embossed Cardholder & Details
            ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.font = '500 28px "JetBrains Mono", monospace';
            ctx.fillText('TITULAR', 220, 960);

            ctx.fillStyle = '#ffffff';
            ctx.font = '700 46px "Plus Jakarta Sans", sans-serif';
            ctx.fillText('RAFAEL JANUÁRIO', 220, 1025);

            ctx.fillStyle = 'rgba(255, 255, 255, 0.4)';
            ctx.font = '500 28px "JetBrains Mono", monospace';
            ctx.fillText('VALIDADE', 1400, 960);

            ctx.fillStyle = '#ffffff';
            ctx.font = '700 46px "JetBrains Mono", monospace';
            ctx.fillText('12/29', 1400, 1025);

            // Subtle Metallic Foil Specular Sheen on Logo
            ctx.font = '800 68px "Plus Jakarta Sans", sans-serif';
            const foilGrad = ctx.createLinearGradient(200, 0, 600, 0);
            foilGrad.addColorStop(0, 'rgba(255, 255, 255, 0.8)');
            foilGrad.addColorStop(0.5, 'rgba(16, 185, 129, 0.9)');
            foilGrad.addColorStop(1, 'rgba(255, 255, 255, 0.7)');
            ctx.fillStyle = foilGrad;
            ctx.fillText('NIVORA', 220, 240);

            const frontTexture = new THREE.CanvasTexture(frontCanvas);
            frontTexture.anisotropy = renderer.capabilities.getMaxAnisotropy();

            // Texture for Back of the Card
            const backCanvas = document.createElement('canvas');
            backCanvas.width = 2048;
            backCanvas.height = 1290;
            const bctx = backCanvas.getContext('2d');
            bctx.fillStyle = '#0a0e12';
            bctx.fillRect(0, 0, 2048, 1290);

            // Magnetic Stripe
            bctx.fillStyle = '#050709';
            bctx.fillRect(0, 160, 2048, 220);

            // Signature & CVV Panel
            bctx.fillStyle = '#1a2228';
            bctx.fillRect(200, 520, 1100, 140);
            bctx.fillStyle = '#ffffff';
            bctx.font = 'bold 36px "JetBrains Mono", monospace';
            bctx.fillText('941', 1350, 610);

            const backTexture = new THREE.CanvasTexture(backCanvas);

            // Materials
            const frontMaterial = new THREE.MeshStandardMaterial({
                map: frontTexture,
                metalness: 0.7,
                roughness: 0.28
            });

            const edgeMaterial = new THREE.MeshStandardMaterial({
                color: 0x18222a,
                metalness: 0.85,
                roughness: 0.2
            });

            const backMaterial = new THREE.MeshStandardMaterial({
                map: backTexture,
                metalness: 0.6,
                roughness: 0.35
            });

            const cardMaterials = [
                edgeMaterial,  // right
                edgeMaterial,  // left
                edgeMaterial,  // top
                edgeMaterial,  // bottom
                frontMaterial, // front
                backMaterial   // back
            ];

            const cardMesh = new THREE.Mesh(cardGeometry, cardMaterials);
            cardGroup.add(cardMesh);

            // Soft Ground Shadow Simulation under card
            const shadowCanvas = document.createElement('canvas');
            shadowCanvas.width = 512;
            shadowCanvas.height = 512;
            const sctx = shadowCanvas.getContext('2d');
            const shadowGrad = sctx.createRadialGradient(256, 256, 10, 256, 256, 220);
            shadowGrad.addColorStop(0, 'rgba(0, 0, 0, 0.7)');
            shadowGrad.addColorStop(0.5, 'rgba(0, 0, 0, 0.25)');
            shadowGrad.addColorStop(1, 'transparent');
            sctx.fillStyle = shadowGrad;
            sctx.fillRect(0, 0, 512, 512);

            const shadowTexture = new THREE.CanvasTexture(shadowCanvas);
            const shadowGeo = new THREE.PlaneGeometry(5, 5);
            const shadowMat = new THREE.MeshBasicMaterial({
                map: shadowTexture,
                transparent: true,
                depthWrite: false
            });
            const shadowMesh = new THREE.Mesh(shadowGeo, shadowMat);
            shadowMesh.position.set(0, -1.75, -0.4);
            shadowMesh.rotation.x = -Math.PI / 2.3;
            scene.add(shadowMesh);

            // Interactive Physics State
            let targetRotX = 0.12;
            let targetRotY = -0.25;
            let currentRotX = targetRotX;
            let currentRotY = targetRotY;

            let isDragging = false;
            let previousPointerPos = { x: 0, y: 0 };
            let mouseNorm = { x: 0, y: 0 };

            // Mouse tracking on container
            container.addEventListener('mousemove', (e) => {
                const rect = container.getBoundingClientRect();
                const x = ((e.clientX - rect.left) / rect.width) * 2 - 1;
                const y = -(((e.clientY - rect.top) / rect.height) * 2 - 1);
                mouseNorm.x = x;
                mouseNorm.y = y;

                if (!isDragging) {
                    targetRotY = x * 0.45;
                    targetRotX = -y * 0.35;
                }

                // Move Key Light to produce realistic specular glide
                keySpotlight.position.x = x * 4;
                keySpotlight.position.y = (y * 3) + 1.5;
            });

            // Drag to rotate in 3D
            container.addEventListener('mousedown', (e) => {
                isDragging = true;
                previousPointerPos = { x: e.clientX, y: e.clientY };
            });

            window.addEventListener('mouseup', () => { isDragging = false; });

            window.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                const deltaX = e.clientX - previousPointerPos.x;
                const deltaY = e.clientY - previousPointerPos.y;
                targetRotY += deltaX * 0.008;
                targetRotX += deltaY * 0.008;
                previousPointerPos = { x: e.clientX, y: e.clientY };
            });

            // Touch interaction
            container.addEventListener('touchmove', (e) => {
                if (e.touches.length > 0) {
                    const touch = e.touches[0];
                    const rect = container.getBoundingClientRect();
                    const x = ((touch.clientX - rect.left) / rect.width) * 2 - 1;
                    const y = -(((touch.clientY - rect.top) / rect.height) * 2 - 1);
                    targetRotY = x * 0.4;
                    targetRotX = -y * 0.3;
                    keySpotlight.position.x = x * 4;
                }
            }, { passive: true });

            // Main Animation Loop
            let clock = new THREE.Clock();

            function renderLoop() {
                requestAnimationFrame(renderLoop);
                const elapsed = clock.getElapsedTime();

                // Damped spring interpolation (Lerp factor 0.06 for silky lag-free glide)
                currentRotX += (targetRotX - currentRotX) * 0.06;
                currentRotY += (targetRotY - currentRotY) * 0.06;

                cardGroup.rotation.x = currentRotX;
                cardGroup.rotation.y = currentRotY;

                // Subtle breathing floating movement
                const floatY = Math.sin(elapsed * 1.6) * 0.07;
                cardGroup.position.y = floatY;

                // Shadow reacts to elevation
                shadowMesh.scale.set(1 + floatY * 0.5, 1 + floatY * 0.5, 1);

                renderer.render(scene, camera);
            }

            renderLoop();

            // Resize Observer
            window.addEventListener('resize', () => {
                width = container.clientWidth;
                height = container.clientHeight;
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
                renderer.setSize(width, height);
            });
        })();
    </script>

    <script>
        (function initOrbitalFlow() {
            const canvas = document.getElementById('orbital-canvas');
            const container = canvas.parentElement;
            if (!canvas || !container || typeof THREE === 'undefined') return;

            const scene = new THREE.Scene();
            const camera = new THREE.PerspectiveCamera(42, container.clientWidth / container.clientHeight, 0.1, 100);
            camera.position.set(0, 0.15, 8.2);
            const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
            renderer.setSize(container.clientWidth, container.clientHeight);

            scene.add(new THREE.AmbientLight(0x6d4b99, 1.6));
            const violetLight = new THREE.PointLight(0xb984ff, 4, 14);
            violetLight.position.set(2, 3, 4);
            scene.add(violetLight);
            const roseLight = new THREE.PointLight(0xff9d86, 2.5, 12);
            roseLight.position.set(-3, -2, 2);
            scene.add(roseLight);

            const orbit = new THREE.Group();
            orbit.rotation.set(0.18, -0.35, 0.05);
            scene.add(orbit);

            const nodes = [];
            const nodeGeometry = new THREE.IcosahedronGeometry(0.16, 2);
            const nodeColors = [0xb984ff, 0x6fdb9b, 0xff9d86, 0xf3cb78];
            for (let index = 0; index < 18; index += 1) {
                const angle = (index / 18) * Math.PI * 2;
                const radius = index % 3 === 0 ? 2.25 : index % 2 === 0 ? 1.65 : 2.8;
                const node = new THREE.Mesh(nodeGeometry, new THREE.MeshStandardMaterial({ color: nodeColors[index % nodeColors.length], emissive: nodeColors[index % nodeColors.length], emissiveIntensity: 0.45, roughness: 0.3, metalness: 0.2 }));
                node.position.set(Math.cos(angle) * radius, Math.sin(angle * 1.7) * 0.75, Math.sin(angle) * radius * 0.55);
                orbit.add(node);
                nodes.push(node);
            }

            const ringMaterial = new THREE.LineBasicMaterial({ color: 0xb984ff, transparent: true, opacity: 0.28 });
            [1.45, 2.15, 2.85].forEach((radius, ringIndex) => {
                const points = [];
                for (let index = 0; index <= 96; index += 1) {
                    const angle = (index / 96) * Math.PI * 2;
                    points.push(new THREE.Vector3(Math.cos(angle) * radius, Math.sin(angle) * 0.75, Math.sin(angle) * radius * 0.55));
                }
                const ring = new THREE.Line(new THREE.BufferGeometry().setFromPoints(points), ringMaterial.clone());
                ring.rotation.z = ringIndex * 0.18;
                ring.rotation.x = ringIndex * 0.12;
                orbit.add(ring);
            });

            const core = new THREE.Mesh(new THREE.SphereGeometry(0.72, 32, 32), new THREE.MeshStandardMaterial({ color: 0x211634, emissive: 0x8f58d1, emissiveIntensity: 0.75, metalness: 0.35, roughness: 0.2 }));
            orbit.add(core);

            let pointerX = 0;
            let pointerY = 0;
            container.addEventListener('pointermove', (event) => {
                const bounds = container.getBoundingClientRect();
                pointerX = ((event.clientX - bounds.left) / bounds.width) * 2 - 1;
                pointerY = ((event.clientY - bounds.top) / bounds.height) * 2 - 1;
            });

            const clock = new THREE.Clock();
            function renderFlow() {
                requestAnimationFrame(renderFlow);
                const elapsed = clock.getElapsedTime();
                orbit.rotation.y += 0.0025;
                orbit.rotation.x += (pointerY * 0.18 + 0.12 - orbit.rotation.x) * 0.025;
                orbit.rotation.z += (pointerX * 0.12 - orbit.rotation.z) * 0.025;
                core.scale.setScalar(1 + Math.sin(elapsed * 1.8) * 0.06);
                nodes.forEach((node, index) => { node.position.y += Math.sin(elapsed * 1.4 + index) * 0.0015; });
                renderer.render(scene, camera);
            }
            renderFlow();

            const resize = () => {
                const width = container.clientWidth;
                const height = container.clientHeight;
                camera.aspect = width / height;
                camera.updateProjectionMatrix();
                renderer.setSize(width, height);
            };
            window.addEventListener('resize', resize);
        })();
    </script>
</body>
</html>
