@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
  <div class="col-xs-12 col-sm-3" v-for="item in dash">
    <dash :value="item.value" :title="item.title" :color="item.color" :icon="item.icon" :prefix="item.prefix" :suffix="item.suffix"></dash>
  </div>
</div>
@include('orchestra/foundation::dashboard._welcome')
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('home').$mount('body')
  app.$set('dash', {!! app('orchestra.widget')->make('dash.orchestra')->toJson() !!})
</script>
@endpush
