<div wire:poll.5s>
    <div class="container-fluid">
    <div class='row'>
        @foreach($teams as $team=>$players)
        @php $class = match($team){ 'zombies' => "danger", 'humans' => 'primary', 'spectator' => 'secondary'} @endphp
        <div class="col-md-12 mt-3">
            <div class="h4 bg-{{ $class }} m-0">{{ ucfirst($team) }} - {{ count($players) }}</div>
            <table class="table table-striped m-0">
                <thead class="table-dark g-0 p-0">
                    <td>Player</td>
                    <td>Points Earned</td>
                    <td>Time online</td>
                </thead>
                <tbody>
                        @foreach ($players as $player)
                        <tr class="table-{{ $class }}">
                            <td><a href="{{ route('player.show', $player->player_id) }}" class="link-dark text-decoration-none">{{ $player->name }}</a></td>
                            <td>{{ $player->skill_change }}</td>
                            <td class="small">{{ $player->time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endforeach
    </div>
    </div>
</div>
