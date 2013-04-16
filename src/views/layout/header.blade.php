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

Basset::collection('orchestra-backend', function ($collection) {

	$collection->directory('packages/orchestra/foundation/js', function ($asset)
	{
		$asset->add('underscore.min.js');
		$asset->add('jquery.min.js');
		//$collection->add('javie', 'packages/orchestra/foundation/js/javie.min.js', array('underscore'));

		//$collection->add('bootstrap', 'packages/orchestra/foundation/vendor/bootstrap/bootstrap.min.css');
		//$collection->add('bootstrap-responsive', 'packages/orchestra/foundation/vendor/bootstrap/bootstrap-responsive.min.css', array('bootstrap'));
		//$collection->add('orchestra', 'packages/orchestra/foundation/css/style.css', array('bootstrap-responsive'));
		//
	});
}); ?>

{{ Basset::show('orchestra-backend.js') }}

@placeholder("orchestra.layout: header")
