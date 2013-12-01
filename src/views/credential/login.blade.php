@extends('orchestra/foundation::layout.extra')

<?

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\Input; ?>

@section('content')
<div class="row">
	<div class="six columns offset-by-three">
		{{ Form::open(array('url' => handles('orchestra::login'), 'action' => 'POST', 'class' => 'form-horizontal')) }}
			<fieldset>
				<div class="form-group{{ $errors->has('email') ? ' error' : '' }}">
					{{ Form::label('email', trans("orchestra/foundation::label.users.email"), array('class' => 'three columns control-label')) }}
					<div class="nine columns">
						{{ Form::input('text', 'email', Input::old('email'), array('required' => true, 'tabindex' => 1, 'class' => 'form-control')) }}
						{{ $errors->first('email', '<p class="help-block">:message</p>') }}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{{ Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'three columns control-label')) }}
					<div class="nine columns">
						{{ Form::input('password', 'password', '', array('required' => true, 'tabindex' => 2, 'class' => 'form-control')) }}
						{{ $errors->first('password', '<p class="help-block">:message</p>') }}
						<p class="help-block">
							<a href="{{ handles('orchestra::forgot') }}">
								{{ trans('orchestra/foundation::title.forgot-password') }}
							</a>
						</p>
					</div>
					<div class="nine columns offset-by-three">
						<label class="checkbox">
							{{ Form::checkbox('remember', 'yes', false, array('tabindex' => 3)) }}
							{{ trans('orchestra/foundation::title.remember-me') }}
						</label>
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">
							{{ trans('orchestra/foundation::title.login') }}
						</button>
						@if (memorize('site.registrable', false))
						<a href="{{ handles('orchestra::register') }}" class="btn btn-link">
							{{ trans('orchestra/foundation::title.register') }}
						</a>
						@endif
					</div>
				</div>
			</fieldset>
		{{ Form::close() }}
	</div>
</div>
@stop
