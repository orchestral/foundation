@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Facades\Form;
use Illuminate\Support\Facades\Input;
use Orchestra\Support\Facades\Site; ?>

@section('content')

<div class="row">

	<div class="six columns offset-by-three">
		
		<?php echo Form::open(array('url' => handles("orchestra::forgot/reset/{$token}"), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
			
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			
			<fieldset>

				<div class="form-group<?php echo $errors->has('email') ? ' error' : ''; ?>">
					<?php echo Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::input('email', 'email', Input::old('email'), array('required' => true, 'class' => 'form-control')); ?>
						<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group<?php echo $errors->has('password') ? ' error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::password('password', array('required' => true, 'class' => 'form-control')); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group<?php echo $errors->has('password_confirmation') ? ' error' : ''; ?>">
					<?php echo Form::label('password_confirmation', trans('orchestra/foundation::label.account.confirm_password'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::password('password_confirmation', array('required' => true, 'class' => 'form-control')); ?>
						<?php echo $errors->first('password_confirmation', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>


				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary"><?php echo Site::get('title', 'Submit'); ?></button>
					</div>
				</div>

			</fieldset>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
