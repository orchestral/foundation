@extends('orchestra/foundation::layouts.app')

@section('content')
@include('orchestra/foundation::dashboard._welcome')
@stop

@push('orchestra.footer')
<script>
  var app = new App({
    data: {
      sidebar: {
        active: 'home'
      }
    }
  }).$mount('body')
</script>
@endpush
