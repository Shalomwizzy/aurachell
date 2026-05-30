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
                // Aurachell Brand Palette — 5-color luxury system
                mahogany: {
                    DEFAULT: '#371220',
                    50:  'rgba(55,18,32,0.05)',
                    100: 'rgba(55,18,32,0.10)',
                    200: 'rgba(55,18,32,0.20)',
                    300: 'rgba(55,18,32,0.30)',
                    400: 'rgba(55,18,32,0.45)',
                    500: 'rgba(55,18,32,0.60)',
                    600: 'rgba(55,18,32,0.70)',
                    700: 'rgba(55,18,32,0.80)',
                    800: '#371220',
                    900: '#371220',
                    950: '#371220',
                },
                warmSand: {
                    DEFAULT: '#F7F2EB',
                    50:  '#F7F2EB',
                    100: '#F7F2EB',
                    300: 'rgba(42,37,34,0.08)',
                    400: 'rgba(42,37,34,0.12)',
                },
                caramel: {
                    DEFAULT: '#C9A96F',
                    light: 'rgba(201,169,111,0.60)',
                    dark:  '#C9A96F',
                },
                surface: '#FFFFFF',
                mahoganyDark: '#160c0b',

                // Legacy aliases (keep for backward compat in old views)
                sage: {
                    DEFAULT: '#371220',
                    50:  'rgba(55,18,32,0.05)',
                    800: '#371220',
                },
                sand: {
                    DEFAULT: 'rgba(247,242,235,0.80)',
                    400: '#F7F2EB',
                },
                bronze:  '#C9A96F',
                cream:   '#F7F2EB',
                'text-dark':  '#2A2522',
                'text-muted': 'rgba(42,37,34,0.55)',
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
