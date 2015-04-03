@extends('orchestra/foundation::layouts.main')

#{{ $label = ['class' => 'three columns control-label'] }}

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{!! app('form')->open(['url' => handles('orchestra::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('host') ? ' error' : '' }}">
					{!! app('form')->label('host', trans('orchestra/foundation::label.extensions.publisher.host'), $label) !!}
					<div class="nine columns">
						{!! app('form')->text('host', app('request')->old('host'), ['class' => 'form-control']) !!}
						{!! $errors->first('host', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('user') ? ' error' : '' }}">
					{!! app('form')->label('user', trans('orchestra/foundation::label.extensions.publisher.user'), $label) !!}
					<div class="nine columns">
						{!! app('form')->text('user', app('request')->old('user'), ['class' => 'form-control']) !!}
						{!! $errors->first('user', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{!! app('form')->label('password', trans('orchestra/foundation::label.extensions.publisher.password'), $label) !!}
					<div class="nine columns">
						{!! app('form')->password('password', ['class' => 'form-control']) !!}
						{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group">
					{!! app('form')->label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), $label) !!}
					<div class="nine columns">
						{!! app('form')->select('connection-type', ['ftp' => 'FTP', 'sftp' => 'SFTP'], app('request')->old('connection-type', 'ftp'), ['role' => 'switcher']) !!}
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
				</div>
			</fieldset>
		{!! app('form')->close() !!}
	</div>
	<div class="four columns">
		@placeholder('orchestra.publisher')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
