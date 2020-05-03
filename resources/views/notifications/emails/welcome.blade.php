@component('mail::message')
# {{ trans('orchestra/foundation::email.register.title') }}

{{ trans('orchestra/foundation::email.register.message.intro') }}

@component('mail::panel')
{{ trans('orchestra/foundation::email.register.message.email', \compact('email')) }}<br>
@unless(is_null($password))
{{ trans('orchestra/foundation::email.register.message.password', \compact('password')) }}
@endunless
@endcomponent

Thanks,<br>
{{ memorize('site.name') }}
@endcomponent
