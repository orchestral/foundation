{{-- Get the variable from Orchestra\Site --}}
<?php

$title       = Orchestra\Site::get('title');
$description = Orchestra\Site::get('description'); ?>

<div class="page-header">
	@if (Orchestra\Site::get('header::add-button'))
	<div class="pull-right">
		<a href="{{ URL::current() }}/create" class="btn btn-primary">
			{{ trans('orchestra/foundation::label.add') }}
		</a>
	</div>
	@endif
	
	<h2>{{ $title ?: '' }}
		@if ( ! empty($description))
		<small>{{ $description ?: '' }}</small>
		@endif
	</h2>
</div>
