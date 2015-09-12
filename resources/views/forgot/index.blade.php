@extends('orchestra/foundation::layouts.extra')

@section('content')
<div class="row">
	<div class="six columns offset-by-three">
		{!! Form::open(['url' => handles('orchestra::forgot'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('email') ? ' error' : '' }}">
					{!! Form::label('email', trans('orchestra/foundation::label.users.email'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! Form::input('email', 'email', old('email'), ['required' => true, 'class' => 'form-control']) !!}
						{!! $errors->first('email', '<p class="help-block">:message</p>') !!}
					</div>
				</div>
				<div class="row">
					<div class="nine columns offset-by-three">
						<button type="submit" class="btn btn-primary">
							{!! get_meta('title', 'Submit') !!}
						</button>
					</div>
				</div>
			</fieldset>
		{!! Form::close() !!}
	</div>
</div>
@stop
