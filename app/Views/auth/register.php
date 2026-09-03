<?= $this->extend(config('Auth')->views['layout']) ?>

<?= $this->section('title') ?>Criar conta<?= $this->endSection() ?>

<?= $this->section('main') ?>
<div class="text-center mb-4">
    <a href="<?= site_url('/') ?>" class="brand-logo mb-3">
        <span class="brand-icon-box"><i class="bi bi-wallet2"></i></span>
        <span>NIVORA</span>
    </a>
    <h1 class="h3 fw-bold text-white mt-3 mb-1">Criar a tua conta</h1>
    <p class="text-secondary small mb-0">Cria uma conta para começares.</p>
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

    <form action="<?= url_to('register') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label" for="name">
                <i class="bi bi-person me-1"></i> Nome completo
            </label>
            <input class="form-control" id="name" type="text" name="name" autocomplete="name" 
                   placeholder="ex: Rafael Januário" value="<?= old('name') ?>" required>
        </div>

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
                       autocomplete="new-password" placeholder="Mínimo 8 caracteres" required>
                <button class="btn btn-dark border border-secondary border-opacity-25 text-secondary" type="button" onclick="togglePasswordVisibility('password', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label" for="password_confirm">
                <i class="bi bi-shield-check me-1"></i> Confirmar palavra-passe
            </label>
            <div class="input-group">
                <input class="form-control" id="password_confirm" type="password" name="password_confirm" 
                       autocomplete="new-password" placeholder="Repetir palavra-passe" required>
                <button class="btn btn-dark border border-secondary border-opacity-25 text-secondary" type="button" onclick="togglePasswordVisibility('password_confirm', this)">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
        </div>

        <button class="btn-brand-primary mb-3" type="submit">
            <i class="bi bi-person-plus-fill"></i> Criar conta
        </button>
    </form>
</div>

<p class="text-center text-secondary small mt-4 mb-2">
    Já tens uma conta? <a href="<?= url_to('login') ?>" class="auth-link">Entrar</a>
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
