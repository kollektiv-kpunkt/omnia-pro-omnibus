module.exports = {
  content: require("fast-glob").sync(["./**/*.html", "*.html"]),
  safelist: ["mix-blend-difference"],
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