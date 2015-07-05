<script>
jQuery(function onSettingPageReady($) { 'use strict';
  var email_driver, get_container;

  email_driver = $('select[name="email_driver"]');

  get_container = function (node) {
    return $(node).parent().parent().parent();
  };

  // Listen to email.driver changed event.
  Javie.on('setting.changed: email.driver', function listen_to_email_driver_changes (e, self) {
    var value, smtp;

    value = self.value ? self.value : '';
    smtp  = ['email_host', 'email_port', 'email_address', 'email_username', 'email_password', 'email_encryption'];

    get_container('input[name^="email_"]').hide();
    get_container('select[name^="email_region"]').hide();
    get_container('input[name="email_queue"]').hide();

    switch (value) {
      case 'smtp' :
        $.each(smtp, function(index, name) {
          get_container('input[name="'+name+'"]').show();
        });

        break;
      case 'sendmail' :
        get_container('input[name^="email_address"]').show();
        get_container('input[name^="email_sendmail"]').show();
        break;
      case 'ses':
        get_container('input[name^="email_key"]').show();
        get_container('input[name^="email_secret"]').show();
        get_container('select[name^="email_region"]').show();
        break;
      case 'mailgun':
        get_container('input[name^="email_secret"]').show();
        get_container('input[name^="email_domain"]').show();
        break;
      case 'mandrill':
        get_container('input[name^="email_secret"]').show();
        break;
      default :
        get_container('input[name^="email_address"]').show();
        break;
    }
  });

  // bind onChange event to publish an event.
  email_driver.on('change', function on_change_email_driver (e) {
    Javie.trigger('setting.changed: email.driver', [e, this]);
  });

  // lets trigger an onChange event.
  email_driver.trigger('change');
});
</script>
