@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="well span3" style="padding: 8px 0;">
		<ul class="nav nav-list">
			<li class="nav-header">{{ trans('orchestra/foundation::install.process') }}</li>
			<li>
				{{ HTML::link(handles('orchestra/foundation::install'), trans('orchestra/foundation::install.steps.requirement')) }}
			</li>
			<li class="active">
				{{ HTML::link(handles('orchestra/foundation::install/create'), trans('orchestra/foundation::install.steps.account')) }}
			</li>
		</ul>
	</div>

	<div class="span6 form-horizontal">

		{{ Form::open(array('url' => handles('orchestra/foundation::install/create'), 'method' => 'POST', 'class' => 'form-horizontal')) }}

		<fieldset>
			<legend>{{ trans('orchestra/foundation::install.steps.account') }}</legend>

			<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
				{{ Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'control-label')) }}
				<div class="controls">
					{{ Form::input('email', 'email', '', array('required' => true, 'class' => 'input-xlarge')) }}
					{{ $errors->first('email', '<p class="help-block">:message</p>') }}
				</div>
			</div>

			<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
				{{ Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'control-label')) }}
				<div class="controls">
					{{ Form::input('password', 'password', '', array('required' => true, 'class' => 'input-xlarge')) }}
					{{ $errors->first('password', '<p class="help-block">:message</p>') }}
				</div>
			</div>

			<div class="control-group {{ $errors->has('fullname') ? 'error' : '' }}">
				{{ Form::label('fullname', trans('orchestra/foundation::label.users.fullname'), array('class' => 'control-label')) }}
				<div class="controls">
					{{ Form::input('text', 'fullname', '', array('required' => true, 'class' => 'input-xlarge')) }}
					{{ $errors->first('fullname', '<p class="help-block">:message</p>') }}
				</div>
			</div>

		</fieldset>

		<fieldset>
			<legend>{{ trans('orchestra/foundation::install.steps.application') }}</legend>

			<div class="control-group {{ $errors->has('site_name') ? 'error' : '' }}">
				{{ Form::label('site_name', trans('orchestra/foundation::label.name'), array('class' => 'control-label')) }}
				<div class="controls">
					{{ Form::input('text', 'site_name', $siteName, array('required' => true, 'class' => 'input-xlarge')) }}
					{{ $errors->first('site_name', '<p class="help-block">:message</p>') }}
				</div>
			</div>

		</fieldset>

		<div class="form-actions clean">
			<button type="submit" class="btn btn-primary">{{ trans('orchestra/foundation::label.submit') }}</button>
		</div>

		{{ Form::close() }}

	</div>

</div>

@stop
