<?php use Illuminate\Support\Facades\Form; ?>
<div id="cancel_password_container">
	<a href="#" id="cancel_password_button" class="btn btn-mini btn-info">
		<?php echo trans('orchestra/foundation::label.cancel'); ?>
	</a>
</div>
<div id="change_password_container">
	<span><?php echo str_repeat('*', strlen($model->email_password)); ?></span>&nbsp;&nbsp;
	<a href="#" id="change_password_button" class="btn btn-mini btn-warning">
		<?php echo trans('orchestra/foundation::label.email.change_password'); ?>
	</a>
	<?php echo Form::hidden('change_password', 'no'); ?>
</div>
