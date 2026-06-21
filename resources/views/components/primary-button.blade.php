<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#009C3B] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#007A2F] focus:bg-[#007A2F] active:bg-[#005C23] focus:outline-none focus:ring-2 focus:ring-[#FFDF00] focus:ring-offset-2 dark:focus:ring-offset-[#001a50] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
