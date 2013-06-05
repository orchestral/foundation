<?php

$title       = Orchestra\Site::get('title');
$description = Orchestra\Site::get('description'); ?>

<div class="page-header">
	<?php if (Orchestra\Site::get('header::add-button')) : ?>
	<div class="pull-right">
		<a href="<?php echo URL::current(); ?>/create" class="btn btn-primary">
			<?php echo trans('orchestra/foundation::label.add'); ?>
		</a>
	</div>
	<?php endif; ?>
	
	<h2><?php echo $title ?: ''; 
		if ( ! empty($description)) : ?>
		<small><?php echo $description ?: ''; ?></small>
		<?php endif; ?>
	</h2>
</div>
