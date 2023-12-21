/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./public/assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
    },
    extend: {
      fontFamily: {
        'sans': ['Inter', 'sans-serif'],
      },
      fontSize: {
        '3xs': '0.5rem',
        '2xs': '0.625rem',
        'xs': '0.75rem',
        'sm': '0.875rem',
        'base': '1rem',
        'lg': '1.125rem',
        'xl': '1.25rem',
        '2xl': '1.5rem',
        '3xl': '2rem',
        '4xl': '2.5rem',
        '5xl': '3rem',
      },
      colors: {
        'transparent': 'transparent',
        'current': 'currentColor',
        'primary': '#009DE0',
        'black': '#000000',
        'white': '#FFFFFF',
        'gray': {
          '100': '#F7F8F9',
          '200': '#E5E5E5',
          '300': '#C4C4C4',
          '400': '#9B9B9B',
          '500': '#727272',
          '600': '#4D4D4D',
          '700': '#333333',
          '800': '#1E1E1E',
          '900': '#141414',
        },

        'status-red': '#FF1717',
        'status-orange': '#FF8D14',
        'status-yellow': '#F7DF00',
        'status-green': '#00F541',
        'status-blue': '#14D8FF',
        'status-indigo': '#1C6CFF',
        'status-purple': '#9742FF',
        'status-pink': '#FF14E0',
        'status-gray': '#9B9B9B',
      },
    },
  },
  plugins: [],
}

