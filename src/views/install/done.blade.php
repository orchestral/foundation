@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="well span3" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header">{{ trans('orchestra/foundation::install.process') }}</li>
			<li>
				{{ Html::link(handles('orchestra/foundation::install'), trans('orchestra/foundation::install.steps.requirement')) }}
			</li>
			<li>
				{{ Html::link(handles('orchestra/foundation::install/create'), trans('orchestra/foundation::install.steps.account')) }}
			</li>
			<li class="active">
				{{ Html::link(handles('orchestra/foundation::install/done'), trans('orchestra/foundation::install.steps.done')) }}
			</li>
		</ul>
	</div>

	<div class="span6 form-horizontal">

		<h2>{{ trans('orchestra/foundation::install.steps.done') }}</h2>

	</div>

</div>

@stop