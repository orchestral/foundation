<?php

use Illuminate\Support\Facades\URL;
use Orchestra\Support\Facades\Site;

$title       = Site::get('title');
$description = Site::get('description'); ?>

<div class="main-header">
	<div class="container">
		<?php if (Site::get('header::add-button')) : ?>
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
</div>
