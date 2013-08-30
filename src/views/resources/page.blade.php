@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="three columns">
		@include('orchestra/foundation::resources.list')
		
		@placeholder("orchestra.resources: {$resource->name}")
		@placeholder('orchestra.resources')
	</div>

	<div class="nine columns">
		<?php echo $content; ?>
	</div>
	
</div>

@stop
