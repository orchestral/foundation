<div class="navbar user-search">
	<form class="navbar-form">
		{!! Form::text('q', request('q', ''), ['placeholder' => 'Search keyword...', 'role' => 'keyword']) !!}
		{!! Form::select('roles[]', $roles, request('roles', []), ['multiple' => true, 'placeholder' => 'Roles', 'role' => 'roles']) !!}
		{!! Form::submit(trans('orchestra/foundation::label.search.button'), ['class' => 'btn btn-primary']) !!}
	</form>
</div>
