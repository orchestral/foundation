@extends('orchestra/foundation::layout.main')

<?

$databaseConnection = $checklist['databaseConnection'];
unset($checklist['databaseConnection']); ?>

@section('content')
<div class="row">
	<div class="three columns">
		<div class="list-group">
			<a href="{{ handles('orchestra::install') }}" class="list-group-item active">
				{{ trans('orchestra/foundation::install.steps.requirement') }}
			</a>
			<a href="#" class="list-group-item disabled">
				{{ trans('orchestra/foundation::install.steps.account') }}
			</a>
			<a href="#" class="list-group-item disabled">
				{{ trans('orchestra/foundation::install.steps.done') }}
			</a>
		</div>
		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 0%"></div>
		</div>
	</div>
	<div id="installation" class="six columns form-horizontal">
		@include('orchestra/foundation::install.index._requirement')
		@include('orchestra/foundation::install.index._database')
		@include('orchestra/foundation::install.index._authentication')
	</div>
</div>
@stop
