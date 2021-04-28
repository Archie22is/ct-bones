module.exports = ({ file, options, env }) => ({
  /* eslint-disable-line */
  plugins: {
    'postcss-import': {},
    'postcss-mixins': {
      mixinsDir: './src/postcss/mixins'
    },
    'postcss-preset-env': {
      importFrom: './src/postcss/variables.css',
      exportTo: 'variables.css',
      stage: 1,
      browsers: 'last 2 versions',
      features: {
        'custom-media-queries': true
      },
      autoprefixer: {
        grid: true
      }
    },
    // Minify style on production using cssano.
    cssnano:
      env === 'production'
        ? {
            preset: [
              'default',
              {
                autoprefixer: false,
                calc: {
                  precision: 8
                },
                convertValues: true,
                discardComments: {
                  removeAll: true
                },
                mergeLonghand: false,
                zindex: false
              }
            ]
          }
        : false
  }
})
