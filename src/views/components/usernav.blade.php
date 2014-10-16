<?php $user = app('auth')->user(); ?>

@if (app('orchestra.site')->get('navigation::usernav', true))
<ul class="nav navbar-nav navbar-right">
	<li class="dropdown" id="user-menu">
		<a href="#user-menu" rel="user-menu" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-user"></i>
			&nbsp;
			{{ $user->fullname or trans('orchestra/foundation::title.login') }}
		</a>
		@unless (is_null($user))
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
		@endunless
	</li>
</ul>
@endif
