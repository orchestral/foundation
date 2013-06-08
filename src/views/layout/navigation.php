<?php 

use Illuminate\Support\Fluent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Orchestra\Support\Facades\App;

$navbar = new Fluent(array(
	'id'         => 'main',
	'title'      => memorize('site.name', 'Orchestra'),
	'url'        => handles('orchestra/foundation::/'),
	'attributes' => array('class' => 'navbar-fixed-top'),
	'menu'       => View::make('orchestra/foundation::layout.widgets.menu', array('menu' => App::menu('orchestra'))),
	'subMenu'    => View::make('orchestra/foundation::layout.widgets.usernav'),
)); ?>

@decorator('navbar', $navbar)

<?php if ( ! Auth::check()) : ?>

<script>
jQuery(function ($) {
	$('a[rel="user-menu"]').on('click', function (e) {
		e.preventDefault();
		
		window.location.href = "<?php echo handles('orchestra/foundation::login'); ?>";

		return false;
	});
});
</script>

<?php endif; ?>

<br>
