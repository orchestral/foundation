@extends('orchestra/foundation::layouts.extra')

@inject('formbuilder', 'form')
@inject('request', 'request')

@section('content')
<div class="row">
	<div class="six columns offset-by-three">
		{!! $formbuilder->open(['url' => handles('orchestra::login'), 'action' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('email') ? ' error' : '' }}">
					{!! $formbuilder->label('email', trans("orchestra/foundation::label.users.email"), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->input('text', 'email', $request->old('email'), ['required' => true, 'tabindex' => 1, 'class' => 'form-control']) !!}
						{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
					{!! $formbuilder->label('password', trans('orchestra/foundation::label.users.password'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->input('password', 'password', '', ['required' => true, 'tabindex' => 2, 'class' => 'form-control']) !!}
						{!! $errors->first('password', '<p class="help-block">:message</p>') !!}
						<p class="help-block">
							<a href="{!! handles('orchestra::forgot') !!}">
								{{ trans('orchestra/foundation::title.forgot-password') }}
							</a>
						</p>
					</div>
					<div class="nine columns offset-by-three">
						<label class="checkbox">
							{!! $formbuilder->checkbox('remember', 'yes', false, ['tabindex' => 3]) !!}
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
						<a href="{!! handles('orchestra::register') !!}" class="btn btn-link">
							{{ trans('orchestra/foundation::title.register') }}
						</a>
						@endif
					</div>
				</div>
			</fieldset>
		{!! $formbuilder->close() !!}
	</div>
</div>
@stop
