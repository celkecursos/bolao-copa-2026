@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-[#003A8C] dark:bg-[#001a50] dark:text-gray-300 focus:border-[#009C3B] dark:focus:border-[#009C3B] focus:ring-[#009C3B] dark:focus:ring-[#009C3B] rounded-md shadow-sm']) }}>
