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

dir = {
  asset: {
    css: 'resources/public/css',
    font: 'resources/public/fonts',
    img: 'resources/public/img',
    js: 'resources/public/js'
  },
  build: {
    css: 'resources/assets/css',
    js: 'resources/assets/js',
    less: 'resources/assets/less'
  },
  js: 'resources/js',
  vendor: 'vendor/bower_components'
}

elixir.config.js.browserify.transformers.push({
  name: 'vueify'
})

elixir.config.sourcemaps = false

elixir(function(mix) {
  mix.copy(dir.vendor+'/font-awesome/fonts', dir.asset.font)
    .copy(dir.vendor+'/html5shiv/dist/html5shiv.min.js', dir.asset.js+'/html5shiv.js')

  mix.less('orchestra.less', dir.asset.css+'/orchestra.css', {
    paths: [dir.vendor]
  })

  mix.browserify('orchestra.js', dir.asset.js+'/orchestra.js', dir.js)

  mix.styles([
    'perfect-scrollbar/css/perfect-scrollbar.css'
  ], dir.asset.css+'/vendor.css', dir.vendor)

  mix.scripts([
    'jquery/jquery.min.js',
    'underscore/underscore-min.js',
    'javie/dist/javie.min.js',
    'bootstrap/dist/js/bootstrap.min.js',
    'mousetrap/mousetrap.min.js',
    'perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'
  ], dir.asset.js+'/vendor.js', dir.vendor)
});
