<div class="navbar user-search">
	<form class="navbar-form">
		{!! Form::text('q', array_get($search, 'keyword', ''), ['placeholder' => 'Search', 'role' => 'keyword']) !!}
		{!! Form::select('roles[]', $roles, array_get($search, 'roles', []), ['multiple' => true, 'native-placeholder' => 'Roles', 'role' => 'roles']) !!}
		{!! Form::submit(trans('orchestra/foundation::label.search.button'), ['class' => 'btn btn-primary']) !!}
	</form>
</div>
