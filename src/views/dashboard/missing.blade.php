@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="col col-lg-6 col-offset-3">
		<h2>I think we're lost.</h2>

		<hr>

		<p>
			We couldn't find the page you requested on our servers. We're really sorry
			about that. It's our fault, not yours. We'll work hard to get this page
			back online as soon as possible.
		</p>

		<p>
			Perhaps you would like to go to our <a href="{{ handles('orchestra/foundation::/') }}">home page</a>?
		</p>
	</div>
</div>
@stop
