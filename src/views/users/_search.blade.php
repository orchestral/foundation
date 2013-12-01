<div class="navbar user-search">
	<form class="navbar-form">
		{{ Form::text('q', $searchKeyword, array('placeholder' => 'Search keyword...', 'role' => 'keyword')) }}
		{{ Form::select('roles[]', $roles, $searchRoles, array('multiple' => true, 'placeholder' => 'Roles', 'role' => 'roles')) }}
		{{ Form::submit(trans('orchestra/foundation::label.search.button'), array('class' => 'btn btn-primary')) }}
	</form>
</div>
