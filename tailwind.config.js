const defaultColors = require('tailwindcss/colors')

let colors = {
	...defaultColors,
    'grey-light': '#F5F6F9',
}

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    colors: colors,
    shadows: {
      default: '0 0 5px 0 rgba(0, 0, 0, 0.08)'
    },
    extend: {},
  },
  plugins: [],
}
