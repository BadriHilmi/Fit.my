const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                mono: ['Geist Mono', 'JetBrains Mono', 'Fira Code', ...defaultTheme.fontFamily.mono],
            },
            colors: {
                primary: colors.emerald,
                neutral: colors.gray,
                accent: colors.orange,
                success: colors.emerald,
                warning: colors.amber,
                error: colors.rose,
                mintlify: {
                    mint: '#00d4a4',
                    deep: '#00b48a',
                    soft: '#e6faf5',
                    tag: '#3772cf',
                    error: '#d45656',
                    ink: '#0a0a0a',
                    charcoal: '#1c1c1e',
                    slate: '#3a3a3c',
                    steel: '#5a5a5c',
                    stone: '#888888',
                    muted: '#a8a8aa',
                    hairline: '#e5e5e5',
                    'hairline-soft': '#ededed',
                    'hairline-dark': '#1f1f1f',
                    surface: '#f7f7f8',
                    'surface-soft': '#fafafa',
                    'surface-code': '#1c1c1e',
                },
                brand: {
                    50: '#e6faf5',
                    100: '#c5f5e8',
                    200: '#94edd6',
                    300: '#56dec0',
                    400: '#22cda8',
                    500: '#00d4a4',
                    600: '#00b48a',
                    700: '#008f6e',
                    800: '#057058',
                    900: '#085c49',
                    950: '#02352b',
                },
                surface: {
                    base: '#e4e8ee',
                    canvas: '#d7dfe9',
                    white: '#ffffff',
                    mint: '#d2e7df',
                },
            },
            boxShadow: {
                subtle: 'rgba(0, 0, 0, 0.04) 0px 1px 2px 0px',
                soft: '0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03)',
                card: '0 8px 24px -4px rgba(15, 23, 42, 0.06), 0 2px 6px -1px rgba(15, 23, 42, 0.03)',
                mockup: '0 24px 48px -8px rgba(0, 0, 0, 0.1), 0 8px 24px -4px rgba(0, 212, 164, 0.08)',
                'mint-glow': '0 10px 25px -5px rgba(0, 212, 164, 0.25)',
                'glow-primary': '0 10px 25px -5px rgba(0, 212, 164, 0.28)',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
