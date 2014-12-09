<div class="navbar user-search">
	<form class="navbar-form">
		{!! app('form')->text('q', app('request')->input('q', ''), ['placeholder' => 'Search keyword...', 'role' => 'keyword']) !!}
		{!! app('form')->select('roles[]', $roles, app('request')->input('roles', []), ['multiple' => true, 'placeholder' => 'Roles', 'role' => 'roles']) !!}
		{!! app('form')->submit(trans('orchestra/foundation::label.search.button'), ['class' => 'btn btn-primary']) !!}
	</form>
</div>
