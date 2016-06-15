@extends('orchestra/foundation::layouts.app')

@php
$label = ['class' => 'col-md-3 control-label'];
@endphp

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-body">
        {{ Form::open(['url' => handles('orchestra::publisher/ftp'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
          <fieldset>
            <div class="form-group{{ $errors->has('host') ? ' has-error' : '' }}">
              {{ Form::label('host', trans('orchestra/foundation::label.extensions.publisher.host'), $label) }}
              <div class="col-md-9">
                {{ Form::text('host', old('host'), ['class' => 'form-control']) }}
                {!! $errors->first('host', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <div class="form-group{{ $errors->has('user') ? ' has-error' : '' }}">
              {{ Form::label('user', trans('orchestra/foundation::label.extensions.publisher.user'), $label) }}
              <div class="col-md-9">
                {{ Form::text('user', old('user'), ['class' => 'form-control']) }}
                {!! $errors->first('user', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
              {{ Form::label('password', trans('orchestra/foundation::label.extensions.publisher.password'), $label) }}
              <div class="col-md-9">
                {{ Form::password('password', ['class' => 'form-control']) }}
                {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <div class="form-group">
              {{ Form::label('connection-type', trans('orchestra/foundation::label.extensions.publisher.connection-type'), $label) }}
              <div class="col-md-9">
                {{ Form::select('connection-type', ['ftp' => 'FTP', 'sftp' => 'SFTP'], old('connection-type', 'ftp'), ['role' => 'switcher']) }}
              </div>
            </div>
            <div class="row">
              <div class="col-md-9 col-md-offset-3">
                <button type="submit" class="btn btn-primary">{{ trans('orchestra/foundation::title.login') }}</button>
              </div>
            </div>
          </fieldset>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  <div class="col-md-4">
    @placeholder('orchestra.publisher')
    @placeholder('orchestra.helps')
  </div>
</div>
@stop
