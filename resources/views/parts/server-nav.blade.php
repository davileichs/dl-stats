<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('server.show', session()->get('current.server')) }}">Server</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('players', session()->get('current.server')['game']) }}">Players</a></li>
            </ul>
        </div>
    </div>
</nav>
