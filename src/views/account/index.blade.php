@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="span8">
		@include('orchestra/foundation::layout.widgets.header')
		<?php echo $form; ?>
	</div>

	<div class="span4">
		@placeholder('orchestra.account')
		@placeholder('orchestra.helps')
	</div>

</div>

@stop
