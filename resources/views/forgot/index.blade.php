@extends('orchestra/foundation::layouts.extra')

@inject('formbuilder', 'form')
@inject('request', 'request')

@section('content')
<div class="row">
	<div class="six columns offset-by-three">
		{!! $formbuilder->open(['url' => handles('orchestra::forgot'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
			<fieldset>
				<div class="form-group{{ $errors->has('email') ? ' error' : '' }}">
					{!! $formbuilder->label('email', trans('orchestra/foundation::label.users.email'), ['class' => 'three columns control-label']) !!}
					<div class="nine columns">
						{!! $formbuilder->input('email', 'email', $request->old('email'), ['required' => true, 'class' => 'form-control']) !!}
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
		{!! $formbuilder->close() !!}
	</div>
</div>
@stop
