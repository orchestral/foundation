<h3 class="page-header">
  @if(get_meta('header::add-button'))
  <div class="pull-right">
    <a href="{!! URL::current() !!}/create" class="btn btn-primary">
      {{ trans('orchestra/foundation::label.add') }}
    </a>
  </div>
  @endif

  {{ $title }}
  @if (!empty($description))
  <small>{{ $description }}</small>
  @endif
</h3>
