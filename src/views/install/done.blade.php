@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="col col-lg-3">
		<div class="list-group">
			<a href="{{ handles('orchestra/foundation::install') }}" class="list-group-item">
				{{ trans('orchestra/foundation::install.steps.requirement') }}
			</a>
			<a href="{{ handles('orchestra/foundation::install/create') }}" class="list-group-item">
				{{ trans('orchestra/foundation::install.steps.account') }}
			</a>
			<a href="{{ handles('orchestra/foundation::install/done') }}" class="list-group-item active">
				{{ trans('orchestra/foundation::install.steps.done') }}
			</a>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 100%"></div>
		</div>
	</div>

	<div class="col col-lg-6 form-horizontal">

		<h2>{{ trans('orchestra/foundation::install.steps.done') }}</h2>

	</div>

</div>

@stop
