/* eslint-disable import/no-extraneous-dependencies */
const mix = require('laravel-mix');
const path = require('path');
require('mix-tailwindcss');
require('laravel-mix-eslint');
require('dotenv').config();

const env = process.env.NODE_ENV;

mix.browserSync({
  proxy: 'bevi.test',
  open: true,
});

if (env !== 'production') {
  mix.setResourceRoot('/app/themes/bevi/dist');
} else {
  mix.setResourceRoot('/wp-content/themes/bevi/dist');
}

mix.setPublicPath('./dist')
  .webpackConfig({
    resolve: {
      alias: {
        '~': path.resolve(__dirname, 'resources'),
      },
    },
  })
  .js('resources/assets/scripts/main.js', 'dist/scripts')
  .eslint()
  .options({
    processCssUrls: false,
  })
  .tailwind('resources/assets/styles/tailwind.config.js')
  .sass('resources/assets/styles/main.scss', 'dist/styles')
  .copyDirectory('resources/assets/fonts', 'dist/fonts')
  .copyDirectory('resources/assets/images', 'dist/images');

if (mix.inProduction()) {
  mix.version();
}