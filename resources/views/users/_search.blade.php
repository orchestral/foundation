<div class="user-search pull-left">
  <form class="navbar-form">
    {{ Form::text('q', $search['keyword'], ['placeholder' => 'Search', 'role' => 'keyword']) }}
    {{ Form::select('roles[]', $roles, $search['roles'], ['multiple' => true, 'native-placeholder' => 'Roles', 'role' => 'roles']) }}
    {{ Form::submit(trans('orchestra/foundation::label.search.button'), ['class' => 'btn btn-info']) }}
  </form>
</div>
