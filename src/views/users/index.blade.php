@extends('orchestra/foundation::layout.main')

<?php Orchestra\Support\Facades\Site::set('header::add-button', true); ?>

@section('content')
<div class="row">
	@include('orchestra/foundation::users._search')
	<div class="twelve columns white rounded box">
		{!! $table !!}
	</div>
</div>
@stop
