@extends('orchestra/foundation::layouts.extra')

@inject('formbuilder', 'form')
@inject('request', 'request')

@section('content')
<div class="row">
	<div class="six columns offset-by-three">
		{!! $formbuilder->open(['url' => handles("orchestra::forgot/reset"), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<input type="hidden" name="token" value="{!! $token !!}">
			<fieldset>
				<div class="form-group{!! $errors->has('email') ? ' error' : '' !!}">
					{!! $formbuilder->label('email', trans('orchestra/foundation::label.users.email'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->input('email', 'email', $request->old('email'), ['required' => true, 'class' => 'form-control']) !!}
						{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{!! $formbuilder->label('password', trans('orchestra/foundation::label.users.password'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->password('password', ['required' => true, 'class' => 'form-control']) !!}
						{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password_confirmation') ? ' error' : '' }}">
					{!! $formbuilder->label('password_confirmation', trans('orchestra/foundation::label.account.confirm_password'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->password('password_confirmation', ['required' => true, 'class' => 'form-control']) !!}
						{!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">{!! get_meta('title', 'Submit') !!}</button>
					</div>
				</div>
			</fieldset>
		{!! $formbuilder->close() !!}
	</div>
</div>
@stop
