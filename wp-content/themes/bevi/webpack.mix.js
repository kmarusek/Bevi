/* eslint-disable import/no-extraneous-dependencies */
const mix = require('laravel-mix');
const path = require('path');
const tailwindcss = require('tailwindcss')
require('laravel-mix-eslint');
require('dotenv').config();

const env = process.env.NODE_ENV;

mix.browserSync({
  proxy: 'bevi.test',
  open: true,
});

// this is set to resolve the way WP Engine organises the file structure
if (env !== 'production') {
  mix.setResourceRoot('/app/themes/bevi/dist');
} else {
  mix.setResourceRoot('/wp-content/themes/bevi/dist');
}

mix.setPublicPath('./dist')
  .alias({
    '~': path.resolve(__dirname, 'resources'),
  })
  .js('resources/assets/scripts/main.js', 'dist/scripts')
  .vue()
  //.eslint()
  .options({
    postCss: [ tailwindcss('resources/assets/styles/tailwind.config.js') ],
  })
  .sass('resources/assets/styles/main.scss', 'dist/styles')
  .copyDirectory('resources/assets/fonts', 'dist/fonts')
  .copyDirectory('resources/assets/images', 'dist/images');

if (mix.inProduction()) {
  mix.version();
}
