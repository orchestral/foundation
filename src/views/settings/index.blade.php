@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="col col-lg-8">
		@include('orchestra/foundation::layout.widgets.header')
		{{ $form }}
	</div>

	<div class="col col-lg-4">
		@placeholder('orchestra.settings')
		@placeholder('orchestra.helps')
	</div>

</div>

<script>
	jQuery(function onSettingPageReady ($) { 'use strict';
		var ev, emailDriver, emailPassword;

		ev = new Javie.Events;

		emailDriver   = $('select[name="email_driver"]');
		emailPassword = $('#email_password').hide();

		$('#change_password_button').on('click', function (e) {
			e.preventDefault();
			
			$('input[name="change_password"]').val('yes');
			emailPassword.show();
			$('#change_password_container').hide();

			return false;
		});

		// Listen to email.driver changed event. 
		ev.listen('setting.changed: email.driver', function listenToEmailDriverChange(e, self) {
			var value, smtp;

			value = self.value ? self.value : '';
			smtp  = ['email_host', 'email_port', 'email_address', 'email_username', 'email_password', 'email_encryption'];

			$('input[name^="email_"]').parent().parent().hide();

			$('input[name="email_queue"]').parent().parent().hide();

			switch (value) {
				case 'smtp' :
					$.each(smtp, function (index, name) {
						$('input[name="'+name+'"]').parent().parent().show();
					});

					break;
				case 'sendmail' :
					$('input[name^="email_address"]').parent().parent().show();
					$('input[name^="email_sendmail"]').parent().parent().show();
					break;
				default :
					$('input[name^="email_address"]').parent().parent().show();
					break;
			}
		});

		// bind onChange event to publish an event.
		emailDriver.on('change', function onChangeEmailDriver (e) {
			ev.fire('setting.changed: email.driver', [e, this]);
		});

		// lets trigger an onChange event.
		emailDriver.trigger('change');
	});
</script>

@stop
