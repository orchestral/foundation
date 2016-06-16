var dir, elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

dir = require('./paths.js')

elixir.config.js.browserify.transformers.push({
  name: 'vueify'
})

elixir.config.sourcemaps = false

elixir(function(mix) {
  mix.copy(dir.vendor+'/font-awesome/fonts', dir.asset.font)
    .copy(dir.vendor+'/select2/select2-spinner.gif', dir.asset.css)
    .copy(dir.vendor+'/select2/select2.png', dir.asset.css)
    .copy(dir.vendor+'/select2/select2x2.png', dir.asset.css)
    .copy(dir.vendor+'/html5shiv/dist/html5shiv.min.js', dir.asset.js+'/html5shiv.js')
    .copy(dir.build.vendor+'/delta/theme/images', dir.asset.css+'/images')
    .copy(dir.build.img, dir.asset.img)

  mix.less('orchestra.less', dir.asset.css+'/orchestra.css', {
    paths: [dir.vendor]
  })

  mix.browserify('orchestra.js', dir.asset.js+'/orchestra.js', dir.js)

  mix.styles([
    'select2/select2.css',
    'select2/select2-bootstrap.css',
    'perfect-scrollbar/css/perfect-scrollbar.css',
    dir.build.vendor+'/delta/theme/jquery-ui.css'
  ], dir.asset.css+'/vendor.css', dir.vendor)

  mix.scripts([
    'jquery/jquery.min.js',
    'underscore/underscore-min.js',
    'javie/dist/javie.min.js',
    'bootstrap/dist/js/bootstrap.min.js',
    'select2/select2.min.js',
    'mousetrap/mousetrap.min.js',
    dir.build.vendor+'/jquery.ui/jquery.ui.js',
    dir.build.vendor+'/delta/js/jquery-ui.toggleSwitch.js',
    'perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'
  ], dir.asset.js+'/vendor.js', dir.vendor)
});
