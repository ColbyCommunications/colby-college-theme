const path = require("path");

const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const autoprefixer = require("autoprefixer");
var WebpackNotifierPlugin = require("webpack-notifier");
const SassLintPlugin = require("sass-lint-webpack");
const Webpack = require("webpack");
const { VueLoaderPlugin } = require("vue-loader");

const projectRoot = path.resolve(__dirname, "../..");

module.exports = {
  mode: "development",

  context: projectRoot,

  entry: {
    scripts: ["./src/index.js"],
  },

  output: {
    filename: "[name].js",
    path: path.resolve(projectRoot, "dist"),
  },

  resolve: {
    alias: {
      styles: path.resolve(projectRoot, "src/styles"),
      variables: path.resolve(projectRoot, "src/styles/global/_variables.scss"),
    },
  },

  module: {
    rules: [
      {
        test: /\.vue$/,
        use: [
          {
            loader: "vue-loader",
          },
        ],
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [
          {
            loader: "babel-loader",
          },
        ],
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
          {
            loader: MiniCssExtractPlugin.loader,
          },
          {
            loader: "css-loader", // translates CSS into CommonJS
            options: {
              sourceMap: true,
            },
          },
          {
            loader: "postcss-loader",
            options: {
              postcssOptions: {
                plugins: (config) => [autoprefixer],
              },
            },
          },
          {
            loader: "sass-loader", // compiles Sass to CSS
            options: {
              sourceMap: true,
            },
          },
        ],
      },
      {
        test: /fonts\/.*\.(eot|svg|ttf|woff|woff2|otf)$/,
        type: "asset/resource",
        exclude: /node_modules/,
      },
      {
        test: /images\/.*\.(png|svg|jpg|gif)$/,
        type: "asset/resource",
        exclude: /node_modules/,
      },
      {
        test: /videos\/.*\.(mp4)$/,
        type: "asset/resource",
        exclude: /node_modules/,
      },
    ],
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
      filename: "styles/[name].css",
    }),
    new WebpackNotifierPlugin({
      title: "Theme",
      alwaysNotify: true,
    }),
    new SassLintPlugin(),
    new Webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false,
    }), // to remove warn in browser console: runtime-core.esm-bundler.js:3607 Feature flags __VUE_OPTIONS_API__, __VUE_PROD_DEVTOOLS__ are not explicitly defined...
  ],
};
