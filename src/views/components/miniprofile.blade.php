<? $user = Illuminate\Support\Facades\Auth::user(); ?>

<div class="dark-blue rounded-top box">
	<div class="box-padding pull-center">
		<h3>{{ $user->fullname }}</h3>
	</div>
</div>
<div class="white rounded-bottom box no-padding list-group">
	<a href="{{ handles('orchestra::account') }}" class="list-group-item">Profile</a>
	<a href="{{ handles('orchestra::logout') }}" class="list-group-item">Logout</a>
</div>
