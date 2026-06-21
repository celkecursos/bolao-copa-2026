<button type="button" onclick="window.toggleTheme()" title="{{ __('Alternar tema') }}"
    class="inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/20 dark:text-gray-300 dark:hover:bg-white/10 focus:outline-none transition">
    {{-- Sol (visível no tema escuro) --}}
    <svg class="h-5 w-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.36 6.36-1.42-1.42M7.05 7.05 5.64 5.64m12.72 0-1.42 1.42M7.05 16.95l-1.41 1.41M12 8a4 4 0 100 8 4 4 0 000-8z" />
    </svg>
    {{-- Lua (visível no tema claro) --}}
    <svg class="h-5 w-5 block dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z" />
    </svg>
</button>
