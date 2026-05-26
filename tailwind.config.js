import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                // Aurachell Brand Palette
                mahogany: {
                    DEFAULT: '#371220',
                    50:  '#f8f0f3',
                    100: '#f0dee4',
                    200: '#e0bdc8',
                    300: '#c994a8',
                    400: '#a96a88',
                    500: '#884568',
                    600: '#6b3051',
                    700: '#542035',
                    800: '#371220',
                    900: '#220B14',
                    950: '#13060c',
                },
                warmSand: {
                    DEFAULT: '#D4B99A',
                    50:  '#fdf9f5',
                    100: '#faf5ed',
                    200: '#f2e8d8',
                    300: '#e5d0b8',
                    400: '#D4B99A',
                    500: '#c09a7d',
                    600: '#a37c62',
                    700: '#84614a',
                    800: '#634736',
                    900: '#3d2c21',
                },
                caramel: {
                    DEFAULT: '#C9A96F',
                    light: '#d4b99a',
                    dark:  '#b08d4e',
                },
                surface: '#FAF5ED',
                mahoganyDark: '#1E0C14',

                // Legacy aliases (keep for backward compat in old views)
                sage: {
                    DEFAULT: '#371220',
                    50:  '#f8f0f3',
                    800: '#220B14',
                },
                sand: {
                    DEFAULT: '#D4B99A',
                    400:     '#C9A96F',
                },
                bronze:  '#C9A96F',
                cream:   '#FAF5ED',
                'text-dark':  '#1E0C14',
                'text-muted': '#8A6B40',
            },
            fontFamily: {
                display: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                'grain': "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E\")",
            },
            animation: {
                'fade-in':    'fadeIn 0.6s ease-out forwards',
                'slide-up':   'slideUp 0.5s ease-out forwards',
                'slide-right':'slideRight 0.4s ease-out forwards',
                'slide-left': 'slideLeft 0.4s ease-out forwards',
                'pulse-soft': 'pulseSoft 2s ease-in-out infinite',
            },
            keyframes: {
                fadeIn:    { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                slideUp:   { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                slideRight:{ '0%': { opacity: '0', transform: 'translateX(100%)' }, '100%': { opacity: '1', transform: 'translateX(0)' } },
                slideLeft: { '0%': { opacity: '0', transform: 'translateX(-100%)' }, '100%': { opacity: '1', transform: 'translateX(0)' } },
                pulseSoft: { '0%,100%': { opacity: '1' }, '50%': { opacity: '0.5' } },
            },
            boxShadow: {
                'luxury':    '0 4px 30px rgba(55,18,32,0.08)',
                'luxury-lg': '0 8px 60px rgba(55,18,32,0.14)',
                'admin':     '0 4px 24px rgba(0,0,0,0.4)',
                'glow':      '0 0 20px rgba(55,18,32,0.30)',
            },
        },
    },

    plugins: [forms, typography],
};
