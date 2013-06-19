@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">

		@include('orchestra/foundation::layout.widgets.header')

		<?php echo Form::open(array('url' => handles('orchestra/foundation::login'), 'action' => 'POST', 'class' => 'form-horizontal')); ?>
			<fieldset>

				<div class="row<?php echo $errors->has('email') ? ' error' : ''; ?>">
					<?php echo Form::label('email', trans("orchestra/foundation::label.users.email"), array('class' => 'col-lg-3 control-label')); ?>
					<div class="col-lg-9">
						<?php echo Form::input('text', 'email', '', array('required' => true, 'class' => 'span12', 'tabindex' => 1)); ?>
						<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

				<div class="row<?php echo $errors->has('password') ? ' error' : ''; ?>">
					<?php echo Form::label('password', trans('orchestra/foundation::label.users.password'), array('class' => 'col-lg-3 control-label')); ?>
					<div class="col-lg-9">
						<?php echo Form::input('password', 'password', '', array('required' => true, 'class' => 'span12', 'tabindex' => 2)); ?>
						<?php echo $errors->first('password', '<p class="help-block">:message</p>'); ?>
						<p class="help-block">
							<a href="<?php echo handles('orchestra/foundation::forgot'); ?>">
								<?php echo trans('orchestra/foundation::title.forgot-password'); ?>
							</a>
						</p>
					</div>
					<div class="col-lg-9 col-offset-3">
						<label class="checkbox">
							<?php echo Form::checkbox('remember', 'yes', false, array('tabindex' => 3)); ?> 
							<?php echo trans('orchestra/foundation::title.remember-me'); ?>
						</label>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-9 col-offset-3">
						<button type="submit" class="btn btn-primary">
							<?php echo trans('orchestra/foundation::title.login'); ?>
						</button>
						<?php if (memorize('site.registrable', false)) : ?>
						<a href="<?php echo handles('orchestra/foundation::register'); ?>" class="btn">
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
