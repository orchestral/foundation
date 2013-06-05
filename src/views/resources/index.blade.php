@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	@include('orchestra/foundation::layout.widgets.header')
	<?php echo $table; ?>
</div>

@stop
