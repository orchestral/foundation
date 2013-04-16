<footer>
	<div class="container{{ (Orchestra\Site::has('layout::fixed') ? '' : '-fluid') }}">
		<hr>
		<p>&copy; 2012 Orchestra Platform</p>
	</div>
</footer>

<?php 

Basset::collection('orchestra/foundation/footer', function ($collection) {
	
	$collection->directory('packages/orchestra/foundation', function ($asset)
	{
		$asset->add('vendor/select2/select2.css');
		$asset->add('vendor/delta/theme/jquery-ui.css');
		$asset->add('vendor/bootstrap/bootstrap.min.js');
		$asset->add('js/script.min.js');
		$asset->add('vendor/select2/select2.min.js');

		// Add jQuery-UI Library with Delta theme.
		$asset->add('vendor/jquery.ui.js');
		$asset->add('vendor/delta/js/jquery-ui.toggleSwitch.js');
	});
}); ?>

{{ Basset::show('orchestra/foundation/footer.css') }}
{{ Basset::show('orchestra/foundation/footer.js') }}

@placeholder("orchestra.layout: footer")
