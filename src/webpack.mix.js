const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/image-preview.js', 'public/js')
    .js('resources/js/category-brand-handler.js', 'public/js')
    .js('resources/js/purchase.js', 'public/js')
    .js('resources/js/avatar-preview.js', 'public/js')
    .js('resources/js/edit-image-preview.js', 'public/js')
    .js('resources/js/edit-category-brand-handler.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);
