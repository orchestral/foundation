<!DOCTYPE html>
<html lang="en">
	<head>
		@include('orchestra/foundation::layout._header')
	</head>
	<body>
		@include('orchestra/foundation::layout._navigation')
		<?php Orchestra\Support\Facades\Site::set('header::class', 'main-header'); ?>
		@include('orchestra/foundation::layout.widgets.header')
		<section class="container main">
			@include('orchestra/foundation::layout.widgets.messages')
			@yield('content')
		</section>
		@include('orchestra/foundation::layout._footer')
	</body>
</html>
