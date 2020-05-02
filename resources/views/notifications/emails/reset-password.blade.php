@component('mail::message')

# {{ trans('orchestra/foundation::email.forgot.title') }}

Hi {{ $fullname }},

{{ trans('orchestra/foundation::email.forgot.message.intro') }}

@component('mail::button', ['url' => $url])
{{ trans('orchestra/foundation::email.forgot.title') }}
@endcomponent

{{ trans('orchestra/foundation::email.forgot.message.expired_in', compact('expiredIn')) }}

{{ trans('orchestra/foundation::email.forgot.message.outro') }}

Thanks,<br>
{{ memorize('site.name') }}
@endcomponent
