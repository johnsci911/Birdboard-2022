const defaultColors = require('tailwindcss/colors')

module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    colors: {
      ...defaultColors,
      'grey'       : 'rgba(0, 0, 0, 0.4)',
      'grey-light' : '#F5F6F9',
      'blue'       : '#47cdff',
      'blue-light' : '#8ae2fe',
    },
    backgroundColor: {
      'page' : 'var(--page-background-color)',
      'card' : 'var(--card-background-color)',
      'button' : 'var(--button-background-color)',
      'header' : 'var(--header-background-color)',
    },
    textColor: {
      ...defaultColors,
      'default' : 'var(--text-default-color)',
    },
    shadows: {
      default: '0 0 5px 0 rgba(0, 0, 0, 0.08)'
    },
    extend: {},
  },
  plugins: []
}
