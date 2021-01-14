@auth

<!-- Top Of navbar -->
<a class="navbar-brand " href="{{ url('/tasks') }}">
    {{ config('app.name', 'goGeet') }}
</a>

<!-- navigation -->
<ul class="navbar-nav mr-auto">
    <li><a href="{{ route('principles.home') }}">Principles</a></li>
    <li><a href="{{ route('missions.home') }}">Missions</a></li>
    <li><a href="{{ route('goals.home') }}">Goals</a></li>
    <li><a href="{{ route('tasks.index') }}">Tasks</a></li>
</ul>
@endauth
