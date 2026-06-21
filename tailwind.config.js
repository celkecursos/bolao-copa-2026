import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'br-green':      '#009C3B',
                'br-green-dark': '#007A2F',
                'br-yellow':     '#FFDF00',
                'br-blue':       '#002776',
                'br-navy':       '#001a50',
                'br-deep':       '#001233',
            },
        },
    },

    plugins: [forms],
};
