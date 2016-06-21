@extends('orchestra/foundation::layouts.landing')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">{{ trans('orchestra/foundation::title.login') }}</h4>
  </div>

  <div class="panel-body">
    {{ Form::open(['url' => handles('orchestra::login'), 'action' => 'POST']) }}
      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        {{ Form::label('email', trans("orchestra/foundation::label.users.email")) }}
        {{ Form::input('text', 'email', old('email'), ['required' => true, 'tabindex' => 1, 'class' => 'form-control']) }}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', trans('orchestra/foundation::label.users.password')) }}
        {{ Form::input('password', 'password', '', ['required' => true, 'tabindex' => 2, 'class' => 'form-control']) }}
      </div>

      <div class="row">
        <div class="col-sm-6">
          <div class="checkbox">
            {{ Form::checkbox('remember', 'yes', false, ['tabindex' => 3, 'id' => 'remember']) }}
            <label for="remember">{{ trans('orchestra/foundation::title.remember-me') }}</label>
          </div>
        </div>
        <div class="col-sm-6 text-right">
          <button type="submit" class="btn btn-primary">{{ trans('orchestra/foundation::title.login') }}</button>
          @if(memorize('site.registrable', false))
          <a href="{{ handles('orchestra::register') }}" class="btn btn-link">
            {{ trans('orchestra/foundation::title.register') }}
          </a>
          @endif
        </div>
      </div>
    {{ Form::close() }}
  </div>

  <div class="panel-footer">
    <a href="{{ handles('orchestra::forgot') }}">Forgot your password?</a>
  </div>
</div>
@stop
