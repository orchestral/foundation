@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">

		@include('orchestra/foundation::layout.widgets.header')

		{{ $form }}

	</div>

</div>

@stop
