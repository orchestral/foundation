@extends('orchestra/foundation::layouts.app')

@set_meta('header::add-button', true)

@section('content')
<div class="row">
  <div class="col-xs-12">
    <div class="panel panel-default">
      <div class="panel-body">
        @include('orchestra/foundation::users._search')
        {{ $table }}
      </div>
    </div>
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  Platform.watch('/', function() {
    $('input[role="keyword"]').first().focus()
    return false
  })

  var app = new App({
    data: {
      sidebar: {
        active: 'users'
      }
    }
  }).$mount('body')
</script>
@endpush
