<div class="sidebar ps-container ps-theme-default">
    <div class="sidebar__close">
        <img src="{{ asset('img/close.svg') }}" alt="Close sidebar">
    </div>

    @include('orchestra/foundation::components.usernav')

    <sidenav :items="sidebar.menu" :active="sidebar.active"></sidenav>
</div>
