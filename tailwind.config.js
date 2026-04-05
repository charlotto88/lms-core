import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
        content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php', // This is the one missing!
        './resources/views/layouts/**/*.blade.php',
        './resources/views/student/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms, 
        typography, // This enables the 'prose' class for your lessons
    ],
};