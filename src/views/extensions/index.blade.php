@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Extension; ?>

@section('content')

<div class="row">

	@include('orchestra/foundation::layout.widgets.header')

	<table class="table table-striped">
		<thead>
			<tr>
				<th><?php echo trans('orchestra/foundation::label.extensions.name'); ?></th>
				<th><?php echo trans('orchestra/foundation::label.description'); ?></th>
			</tr>
		</thead>
		<tbody>
			@if (empty($extensions))
			<tr>
				<td colspan="2"><?php echo trans('orchestra/foundation::label.no-extension'); ?></td>
			</tr>
			@else
			@foreach ($extensions as $name => $extension)
			<?php $extension = new Fluent($extension); ?>
			<tr>
				<td>
					<strong>
						<?php 
						$active  = Extension::isActive($name);
						$started = Extension::started($name);
						$uid     = str_replace('/', '.', $name); ?>
						@if ( ! ($started))
							<?php echo $extension->name; ?>
						@else
							<a href="<?php echo handles("orchestra/foundation::extensions/configure/{$uid}"); ?>">
								<?php echo $extension->name; ?>
							</a>
						@endif
					</strong>
					<div class="pull-right btn-group">
						@if ( ! ($started or $active))
							<a href="<?php echo handles("orchestra/foundation::extensions/activate/{$uid}"); ?>" class="btn btn-primary btn-mini">
								<?php echo trans('orchestra/foundation::label.extensions.actions.activate'); ?>
							</a>
						@else
							<a href="<?php echo handles("orchestra/foundation::extensions/deactivate/{$uid}"); ?>" class="btn btn-warning btn-mini">
								<?php echo trans('orchestra/foundation::label.extensions.actions.deactivate'); ?>
							</a>
						@endif

					</div>
				</td>
				<td>
					<p>
						<?php echo $extension->description; ?>
					</p>

					<span class="meta">
						<?php echo trans('orchestra/foundation::label.extensions.version', array('version' => $extension->version )); ?> |
						<?php echo trans('orchestra/foundation::label.extensions.author', array('author' => HTML::link($extension->url ?: '#', $extension->author))); ?>
					</span>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>

</div>

@stop
