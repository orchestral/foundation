<div class="row">
	<div class="twelve columns">
		<h3>{{ trans('orchestra/foundation::install.database.title') }}</h3>

		<p>
			{{ trans('orchestra/foundation::install.verify', array(
				'filename' => '<code title="'.app_path().'config/database.php'.'">app/config/database.php</code>'
			)) }}
		</p>

		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.database.type') }}</label>
			<div class="nine columns">
				<input disabled class="form-control" type="text" value="{{ $database['driver'] }}">
			</div>
		</div>

		@if (isset($database['host']))
		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.database.host') }}</label>
			<div class="nine columns">
				<input disabled class="form-control" type="text" value="{{ $database['host'] }}">
			</div>
		</div>
		@endif

		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.database.name') }}</label>
			<div class="nine columns">
				<input disabled class="form-control" type="text" value="{{ $database['database'] }}">
			</div>
		</div>

		@if (isset($database['username']))
		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.database.username') }}</label>
			<div class="nine columns">
				<input disabled class="form-control" type="text" value="{{ $database['username'] }}">
			</div>
		</div>
		@endif

		@if (isset($database['password']))
		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.database.password') }}</label>
			<div class="nine columns">
				<input disabled class="form-control" type="text" value="{{ $database['password'] }}">
				<p class="help-block">{{ trans('orchestra/foundation::install.hide-password') }}</p>
			</div>
		</div>
		@endif

		<div class="form-group">
			<label class="three columns control-label">{{ trans('orchestra/foundation::install.connection.status') }}</label>

			<div class="nine columns">
				@if (true === $databaseConnection['is'])
				<button class="btn btn-success disabled input-xlarge">
					{{ trans('orchestra/foundation::install.connection.success') }}
				</button>
				@else
				<button class="btn btn-danger disabled input-xlarge">
					{{ trans('orchestra/foundation::install.connection.fail') }}
				</button>
				@if (isset($databaseConnection['data']['error']))
				<div class="alert alert-danger">
					<strong>Error:</strong> {{ $databaseConnection['data']['error'] }}
				</div>
				@endif
				@endif
			</div>
		</div>
	</div>
</div>
