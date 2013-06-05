<?php $attributes = HTML::decorate($navbar->attributes ?: array(), array('class' => 'navbar')); ?>

<div<?php echo HTML::attributes($attributes); ?>>
	<div class="container">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".<?php echo $navbar->id; ?>-responsive-collapse">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a href="<?php echo $navbar->url; ?>" class="navbar-brand">
			<?php echo $navbar->title; ?>
		</a>
		<div class="nav-collapse collapse <?php echo $navbar->id; ?>-responsive-collapse">	
		<?php echo $navbar->menu; ?>
		<?php echo $navbar->subMenu; ?>
		</div>
	</div>
</div>
