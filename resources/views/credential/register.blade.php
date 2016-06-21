@extends('orchestra/foundation::layouts.landing')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">{{ trans('orchestra/foundation::title.register') }}</h4>
  </div>

  <div class="panel-body">
    {{ $form->layout('orchestra/foundation::components.form-inline', ['button' => ['class' => 'text-right']]) }}
  </div>

  <div class="panel-footer">
    <a href="{{ handles('orchestra::login') }}"><i class="fa fa-arrow-left"></i> {{ trans('orchestra/foundation::title.login') }}</a>
  </div>
</div>
@stop
