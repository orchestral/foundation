<footer>
	<div class="container">
		<hr>
		<p>
			Powered by <a href="http://orchestraplatform.com" target="_blank">Orchestra Platform</a>.
		</p>
	</div>
</footer>

#{{ $asset = app('orchestra.asset')->container('orchestra/foundation::footer') }}
#{{ $asset->script('bootstrap', 'packages/orchestra/foundation/vendor/bootstrap/js/bootstrap.min.js') }}
#{{ $asset->script('jquery-ui', 'packages/orchestra/foundation/vendor/jquery.ui/jquery.ui.js') }}
#{{ $asset->script('orchestra', 'packages/orchestra/foundation/js/orchestra.min.js', array('bootstrap', 'jquery-ui')) }}
#{{ $asset->script('jui-toggleSwitch', 'packages/orchestra/foundation/vendor/delta/js/jquery-ui.toggleSwitch.js', array('jquery-ui')) }}
#{{ $asset->script('select2', 'packages/orchestra/foundation/components/select2/select2.min.js') }}

{!! $asset->styles() !!}
{!! $asset->scripts() !!}

@placeholder('orchestra.layout: footer')

<script>
jQuery(function on_page_ready($) { 'use strict';
  Javie.trigger("orchestra.ready: {!! app('request')->path() !!}");
});
</script>

@stack('orchestra.footer')
