@extends('orchestra/foundation::layout.main')

@section('content')
<div class="row">
	<div class="col col-lg-8">
		
		@include('orchestra/foundation::layout.widgets.header')

		<?php echo Form::open(array('url' => handles('orchestra/foundation::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
			<fieldset>
				<div class="control-group <?php echo $errors->has('host') ? 'error' : ''; ?>">
					<?php echo Form::label('host', trans('orchestra/foundation::label.extensions.publisher.host'), array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo Form::text('host', Input::old('host'), array('class' => 'input-xxlarge')); ?>
						<?php echo $errors->first('host', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="control-group <?php echo $errors->has('user') ? 'error' : ''; ?>">
					<?php echo Form::label('user', trans('orchestra/foundation::label.extensions.publisher.user'), array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo Form::text('user', Input::old('user'), array('class' => 'input-xxlarge')); ?>
						<?php echo $errors->first('user', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="control-group <?php echo $errors->has('password') ? 'error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.extensions.publisher.password'), array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo Form::password('password', array('class' => 'input-xxlarge')); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="control-group">
					<?php echo Form::label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo Form::select('connection-type', array('ftp' => 'FTP', 'sftp' => 'SFTP'), Input::old('connection-type', 'ftp'), array('role' => 'switcher')); ?>
					</div>
				</div>
			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary">Login</button>
			</div>
		<?php echo Form::close(); ?>
	</div>

	<div class="col col-lg-4">

	</div>
</div>
@stop
