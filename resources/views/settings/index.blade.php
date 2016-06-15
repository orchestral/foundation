@extends('orchestra/foundation::layouts.app')

@section('content')
<div class="row">
	<div class="col-md-8 col-xs-12">
    {{ $form }}
	</div>
	<div class="col-md-4 col-xs-12">
		@placeholder('orchestra.settings')
		@placeholder('orchestra.helps')
	</div>
</div>
@stop

@push('orchestra.footer')
<script>
  var app = new App({
    data: {
      sidebar: {
        active: 'settings'
      }
    },
    methods: {
      driver: function (name) {
        this.container('input[name^="email_"]').addClass('hidden')
        this.container('select[name^="email_region"]').addClass('hidden')
        this.container('input[name="email_queue"]').addClass('hidden')

        Javie.trigger('setting.changed: email.driver', [name, this])
        Javie.trigger('setting.changed: email.driver.'+name, [this])

        this.container('input[name^="email_address"]').removeClass('hidden')
      },
      container: function(node) {
        return $(node).parent().parent().parent()
      },
    }
  }).$mount('body')
</script>
@include('orchestra/foundation::settings._script')
@endpush
