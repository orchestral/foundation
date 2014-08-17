
<div class="list-group">
	@foreach ($resources['list'] as $name => $resource)
	@unless (false === value($resource->visible))
	<?php $current = Orchestra\Support\Facades\App::is("orchestra::resources/{$name}*"); ?>
	<a href="{{ resources($name) }}" class="list-group-item {{ $current ? 'active' : '' }}">
		{{ $resource->name }}
		<span class="glyphicon glyphicon-chevron-right pull-right"></span>
	</a>
	@endunless
	@endforeach
</div>
