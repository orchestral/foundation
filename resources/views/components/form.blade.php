{{ Form::open(array_merge($grid->attributes(), ['class' => 'form-horizontal'])) }}

@if($token)
{{ csrf_field() }}
@endif

@foreach($grid->hiddens() as $hidden)
{!! $hidden !!}
@endforeach

@foreach($grid->fieldsets() as $fieldset)
  <fieldset{{ HTML::attributes($fieldset->attributes ?: []) }}>
    @if($fieldset->name)
    <legend>{!! $fieldset->name ? $fieldset->name : '' !!}</legend>
    @endif

    @foreach($fieldset->controls() as $control)
    <div class="form-group{{ $errors->has($control->id) ? ' has-error' : '' }}">
      {{ Form::label($control->name, $control->label, ['class' => 'col-md-3 control-label']) }}

      <div class="col-md-9">
        <div>{!! $control->attributes(['class' => 'col-md-12'])->getField($grid->data()) !!}</div>
        @if($control->inlineHelp)
        <span class="help-inline">{!! $control->inlineHelp !!}</span>
        @endif
        @if($control->help)
        <p class="help-block">{!! $control->help !!}</p>
        @endif
        {!! $errors->first($control->id, $format) !!}
      </div>
    </div>
    @endforeach
  </fieldset>
@endforeach

<div class="row">
  <div{!! HTML::attributable(array_get($meta, 'button', []), ['class' => 'col-md-9 col-md-offset-3']) !!}>
    <button type="submit" class="btn btn-primary">
      {!! $submit !!}
    </button>
  </div>
</div>

{{ Form::close() }}
