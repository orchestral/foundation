@extends('orchestra/foundation::layout.main')

@section('content')
<div class="row">
	<div class="three columns">
		<div class="list-group">
			<a href="{{ handles('orchestra::install') }}" class="list-group-item">
				{{ trans('orchestra/foundation::install.steps.requirement') }}
			</a>
			<a href="{{ handles('orchestra::install/create') }}" class="list-group-item active">
				{{ trans('orchestra/foundation::install.steps.account') }}
			</a>
			<a href="#" class="list-group-item disabled">
				{{ trans('orchestra/foundation::install.steps.done') }}
			</a>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 50%"></div>
		</div>
	</div>
	<div class="six columns rounded box">
		@include('orchestra/foundation::install.create._form')
	</div>
</div>
@stop
