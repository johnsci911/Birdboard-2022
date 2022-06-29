const defaultColors = require('tailwindcss/colors')

let colors = {
	...defaultColors,
	'grey': 'rgba(0, 0, 0, 0.4)',
  'grey-light': '#F5F6F9',
  'blue': '#47cdff',
  'blue-light': '#8ae2fe'
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
  plugins: []
}
