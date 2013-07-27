@extends('orchestra/foundation::layout.main')

<?php use Illuminate\Support\Facades\Form; ?>

@section('content')

<div class="row">
	
	<div class="six columns offset-by-three">

		<?php echo Form::open(array('url' => handles('orchestra::login'), 'action' => 'POST', 'class' => 'form-horizontal')); ?>
			<fieldset>

				<div class="form-group<?php echo $errors->has('email') ? ' error' : ''; ?>">
					<?php echo Form::label('email', trans("orchestra/foundation::label.users.email"), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::input('text', 'email', '', array('required' => true, 'tabindex' => 1, 'class' => 'form-control')); ?>
						<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="form-group<?php echo $errors->has('password') ? ' error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'three columns control-label')); ?>
					<div class="nine columns">
						<?php echo Form::input('password', 'password', '', array('required' => true, 'tabindex' => 2, 'class' => 'form-control')); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
						<p class="help-block">
							<a href="<?php echo handles('orchestra::forgot'); ?>">
								<?php echo trans('orchestra/foundation::title.forgot-password'); ?>
							</a>
						</p>
					</div>
					<div class="nine columns offset-by-three">
						<label class="checkbox">
							<?php echo Form::checkbox('remember', 'yes', false, array('tabindex' => 3)); ?> 
							<?php echo trans('orchestra/foundation::title.remember-me'); ?>
						</label>
					</div>
				</div>

				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">
							<?php echo trans('orchestra/foundation::title.login'); ?>
						</button>
						<?php if (memorize('site.registrable', false)) : ?>
						<a href="<?php echo handles('orchestra::register'); ?>" class="btn btn-link">
							<?php echo trans('orchestra/foundation::title.register'); ?>
						</a>
						<?php endif; ?>
					</div>
				</div>

			</fieldset>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
