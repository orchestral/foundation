@extends('orchestra/foundation::layouts.main')

@section('content')
<div class="row">
	@if(count($panes) > 0)

@php
$panes->add('mini-profile', '<')->title('Mini Profile')
	->attributes(['class' => 'three columns widget'])
	->content(view('orchestra/foundation::components.miniprofile'));
@endphp

	@foreach($panes as $id => $pane)
		<div{!! HTML::attributable($pane->get('attributes'), ['class' => 'panel']) !!}>
		@if(! empty($pane->get('html')))
		{!! $pane->get('html') !!}
		@else
		<div class="panel-heading">
			{!! $pane->get('title') !!}
		</div>
		{!! $pane->get('content') !!}
		@endif
		</div>
	@endforeach
	@else
	@include('orchestra/foundation::dashboard._welcome')
	@endif
</div>

@stop
