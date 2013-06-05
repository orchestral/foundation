@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-6 col-offset-3 guest-form">
		
		@include('orchestra/foundation::layout.widgets.header')

		<?php echo Form::open(array('url' => handles('orchestra/foundation::forgot'), 'method' => 'POST', 'class' => 'form-horizontal')); ?>
			<fieldset>

				<div class="control-group <?php echo $errors->has('email') ? 'error' : ''; ?>">
					<?php echo Form::label('email', trans('orchestra/foundation::label.users.email'), array('class' => 'control-label')); ?>
					<div class="controls">
						<?php echo Form::input('email', 'email', '', array('required' => true, 'class' => 'span12')); ?>
						<?php echo $errors->first('email', '<p class="help-block">:message</p>'); ?>
					</div>
				</div>

			</fieldset>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><?php echo Orchestra\Site::get('title', 'Submit'); ?></button>
			</div>

		<?php echo Form::close(); ?>

	</div>

</div>

@stop
