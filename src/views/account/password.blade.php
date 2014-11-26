@extends('orchestra/foundation::layouts.main')

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{!! $form !!}
	</div>
	<div class="four columns">
		@placeholder('orchestra.account')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
