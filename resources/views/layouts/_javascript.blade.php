@inject('user', 'Illuminate\Contracts\Auth\Authenticatable')

@php
$asset = app('orchestra.asset')->container('orchestra/foundation::footer');
$asset->script('orchestra', 'packages/orchestra/foundation/js/orchestra.js');
@endphp

{{ $asset }}

<script>
  Platform.route("{{ Request::path() }}")
  Javie.put('csrf.token', "{{ csrf_token() }}")
</script>
@placeholder('orchestra.layout: footer')
@stack('orchestra.footer')
<script>
  if (_.isObject(app) && app instanceof Vue) {
    app.$set('sidebar.menu', {!! app('orchestra.widget')->make('menu.'.get_meta('html::menu', 'orchestra'))->toJson() !!})
    @unless(is_null($user))
    app.$set('user', {!! $user->toJson() !!})
    @endunless
  }
</script>
