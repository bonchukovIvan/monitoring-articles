let mix = require('laravel-mix');

mix.sass('assets/css/src/app.scss', 'assets/css')
.combine(['assets/css/reset.css', 'assets/css/app.css'], 'assets/css/style.css')
.minify(['assets/css/style.css']);

mix.js([
    'assets/js/src/scripts.js',
], 'assets/js/app.js')
.minify('assets/js/app.js', 'assets/js/app.min.js');