@extends('orchestra/foundation::layouts.landing')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">{{ trans('orchestra/foundation::title.login') }}</h4>
  </div>

  <div class="panel-body">
    {{ Form::open(['url' => handles('orchestra::sudo'), 'action' => 'POST']) }}
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', trans('orchestra/foundation::label.users.password')) }}
        {{ Form::input('password', 'password', '', ['required' => true, 'tabindex' => 1, 'class' => 'form-control']) }}
      </div>

      <div class="row">
        <div class="col-sm-12 text-right">
          <button type="submit" class="btn btn-primary">{{ trans('orchestra/foundation::title.login') }}</button>
        </div>
      </div>
    {{ Form::close() }}
  </div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').$mount('body')
</script>
@endpush
