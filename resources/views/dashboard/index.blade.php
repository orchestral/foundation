@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
  <div class="col-xs-12 col-sm-3" v-for="item in dash">
    <dash :title="item.title" :value="item.value" :prefix="item.prefix" :suffix="item.suffix" :icon="item.icon" :color="item.color" ></dash>
  </div>
</div>
<div class="row">
  <div class="col-xs-12 col-sm-6" v-for="item in pane">
    <pane :title="item.title" :description="item.description" :html="item.html" :content="item.content" :type="item.type"></pane>
  </div>
</div>
@include('orchestra/foundation::dashboard._welcome')
@stop

@push('orchestra.footer')

@php
$orchestra = app('orchestra.app');
@endphp

<script>
  var app = Platform.make('dashboard').$mount('body')
  app.$set('dash', {!! $orchestra->widget('dash')->toJson() !!})
  app.$set('pane', {!! $orchestra->widget('pane')->toJson() !!})
</script>
@endpush
