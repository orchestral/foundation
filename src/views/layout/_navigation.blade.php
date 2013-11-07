<?php

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;

$navbar = new Fluent(array(
	'id'         => 'main',
	'title'      => memorize('site.name', 'Orchestra'),
	'url'        => handles('orchestra::/'),
	'attributes' => array('class' => 'navbar-fixed-top navbar-inverse'),
	'left'       => View::make('orchestra/foundation::components.menu', array('menu' => App::menu('orchestra'))),
	'right'      => View::make('orchestra/foundation::components.usernav'),
)); ?>

@decorator('navbar', $navbar)

<?php if ( ! Auth::check()) : ?>

<script>
jQuery(function ($) {
	$('a[rel="user-menu"]').on('click', function (e) {
		e.preventDefault();

		window.location.href = "<?php echo handles('orchestra::login'); ?>";

		return false;
	});
});
</script>

<?php endif; ?>

<br>
