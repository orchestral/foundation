<script>
Javie.on('setting.changed: email.driver.smtp', function (app) {
  $.each(['email_host', 'email_port', 'email_username', 'email_password', 'email_encryption'], function(index, name) {
    app.container('input[name="'+name+'"]').removeClass('hidden')
  })
})

Javie.on('setting.changed: email.driver.sendmail', function (app) {
  app.container('input[name^="email_sendmail"]').removeClass('hidden')
})

Javie.on('setting.changed: email.driver.ses', function (app) {
  $.each(['email_key', 'email_secret', 'email_region'], function(index, name) {
    app.container('input[name="'+name+'"]').removeClass('hidden')
  })
})

Javie.on('setting.changed: email.driver.mailgun', function (app) {
  $.each(['email_secret', 'email_domain'], function(index, name) {
    app.container('input[name="'+name+'"]').removeClass('hidden')
  })
})

Javie.on('setting.changed: email.driver.mandrill', function (app) {
  app.container('input[name="email_secret"]').removeClass('hidden')
})

Javie.on('setting.changed: email.driver.sparkpost', function (app) {
  app.container('input[name="email_secret"]').removeClass('hidden')
})
</script>
