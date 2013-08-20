<?php 

use Illuminate\Support\Facades\HTML;

$attributes = HTML::decorate($navbar->attributes ?: array(), array('class' => 'navbar', 'role' => 'navigation')); ?>

<nav<?php echo HTML::attributes($attributes); ?>>
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".<?php echo $navbar->id; ?>-responsive-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="<?php echo $navbar->url; ?>" class="navbar-brand">
				<?php echo $navbar->title; ?>
			</a>
		</div>

		<div class="collapse navbar-collapse <?php echo $navbar->id; ?>-responsive-collapse">	
			<?php echo $navbar->left; ?>
			<?php echo $navbar->right; ?>
			<?php echo $navbar->menu; ?>
		</div>
	</div>
</nav>
