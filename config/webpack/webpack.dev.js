const { merge } = require('webpack-merge');
const baseConfig = require('./webpack.base.js');
const SVGSpritemapPlugin = require('svg-spritemap-webpack-plugin');

const devConfig = {
  mode: 'development',
  devtool: 'source-map',
  plugins: [
    new SVGSpritemapPlugin(
      'src/images/**/*.svg',
      {
        output: {
          filename: 'main.svg',
        },
        sprite: {
          prefix: 'u-icon-',
        }
      }
    ),
  ]
};

module.exports = merge(
  baseConfig,
  devConfig
);