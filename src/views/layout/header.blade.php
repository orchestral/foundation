<meta charset="utf-8">
{{ Html::title() }}
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Le styles -->
<link href="{{ asset('packages/orchestra/foundation/vendor/bootstrap/css/bootstrap.min.css') }}" media="all" type="text/css" rel="stylesheet">
<link href="{{ asset('packages/orchestra/foundation/css/style.css') }}" media="all" type="text/css" rel="stylesheet">
<script src="{{ asset('packages/orchestra/foundation/js/underscore.min.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/js/jquery.min.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/js/javie.min.js') }}"></script>

{{ basset_stylesheet('orchestra-foundation.header') }}
{{ basset_javascript('orchestra-foundation.header') }}

@placeholder("orchestra.layout: header")
