@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Facades\Form; ?>

@section('content')

<div class="row">
	<div class="eight columns rounded box">
		
		<?php echo Form::open(array('url' => handles('orchestra::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
			<fieldset>
				<div class="form-group<?php echo $errors->has('host') ? ' error' : ''; ?>">
					<?php echo Form::label('host', trans('orchestra/foundation::label.extensions.publisher.host'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::text('host', Input::old('host'), array('class' => 'form-control')); ?>
						<?php echo $errors->first('host', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group<?php echo $errors->has('user') ? ' error' : ''; ?>">
					<?php echo Form::label('user', trans('orchestra/foundation::label.extensions.publisher.user'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::text('user', Input::old('user'), array('class' => 'form-control')); ?>
						<?php echo $errors->first('user', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group<?php echo $errors->has('password') ? ' error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.extensions.publisher.password'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::password('password', array('class' => 'form-control')); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group">
					<?php echo Form::label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::select('connection-type', array('ftp' => 'FTP', 'sftp' => 'SFTP'), Input::old('connection-type', 'ftp'), array('role' => 'switcher')); ?>
					</div>
				</div>

				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">Login</button>
					</div>
				</div>
			
			</fieldset>
		<?php echo Form::close(); ?>
	</div>

	<div class="four columns">
		@placeholder('orchestra.publisher')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop
