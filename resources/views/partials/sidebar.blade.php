<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    @auth
    <ul class="navbar-nav mr-auto">
        <li><a href="{{ route('principles.home') }}">Principles</a></li>
        <li><a href="{{ route('missions.home') }}">Missions</a></li>
        <li><a href="{{ route('goals.home') }}">Goals</a></li>
        <li><a href="{{ route('tasks.index') }}">My Tasks</a></li>
    </ul>
    @endauth
</nav>
