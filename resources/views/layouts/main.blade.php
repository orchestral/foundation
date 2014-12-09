<!DOCTYPE html>
<html lang="en">
	<head>
		@include('orchestra/foundation::layouts._header')
	</head>
	<body>
		@include('orchestra/foundation::layouts._navigation')
		<?php set_meta('header::class', 'main-header') ?>
		@include('orchestra/foundation::components.header')
		<section class="container main">
			@include('orchestra/foundation::components.messages')
			@yield('content')
		</section>
		@include('orchestra/foundation::layouts._footer')
	</body>
</html>
