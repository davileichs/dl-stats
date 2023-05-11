<div>
    <div class="row row-flex">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-3">
                    <img src="{{ $player->avatar }}" class="img-thumbnail border border-warning border-2">
                </div>
                <div class="col-md-8">
                    <div class="nav-item dropdown mb-2">
                        <a class="dropdown-toggle  text-decoration-none text-white" href="#" id="dropPlayerName" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class='h2 text-white text-decoration-none'>{{ $player->nickname }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropPlayerName">
                            <li class="mx-3 my-1 text-secondary">Played also as</li>
                            @foreach($also_name as  $name)
                            <li class="mx-3 my-1">{{ $name }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <p class="mt-0"><img src="/images/flags/{{ strtolower($player->flag) }}.gif"> {{ $player->country }}</p>
                    <p class="mt-4"><a class="btn btn-light btn-sm" href="http://steamcommunity.com/profiles/{{ $player->steam_id }}" role="button" target="_blank">Steam profile</a></p>
                    @if ($player->is_online)
                        <p class="text-success h6">Online</p>
                    @else
                        <p class="text-danger">Last Access: {{ $player->last_event }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="border border-2 border-warning rounded-1 p-3 bg-dark">
                <p class="mb-3 h2">Score: {{ $player->skill }}</p>
                <p class="my-3 ">Position: <span class="h4">#{{ $player->ranking }}</span></p>
                <p class="my-3 ">First access time: <span class="h5">{{ $player->createdate }}</a></p>
                <p class="my-3 ">Total Connection: <span class="h6">{{ $player->connection_time }}</a></p>
            </div>
        </div>
    </div>
</div>
