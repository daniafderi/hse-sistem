import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
            flex: {
                '1/2': '1 1 calc(50%)',
                '1/3': '1 1 calc(33.3333%)',
                '1/4': '1 1 calc(25%)'
            },
            borderWidth: {
                '1': '1px'
            }
        },
    },

    plugins: [forms],
};
