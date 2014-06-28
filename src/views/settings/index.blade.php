@extends('orchestra/foundation::layout.main')

@section('content')
<div class="row">
	<div class="eight columns rounded box">
		{{ $form }}
	</div>
	<div class="four columns">
		@placeholder('orchestra.settings')
		@placeholder('orchestra.helps')
	</div>
</div>

<script>
jQuery(function onSettingPageReady($) { 'use strict';
	var dispatcher, emailDriver, emailPassword, changeContainer,
		cancelContainer, changeButton, cancelButton, hiddenPassword;

	hiddenPassword  = $('input[name="change_password"]');
	changeButton    = $('#change_password_button');
	cancelButton    = $('#cancel_password_button');
	changeContainer = $('#change_password_container').show();
	cancelContainer = $('#cancel_password_container').hide();
	dispatcher      = Javie.make('event');
	emailDriver     = $('select[name="email_driver"]');
	emailPassword   = $('#email_password').hide();

	// Listen to email.driver changed event.
	dispatcher.listen('setting.changed: email.driver', function listenToEmailDriverChange(e, self) {
		var value, smtp;

		value = self.value ? self.value : '';
		smtp  = ['email_host', 'email_port', 'email_address', 'email_username', 'email_password', 'email_encryption'];

		$('input[name^="email_"]').parent().parent().hide();

		$('input[name="email_queue"]').parent().parent().hide();

		switch (value) {
			case 'smtp' :
				$.each(smtp, function(index, name) {
					$('input[name="'+name+'"]').parent().parent().parent().show();
				});

				break;
			case 'sendmail' :
				$('input[name^="email_address"]').parent().parent().show();
				$('input[name^="email_sendmail"]').parent().parent().show();
				break;
			default :
				$('input[name^="email_address"]').parent().parent().parent().show();
				break;
		}
	});

	changeButton.on('click', function(e) {
		e.preventDefault();

		cancelContainer.show();
		changeContainer.hide();
		emailPassword.show();
		hiddenPassword.val('yes');

		return false;
	});

	cancelButton.on('click', function(e) {
		e.preventDefault();

		cancelContainer.hide();
		changeContainer.show();
		emailPassword.hide();
		hiddenPassword.val('no');

		return false;
	});

	// bind onChange event to publish an event.
	emailDriver.on('change', function onChangeEmailDriver(e) {
		dispatcher.fire('setting.changed: email.driver', [e, this]);
	});

	// lets trigger an onChange event.
	emailDriver.trigger('change');
});
</script>
@stop
