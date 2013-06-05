@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	
	<div class="col col-lg-8">
		@include('orchestra/foundation::layout.widgets.header')
		<?php echo $form; ?>
	</div>

	<div class="col col-lg-4">
		@placeholder('orchestra.users')
		@placeholder('orchestra.helps')
	</div>

</div>

@stop
