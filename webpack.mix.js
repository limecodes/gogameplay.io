const mix = require('laravel-mix');
const S3Plugin = require('webpack-s3-plugin');

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

mix.react('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copyDirectory('resources/images', 'public/images');

if (mix.inProduction()) {
    mix.version();

    mix.webpackConfig({
        plugins: [
            new S3Plugin({
                include: /.*\.(css|js|png|gif)/,
                s3Options: {
                    accessKeyId: process.env.MIX_AWS_ACCESS_KEY_ID,
                    secretAccessKey: process.env.MIX_AWS_SECRET_ACCESS_KEY,
                    region: process.env.MIX_AWS_DEFAULT_REGION
                },
                s3UploadOptions: {
                    Bucket: process.env.MIX_AWS_BUCKET
                },
                directory: 'public'
            })
        ]
    });
}
