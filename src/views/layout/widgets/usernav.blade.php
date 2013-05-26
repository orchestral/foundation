@if (Orchestra\Site::get('navigation::usernav', true))
<ul class="nav pull-right">
	<li class="dropdown" id="user-menu">
		<a href="#user-menu" rel="user-menu" class="btn navbar-btn dropdown-toggle" data-toggle="dropdown">
			<i class="icon-user"></i> {{ ( ! Auth::guest() ? Auth::user()->fullname : trans('orchestra/foundation::title.login')) }}
		</a> 
		
		@if (Auth::check())

		<ul class="dropdown-menu">
			
			<li>{{ HTML::link(handles('orchestra/foundation::account'), trans('orchestra/foundation::title.account.profile')) }}</li>
			<li>{{ HTML::link(handles('orchestra/foundation::account/password'), trans('orchestra/foundation::title.account.password')) }}</li>
			<li class="divider"></li>
			<li>{{ HTML::link(handles('orchestra/foundation::logout'), trans('orchestra/foundation::title.logout')) }}</li>
		</ul>

		@endif

	</li>
</ul>
@endif
