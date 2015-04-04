@extends('orchestra/foundation::layouts.main')

@inject('formbuilder', 'form')
@inject('request', 'request')
#{{ $label = ['class' => 'three columns control-label'] }}

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{!! $formbuilder->open(['url' => handles('orchestra::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('host') ? ' error' : '' }}">
					{!! $formbuilder->label('host', trans('orchestra/foundation::label.extensions.publisher.host'), $label) !!}
					<div class="nine columns">
						{!! $formbuilder->text('host', $request->old('host'), ['class' => 'form-control']) !!}
						{!! $errors->first('host', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('user') ? ' error' : '' }}">
					{!! $formbuilder->label('user', trans('orchestra/foundation::label.extensions.publisher.user'), $label) !!}
					<div class="nine columns">
						{!! $formbuilder->text('user', $request->old('user'), ['class' => 'form-control']) !!}
						{!! $errors->first('user', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{!! $formbuilder->label('password', trans('orchestra/foundation::label.extensions.publisher.password'), $label) !!}
					<div class="nine columns">
						{!! $formbuilder->password('password', ['class' => 'form-control']) !!}
						{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group">
					{!! $formbuilder->label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), $label) !!}
					<div class="nine columns">
						{!! $formbuilder->select('connection-type', ['ftp' => 'FTP', 'sftp' => 'SFTP'], $request->old('connection-type', 'ftp'), ['role' => 'switcher']) !!}
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
				</div>
			</fieldset>
		{!! $formbuilder->close() !!}
	</div>
	<div class="four columns">
		@placeholder('orchestra.publisher')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
