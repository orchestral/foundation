@inject('formbuilder', 'form')
@inject('request', 'request')

<div class="navbar user-search">
	<form class="navbar-form">
		{!! $formbuilder->text('q', $request->input('q', ''), ['placeholder' => 'Search keyword...', 'role' => 'keyword']) !!}
		{!! $formbuilder->select('roles[]', $roles, $request->input('roles', []), ['multiple' => true, 'placeholder' => 'Roles', 'role' => 'roles']) !!}
		{!! $formbuilder->submit(trans('orchestra/foundation::label.search.button'), ['class' => 'btn btn-primary']) !!}
	</form>
</div>
