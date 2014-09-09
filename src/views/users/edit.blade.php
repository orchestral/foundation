@extends('orchestra/foundation::layout.main')

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{!! $form !!}
	</div>
	<div class="four columns">
		@placeholder('orchestra.users')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
