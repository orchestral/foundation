@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">
	<div class="col col-lg-3">
		<div class="list-group">
			<a href="<?php echo handles('orchestra/foundation::install'); ?>" class="list-group-item">
				<?php echo trans('orchestra/foundation::install.steps.requirement'); ?>
			</a>
			<a href="<?php echo handles('orchestra/foundation::install/create'); ?>" class="list-group-item active">
				<?php echo trans('orchestra/foundation::install.steps.account'); ?>
			</a>
			<a href="#" class="list-group-item disabled">
				<?php echo trans('orchestra/foundation::install.steps.done'); ?>
			</a>
		</div>

		<div class="progress">
			<div class="progress-bar progress-bar-success" style="width: 50%"></div>
		</div>
	</div>

	<div class="col col-lg-6 form-horizontal">

		<?php echo Form::open(array('url' => handles('orchestra/foundation::install/create'), 'method' => 'POST', 'class' => 'form-horizontal')); ?>

		<fieldset>
			<legend><?php echo trans('orchestra/foundation::install.steps.account'); ?></legend>

			<div class="control-group <?php echo $errors->has('email') ? 'error' : ''; ?>">
				<?php echo Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::input('email', 'email', '', array('required' => true, 'class' => 'input-xlarge')); ?>
					<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

			<div class="control-group <?php echo $errors->has('password') ? 'error' : ''; ?>">
				<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::input('password', 'password', '', array('required' => true, 'class' => 'input-xlarge')); ?>
					<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

			<div class="control-group <?php echo $errors->has('fullname') ? 'error' : ''; ?>">
				<?php echo Form::label('fullname', trans('orchestra/foundation::label.users.fullname'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::input('text', 'fullname', '', array('required' => true, 'class' => 'input-xlarge')); ?>
					<?php echo $errors->first('fullname', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

		</fieldset>

		<fieldset>
			<legend><?php echo trans('orchestra/foundation::install.steps.application'); ?></legend>

			<div class="control-group <?php echo $errors->has('site_name') ? 'error' : ''; ?>">
				<?php echo Form::label('site_name', trans('orchestra/foundation::label.name'), array('class' => 'control-label')); ?>
				<div class="controls">
					<?php echo Form::input('text', 'site_name', $siteName, array('required' => true, 'class' => 'input-xlarge')); ?>
					<?php echo $errors->first('site_name', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

		</fieldset>

		<div class="form-actions clean">
			<button type="submit" class="btn btn-primary"><?php echo trans('orchestra/foundation::label.submit'); ?></button>
		</div>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
