<div id="cancel_{{ $action }}_container">
  <a href="#" id="cancel_{{ $action }}_button" class="btn btn-mini btn-info cancel_email_hidden_button">
    {{ trans('orchestra/foundation::label.cancel') }}
  </a>
</div>
<div id="{{ $action }}_container">
  <span>{!! str_repeat('*', strlen($model->get($field))) !!}</span>
  &nbsp;&nbsp;
  <a href="#" id="{{ $action }}_button" class="btn btn-mini btn-warning">
    {{ trans("orchestra/foundation::label.email.{$action}") }}
  </a>
  {!! app('form')->hidden("enable_{$action}", 'no') !!}
</div>

@push('orchestra.footer')
<script>
jQuery(function ($) { 'use strict';
  var email_password, change_container, cancel_container, change_button, cancel_button, hidden_password;

  hidden_password = $('input[name="enable_' + '{{ $action }}"]');
  change_button = $('#'+ '{{ $action }}_button');
  cancel_button = $('#cancel_' + '{{ $action }}_button');
  change_container = $('#' + '{{ $action }}_container').show();
  cancel_container = $('#cancel_' + '{{ $action }}_container').hide();
  email_password = $('#' + '{{ $field }}').hide();

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
});
</script>
@endpush
