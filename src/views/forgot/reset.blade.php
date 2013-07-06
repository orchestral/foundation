@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Facades\Form;
use Orchestra\Support\Facades\Site; ?>

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">
		
		<?php echo Form::open(array('url' => handles("orchestra::forgot/reset/{$token}"), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
			
			<input type="hidden" name="token" value="<?php echo $token; ?>">
			
			<fieldset>

				<div class="row<?php echo $errors->has('email') ? ' error' : ''; ?>">
					<?php echo Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'col-lg-3 control-label')); ?>
					<div class="col-lg-9">
						<?php echo Form::input('email', 'email', '', array('required' => true, 'class' => 'span12')); ?>
						<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="row<?php echo $errors->has('password') ? ' error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'col-lg-3 control-label')); ?>
					<div class="col-lg-9">
						<?php echo Form::password('password', array('required' => true, 'class' => 'span12')); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="row<?php echo $errors->has('password_confirmation') ? ' error' : ''; ?>">
					<?php echo Form::label('password_confirmation', trans('orchestra/foundation::label.account.confirm_password'), array('class' => 'col-lg-3 control-label')); ?>
					<div class="col-lg-9">
						<?php echo Form::password('password_confirmation', array('required' => true, 'class' => 'span12')); ?>
						<?php echo $errors->first('password_confirmation', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>


				<div class="row">
					<div class="col-lg-9 col-offset-3">
						<button type="submit" class="btn btn-primary"><?php echo Site::get('title', 'Submit'); ?></button>
					</div>
				</div>

			</fieldset>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
