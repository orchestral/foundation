<!DOCTYPE html>
<html lang="en">
	<head>
		@include('orchestra/foundation::layout.header')
	</head>
	<body>
		@include('orchestra/foundation::layout.navigation')
		<section class="container">
			@include('orchestra/foundation::layout.widgets.messages')
			@yield('content')
		</section>
		@include('orchestra/foundation::layout.footer')
	</body>
</html>
