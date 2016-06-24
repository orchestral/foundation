<!DOCTYPE html>
<html lang="en">
  <head>
    @include('orchestra/foundation::layouts._header')
  </head>
  <body>
    @if(get_meta('html::navbar', true))
    <div class="container-fluid">
      @include('orchestra/foundation::layouts._navbar')
    </div>
    @endif

    <div class="container">
      <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3">
          <div class="sign__container">
            @include('orchestra/foundation::components.messages')
            @yield('content')
          </div>
        </div>
      </div>
    </div>

    @include('orchestra/foundation::layouts._javascript')
  </body>
</html>
