#{{ $title = get_meta('title') }}
#{{ $description = get_meta('description') }}

<div class="{!! get_meta('header::class', 'page-header') !!}">
	<div class="container">
		@if (get_meta('header::add-button'))
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

#{{ set_meta('header::class', 'page-header') }}
