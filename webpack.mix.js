const mix = require("laravel-mix");
require("laravel-mix-purgecss");

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

 mix.js("resources/js/app.js", "public/js")
 .copy("resources/img", "public/img")
 .sass("resources/sass/app.scss", "public/css")
 .sass("resources/sass/login.scss", "public/css")
 /** Admin */
 .scripts(["resources/js/company.js"], "public/js/company.js")
 .scripts(["resources/js/address.js"], "public/js/address.js")
 .scripts(["resources/js/phone.js"], "public/js/phone.js")
 .scripts(["resources/js/snow.js"], "public/js/snow.js")
 .scripts(
     ["resources/js/tools-observations.js"],
     "public/js/tools-observations.js"
 )
 .scripts(["resources/js/tools-files.js"], "public/js/tools-files.js")
 .scripts(
     ["resources/js/operations-files.js"],
     "public/js/operations-files.js"
 )
 .scripts(["resources/js/kanban.js"], "public/js/kanban.js")
 .scripts(["resources/js/kanbanActions.js"], "public/js/kanbanActions.js")
 .scripts(
     ["resources/js/document-person.js"],
     "public/js/document-person.js"
 )
 .scripts(["resources/js/chat.js"], "public/js/chat.js")
 .options({
     processCssUrls: false,
 })
 .sourceMaps()
 .purgeCss();
