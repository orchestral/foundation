@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<?php Orchestra\Site::set('header::add-button', true); ?>
	@include('orchestra/foundation::layout.widgets.header')
	@include('orchestra/foundation::users.search')
	<?php echo $table; ?>
</div>

<script>
jQuery(function($) {
	$('select').select2();
});
</script>

@stop
