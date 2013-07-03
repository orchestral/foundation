@extends('orchestra/foundation::layout.main')

<?php Orchestra\Site::set('header::add-button', true); ?>

@section('content')

<div class="row">
	@include('orchestra/foundation::users.search')
	<div class="col col-lg-12 box rounded">
		<?php echo $table; ?>
	</div>
</div>


@stop
