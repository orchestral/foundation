@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row-fluid">
	@include('orchestra/foundation::layout.widgets.header')
	{{ $table }}
</div>

@stop
