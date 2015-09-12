@extends('orchestra/foundation::layouts.main')

#{{ $label = ['class' => 'three columns control-label'] }}

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{!! Form::open(['url' => handles('orchestra::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('host') ? ' error' : '' }}">
					{!! Form::label('host', trans('orchestra/foundation::label.extensions.publisher.host'), $label) !!}
					<div class="nine columns">
						{!! Form::text('host', old('host'), ['class' => 'form-control']) !!}
						{!! $errors->first('host', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('user') ? ' error' : '' }}">
					{!! Form::label('user', trans('orchestra/foundation::label.extensions.publisher.user'), $label) !!}
					<div class="nine columns">
						{!! Form::text('user', old('user'), ['class' => 'form-control']) !!}
						{!! $errors->first('user', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{!! Form::label('password', trans('orchestra/foundation::label.extensions.publisher.password'), $label) !!}
					<div class="nine columns">
						{!! Form::password('password', ['class' => 'form-control']) !!}
						{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group">
					{!! Form::label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), $label) !!}
					<div class="nine columns">
						{!! Form::select('connection-type', ['ftp' => 'FTP', 'sftp' => 'SFTP'], old('connection-type', 'ftp'), ['role' => 'switcher']) !!}
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
				</div>
			</fieldset>
		{!! Form::close() !!}
	</div>
	<div class="four columns">
		@placeholder('orchestra.publisher')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
