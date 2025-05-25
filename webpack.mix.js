const mix = require('laravel-mix');
const TerserPlugin = require('terser-webpack-plugin');

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

mix.scripts([
    "resources/js/app.js",
    "resources/js/pages/*.js",
], "public/js/app.js", true);

mix.styles([
    "resources/css/app.css",
], "public/css/app.css", true);

if (mix.inProduction()) {
    mix.webpackConfig({
        optimization: {
            minimize: true,
            minimizer: [
                new TerserPlugin({
                    terserOptions: {
                        compress: {
                            drop_console: true,
                        },
                        format: {
                            comments: false,
                        },
                    },
                    extractComments: false,
                }),
            ],
        },
    });
}