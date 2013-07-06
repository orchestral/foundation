@extends('orchestra/foundation::layout.main')

@section('content')

<div class="row">

	<div class="eight columns rounded box">
		<?php echo $form; ?>
	</div>

	<div class="four columns">
		@placeholder('orchestra.settings')
		@placeholder('orchestra.helps')
	</div>

</div>

<script>
jQuery(function onSettingPageReady ($) { 'use strict';
	var events, emailDriver, emailPassword;

	events        = new Javie.Events;
	emailDriver   = $('select[name="email_driver"]');
	emailPassword = $('#email_password').hide();

	$('#change_password_button').on('click', function (e) {
		e.preventDefault();
		
		$('input[name="change_password"]').val('yes');
		emailPassword.show();
		$('#change_password_container').hide();

		return false;
	});

	// bind onChange event to publish an event.
	emailDriver.on('change', function onChangeEmailDriver (e) {
		events.fire('setting.changed: email.driver', [e, this]);
	});

	// lets trigger an onChange event.
	emailDriver.trigger('change');
});
</script>

@stop
