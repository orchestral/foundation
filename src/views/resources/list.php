<div class="list-group">
	<?php foreach ($resources['list'] as $name => $resource) : ?>
	<?php if(false === value($resource->visible)): continue; endif; ?>
	<a href="<?php echo resources($name); ?>" 
		class="list-group-item <?php echo Request::is("*/resources/{$name}*") ? 'active' : ''; ?>">
		<?php echo $resource->name; ?>
		<span class="glyphicon glyphicon-chevron-right pull-right"></span>
	</a>
	<?php endforeach; ?>
</div>
