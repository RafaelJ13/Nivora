<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nivora — Gestão de Finanças Pessoais</title>
    <meta name="description" content="Regista contas, rendimentos e despesas, sem ligação bancária.">
    <link rel="icon" type="image/svg+xml" href="<?= base_url('/favicon.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/nivora.css?v=' . filemtime(FCPATH . 'assets/nivora.css')) ?>">
</head>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nav = document.querySelector('.site-nav');
        if (!nav) return;
        const onScroll = () => {
            if (window.scrollY > 10) {
                nav.classList.add('is-scrolled');
            } else {
                nav.classList.remove('is-scrolled');
            }
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    });
</script>

<body class="landing-page">

    <!-- NAVBAR -->
    <nav class="site-nav">
        <div class="container d-flex justify-content-between align-items-center">
            <a href="<?= site_url('/') ?>" class="brand-text">
                <img src="<?= base_url('/favicon.png') ?>" alt="Nivora" class="landing-brand-icon">
                <span>Nivora</span>
            </a>

            <div class="d-none d-md-flex align-items-center gap-1 nav-center">
                <a href="#funcionalidades" class="nav-link-item">Funcionalidades</a>
                <a href="#planos" class="nav-link-item">Preços</a>
                <a href="#faq" class="nav-link-item">FAQ</a>
            </div>

            <div class="d-flex align-items-center gap-3">
                <?php if (function_exists('auth') && auth()->loggedIn()) : ?>
                    <a href="<?= site_url('dashboard') ?>" class="btn-action-primary">Abrir Dashboard</a>
                <?php else : ?>
                    <a href="<?= url_to('login') ?>" class="nav-link-item">Entrar</a>
                    <a href="<?= url_to('register') ?>" class="btn-action-primary">Criar conta</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- HERO CENTRADO -->
    <header class="hero" data-od-id="landing-hero">
        <div class="hero-glow"></div>

        <div class="container hero-inner">


            <h1 class="hero-headline">
                Gestão financeira<br>manual e transparente
            </h1>

            <p class="hero-subhead">
                Controlo total sem integração bancária. Regista tudo, de bancos digitais a dinheiro em espécie, num só lugar.
            </p>

            <div class="hero-actions">
                <?php if (function_exists('auth') && auth()->loggedIn()) : ?>
                    <a href="<?= site_url('dashboard') ?>" class="btn-action-primary">
                        Ir para o Painel <i class="bi bi-arrow-right"></i>
                    </a>
                <?php else : ?>
                    <a href="<?= url_to('register') ?>" class="btn-action-primary">
                        Começar agora <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#painel" class="btn-action-secondary">Ver interface</a>
                <?php endif; ?>
            </div>

            <!-- MOCKUP DOS TELEMÓVEIS -->
            <div class="hero-mockup">
                <!-- Card flutuante esquerdo topo -->
                <div class="float-card float-card-tl">
                    <span class="fc-label">Target Achieved</span>
                    <span class="fc-value">85%</span>
                    <div class="fc-bar"><span style="width:85%"></span></div>
                </div>

                <!-- Card flutuante esquerdo baixo -->
                <div class="float-card float-card-bl">
                    <span class="fc-label">Recent Spend</span>
                    <span class="fc-value">€150.22</span>
                </div>

                <!-- Card flutuante direito -->
                <div class="float-card float-card-r">
                    <span class="fc-label">Net Worth Trend</span>
                    <span class="fc-value fc-up">+3.2%</span>
                </div>

                <!-- Imagem dos telemóveis -->
                <img src="<?= base_url('assets/landing/hero-phones.png') ?>"
                     alt="Nivora no telemóvel — dashboard financeiro"
                     loading="eager"
                     onerror="this.onerror=null;this.src='<?= base_url('assets/landing/hero-phones.svg') ?>';">
            </div>
        </div>
    </header>

    <!-- PAINEL -->
    <section id="painel" class="panel-section" data-od-id="product-preview" data-scroll-reveal>
        <div class="container">
            <div class="section-heading">
                <h2>Painel de Controlo Nivora</h2>
                <p>Tenha uma visão clara de todas as suas finanças manuais.</p>
            </div>

            <div class="panel-mockup">
                <img src="<?= base_url('assets/landing/painel-dashboard.png') ?>"
                     alt="Painel de Controlo Nivora — versão web e mobile"
                     loading="lazy"
                     onerror="this.onerror=null;this.src='<?= base_url('assets/landing/painel-dashboard.svg') ?>';">
            </div>
        </div>
    </section>

    <!-- FUNCIONALIDADES -->
    <section id="funcionalidades" class="features-section" data-od-id="product-capabilities" data-scroll-reveal>
        <div class="container">
            <div class="section-heading">
                <h2>Funcionalidades Principais</h2>
                <p>Tenha uma visão clara de todas as suas finanças manuais.</p>
            </div>

            <div class="features-row">
                <div class="feature-card scroll-reveal-item">
                    <h3>100% Manual, Totalmente Privado</h3>
                    <p>Sem ligação bancária, sem partilhar credenciais. Tu controlas exatamente o que entra no Nivora.</p>
                    <div class="feature-visual">
                        <img src="<?= base_url('assets/landing/feature-manual.png') ?>"
                             alt="Contas" loading="lazy"
                             onerror="this.onerror=null;this.src='<?= base_url('assets/landing/feature-manual.svg') ?>';">
                    </div>
                </div>

                <div class="feature-card scroll-reveal-item">
                    <h3>Categorias Personalizadas</h3>
                    <p>Organiza despesas e receitas do teu jeito.</p>
                    <div class="feature-visual">
                        <img src="<?= base_url('assets/landing/feature-categorias.png') ?>"
                             alt="Categorias" loading="lazy"
                             onerror="this.onerror=null;this.src='<?= base_url('assets/landing/feature-categorias.svg') ?>';">
                    </div>
                </div>

                <div class="feature-card scroll-reveal-item">
                    <h3>Registo Rápido de Movimentos</h3>
                    <p>Adiciona uma despesa ou receita em segundos, sem formulários complicados.</p>
                    <div class="feature-visual">
                        <img src="<?= base_url('assets/landing/feature-registo.png') ?>"
                             alt="Registo" loading="lazy"
                             onerror="this.onerror=null;this.src='<?= base_url('assets/landing/feature-registo.svg') ?>';">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ -->
    <section id="faq" class="py-5" data-od-id="faq" data-scroll-reveal>
        <div class="container py-4">
            <div class="section-heading">
                <h2 class="h3 fw-bold mb-0">FAQ</h2>
            </div>

            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-q">Preciso de partilhar credenciais bancárias?</div>
                    <p class="faq-a">Não. O Nivora não se liga diretamente ao teu banco. Registas apenas os movimentos que queres acompanhar.</p>
                </div>
                <div class="faq-item">
                    <div class="faq-q">Como registo dinheiro em espécie (físico)?</div>
                    <p class="faq-a">Cria uma conta do tipo "Dinheiro" e regista as entradas e saídas manualmente.</p>
                </div>
                <div class="faq-item">
                    <div class="faq-q">Posso importar dados de outra app ou do banco?</div>
                    <p class="faq-a">Ainda não. Todos os movimentos são inseridos manualmente.</p>
                </div>
                <div class="faq-item">
                    <div class="faq-q">O Nivora é seguro?</div>
                    <p class="faq-a">Hashing de passwords, proteção CSRF e validação de dados. Cada utilizador só acede aos seus registos.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-md-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="<?= base_url('favicon.png') ?>" alt="Nivora" style="width:22px;height:22px;">
                        <span class="text-white fw-bold">Nivora</span>
                    </div>
                    <p class="small" style="color: var(--vault-dim);">Gestão financeira pessoal.</p>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-col-title">Recursos</div>
                    <a href="<?= site_url('dashboard') ?>" class="footer-link">Dashboard</a>
                    <a href="#funcionalidades" class="footer-link">Funcionalidades</a>
                    <a href="#painel" class="footer-link">Painel</a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-col-title">Preços</div>
                    <a href="#planos" class="footer-link">Plano Grátis</a>
                    <a href="#planos" class="footer-link">Pro</a>
                </div>
                <div class="col-6 col-md-3">
                    <div class="footer-col-title">Legal</div>
                    <a href="#faq" class="footer-link">FAQ</a>
                    <a href="<?= site_url('termos') ?>" class="footer-link">Termos</a>
                    <a href="<?= site_url('privacidade') ?>" class="footer-link">Privacidade</a>
                </div>
            </div>

            <div class="footer-bottom d-flex flex-column flex-sm-row justify-content-between align-items-center gap-2">
                <span>&copy; <?= date('Y') ?> Nivora &bull; Gestão Financeira Pessoal V1.0</span>
                <div class="d-flex gap-3">
                    <a href="<?= url_to('login') ?>" class="footer-link d-inline">Entrar</a>
                    <a href="<?= url_to('register') ?>" class="footer-link d-inline">Registo</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        const scrollRevealElements = document.querySelectorAll('[data-scroll-reveal]');
        scrollRevealElements.forEach(section => {
            section.querySelectorAll('.scroll-reveal-item, .faq-item').forEach((item, i) => {
                item.classList.add('scroll-reveal-item');
                item.style.setProperty('--reveal-delay', `${i * 70}ms`);
            });
        });
        if ('IntersectionObserver' in window) {
            const obs = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.12 });
            scrollRevealElements.forEach(el => obs.observe(el));
        } else {
            scrollRevealElements.forEach(el => el.classList.add('is-visible'));
        }
    </script>
</body>
</html>