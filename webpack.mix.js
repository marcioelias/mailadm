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

mix.js('resources/assets/js/app.js', 'public/js')
   .extract(['tether',
             'jquery',
             'jquery-ui',
             'jquery-smooth-scroll',
             'jquery-browserify',
             'jquery-mask-plugin',
             //'bootstrap',
             'bootstrap-switch'
             ])
   .autoload({
        jquery: ['$', 'jQuery', 'jquery'],
        tether: ['window.Tether', 'Tether']
    })
   .sass('resources/assets/sass/app.scss', 'public/css/app.css');
   /*.styles([], 'public/css/other.css'); */