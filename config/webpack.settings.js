module.exports = {
  entries: {
    // JS File
    'frontend-script': './src/js/frontend.js',
    'frontend-style': './src/postcss/frontend.css',
    'admin-style': './src/postcss/admin.css',
    'woo-frontend-style': './src/postcss/woocommerce.css',
    'woo-frontend-script': './src/js/woocommerce.js',
    cart: './src/js/cart.js',
    checkout: './src/js/checkout.js'
  },
  filename: {
    js: 'js/[name].js',
    css: 'css/[name].css',
    minifiedJs: 'js/[name].min.js',
    minifiedCss: 'css/[name].min.css'
  },
  paths: {
    src: {
      base: './src/',
      css: './src/postcss/',
      js: './src/js/'
    },
    dist: {
      base: './assets',
      clean: ['./css', './js']
    }
  },
  stats: {
    // Copied from `'minimal'`.
    all: false,
    errors: true,
    maxModules: 0,
    modules: true,
    warnings: true,
    // Our additional options.
    assets: true,
    errorDetails: true,
    excludeAssets: /\.(jpe?g|png|gif|svg|woff|woff2)$/i,
    moduleTrace: true,
    performance: true
  },
  ImageminPlugin: {
    test: /\.(jpe?g|png|gif)$/i
  },
  BrowserSyncConfig: {
    host: 'localhost',
    port: 3000,
    watch: true,
    proxy: {
      target: 'http://codetot-shop.test',
      proxyReq: [
        proxyReq => {
          proxyReq.setHeader('X-Codetot-Header', 'development')
        }
      ]
    },
    ignorePaths: '/wp-admin/**',
    open: 'local',
    browser: 'google chrome',
    notify: false,
    files: [
      '**/*.php',
      'assets/js/**/*.js',
      'assets/css/**/*.css',
      'assets/svg/**/*.svg',
      'assets/img/**/*.{jpg,jpeg,png,gif}',
      'assets/fonts/**/*.{eot,ttf,woff,woff2,svg}',
      'src/**/*.css',
      'src/**/*.js'
    ]
  },
  performance: {
    maxAssetSize: 100000
  },
  manifestConfig: {
    basePath: ''
  }
}
