@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Extension; ?>

@section('content')

<div class="row">
	<div class="twelve columns white rounded box">
		@include('orchestra/foundation::extensions.table')
	</div>
</div>

@stop
