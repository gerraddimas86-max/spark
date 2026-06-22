/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/Http/Controllers/**/*.php",
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    "Segoe UI",
                    "Poppins",
                    "system-ui",
                    "-apple-system",
                    "sans-serif",
                ],
                battlesbridge: ["Battlesbridge", "sans-serif"],
            },
            letterSpacing: {
                widestest: "0.3em",
            },
        },
    },
};
