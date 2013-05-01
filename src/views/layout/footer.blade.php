<footer>
	<div class="container">
		<hr>
		<p>&copy; 2012 Orchestra Platform</p>
	</div>
</footer>

<link href="{{ asset('packages/orchestra/foundation/vendor/select2/select2.css') }}" media="all" type="text/css" rel="stylesheet">
<link href="{{ asset('packages/orchestra/foundation/vendor/delta/theme/jquery-ui.css') }}" media="all" type="text/css" rel="stylesheet">
<script src="{{ asset('packages/orchestra/foundation/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/js/script.min.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/vendor/select2/select2.min.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/vendor/jquery.ui.js') }}"></script>
<script src="{{ asset('packages/orchestra/foundation/vendor/delta/js/jquery-ui.toggleSwitch.js') }}"></script>

{{ basset_stylesheet('orchestra-foundation.footer') }}
{{ basset_javascript('orchestra-foundation.footer') }}

@placeholder("orchestra.layout: footer")
