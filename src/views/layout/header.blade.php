<meta charset="utf-8">
{{ Html::title() }}
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<meta name="description" content="">
<meta name="author" content="">

<!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
<!--[if lt IE 9]>
  <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Le styles -->
<?php

Basset::collection('orchestra/foundation', function ($collection) {

	$collection->directory('packages/orchestra/foundation', function ($asset)
	{
		$asset->add('js/underscore.min.js');
		$asset->add('js/jquery.min.js');
		$asset->add('js/javie.min.js');

		$asset->add('vendor/bootstrap/bootstrap.min.css');
		$asset->add('vendor/bootstrap/bootstrap-responsive.min.css');
		$asset->add('css/style.css');
	});
}); ?>

{{ Basset::show('orchestra/foundation.css') }}
{{ Basset::show('orchestra/foundation.js') }}

@placeholder("orchestra.layout: header")
