@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-8 box rounded">
		<?php echo $form; ?>
	</div>

	<div class="col col-lg-4">
		@placeholder('orchestra.account')
		@placeholder('orchestra.helps')
	</div>

</div>

@stop
