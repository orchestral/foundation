@inject('factory', 'Orchestra\Contracts\Extension\Factory')

@php
use Illuminate\Support\Fluent;
@endphp

<table class="table table-hover">
  <thead>
    <tr>
      <th>{{ trans('orchestra/foundation::label.extensions.name') }}</th>
      <th>{{ trans('orchestra/foundation::label.description') }}</th>
    </tr>
  </thead>
  <tbody>
    @forelse($extensions as $name => $extension)
    @php
    $extension = new Fluent($extension);
    $activated = $factory->activated($name);
    $started = $factory->started($name);
    @endphp
    <tr>
      <td>
        <strong>
          @if(! $started)
            {{ $extension->name }}
          @else
            <a href="{!! handles("orchestra::extensions/{$name}/configure") !!}">
              {{ $extension->name }}
            </a>
          @endif
        </strong>
        <div class="pull-right btn-group">
          @if(! ($started || $activated))
            <a href="{!! handles("orchestra::extensions/{$name}/activate") !!}"
              data-method="POST"
              class="btn btn-primary btn-xs btn-label"
            >
              {{ trans('orchestra/foundation::label.extensions.actions.activate') }}
            </a>
          @else
            <a href="{!! handles("orchestra::extensions/{$name}/deactivate") !!}"
              data-method="POST"
              class="btn btn-warning btn-xs btn-label"
            >
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
    @empty
    <tr>
      <td colspan="2">{{ trans('orchestra/foundation::label.no-extension') }}</td>
    </tr>
    @endforelse
  </tbody>
</table>
