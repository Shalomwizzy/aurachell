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
                    DEFAULT: '#6B2016',
                    50:  '#fdf3f1',
                    100: '#fae4e0',
                    200: '#f4cac3',
                    300: '#eca79c',
                    400: '#e07769',
                    500: '#cc4e3f',
                    600: '#b0332a',
                    700: '#8b3a2c',
                    800: '#6B2016',
                    900: '#4a1510',
                    950: '#2a0a08',
                },
                warmSand: {
                    DEFAULT: '#D4B8A0',
                    50:  '#faf6f2',
                    100: '#f5ede4',
                    200: '#ede1d5',
                    300: '#D4B8A0',
                    400: '#C4A48C',
                    500: '#b08d73',
                    600: '#8a6d55',
                    700: '#6d5343',
                    800: '#4e3b30',
                    900: '#2c1f18',
                },
                caramel: {
                    DEFAULT: '#C4A48C',
                    light: '#d4b8a0',
                    dark:  '#a08267',
                },
                surface: '#F5EDE4',
                mahoganyDark: '#2C0F0A',

                // Legacy aliases (keep for backward compat in old views)
                sage: {
                    DEFAULT: '#6B2016',
                    50:  '#fdf3f1',
                    800: '#4a1510',
                },
                sand: {
                    DEFAULT: '#C4A48C',
                    400:     '#D4B8A0',
                },
                bronze:  '#C4A48C',
                cream:   '#F5EDE4',
                'text-dark':  '#2C0F0A',
                'text-muted': '#8a5e50',
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
                'luxury':    '0 4px 30px rgba(107,32,22,0.07)',
                'luxury-lg': '0 8px 60px rgba(107,32,22,0.12)',
                'admin':     '0 4px 24px rgba(0,0,0,0.4)',
                'glow':      '0 0 20px rgba(107,32,22,0.3)',
            },
        },
    },

    plugins: [forms, typography],
};
