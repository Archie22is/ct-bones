const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const postcssMixins = require('postcss-mixins')
const postcssPresetEnv = require('postcss-preset-env')
const devMode = process.env.NODE_ENV !== 'production'

module.exports = {
  entry: {
		frontend: path.resolve(process.cwd(), './src/frontend.js'),
		editor: path.resolve(process.cwd(), './src/editor.js'),
		cart: path.resolve(process.cwd(), './src/cart.js'),
		checkout: path.resolve(process.cwd(), './src/checkout.js'),
		'legacy-admin': path.resolve(process.cwd(), './src/legacy-admin.js'),
		'legacy-frontend': path.resolve(process.cwd(), './src/legacy-frontend.js'),
		'legacy-woocommerce': path.resolve(process.cwd(), './src/legacy-woocommerce.js')
  },
  output: {
    path: path.resolve(__dirname, 'assets'),
    filename: !devMode ? './js/[name].min.js' : './js/[name].js',
		clean: true
  },
  watch: devMode,
  devtool: 'eval-cheap-source-map',
	resolve: {
    alias: {
      lib: path.resolve(process.cwd(), './src/js/lib/')
    },
    extensions: ['.js', '.jsx']
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules|bower_components)/,
        resolve: {
          extensions: ['.js', '.jsx']
        },
        use: {
          loader: 'babel-loader'
        }
      },
      {
        test: /\.(p|c)ss$/,
        use: [
          devMode ? 'style-loader' : MiniCssExtractPlugin.loader,
          devMode
            ? {
                loader: 'css-loader',
                options: {
                  sourceMap: true
                }
              }
            : 'css-loader',
          {
            loader: 'postcss-loader',
            options: {
              postcssOptions: {
                plugins: [
                  require('autoprefixer'),
                  require('postcss-import'),
									postcssMixins({
										mixinsDir: path.join(__dirname, 'src/postcss/mixins')
									}),
                  postcssPresetEnv({
										importFrom: path.join(__dirname, 'src/postcss/variables.css'),
										exportTo: 'variables.css',
                    stage: 1,
                    features: {
                      'custom-media-queries': true,
                      'nesting-rules': true
                    }
                  })
                ]
              }
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: devMode ? './css/[name].css' : './css/[name].min.css'
    }),
		new CopyWebpackPlugin({
      patterns: [
        {
          from: '**/vendors/*.js',
          to: '[path][name].min[ext]',
          context: path.resolve(process.cwd(), 'src/js/')
        },
        {
          from: '**/img/*.{jpg,jpeg,png,gif}',
          to: '[path][name][ext]',
          context: path.resolve(process.cwd(), 'src/')
        },
        {
          from: '**/svg/*.svg',
          to: '[path][name][ext]',
          context: path.resolve(process.cwd(), 'src/')
        }
      ]
    }),
    // Lint CSS.
    new StyleLintPlugin({
			context: path.resolve(process.cwd(), './src/postcss/'),
			files: '**/*.css'
		}),
    new BrowserSyncPlugin({
      host: 'localhost',
      port: 3000,
      watch: true,
      proxy: {
        target: 'http://kweb.test/',
        proxyReq: [
          proxyReq => {
            proxyReq.setHeader(
              'X-Codetot-Parent-Theme-Header',
              process.env.NODE_ENV
            )
          }
        ]
      }
    })
  ],
  externals: {
    jQuery: 'jQuery'
  }
}
