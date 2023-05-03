<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="{{ route('server.show', $server->get()) }}">Server</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('players', $server->get()->game) }}">Players</a></li>
            </ul>
        </div>
    </div>
</nav>
