<?php

use Illuminate\Support\Facades\URL;
use Orchestra\Support\Facades\Site;

$title       = Site::get('title');
$description = Site::get('description'); ?>

<div class="{{ Site::get('header::class', 'page-header') }}">
	<div class="container">
		@if (Site::get('header::add-button'))
		<div class="pull-right">
			<a href="{{ URL::current() }}/create" class="btn btn-primary">
				{{ trans('orchestra/foundation::label.add') }}
			</a>
		</div>
		@endif

		<h2>{{ $title ?: '' }}
			@if (! empty($description))
			<small>{{ $description ?: '' }}</small>
			@endif
		</h2>
	</div>
</div>

<?php Site::set('header::class', 'page-header'); ?>
