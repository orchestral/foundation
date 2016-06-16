@inject('user', 'Illuminate\Contracts\Auth\Authenticatable')

@unless(is_null($user))
<div class="sidebar__user">
  @if(app()->bound('orchestra.avatar'))
  <div class="sidebar-user__avatar">
    <img src="{{ app('orchestra.avatar')->user($user)->setSize(55)->render() }}" alt="...">
  </div>
  @endif

  <a class="sidebar-user__info">
    <h4>{{ $user->fullname }}</h4>
    <p>{{ $user->roles()->first()->name }} <i class="fa fa-caret-down"></i></p>
  </a>
</div>

<nav class="sidebar-user__nav">
  <ul class="sidebar__nav">
    <li>
      <a href="{!! handles('orchestra::account') !!}">
        <i class="fa fa-edit"></i> {{ trans('orchestra/foundation::title.account.profile') }}
      </a>
    </li>
    <li>
      <a href="{!! handles('orchestra::account/password') !!}">
        <i class="fa fa-unlock"></i> {{ trans('orchestra/foundation::title.account.password') }}
      </a>
    </li>
    <li>
      <a href="{!! handles('orchestra::logout') !!}">
        <i class="fa fa-sign-out"></i> {{ trans('orchestra/foundation::title.logout') }}
      </a>
    </li>
  </ul>
</nav>
@endunless
