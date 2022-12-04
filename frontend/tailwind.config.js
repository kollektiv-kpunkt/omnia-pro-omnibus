module.exports = {
  content: require("fast-glob").sync(["./**/*.html", "*.html"]),
  safelist: ["opacity-100", "bg-black", "bg-white", "opacity-30"],
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