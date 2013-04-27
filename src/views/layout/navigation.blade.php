{{-- Define navbar attributes --}}
<?php $navbar = new Illuminate\Support\Fluent(array(
	'id'         => 'main',
	'title'      => memorize('site.name', 'Orchestra'),
	'url'        => handles('orchestra/foundation::/'),
	'attributes' => array('class' => 'navbar-fixed-top'),
	'menu'       => View::make('orchestra/foundation::layout.widgets.menu', array('menu' => Orchestra\App::menu('orchestra'))),
	'subMenu'    => View::make('orchestra/foundation::layout.widgets.usernav'),
)); ?>

{{ Orchestra\Decorator::navbar($navbar) }}

@if ( ! Auth::check())

<script>
jQuery(function ($) {
	$('a[rel="user-menu"]').on('click', function (e) {
		e.preventDefault();
		
		window.location.href = "{{ handles('orchestra/foundation::login') }}";

		return false;
	});
});
</script>

@endif

<br>
