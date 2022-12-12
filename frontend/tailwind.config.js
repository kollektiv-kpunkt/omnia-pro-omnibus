module.exports = {
    content: require("fast-glob").sync(["./**/*.html", "*.html", "./**/*.md", "*.md", "./**/*.php", "*.php"]),
    safelist: ["mix-blend-difference", "bg-black", "bg-opacity-50", "opacity-100", "backdrop-filter", "backdrop-blur-sm", "opacity-0", "transition-opacity", "duration-500"],
    theme: {
        extend: {
            colors: {
                background: "#000000",
                accent: "#CC0000"
            },
        },
    },
    plugins: [],
};