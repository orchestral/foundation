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
  mix.copy(dir.build.js+'/html5shiv.js', dir.asset.js+'/html5shiv.js')
    .copy(dir.build.css, dir.asset.css)
    .copy(dir.build.font, dir.asset.font)
    .copy(dir.build.img, dir.asset.img)
    .copy(dir.build.vendor+'/delta/theme/images', dir.asset.css+'/images')

  mix.less('orchestra.less', dir.asset.css+'/orchestra.css', {
    paths: [dir.vendor]
  })

  mix.browserify('orchestra.js', dir.asset.js+'/orchestra.js', dir.js)

  mix.styles([
    'vendor.css'
  ], dir.asset.css+'/vendor.css', dir.build.vendor)

  mix.scripts([
    'vendor.js'
  ], dir.asset.js+'/vendor.js', dir.build.vendor)
});
