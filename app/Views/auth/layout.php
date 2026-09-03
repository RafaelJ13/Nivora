<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('title') ? $this->renderSection('title') . ' — Nivora' : 'Autenticação — Nivora' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --nivora-dark: #070e12;
            --nivora-dark-card: #0d171d;
            --nivora-dark-card-border: rgba(255, 255, 255, 0.08);
            --nivora-teal: #10b981;
            --nivora-teal-deep: #0e4b49;
            --nivora-teal-glow: rgba(16, 185, 129, 0.25);
            --nivora-coral: #f43f5e;
            --nivora-light: #f8fafc;
            --nivora-slate: #94a3b8;
            --font-main: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'Space Grotesk', monospace;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            background-color: var(--nivora-dark);
            color: var(--nivora-light);
            font-family: var(--font-main);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-x: hidden;
            padding: 2rem 1rem;
        }

        .ambient-glow-auth {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.15) 0%, rgba(14, 75, 73, 0.03) 50%, transparent 70%);
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }

        .auth-panel {
            width: min(100%, 28rem);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 1.4rem;
            color: #ffffff;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
        }

        .brand-icon-box {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--nivora-teal) 0%, var(--nivora-teal-deep) 100%);
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 1.1rem;
            box-shadow: 0 0 16px var(--nivora-teal-glow);
        }

        .auth-card {
            background: var(--nivora-dark-card);
            border: 1px solid var(--nivora-dark-card-border);
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background-color: #060b0e !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--nivora-teal) !important;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.18) !important;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--nivora-slate);
            margin-bottom: 0.4rem;
        }

        .btn-brand-primary {
            background: linear-gradient(135deg, var(--nivora-teal) 0%, #059669 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            box-shadow: 0 4px 18px var(--nivora-teal-glow);
            transition: all 0.25s ease;
            width: 100%;
        }

        .btn-brand-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(16, 185, 129, 0.4);
            color: #ffffff;
        }

        .auth-link {
            color: var(--nivora-teal);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: #34d399;
            text-decoration: underline;
        }

        /* Open Design: tech-utility direction, adapted for Nivora. */
        :root {
            --nivora-dark: #09100f;
            --nivora-dark-card: #111a17;
            --nivora-dark-card-border: rgba(214, 232, 221, 0.12);
            --nivora-teal: #70e0b0;
            --nivora-teal-deep: #164d3d;
            --nivora-teal-glow: rgba(112, 224, 176, 0.16);
            --nivora-light: #edf5ef;
            --nivora-slate: #9caf9f;
            --font-main: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'Space Grotesk', monospace;
        }

        body {
            background-color: var(--nivora-dark);
            background-image: linear-gradient(rgba(112, 224, 176, 0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(112, 224, 176, 0.025) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .ambient-glow-auth {
            width: 360px;
            height: 360px;
            background: radial-gradient(circle, rgba(112, 224, 176, 0.09) 0%, transparent 68%);
            filter: blur(55px);
        }

        .brand-icon-box {
            background: var(--nivora-teal);
            color: #092019;
            border-radius: 5px;
            box-shadow: none;
        }

        .auth-card {
            border-radius: 8px;
            border-color: rgba(214, 232, 221, 0.14);
            box-shadow: 0 18px 40px rgba(0, 0, 0, 0.24);
        }

        .form-control {
            background-color: #0b1311 !important;
            border-color: rgba(214, 232, 221, 0.16) !important;
            border-radius: 5px;
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

        .auth-link {
            color: var(--nivora-teal);
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/nivora.css') ?>">
</head>
<body>
    <div class="ambient-glow-auth"></div>
    <div class="auth-panel">
        <?= $this->renderSection('main') ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('pageScripts') ?>
</body>
</html>
