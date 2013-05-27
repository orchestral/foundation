@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">
		
		@include('orchestra/foundation::layout.widgets.header')

		{{ Form::open(array('url' => handles("orchestra/foundation::forgot/reset/{$token}"), 'method' => 'POST', 'class' => 'form-horizontal')) }}
			<input type="hidden" name="token" value="{{ $token }}">
			<fieldset>

				<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
					{{ Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::input('email', 'email', '', array('required' => true, 'class' => 'span12')) }}
						{{ $errors->first('email', '<p class="help-block">:message</p>') }}
					</div>
				</div>

				<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
					{{ Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::password('password', array('required' => true, 'class' => 'span12')) }}
						{{ $errors->first('password', '<p class="help-block">:message</p>') }}
					</div>
				</div>

				<div class="control-group {{ $errors->has('password_confirmation') ? 'error' : '' }}">
					{{ Form::label('password_confirmation', trans('orchestra/foundation::label.account.confirm_password'), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::password('password_confirmation', array('required' => true, 'class' => 'span12')) }}
						{{ $errors->first('password_confirmation', '<p class="help-block">:message</p>') }}
					</div>
				</div>

			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">{{ Orchestra\Site::get('title', 'Submit') }}</button>
			</div>

		{{ Form::close() }}

	</div>

</div>

@stop
