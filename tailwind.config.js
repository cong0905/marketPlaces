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
                sans: ['Geist', ...defaultTheme.fontFamily.sans],
                "price-lg": ["Geist"],
                "headline-sm": ["Geist"],
                "label-md": ["Geist"],
                "headline-lg": ["Geist"],
                "headline-md": ["Geist"],
                "headline-lg-mobile": ["Geist"],
                "display-lg": ["Geist"],
                "body-md": ["Geist"],
                "body-lg": ["Geist"],
                "body-sm": ["Geist"]
            },
            colors: {
                "background": "#f4f4f5", // zinc-100
                "surface": "#ffffff",
                "surface-container-lowest": "#ffffff",
                "surface-container-low": "#fafafa", // zinc-50
                "surface-container": "#f4f4f5", // zinc-100
                "surface-container-high": "#e4e4e7", // zinc-200
                "surface-container-highest": "#d4d4d8", // zinc-300
                "on-background": "#09090b", // zinc-950
                "on-surface": "#09090b", // zinc-950
                "on-surface-variant": "#71717a", // zinc-500 (Steel Secondary)
                "outline": "#a1a1aa", // zinc-400
                "outline-variant": "#e4e4e7", // zinc-200
                "primary": "#F59E0B", // Amber Warmth
                "on-primary": "#ffffff",
                "primary-container": "#fef3c7", // amber-100
                "on-primary-container": "#78350f", // amber-900
                "primary-fixed-dim": "#fbbf24", // amber-400
                "error": "#E11D48", // Deep Rose
                "on-error": "#ffffff",
                "error-container": "#ffe4e6", // rose-100
                "on-error-container": "#881337", // rose-900
                // Default fallbacks for remaining colors
                "secondary": "#09090b",
                "on-secondary": "#ffffff",
                "secondary-container": "#f4f4f5",
                "on-secondary-container": "#09090b",
                "tertiary": "#09090b",
                "on-tertiary": "#ffffff",
            },
            boxShadow: {
                "premium": "0 20px 40px -15px rgba(0,0,0,0.05)",
                "premium-hover": "0 30px 50px -20px rgba(0,0,0,0.08)"
            },
            borderRadius: {
                "premium": "2.5rem"
            },
            spacing: {
                "margin-mobile": "16px",
                "xl": "40px",
                "gutter": "16px",
                "2xl": "64px",
                "base": "4px",
                "sm": "8px",
                "margin-desktop": "32px",
                "xs": "4px",
                "lg": "24px",
                "md": "16px",
                "container-max": "1280px"
            },
            fontSize: {
                "price-lg": ["20px", { "lineHeight": "1", "fontWeight": "700" }],
                "headline-sm": ["20px", { "lineHeight": "1.3", "fontWeight": "600" }],
                "label-md": ["14px", { "lineHeight": "1", "letterSpacing": "0.05em", "fontWeight": "600" }],
                "headline-lg": ["32px", { "lineHeight": "1.2", "fontWeight": "700" }],
                "headline-md": ["24px", { "lineHeight": "1.3", "fontWeight": "600" }],
                "headline-lg-mobile": ["24px", { "lineHeight": "1.2", "fontWeight": "700" }],
                "display-lg": ["48px", { "lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                "body-md": ["16px", { "lineHeight": "1.65", "fontWeight": "400" }],
                "body-lg": ["18px", { "lineHeight": "1.65", "fontWeight": "400" }],
                "body-sm": ["14px", { "lineHeight": "1.65", "fontWeight": "400" }]
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(20px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                scaleUp: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                }
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.6s ease-out forwards',
                'fade-in-up-delay-1': 'fadeInUp 0.6s ease-out 0.1s forwards',
                'fade-in-up-delay-2': 'fadeInUp 0.6s ease-out 0.2s forwards',
                'fade-in-up-delay-3': 'fadeInUp 0.6s ease-out 0.3s forwards',
                'fade-in': 'fadeIn 0.5s ease-out forwards',
                'scale-up': 'scaleUp 0.4s ease-out forwards',
            }
        },
    },

    plugins: [forms],
};
