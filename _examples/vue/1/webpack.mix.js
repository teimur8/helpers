let mix = require('laravel-mix');
let tailwindcss = require('tailwindcss');

mix.setPublicPath(path.normalize('./'));

//mix.sass('assets/sass/front/app.scss', 'frontend/web/css')
//    .options({
//        processCssUrls: false,
//        postCss: [ tailwindcss('./tailwind.js') ],
//    });



mix.js('assets/vue/front/app.js', 'frontend/web/js');
mix.js('assets/vue/front/user/profile/user_profile.js', 'frontend/web/js');
