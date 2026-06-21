@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-[#002776] dark:text-[#FFDF00]/80']) }}>
    {{ $value ?? $slot }}
</label>
