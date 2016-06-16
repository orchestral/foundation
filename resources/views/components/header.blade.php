<h3 class="page-header">
  <div class="pull-right">
  @if(get_meta('header::add-button'))
    <a href="{!! URL::current() !!}/create" class="btn btn-primary">
      {{ trans('orchestra/foundation::label.add') }}
    </a>
  @endif
  @yield('header::right')
  </div>

  {{ $title }}
  @if (!empty($description))
  <small>{{ $description }}</small>
  @endif
</h3>
