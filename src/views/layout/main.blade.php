<!DOCTYPE html>
<html lang="en">
	<head>
		@include('orchestra/foundation::layout.header')
	</head>
	<body>
		@include('orchestra/foundation::layout.navigation')
		@include('orchestra/foundation::layout.widgets.mainheader')
		<section class="container main">
			@include('orchestra/foundation::layout.widgets.messages')
			@yield('content')
		</section>
		@include('orchestra/foundation::layout.footer')
	</body>
</html>
