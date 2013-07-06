@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="three columns">
		<div class="list-group">
			<?php foreach ($resources['list'] as $name => $resource) : ?>
			<a href="<?php echo resources($name); ?>" 
				class="list-group-item <?php echo Request::is("*/resources/{$name}*") ? 'active' : ''; ?>">
				<?php echo $resource->name; ?>
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
			<?php endforeach; ?>
		</div>
		
		@placeholder("orchestra.resources: {$resource->name}")
		@placeholder('orchestra.resources')
	</div>

	<div class="nine columns">
		<?php echo $content; ?>
	</div>
	
</div>

@stop
