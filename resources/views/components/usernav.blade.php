@inject('user', 'Illuminate\Contracts\Auth\Authenticatable')

@if(get_meta('navigation::usernav', true))
<ul class="nav navbar-nav navbar-right">
	<li class="dropdown" id="user-menu">
	    @if(is_null($user))
		<a href="#user-menu" rel="user-menu" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-user"></i>
			{{ trans('orchestra/foundation::title.login') }}
		</a>
		@else
		<a href="#user-menu" rel="user-menu" class="dropdown-toggle" data-toggle="dropdown">
		    <i class="icon-user"></i>
		    {{ ! is_null($user) ? $user->fullname : trans('orchestra/foundation::title.login') }}
		    <span class="caret"></span>
	    </a>
		<ul class="dropdown-menu">
			<li>
				<a href="{!! handles('orchestra::account') !!}">
					{{ trans('orchestra/foundation::title.account.profile') }}
				</a>
			</li>
			<li>
				<a href="{!! handles('orchestra::account/password') !!}">
					{{ trans('orchestra/foundation::title.account.password') }}
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="{!! handles('orchestra::logout') !!}">
					{{ trans('orchestra/foundation::title.logout') }}
				</a>
			</li>
		</ul>
		@endif
	</li>
</ul>
@endif
