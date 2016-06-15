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
  mix.less('orchestra.less', dir.asset.css+'/orchestra.css', {
    paths: [dir.vendor]
  })

  mix.browserify('orchestra.js', dir.asset.js+'/orchestra.js', dir.js)
});
