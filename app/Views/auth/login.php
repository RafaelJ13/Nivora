<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Entrar<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="auth-card auth-card-login">
    <div class="auth-kicker">Área pessoal</div>
    <h2 class="h3 fw-bold mb-2">Continua de onde ficaste</h2>
    <p class="text-secondary small mb-4">Entra na tua conta para acompanhares o teu dinheiro com clareza.</p>
    <?php if (session('error') !== null) : ?>
        <div class="alert alert-danger bg-danger bg-opacity-20 border-danger border-opacity-50 text-white small mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-exclamation-circle-fill text-danger fs-5"></i>
            <div><?= esc(session('error')) ?></div>
        </div>
    <?php elseif (session('errors') !== null) : ?>
        <div class="alert alert-danger bg-danger bg-opacity-20 border-danger border-opacity-50 text-white small mb-4" role="alert">
            <?php foreach ((array) session('errors') as $error) : ?>
                <div><i class="bi bi-dot"></i> <?= esc($error) ?></div>
            <?php endforeach ?>
        </div>
    <?php endif ?>

    <?php if (session('message') !== null) : ?>
        <div class="alert alert-success bg-success bg-opacity-20 border-success border-opacity-50 text-white small mb-4 d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill text-success fs-5"></i>
            <div><?= esc(session('message')) ?></div>
        </div>
    <?php endif ?>

    <form action="<?= url_to('login') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label" for="email">
                <i class="bi bi-envelope me-1"></i> Email
            </label>
            <input class="form-control" id="email" type="email" name="email" autocomplete="email" 
                   placeholder="teu.email@exemplo.pt" value="<?= old('email') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label" for="password">
                <i class="bi bi-lock me-1"></i> Palavra-passe
            </label>
            <div class="password-field">
                <input class="form-control" id="password" type="password" name="password" 
                       autocomplete="current-password" placeholder="••••••••" required>
                <button class="password-toggle" type="button" aria-label="Mostrar palavra-passe" onclick="togglePasswordVisibility('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <?php if (setting('Auth.sessionConfig')['allowRemembering']) : ?>
            <div class="form-check mb-4">
                <input class="form-check-input" id="remember" type="checkbox" name="remember" value="1" <?= old('remember') ? 'checked' : '' ?>>
                <label class="form-check-label text-secondary small" for="remember">
                    Manter sessão iniciada neste dispositivo
                </label>
            </div>
        <?php endif ?>

        <button class="btn-brand-primary mb-3" type="submit">
            <i class="bi bi-arrow-right-circle"></i> Entrar na Nivora
        </button>
    </form>
</div>

<p class="auth-footer text-center mt-4 mb-2">
Ainda não tens conta? <a href="<?= url_to('register') ?>" class="auth-link">Começar agora</a>
</p>
<p class="auth-footer text-center mb-0">
    <a href="<?= site_url('/') ?>" class="back-link text-decoration-none">
        &larr; Voltar à página principal
    </a>
</p>

<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    }
</script>
<?= $this->endSection() ?>
