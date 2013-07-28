<?php 

use Illuminate\Support\Facades\Auth;
use Orchestra\Support\Facades\Site;

if (Site::get('navigation::usernav', true)) : ?>
<ul class="nav navbar-nav pull-right">
	<li class="dropdown" id="user-menu">
		<a href="#user-menu" rel="user-menu" class="dropdown-toggle" data-toggle="dropdown">
			<i class="icon-user"></i> <?php echo ( ! Auth::guest() ? Auth::user()->fullname : trans('orchestra/foundation::title.login')); ?>
		</a> 
		
		<?php if (Auth::check()) : ?>

		<ul class="dropdown-menu">
			<li>
				<a href="<?php echo handles('orchestra::account'); ?>">
					<?php echo trans('orchestra/foundation::title.account.profile'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo handles('orchestra::account/password'); ?>">
					<?php echo trans('orchestra/foundation::title.account.password'); ?>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="<?php echo handles('orchestra::logout'); ?>">
					<?php echo trans('orchestra/foundation::title.logout'); ?>
				</a>
			</li>
		</ul>

		<?php endif; ?>

	</li>
</ul>
<?php endif; ?>
