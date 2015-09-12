@inject('factory', 'Orchestra\Contracts\Extension\Factory')

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
			#{{ $extension = new Illuminate\Support\Fluent($extension) }}
		<tr>
			<td>
				<strong>
					#{{ $activated = $factory->activated($name) }}
					#{{ $started = $factory->started($name) }}

					@if (! $started)
						{{ $extension->name }}
					@else
						<a href="{!! handles("orchestra::extensions/{$name}/configure") !!}">
							{{ $extension->name }}
						</a>
					@endif
				</strong>
				<div class="pull-right btn-group">
					@if (! ($started || $activated))
						<a href="{!! handles("orchestra::extensions/{$name}/activate", ['csrf' => true]) !!}" class="btn btn-primary btn-mini">
							{{ trans('orchestra/foundation::label.extensions.actions.activate') }}
						</a>
					@else
						<a href="{!! handles("orchestra::extensions/{$name}/deactivate", ['csrf' => true]) !!}" class="btn btn-warning btn-mini">
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
					{{ trans('orchestra/foundation::label.extensions.version', ['version' => $extension->version]) }} |
					{!! trans('orchestra/foundation::label.extensions.author', ['author' => sprintf('<a href="%s" target="_blank">%s</a>', $extension->url ?: '#', $extension->author)]) !!}
				</span>
			</td>
		</tr>
		@endforeach
		@endif
	</tbody>
</table>
