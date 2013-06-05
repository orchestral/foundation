@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-2">
		<div class="list-group">
			@foreach ($resources['list'] as $name => $resource)
			<a href="<?php echo handles("orchestra/foundation::resources/{$name}"); ?>" 
				class="list-group-item <?php echo Request::is("*/resources/{$name}*") ? 'active' : ''; ?>">
				<?php echo $resource->name; ?>
				<span class="glyphicon glyphicon-chevron-right"></span>
			</a>
			@endforeach
		</div>
	</div>

	<div class="col col-lg-10">
		<?php echo $content; ?>
	</div>
	
</div>

@stop
