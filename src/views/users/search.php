<div class="navbar user-search">
	<form class="navbar-form">
		<?php echo Form::text('q', $searchKeyword, array('placeholder' => 'Search keyword...', 'role' => 'keyword')); ?>
		<?php echo Form::select('roles[]', $roles, $searchRoles, array('multiple' => true, 'placeholder' => 'Roles', 'role' => 'roles')); ?>
		<?php echo Form::submit(trans('orchestra/foundation::label.search.button'), array('class' => 'btn btn-primary')); ?>
	</form>
</div>
