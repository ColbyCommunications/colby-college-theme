const { merge } = require('webpack-merge');
const OptimizeCssAssetsPlugin = require('optimize-css-assets-webpack-plugin');
const TerserPlugin = require('terser-webpack-plugin');
const baseConfig = require('./webpack.base.js');

const prodConfig = {
  // mode: 'production',
  // optimization: {
  //   minimize: true,
  //   minimizer: [
  //     new TerserPlugin(),
  //   ],
  // },
  // plugins: [
  //   new OptimizeCssAssetsPlugin(),
  // ]
};

module.exports = merge(
    baseConfig
    // prodConfig
);
