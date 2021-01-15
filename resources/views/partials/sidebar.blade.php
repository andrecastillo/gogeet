@auth

<!-- Top Of navbar -->
<a class="navbar-brand " href="{{ url('/tasks') }}">
    {{ config('app.name', 'goGeet') }}
</a>

<!-- navigation -->
<ul class="navbar-nav mr-auto">
    <a class="nav-item nav-link" style="font-size: 1.5rem;" href="{{ route('principles.home') }}">Principles</a>
    <a class="nav-item nav-link" style="font-size: 1.5rem;" href="{{ route('missions.home') }}">Missions</a>
    <a class="nav-item nav-link" style="font-size: 1.5rem;" href="{{ route('goals.home') }}">Goals</a>
    <a class="nav-item nav-link" style="font-size: 1.5rem;" href="{{ route('tasks.index') }}">Tasks</a>
</ul>
@endauth
