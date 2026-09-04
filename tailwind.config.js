/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/js/**/*.ts',
    ],
    theme: {
        extend: {
            colors: {
                // Pine Teal Primary (Inspired by reference UI aesthetic)
                brand: {
                    50: '#f2f8f6',
                    100: '#e1f0ec',
                    200: '#c5e2da',
                    300: '#9bcdc2',
                    400: '#69b0a3',
                    500: '#439486',
                    600: '#2d6a5d', // Core rich pine teal
                    700: '#25564c',
                    800: '#21463e',
                    900: '#1e3b35',
                    950: '#0d221e',
                },
                // Deep Slate Navy / Midnight Teal (For dark cards and hero banners)
                midnight: {
                    50: '#f4f7f9',
                    100: '#e5ecf0',
                    200: '#cfdce3',
                    300: '#abc2cf',
                    400: '#7fa1b6',
                    500: '#5e849d',
                    600: '#486b84',
                    700: '#3c576d',
                    800: '#163140',
                    900: '#0b1e28', // Signature dark container color
                    950: '#061118',
                },
                // Vibrant Terracotta Coral (The signature CTA & highlight color)
                coral: {
                    50: '#fef5f2',
                    100: '#fee8e3',
                    200: '#fed5cb',
                    300: '#fcb5a5',
                    400: '#f8866e',
                    500: '#f05b3d',
                    600: '#dd4124',
                    700: '#ba321a',
                    800: '#992c19',
                    900: '#7e2a1b',
                    950: '#441109',
                },
                accent: {
                    50: '#fef5f2',
                    100: '#fee8e3',
                    200: '#fed5cb',
                    300: '#fcb5a5',
                    400: '#f8866e',
                    500: '#f05b3d', // Terracotta coral
                    600: '#dd4124',
                    700: '#ba321a',
                    800: '#992c19',
                    900: '#7e2a1b',
                },
            },
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'Inter', 'system-ui', '-apple-system', 'sans-serif'],
            },
            borderRadius: {
                '2xl': '1rem',
                '3xl': '1.5rem',
                '4xl': '2rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
