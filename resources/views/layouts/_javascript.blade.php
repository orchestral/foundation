@inject('user', 'Illuminate\Contracts\Auth\Authenticatable')

@php
$asset = app('orchestra.asset')->container('orchestra/foundation::footer');
$asset->script('vendor', 'js/vendor.js');
$asset->script('orchestra', 'js/orchestra.js', ['vendor']);
@endphp

{{ $asset }}

<script>
Platform.route("{{ Request::path() }}")
</script>

@placeholder('orchestra.layout: footer')
@stack('orchestra.footer')

<script>
if (app instanceof App) {
  app.$set('sidebar.menu', {!! app('orchestra.platform.menu')->toJson() !!})
  app.$set('user', {!! $user->toJson() !!})
}
</script>
