@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row-fluid">
	
	<div class="span8">
		@include('orchestra/foundation::layout.widgets.header')
		{{ $form }}
	</div>

	<div class="span4">
		@placeholder('orchestra.users')
		@placeholder('orchestra.helps')
	</div>

</div>

@endsection
