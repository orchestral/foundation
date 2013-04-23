@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row-fluid">

	<div class="span6 offset3 guest-form">

		@include('orchestra/foundation::layout.widgets.header')

		{{ Form::open(array('url' => handles('orchestra/foundation::login'), 'action' => 'POST', 'class' => 'form-horizontal')) }}
			{{ Form::hidden('redirect', $redirect) }}
			{{ Form::token() }}
			<fieldset>

				<div class="control-group {{ $errors->has('username') ? 'error' : '' }}">
					{{ Form::label('username', trans("orchestra/foundation::label.users.email"), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::input('text', 'username', '', array('required' => true, 'class' => 'span12', 'tabindex' => 1)) }}
						{{ $errors->first('username', '<p class="help-block">:message</p>') }}
					</div>
				</div>

				<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
					{{ Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::input('password', 'password', '', array('required' => true, 'class' => 'span12', 'tabindex' => 2)) }}
						{{ $errors->first('password', '<p class="help-block">:message</p>') }}
						<p class="help-block">
							{{ Html::link(handles('orchestra/foundation::forgot'), trans('orchestra/foundation::title.forgot-password')) }}
						</p>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
					<label class="checkbox">
						{{ Form::checkbox('remember', 'yes', false, array('tabindex' => 3)) }} 
						{{ trans('orchestra/foundation::title.remember-me') }}
					</label>
				</div>

			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">{{ trans('orchestra/foundation::title.login') }}</button>
				@if(memorize('site.users.registration', false))
				{{ Html::link(handles('orchestra/foundation::register'), trans('orchestra/foundation::title.register'), array('class' => 'btn')) }}
				@endif
			</div>
			
		{{ Form::close() }}

	</div>

</div>

@stop
