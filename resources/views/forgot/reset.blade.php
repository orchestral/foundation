@extends('orchestra/foundation::layouts.sign')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <h4 class="panel-title">{{ trans('orchestra/foundation::title.reset-password') }}</h4>
  </div>

  <div class="panel-body">
    {{ Form::open(['url' => handles("orchestra::forgot/reset"), 'method' => 'POST']) }}
      <input type="hidden" name="token" value="{!! $token !!}">
      <div class="form-group{!! $errors->has('email') ? ' has-error' : '' !!}">
        {{ Form::label('email', trans('orchestra/foundation::label.users.email'), ['class' => 'control-label']) }}
        {{ Form::input('email', 'email', isset($email) ? $email : old('email'), ['required' => true, 'class' => 'form-control']) }}
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        {{ Form::label('password', trans('orchestra/foundation::label.users.password'), ['class' => 'control-label']) }}
        {{ Form::password('password', ['required' => true, 'class' => 'form-control']) }}
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
        {{ Form::label('password_confirmation', trans('orchestra/foundation::label.account.confirm_password'), ['class' => 'control-label']) }}
        {{ Form::password('password_confirmation', ['required' => true, 'class' => 'form-control']) }}
        {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
      </div>
      <div class="row">
        <div class="columns six-sm offset-by-six-sm text-right">
          <button type="submit" class="btn btn-primary">
            {{ get_meta('title', 'Submit') }}
          </button>
        </div>
      </div>
    {{ Form::close() }}
  </div>

  <div class="panel-footer">
    <a href="{{ handles('orchestra::login') }}"><i class="fa fa-arrow-left"></i> {{ trans('orchestra/foundation::title.login') }}</a>
  </div>
</div>
@stop
