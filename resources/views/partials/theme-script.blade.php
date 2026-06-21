{{-- Aplica o tema salvo antes da renderização (evita "flash") e expõe o toggle. --}}
<script>
    (function () {
        const apply = (dark) => document.documentElement.classList.toggle('dark', dark);
        const stored = localStorage.getItem('theme');
        apply(stored ? stored === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches);

        window.toggleTheme = function () {
            const dark = !document.documentElement.classList.contains('dark');
            apply(dark);
            localStorage.setItem('theme', dark ? 'dark' : 'light');
        };
    })();
</script>
