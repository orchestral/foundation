@extends('orchestra/foundation::layouts.main')

@section('content')
<div class="row">
	@if (count($panes) > 0)

	<?php $panes->add('mini-profile', '<')->title('Mini Profile')
		->attributes(['class' => 'three columns widget'])
		->content(view('orchestra/foundation::components.miniprofile')); ?>

	@foreach ($panes as $id => $pane)
		#{{ $attributes = app('html')->decorate($pane->attributes, ['class' => 'panel']) }}
		<div{!! app('html')->attributes($attributes) !!}>
		@if (! empty($pane->html))
		{!! $pane->html !!}
		@else
		<div class="panel-heading">
			{!! $pane->title !!}
		</div>
		{!! $pane->content !!}
		@endif
		</div>
	@endforeach
	@else
	@include('orchestra/foundation::dashboard._welcome')
	@endif
</div>

@stop
