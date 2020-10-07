module.exports = {
  root: true,
  env: {
    browser: true,
    node: true
  },
  parserOptions: {
    parser: 'babel-eslint'
  },
  extends: [
    'airbnb-base',
    'plugin:vue/strongly-recommended',
  ],
  settings: {
    'import/core-modules': ['vue', 'vuex', 'axios'],
  },
  rules: {
    'nuxt/no-cjs-in-config': 'off',
    quotes: ['error', 'single'],
    'max-len': ['error', { code: 300, ignoreStrings: true, ignoreUrls: true }],
    'import/no-unresolved': 0,
    'linebreak-style': 0,
    'comma-dangle': 1,
    'import/prefer-default-export': 0,
    'no-unused-expressions': ['error', { allowTernary: true }],
    'no-underscore-dangle': 0,
    'no-param-reassign': 0,
    'object-curly-newline': ['error', { ObjectPattern: 'never' }],
    'vue/html-closing-bracket-newline': ['error', {
      singleline: 'never',
      multiline: 'always'
    }],
    'no-trailing-spaces': ['error', {
      skipBlankLines: true
    }],
    'vue/script-indent': ['error', 2, { 'baseIndent': 1 }],
    'template-curly-spacing': ['error', 'always'],
    'import/extensions': ['error', 'never'],
    'vue/require-default-prop': 0,
  },
  'overrides': [
    {
      'files': ['*.vue'],
      'rules': {
        'indent': 'off'
      }
    }
  ]
};