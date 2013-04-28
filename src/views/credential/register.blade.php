@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row-fluid">

	<div class="span6 offset3 guest-form">

		@include('orchestra/foundation::layout.widgets.header')

		{{ $form }}

	</div>

</div>

@stop
