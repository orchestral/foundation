@extends('orchestra/foundation::layouts.main')

<?php set_meta('header::add-button', true); ?>

@section('content')
<div class="row">
	@include('orchestra/foundation::users._search')
	<div class="twelve columns white rounded box">
		{!! $table !!}
	</div>
</div>
@stop
