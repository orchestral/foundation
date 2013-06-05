<?php if (Orchestra\Site::get('navigation::usernav', true)) : ?>
<ul class="nav pull-right">
	<li class="dropdown" id="user-menu">
		<a href="#user-menu" rel="user-menu" class="btn navbar-btn dropdown-toggle" data-toggle="dropdown">
			<i class="icon-user"></i> <?php echo ( ! Auth::guest() ? Auth::user()->fullname : trans('orchestra/foundation::title.login')); ?>
		</a> 
		
		<?php if (Auth::check()) : ?>

		<ul class="dropdown-menu">
			<li>
				<a href="<?php echo handles('orchestra/foundation::account'); ?>">
					<?php echo trans('orchestra/foundation::title.account.profile'); ?>
				</a>
			</li>
			<li>
				<a href="<?php echo handles('orchestra/foundation::account/password'); ?>">
					<?php echo trans('orchestra/foundation::title.account.password'); ?>
				</a>
			</li>
			<li class="divider"></li>
			<li>
				<a href="<?php echo handles('orchestra/foundation::logout'); ?>">
					<?php echo trans('orchestra/foundation::title.logout'); ?>
				</a>
			</li>
		</ul>

		<?php endif; ?>

	</li>
</ul>
<?php endif; ?>
