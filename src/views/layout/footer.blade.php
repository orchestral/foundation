<?php

use Orchestra\Support\Facades\Asset; ?>

<footer>
	<div class="container">
		<hr>
		<p>&copy; 2012 Orchestra Platform</p>
	</div>
</footer>

<?php

$asset = Asset::container('orchestra/foundation::footer');

$asset->script('bootstrap', 'packages/orchestra/foundation/vendor/bootstrap/js/bootstrap.min.js');
$asset->script('jquery-ui', 'packages/orchestra/foundation/vendor/jquery.ui.js');
$asset->script('orchestra', 'packages/orchestra/foundation/js/script.min.js', array('bootstrap', 'jquery-ui'));
$asset->script('jui-toggleSwitch', 'packages/orchestra/foundation/vendor/delta/js/jquery-ui.toggleSwitch.js', array('jquery-ui'));
$asset->script('select2', 'packages/orchestra/foundation/vendor/select2/select2.min.js');

echo $asset->styles();
echo $asset->scripts(); ?>

@placeholder("orchestra.layout: footer")
