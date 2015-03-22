<script>
jQuery(function onSettingPageReady($) { 'use strict';
  var dispatcher, email_driver, email_password, change_container,
    cancel_container, change_button, cancel_button, hidden_password;

  hidden_password = $('input[name="change_password"]');
  change_button = $('#change_password_button');
  cancel_button = $('#cancel_password_button');
  change_container = $('#change_password_container').show();
  cancel_container = $('#cancel_password_container').hide();
  dispatcher = Javie.make('event');
  email_driver = $('select[name="email_driver"]');
  email_password = $('#email_password').hide();

  // Listen to email.driver changed event.
  dispatcher.listen('setting.changed: email.driver', function listen_to_email_driver_changes (e, self) {
    var value, smtp;

    value = self.value ? self.value : '';
    smtp  = ['email_host', 'email_port', 'email_address', 'email_username', 'email_password', 'email_encryption'];

    $('input[name^="email_"]').parent().parent().parent().hide();
    $('input[name="email_queue"]').parent().parent().parent().hide();

    switch (value) {
      case 'smtp' :
        $.each(smtp, function(index, name) {
          $('input[name="'+name+'"]').parent().parent().parent().show();
        });

        break;
      case 'sendmail' :
        $('input[name^="email_address"]').parent().parent().parent().show();
        $('input[name^="email_sendmail"]').parent().parent().parent().show();
        break;
      case 'mailgun':
        $('input[name^="email_secret"]').parent().parent().parent().show();
        $('input[name^="email_domain"]').parent().parent().parent().show();
        break;
      case 'mandrill':
        $('input[name^="email_secret"]').parent().parent().parent().show();
        break;
      default :
        $('input[name^="email_address"]').parent().parent().parent().show();
        break;
    }
  });

  change_button.on('click', function(e) {
    e.preventDefault();

    cancel_container.show();
    change_container.hide();
    email_password.show();
    hidden_password.val('yes');

    return false;
  });

  cancel_button.on('click', function(e) {
    e.preventDefault();

    cancel_container.hide();
    change_container.show();
    email_password.hide();
    hidden_password.val('no');

    return false;
  });

  // bind onChange event to publish an event.
  email_driver.on('change', function on_change_email_driver (e) {
    dispatcher.fire('setting.changed: email.driver', [e, this]);
  });

  // lets trigger an onChange event.
  email_driver.trigger('change');
});
</script>
