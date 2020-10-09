module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: [],
  theme: {
    fontFamily: {
      body: ['Italian Plate', 'sans-serif'],
      space: ['Space Grotesk', 'sans-serif  '],
    },
    opacity: {
      '0': '0',
      '10': '.1',
      '20': '.2',
      '30': '.3',
      '40': '.4',
      '50': '.5',
      '60': '.6',
      '70': '.7',
      '80': '.8',
      '90': '.9',
      '100': '1',
    },
    extend: {
      colors: {
        primary: '#246EFF',
        secondary: '#404040',
        green: {
          100: '#DBFFA8',
          200: '#C7E4A0',
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
          600: '#00223E',
          700: '#9B552B',
        },
        yellow: {
          100: '#FCF5CB',
          200: '#FCF1A7',
          default: '#FFD864',
          500: '#FFC002',
          600: '#B28600',
        },
        grey: {
          100: '#FAFAFA',
          200: '#F2F2F2',
          300: '#E5E5E5',
          400: '#CCCCCC',
          default: '#999999',
          700: '#666666',
          800: '#B28600',
        }
      },
      fontSize: {
        base: '0.95rem',
        '3xl': '1.75rem',
      },
    },
  },
  variants: {},
  plugins: [],
}
