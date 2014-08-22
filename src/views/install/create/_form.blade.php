<?php

use Orchestra\Support\Facades\Form; ?>

{{ Form::open(array('url' => handles('orchestra::install/create'), 'method' => 'POST', 'class' => 'form-horizontal')) }}

<fieldset>
	<div class="page-header">
		<h3>{{ trans('orchestra/foundation::install.steps.account') }}</h3>
	</div>
	<div class="form-group{{ $errors->has('email') ? ' error' : '' }}">
		{{ Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'three columns control-label')) }}
		<div class="nine columns">
			{{ Form::input('email', 'email', '', array('required' => true, 'class' => 'form-control')) }}
			{{ $errors->first('email', '<p class="help-block">:message</p>') }}
		</div>
	</div>
	<div class="form-group{{ $errors->has('password') ? ' error' : '' }}">
		{{ Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'three columns control-label')) }}
		<div class="nine columns">
			{{ Form::input('password', 'password', '', array('required' => true, 'class' => 'form-control')) }}
			{{ $errors->first('password', '<p class="help-block">:message</p>') }}
		</div>
	</div>
	<div class="form-group{{ $errors->has('fullname') ? ' error' : '' }}">
		{{ Form::label('fullname', trans('orchestra/foundation::label.users.fullname'), array('class' => 'three columns control-label')) }}
		<div class="nine columns">
			{{ Form::input('text', 'fullname', 'Administrator', array('required' => true, 'class' => 'form-control')) }}
			{{ $errors->first('fullname', '<p class="help-block">:message</p>') }}
		</div>
	</div>
</fieldset>
<fieldset>
	<div class="page-header">
		<h3>{{ trans('orchestra/foundation::install.steps.application') }}</h3>
	</div>
	<div class="form-group{{ $errors->has('site_name') ? ' error' : '' }}">
		{{ Form::label('site_name', trans('orchestra/foundation::label.name'), array('class' => 'three columns control-label')) }}
		<div class="nine columns">
			{{ Form::input('text', 'site_name', $siteName, array('required' => true, 'class' => 'form-control')) }}
			{{ $errors->first('site_name', '<p class="help-block">:message</p>') }}
		</div>
	</div>
	<div class="row">
		<div class="nine columns offset-by-three">
			<button type="submit" class="btn btn-primary">
				{{ trans('orchestra/foundation::label.submit') }}
			</button>
		</div>
	</div>
</fieldset>

{{ Form::close() }}
