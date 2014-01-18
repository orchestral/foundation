<?php

use Illuminate\Support\Fluent;
use Orchestra\Support\Facades\Extension; ?>
<table class="table table-striped">
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
			<? $extension = new Fluent($extension); ?>
		<tr>
			<td>
				<strong>
					<?
					$active  = Extension::activated($name);
					$started = Extension::started($name);
					$uid     = str_replace('/', '.', $name); ?>

					@if (! ($started))
						{{ $extension->name }}
					@else
						<a href="{{ handles("orchestra::extensions/configure/{$uid}") }}">
							{{ $extension->name }}
						</a>
					@endif
				</strong>
				<div class="pull-right btn-group">
					@if (! ($started || $active))
						<a href="{{ handles("orchestra::extensions/activate/{$uid}") }}" class="btn btn-primary btn-mini">
							{{ trans('orchestra/foundation::label.extensions.actions.activate') }}
						</a>
					@else
						<a href="{{ handles("orchestra::extensions/deactivate/{$uid}") }}" class="btn btn-warning btn-mini">
							{{ trans('orchestra/foundation::label.extensions.actions.deactivate') }}
						</a>
					@endif

				</div>
			</td>
			<td>
				<p>
					{{ $extension->description }}
				</p>

				<span class="meta">
					{{ trans('orchestra/foundation::label.extensions.version', array('version' => $extension->version )) }} |
					{{ trans('orchestra/foundation::label.extensions.author', array('author' => sprintf('<a href="%s" target="_blank">%s</a>', $extension->url ?: '#', $extension->author))) }}
				</span>
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>
