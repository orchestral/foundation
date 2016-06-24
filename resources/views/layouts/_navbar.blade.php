<div class="row">
  <div class="columns twelve-xs">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <div class="collapse navbar-collapse" id="navbar_main">
          <offcanvas element=".wrapper"></offcanvas>
          <a class="navbar-brand navbar-left" href="{{ handles('orchestra::/') }}">
            {{ memorize('site.name', 'Orchestra Platform') }}
          </a>

          <div class="navbar-form navbar-left hidden-xs">
            @yield('navbar-left')
          </div>

          @section('navbar-right')
          <a href="{{ handles('orchestra::logout', ['csrf' => true]) }}" class="btn btn-primary navbar-btn navbar-right v-cloak--hidden" v-if="user">
            {{ trans('orchestra/foundation::title.logout') }}
          </a>
          @show
        </div>
      </div>
    </nav>
  </div>
</div>

