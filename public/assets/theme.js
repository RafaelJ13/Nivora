(function () {
    const preferenceKey = 'nivora-theme';
    const root = document.documentElement;

    try {
        root.dataset.theme = localStorage.getItem(preferenceKey) === 'light' ? 'light' : 'dark';
    } catch (error) {
        root.dataset.theme = 'dark';
        console.warn('Não foi possível carregar a preferência de tema.', error);
    }

    function updateThemeControls() {
        const isLight = root.dataset.theme === 'light';

        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            const icon = button.querySelector('[data-theme-icon]');
            const label = button.querySelector('[data-theme-label]');
            const action = isLight ? 'escuro' : 'claro';

            button.setAttribute('aria-label', `Ativar modo ${action}`);
            button.setAttribute('aria-pressed', String(isLight));
            button.title = `Ativar modo ${action}`;

            if (icon) icon.className = `bi ${isLight ? 'bi-moon' : 'bi-sun'}`;
            if (label) label.textContent = `Modo ${action}`;
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateThemeControls();

        document.querySelectorAll('[data-theme-toggle]').forEach(button => {
            button.addEventListener('click', function () {
                const nextTheme = root.dataset.theme === 'light' ? 'dark' : 'light';
                root.dataset.theme = nextTheme;

                try {
                    localStorage.setItem(preferenceKey, nextTheme);
                } catch (error) {
                    console.warn('Não foi possível guardar a preferência de tema.', error);
                }

                updateThemeControls();
            });
        });
    });
})();
