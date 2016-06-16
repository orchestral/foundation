@extends('orchestra/foundation::layouts.app')

@section('content')
@include('orchestra/foundation::dashboard._welcome')
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('home').$mount('body')
</script>
@endpush
