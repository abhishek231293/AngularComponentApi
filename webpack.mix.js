let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/assets/js/app.js', 'public/js');
// mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.styles([
    'resources/assets/vendor/css/bootstrap.css',
    'resources/assets/vendor/font-awesome/css/font-awesome.css',
    'resources/assets/vendor/font-awesome/css/font-awesome.css',
    'resources/assets/vendor/css/animate.css',
    'resources/assets/vendor/css/style.css',
    ], 'public/css/all.css');
mix.scripts([
    'resources/assets/vendor/js/jquery-2.1.1.js',
    'resources/assets/vendor/js/bootstrap.min.js',
    'resources/assets/vendor/plugins/metisMenu/jquery.metisMenu.js',
    'resources/assets/vendor/plugins/slimscroll/jquery.slimscroll.min.js',
    'resources/assets/vendor/js/inspinia.js',
    'resources/assets/vendor/js/plugins/pace/pace.min.js',
    'resources/assets/vendor/plugins/iCheck/icheck.min.js',
    ], 'public/js/app.js');
