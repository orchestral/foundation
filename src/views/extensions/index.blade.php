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
			<?php if (empty($extensions)) : ?>
			<tr>
				<td colspan="2"><?php echo trans('orchestra/foundation::label.no-extension'); ?></td>
			</tr>
			<?php else : 
			foreach ($extensions as $name => $extension) :
				$extension = new Fluent($extension); ?>
			<tr>
				<td>
					<strong>
						<?php 
						$active  = Extension::isActive($name);
						$started = Extension::started($name);
						$uid     = str_replace('/', '.', $name);

						if ( ! ($started)) :
							echo $extension->name;
						else : ?>
							<a href="<?php echo handles("orchestra/foundation::extensions/configure/{$uid}"); ?>">
								<?php echo $extension->name; ?>
							</a>
						<?php endif; ?>
					</strong>
					<div class="pull-right btn-group">
						<?php if ( ! ($started or $active)) : ?>
							<a href="<?php echo handles("orchestra/foundation::extensions/activate/{$uid}"); ?>" class="btn btn-primary btn-mini">
								<?php echo trans('orchestra/foundation::label.extensions.actions.activate'); ?>
							</a>
						<?php else : ?>
							<a href="<?php echo handles("orchestra/foundation::extensions/deactivate/{$uid}"); ?>" class="btn btn-warning btn-mini">
								<?php echo trans('orchestra/foundation::label.extensions.actions.deactivate'); ?>
							</a>
						<?php endif; ?>

					</div>
				</td>
				<td>
					<p>
						<?php echo $extension->description; ?>
					</p>

					<span class="meta">
						<?php echo trans('orchestra/foundation::label.extensions.version', array('version' => $extension->version )); ?> |
						<?php echo trans('orchestra/foundation::label.extensions.author', array('author' => '<a href="'.($extension->url ?: '#').'">'.$extension->author.'</a>')); ?>
					</span>
				</td>
			</tr>
			<?php endforeach;
			endif; ?>
		</tbody>
	</table>

</div>

@stop
