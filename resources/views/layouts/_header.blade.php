<meta charset="utf-8">
@title()
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="{{ memorize('site.description', 'Orchestra Platform') }}">
<meta name="author" content="{{ memorize('site.author', 'Orchestra Platform') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="{!! asset('packages/orchestra/foundation/js/html5shiv.js') !!}"></script>
<![endif]-->
<link href="https://fonts.googleapis.com/css?family=Roboto:400,700,300,500" rel="stylesheet" type="text/css">

@php
$asset = app('orchestra.asset')->container('orchestra/foundation::header');
$asset->style('vendor', 'packages/orchestra/foundation/css/vendor.css');
$asset->style('orchestra', 'packages/orchestra/foundation/css/orchestra.css', ['vendor']);
@endphp

{{ $asset }}

@placeholder("orchestra.layout: header")
@stack('orchestra.header')
