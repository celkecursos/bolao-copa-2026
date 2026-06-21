@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-[#FFDF00] text-sm font-medium leading-5 text-white focus:outline-none focus:border-[#FFDF00] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-white/80 hover:text-[#FFDF00] hover:border-[#FFDF00]/50 focus:outline-none focus:text-[#FFDF00] focus:border-[#FFDF00]/50 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
