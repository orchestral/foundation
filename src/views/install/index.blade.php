@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="well span3" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header">{{ trans('orchestra/foundation::install.process') }}</li>
			<li class="active">
				{{ Html::link(handles('orchestra/foundation::install'), trans('orchestra/foundation::install.steps.requirement')) }}
			</li>
		</ul>

	</div>

	<div id="installation" class="span6 form-horizontal">

		<h3>{{ trans('orchestra/foundation::install.system.title') }}</h3>

		<p>{{ trans('orchestra/foundation::install.system.description') }}</p>

		<table class="table table-bordered table-striped requirements">
			<thead>
				<tr>
					<th>{{ trans('orchestra/foundation::install.system.requirement') }}</th>
					<th>{{ trans('orchestra/foundation::install.system.status') }}</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$databaseConnection = $checklist['databaseConnection'];
				unset($checklist['databaseConnection']); ?>
				@foreach ($checklist as $name => $requirement)
				<tr>
					<td>
						{{ trans("orchestra/foundation::install.system.{$name}.name", $requirement['data']) }}
						@unless ($requirement['is'] === $requirement['should'])
						<div class="alert{{ true === $requirement['explicit'] ? ' alert-error ' : '' }}">
							<strong>{{ trans("orchestra/foundation::install.solution") }}:</strong>
							{{ trans("orchestra/foundation::install.system.{$name}.solution", $requirement['data']) }}
						</div>
						@endunless
					</td>
					<td>
						@if ($requirement['is'] === $requirement['should'])
							<button class="btn btn-success btn-block disabled">
								{{ trans('orchestra/foundation::install.status.work') }}
							</button>
						@else
							@if (true === $requirement['explicit'])
								<button class="btn btn-danger btn-block disabled">
									{{ trans('orchestra/foundation::install.status.not') }}
								</button>
							@else
								<button class="btn btn-warning btn-block disabled">
									{{ trans('orchestra/foundation::install.status.still') }}
								</button>
							@endif
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

		<h3>{{ trans('orchestra/foundation::install.database.title') }}</h3>

		<p>
			{{ trans('orchestra/foundation::install.verify', array('filename' => Html::create('code', 'app/config/database.php', array('title' => app_path().'config/database.php')))) }}
		</p>

		<fieldset>

			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.database.type') }}</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $database['driver'] }}</span>
				</div>
			</div>

			@if (isset($database['host']))
			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.database.host') }}</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $database['host'] }}</span>
				</div>
			</div>
			@endif

			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.database.name') }}</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $database['database'] }}</span>
				</div>
			</div>

			@if (isset($database['username']))
			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.database.username') }}</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $database['username'] }}</span>
				</div>
			</div>
			@endif

			@if (isset($database['password']))
			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.database.password') }}</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $database['password'] }}</span>
					<p class="help-block">{{ trans('orchestra/foundation::install.hide-password') }}</p>
				</div>
			</div>
			@endif

			<div class="control-group">
				<label class="control-label">{{ trans('orchestra/foundation::install.connection.status') }}</label>
				<div class="controls">
					@if (true === $databaseConnection['is'])
					<button class="btn btn-success disabled input-xlarge">
						{{ trans('orchestra/foundation::install.connection.success') }}
					</button>
					@else
					<button class="btn btn-danger disabled input-xlarge">
						{{ trans('orchestra/foundation::install.connection.fail') }}
					</button>
					@endif
				</div>
			</div>

		</fieldset>

		<fieldset>

			<h3>{{ trans('orchestra/foundation::install.auth.title') }}</h3>

			<p>
				{{ trans('orchestra/foundation::install.verify', array('filename' => Html::create('code', 'app/config/auth.php', array('title' => app_path().'config/auth.php')))) }}
			</p>

			<div class="control-group">
				<label class="control-label {{ 'fluent' === $auth['driver'] ? 'error' : '' }}">
					{{ trans('orchestra/foundation::install.auth.driver') }}
				</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $auth['driver'] }}</span>
					@if ('fluent' === $auth['driver'])
					<p class="help-block">{{ trans('orchestra/foundation::install.auth.requirement.driver') }}</p>
					@endif
				</div>
			</div>

			<div class="control-group {{ false === $authentication ? 'error' : '' }} {{ 'eloquent' !== $auth['driver'] ? 'hide' : '' }}">
				<label class="control-label">
					{{ trans('orchestra/foundation::install.auth.model') }}
				</label>
				<div class="controls">
					<span class="uneditable-input input-xlarge">{{ $auth['model'] }}</span>
					@if (false === $authentication)
					<p class="help-block">
						{{ trans('orchestra/foundation::install.auth.requirement.driver', array('class' => Html::create('code', 'Orchestra\Model\User'))) }}
					</p>
					@endif
				</div>
			</div>

		</fieldset>

		@if ($installable)

		<div class="form-actions clean">
			{{ Html::link(handles('orchestra/foundation::install/create'), trans('orchestra/foundation::label.next'), array('class' => 'btn btn-primary')) }}
		</div>

		@endif

	</div>

</div>

@stop