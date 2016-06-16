@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
	<div class="col-xs-12">
    <div class="panel panel-default">
      <div class="panel-body">
        @include('orchestra/foundation::extensions._table')
      </div>
    </div>
	</div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = Platform.make('app').nav('extensions').$mount('body')
</script>
@endpush
