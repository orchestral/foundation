@extends('orchestra/foundation::layout.main')

<?php 

use Illuminate\Support\Fluent,
	Orchestra\Extension; ?>

@section('content')

<div class="row-fluid">

	@include('orchestra/foundation::layout.widgets.header')

	<table class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>{{ trans('orchestra/foundation::label.extensions.name') }}</th>
				<th>{{ trans('orchestra/foundation::label.description') }}</th>
			</tr>
		</thead>
		<tbody>
			@if (empty($extensions))
			<tr>
				<td colspan="2">{{ trans('orchestra/foundation::label.no-extension') }}</td>
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
							{{ $extension->name }}
						@else
							{{ Html::link(handles("orchestra/foundation::extensions/configure/{$uid}"), $extension->name) }}
						@endif
					</strong>
					<div class="pull-right btn-group">
						@if ( ! ($started or $active))
							{{ Html::link(handles("orchestra/foundation::extensions/activate/{$uid}"), trans('orchestra/foundation::label.extensions.actions.activate'), array('class' => 'btn btn-primary btn-mini')) }}
						@else
							{{ Html::link(handles("orchestra/foundation::extensions/deactivate/{$uid}"), trans('orchestra/foundation::label.extensions.actions.deactivate'), array('class' => 'btn btn-warning btn-mini')) }}
						@endif

					</div>
				</td>
				<td>
					<p>{{ $extension->description }}</p>

					<span class="meta">
						{{ trans('orchestra/foundation::label.extensions.version', array('version' => $extension->version )) }} |
						{{ trans('orchestra/foundation::label.extensions.author', array('author' => Html::link($extension->url ?: '#', $extension->author))) }}
					</span>
				</td>
			</tr>
			@endforeach
			@endif
		</tbody>
	</table>

</div>

@stop
