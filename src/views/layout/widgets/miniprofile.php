<?php 

use Illuminate\Support\Facades\Auth;

$user = Auth::user(); ?>

<div class="box dark-blue rounded-top">
	<div class="box-padding pull-center">
		<h3><?php echo $user->fullname; ?></h3>
	</div>
</div>
<div class="box white rounded-bottom no-padding list-group">
	<a href="<?php echo handles('orchestra::account'); ?>" class="list-group-item">Profile</a>
	<a href="<?php echo handles('orchestra::logout'); ?>" class="list-group-item">Logout</a>
</div>
