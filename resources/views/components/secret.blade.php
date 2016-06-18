<secret
  action="{{ $action }}"
  element="{{ $field }}"
  title="{{ trans("orchestra/foundation::label.email.{$action}") }}"
  cancel="{{ trans('orchestra/foundation::label.cancel') }}"
  value="{!! str_limit(str_repeat('*', strlen($value)), 15) !!}">
</secret>
