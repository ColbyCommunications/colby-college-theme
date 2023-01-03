let mix = require('laravel-mix');

require('mix-tailwindcss');

mix.js('src/index.js', 'dist/scripts.js').vue();
mix.sass('src/styles/styles.scss', 'dist/styles/scripts.css').tailwind('./tailwind.config.js');
