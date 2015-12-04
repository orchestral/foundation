<meta charset="utf-8">
@title()
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ memorize('site.description', 'Orchestra Platform') }}">
<meta name="author" content="{{ memorize('site.author', 'Orchestra Platform') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="{!! asset('packages/orchestra/foundation/components/html5shiv/html5shiv.min.js') !!}"></script>
<![endif]-->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700,300,500" rel="stylesheet" type="text/css">

#{{ $asset = app('orchestra.asset')->container('orchestra/foundation::header') }}
#{{ $asset->style('select2', 'packages/orchestra/foundation/components/select2/select2.css') }}
#{{ $asset->style('jquery-ui', 'packages/orchestra/foundation/vendor/delta/theme/jquery-ui.css') }}
#{{ $asset->style('bootstrap', 'packages/orchestra/foundation/vendor/bootstrap/css/bootstrap.min.css') }}
#{{ $asset->style('font-awesome', 'packages/orchestra/foundation/components/font-awesome/css/font-awesome.min.css', ['bootstrap']) }}
#{{ $asset->style('orchestra', 'packages/orchestra/foundation/css/orchestra.css', ['bootstrap', 'select2']) }}
#{{ $asset->script('vue', 'packages/orchestra/foundation/components/vue/vue.min.js') }}
#{{ $asset->script('underscore', 'packages/orchestra/foundation/components/underscore/underscore.js') }}
#{{ $asset->script('jquery', 'packages/orchestra/foundation/components/jquery/jquery.min.js') }}
#{{ $asset->script('javie', 'packages/orchestra/foundation/components/javie/javie.min.js', ['jquery', 'underscore']) }}

{!! $asset->styles() !!}
{!! $asset->scripts() !!}

<script>
Javie.detectEnvironment(function () {
  return "{!! app('env') !!}";
});
</script>

@placeholder("orchestra.layout: header")
@stack('orchestra.header')
