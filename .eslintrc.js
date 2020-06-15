module.exports = {
  env: {
    browser: true,
    es6: true,
    node: true
  },
  extends: [
    'eslint:recommended',
    'standard',
    'plugin:vue/essential',
    //'vuetify',
    'plugin:vue/base'
  ],
  globals: {
    Atomics: 'readonly',
    SharedArrayBuffer: 'readonly'
  },
  parser: "vue-eslint-parser",
  parserOptions: {
    parser: 'babel-eslint',
    ecmaVersion: 2018,
    parser: '@typescript-eslint/parser',
    sourceType: 'module'
  },
  plugins: ['vue', '@typescript-eslint'],
  rules: {
    indent: ['error', 2],
    'comma-dangle': ['error', 'never'],
    'comma-spacing': ['error', { before: false, after: true }],
    'comma-style': ['error', 'last'],
    'brace-style': 'error',
    'object-curly-spacing': ['error', 'always', { arraysInObjects: false }],
    'arrow-parens': 0,
    'generator-star-spacing': 0
  }
}
