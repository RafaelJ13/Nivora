<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Entrar<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="text-center mb-4">
    <a href="<?= site_url('/') ?>" class="brand-logo mb-3">
        <span class="brand-icon-box"><i class="bi bi-wallet2"></i></span>
        <span>NIVORA</span>
    </a>
    <h1 class="h3 fw-bold text-white mt-3 mb-1">Bem-vindo de volta</h1>
    <p class="text-secondary small mb-0">Entra para veres as tuas contas.</p>
</div>

<div class="auth-card">
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
            <div class="input-group">
                <input class="form-control" id="password" type="password" name="password" 
                       autocomplete="current-password" placeholder="••••••••" required>
                <button class="btn btn-dark border border-secondary border-opacity-25 text-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
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
            <i class="bi bi-box-arrow-in-right"></i> Entrar
        </button>
    </form>
</div>

<p class="text-center text-secondary small mt-4 mb-2">
    Ainda não tens conta? <a href="<?= url_to('register') ?>" class="auth-link">Criar conta</a>
</p>
<p class="text-center">
    <a href="<?= site_url('/') ?>" class="text-secondary text-decoration-none small">
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
