@extends('orchestra/foundation::layout.main')

<?php use Illuminate\Support\Facades\Form; ?>

@section('content')

<div class="row">
	<div class="three columns">
		<div class="list-group">
			<a href="<?php echo handles('orchestra::install'); ?>" class="list-group-item">
				<?php echo trans('orchestra/foundation::install.steps.requirement'); ?>
			</a>
			<a href="<?php echo handles('orchestra::install/create'); ?>" class="list-group-item active">
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

	<div class="six columns rounded box">

		<?php echo Form::open(array('url' => handles('orchestra::install/create'), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
		
		<fieldset>
			<div class="page-header">
				<h3><?php echo trans('orchestra/foundation::install.steps.account'); ?></h3>
			</div>
		

			<div class="form-group<?php echo $errors->has('email') ? ' error' : ''; ?>">
				<?php echo Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'three columns control-label')); ?>
				<div class="nine columns">
					<?php echo Form::input('email', 'email', '', array('required' => true, 'class' => 'form-control')); ?>
					<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

			<div class="form-group<?php echo $errors->has('password') ? ' error' : ''; ?>">
				<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'three columns control-label')); ?>
				<div class="nine columns">
					<?php echo Form::input('password', 'password', '', array('required' => true, 'class' => 'form-control')); ?>
					<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

			<div class="form-group<?php echo $errors->has('fullname') ? ' error' : ''; ?>">
				<?php echo Form::label('fullname', trans('orchestra/foundation::label.users.fullname'), array('class' => 'three columns control-label')); ?>
				<div class="nine columns">
					<?php echo Form::input('text', 'fullname', 'Administrator', array('required' => true, 'class' => 'form-control')); ?>
					<?php echo $errors->first('fullname', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

		</fieldset>

		<fieldset>
			<div class="page-header">
				<h3><?php echo trans('orchestra/foundation::install.steps.application'); ?></h3>
			</div>

			<div class="form-group<?php echo $errors->has('site_name') ? ' error' : ''; ?>">
				<?php echo Form::label('site_name', trans('orchestra/foundation::label.name'), array('class' => 'three columns control-label')); ?>
				<div class="nine columns">
					<?php echo Form::input('text', 'site_name', $siteName, array('required' => true, 'class' => 'form-control')); ?>
					<?php echo $errors->first('site_name', '<p class="help-block">:message</p>'); ?>
				</div>
			</div>

			<div class="row">
				<div class="nine columns offset-by-three">
					<button type="submit" class="btn btn-primary">
						<?php echo trans('orchestra/foundation::label.submit'); ?>
					</button>
				</div>
			</div>

		</fieldset>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
