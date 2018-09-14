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
            /* 'vue', */
             //'jquery',
             'jquery-ui',
             'jquery-smooth-scroll',
             'jquery-browserify',
             'jquery-mask-plugin',
             //'bootstrap-sass',
             'bootstrap-switch',
             //'bootstrap-toggle',
             'vue-bootstrap-toggle'
             //'bootstrap-select',
             ])
   .autoload({
        jquery: ['$', 'jQuery', 'jquery'],
        tether: ['window.Tether', 'Tether']
    })
   .sass('resources/assets/sass/app.scss', 'public/css/app.css')
   .styles([
        'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
        'node_modules/jquery-ui/themes/base/base.css',
        'node_modules/bootstrap-toggle/css/bootstrap2-toggle.css',
    ], 'public/css/other.css'); 
   
mix.js('resources/assets/js/aliasmembers.js', 'public/js');
mix.copy('node_modules/bootstrap-sass/assets/fonts/bootstrap', 'public/fonts');