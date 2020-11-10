module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  theme: {
    fontFamily: {
      body: ['Italian Plate', 'sans-serif'],
      space: ['Space Grotesk', 'sans-serif  '],
    },
    opacity: {
      0: '0',
      10: '.1',
      20: '.2',
      30: '.3',
      40: '.4',
      50: '.5',
      60: '.6',
      70: '.7',
      80: '.8',
      90: '.9',
      100: '1',
    },
    stroke: (theme) => ({
      red: theme('colors.red.500'),
      green: theme('colors.green.600'),
      blue: theme('colors.blue'),
      white: theme('colors.white'),
    }),
    extend: {
      fontSize: {
        '4.5xl': '2.8rem',
        '5xl': '3.2rem',
        '6xl': '4.5rem',
      },
      spacing: {
        28: '7.75rem',
        36: '9rem',
      },
      colors: {
        primary: '#246EFF',
        secondary: '#404040',
        green: {
          100: '#E2F1CF',
          200: '#DBFFA8',
          300: '#C7E4A0',
          default: '#73BC25',
          500: '#1A8C25',
          600: '#105128',
        },
        blue: {
          100: '#D4E4F8',
          200: '#A8C3E4',
          default: '#246EFF',
          500: '#174CBB',
          600: '#00223E',
        },
        purple: {
          200: '#E9D9E8',
          default: '#4B273A',
        },
        red: {
          200: '#F7DBDA',
          default: '#DC3A32',
          500: '#AB2328',
        },
        orange: {
          100: '#FCEDDA',
          200: '#FFD7B1',
          default: '#FFA24E',
          500: '#FF7800',
          600: '#9B552B',
        },
        yellow: {
          100: '#FCF5CB',
          200: '#FCF1A7',
          default: '#FFD864',
          500: '#FFC002',
          600: '#B28600',
        },
        gray: {
          100: '#FAFAFA',
          200: '#F2F2F2',
          300: '#E5E5E5',
          400: '#CCCCCC',
          500: '#F5F5F5',
          550: '#333333',
          default: '#999999',
          700: '#666666',
        },
      },
      width: {
        30: '30%',
      },
      height: {
        70: '20rem',
      },
    },
    container: {
      center: true,
      padding: {
        default: '1rem',
      },
    },
  },
  variants: {},
  plugins: [],
  purge: {
    enabled: process.env.NODE_ENV === 'production',
    content: [
      './resources/assets/styles/**/*.scss',
      './resources/components/**/**/*.vue',
      './resources/views/**/*.blade.php',
    ],
  },
};
