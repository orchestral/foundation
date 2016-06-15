<!DOCTYPE html>
<html lang="en">
    <head>
        @include('orchestra/foundation::layouts._header')
    </head>
    <body>
        <div class="{{ get_meta('body::html', 'wrapper') }}">
            @include('orchestra/foundation::layouts._sidebar')

            <div class="container-fluid">
                @include('orchestra/foundation::layouts._navbar')

                <div class="row">
                    <div class="columns twelve">
                        @include('orchestra/foundation::components.header', [
                            'title' => get_meta('title', ''),
                            'description' => get_meta('description')
                        ])
                    </div>
                </div>

                <div class="row">
                    <div class="columns twelve">
                        @include('orchestra/foundation::components.messages')
                        @yield('content')
                    </div>
                </div>

                <div class="row">
                    <div class="columns twelve">
                        @include('orchestra/foundation::layouts._footer')
                    </div>
                </div>
            </div>
        </div>

        @include('orchestra/foundation::layouts._javascript')
    </body>
</html>
