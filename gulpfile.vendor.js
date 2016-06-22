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

elixir.config.sourcemaps = false

elixir(function(mix) {
  mix.copy(dir.vendor+'/font-awesome/fonts', dir.build.font)
    .copy(dir.vendor+'/select2/select2-spinner.gif', dir.build.css)
    .copy(dir.vendor+'/select2/select2.png', dir.build.css)
    .copy(dir.vendor+'/select2/select2x2.png', dir.build.css)
    .copy(dir.vendor+'/html5shiv/dist/html5shiv.min.js', dir.build.js+'/html5shiv.js')

  mix.styles([
    'select2/select2.css',
    'select2/select2-bootstrap.css',
    'perfect-scrollbar/css/perfect-scrollbar.css',
    dir.build.vendor+'/delta/theme/jquery-ui.css'
  ], dir.build.vendor+'/vendor.css', dir.vendor)

  mix.scripts([
    'jquery/jquery.min.js',
    'underscore/underscore-min.js',
    'moment/min/moment.min.js',
    'javie/dist/javie.min.js',
    'bootstrap/dist/js/bootstrap.min.js',
    'select2/select2.min.js',
    'mousetrap/mousetrap.min.js',
    'clipboard/dist/clipboard.min.js',
    'Chart.js/dist/Chart.min.js',
    dir.build.vendor+'/jquery.ui/jquery.ui.js',
    dir.build.vendor+'/delta/js/jquery-ui.toggleSwitch.js',
    'perfect-scrollbar/js/perfect-scrollbar.jquery.min.js'
  ], dir.build.vendor+'/vendor.js', dir.vendor)
});
