@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">
		
		@include('orchestra/foundation::layout.widgets.header')

		{{ Form::open(array('url' => handles('orchestra/foundation::forgot'), 'method' => 'POST', 'class' => 'form-horizontal')) }}
			{{ Form::token() }}
			<fieldset>

				<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
					{{ Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'control-label')) }}
					<div class="controls">
						{{ Form::input('email', 'email', '', array('required' => true, 'class' => 'span12')) }}
						{{ $errors->first('email', '<p class="help-block">:message</p>') }}
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
