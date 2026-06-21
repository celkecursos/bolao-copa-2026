@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-[#FFDF00] text-start text-base font-medium text-[#FFDF00] bg-[#006830] dark:bg-[#001a50] focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-white/80 hover:text-[#FFDF00] hover:bg-[#006830] dark:hover:bg-[#001a50] hover:border-[#FFDF00]/50 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
