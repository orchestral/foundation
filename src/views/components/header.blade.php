<?php

$title       = app('orchestra.site')->get('title');
$description = app('orchestra.site')->get('description'); ?>

<div class="{!! app('orchestra.site')->get('header::class', 'page-header') !!}">
	<div class="container">
		@if (app('orchestra.site')->get('header::add-button'))
		<div class="pull-right">
			<a href="{!! app('url')->current() !!}/create" class="btn btn-primary">
				{{ trans('orchestra/foundation::label.add') }}
			</a>
		</div>
		@endif

		<h2>{!! $title or '' !!}
			@if (! empty($description))
			<small>{!! $description or '' !!}</small>
			@endif
		</h2>
	</div>
</div>

<?php app('orchestra.site')->set('header::class', 'page-header'); ?>
