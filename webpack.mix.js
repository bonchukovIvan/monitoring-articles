let mix = require('laravel-mix');

mix.sass('assets/css/src/app.scss', 'assets/css')
.combine(['assets/css/reset.css', 'assets/css/app.css'], 'assets/css/style.css')
.minify(['assets/css/style.css']);