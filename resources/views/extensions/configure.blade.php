@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
	<div class="col-xs-12 col-sm-9">
    @if(data_get($eloquent, 'publishing', true))
    <div class="panel panel-blank">
      <div class="panel-body">
        <a href="{{ handles("orchestra::extensions/{$extension->get('name')}/update") }}"
          data-method="POST"
          class="btn btn-info btn-block"
        >
          {{ trans('orchestra/foundation::label.extensions.actions.update') }}
        </a>
      </div>
    </div>
    @endif

    <div class="panel panel-blank">
      <div class="panel-body">
        {{ $form }}
      </div>
    </div>
	</div>
	<div class="col-xs-12 col-sm-3">
		@placeholder('orchestra.extensions')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('extensions').$mount('body')
</script>
@endpush
