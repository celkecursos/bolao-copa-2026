<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-[#001a50] border border-gray-300 dark:border-[#003A8C] rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-[#002050] focus:outline-none focus:ring-2 focus:ring-[#009C3B] focus:ring-offset-2 dark:focus:ring-offset-[#001a50] disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
