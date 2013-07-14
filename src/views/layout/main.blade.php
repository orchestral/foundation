<!DOCTYPE html>
<html lang="en">
	<head>
		@include('orchestra/foundation::layout.header')
	</head>
	<body>
		@include('orchestra/foundation::layout.navigation')
		<?php Orchestra\Support\Facades\Site::set('header::class', 'main-header'); ?>
		@include('orchestra/foundation::layout.widgets.header')
		<section class="container main">
			@include('orchestra/foundation::layout.widgets.messages')
			@yield('content')
		</section>
		@include('orchestra/foundation::layout.footer')
	</body>
</html>
