<ul class="nav navbar-nav" role="menu">
	<?php foreach ($menu as $item) : 
		if (1 > count($item->childs)) : ?>
			<li>
				<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
			</li>
		<?php else : ?>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $item->title; ?></a>
				<ul class="dropdown-menu">
					<li>
						<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
					</li>
					<li class="divider"></li>

					<?php foreach ($item->childs as $child) : 
						$grands = $child->childs; ?>

						<li class="<?php echo ( ! empty($grands) ? "dropdown-submenu" : "normal" ); ?>">
							<a href="<?php echo $child->link; ?>"><?php echo $child->title; ?></a>
							<?php if ( ! empty($child->childs)) : ?>
							<ul class="dropdown-menu">
								<?php foreach ($child->childs as $grand) : ?>
								<li>
									<a href="<?php echo $grand->link; ?>"><?php echo $grand->title; ?></a>
								</li>
								<?php endforeach; ?>
							</ul>
							<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>
