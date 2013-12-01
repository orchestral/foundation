@extends('orchestra/foundation::layout.main')

@section('content')
<div class="row">
	<div class="three columns">
		<div class="list-group">
			<a href="{{ handles('orchestra::install') }}" class="list-group-item">
				{{ trans('orchestra/foundation::install.steps.requirement') }}
			</a>
			<a href="{{ handles('orchestra::install/create') }}" class="list-group-item">
				{{ trans('orchestra/foundation::install.steps.account') }}
			</a>
			<a href="{{ handles('orchestra::install/done') }}" class="list-group-item active">
				{{ trans('orchestra/foundation::install.steps.done') }}
			</a>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 100%"></div>
		</div>
	</div>

	<div class="six columns rounded box">
		<h3>{{ trans('orchestra/foundation::install.steps.done') }}</h3>
	</div>
</div>
@stop
