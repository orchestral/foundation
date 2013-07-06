@extends('orchestra/foundation::layout.main')

<?php use Orchestra\Support\Facades\Site;

Site::set('header::add-button', true); ?>

@section('content')

<div class="row">
	@include('orchestra/foundation::users.search')
	<div class="twelve columns rounded box">
		<?php echo $table; ?>
	</div>
</div>


@stop
