{{ Form::open(array_merge($form, array('class' => 'form-horizontal'))) }}

@if ($token)
{{ Form::token() }}
@endif

@foreach ($hiddens as $hidden)
{{ $hidden }}
@endforeach

@foreach ($fieldsets as $fieldset)
	<fieldset{{ HTML::attributes($fieldset->attributes ?: array()) }}>
		@if ($fieldset->name)
		<legend>{{ $fieldset->name ?: '' }}</legend>
		@endif

		@foreach ($fieldset->controls() as $control)
		<div class="form-group{{ $errors->has($control->name) ? ' has-error' : '' }}">
			{{ Form::label($control->name, $control->label, array('class' => 'three columns control-label')) }}

			<div class="nine columns">
				<div>{{ $control->getField($row, $control, array()) }}</div>
				@if ($control->inlineHelp)
				<span class="help-inline">{{ $control->inlineHelp }}</span>
				@endif
				@if ($control->help)
				<p class="help-block">{{ $control->help }}</p>
				@endif
				{{ $errors->first($control->name, $format) }}
			</div>
		</div>
		@endforeach
	</fieldset>
@endforeach

<fieldset>
	<div class="row">
		{{-- Fixed row issue on Bootstrap 3 --}}
	</div>
	<div class="row">
		<div class="nine columns offset-by-three">
			<button type="submit" class="btn btn-primary">
				{{ $submit }}
			</button>
		</div>
	</div>
</fieldset>

{{ Form::close() }}
