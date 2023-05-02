<div wire:poll.5s>
    <div class="container-fluid">
    <div class='row'>
        @foreach($teams as $team=>$players)
        @php $class = match($team){ 'zombies' => "danger", 'humans' => 'primary', 'spectator' => 'secondary'} @endphp
        <div class="col-md-12 mt-3">
            <div class="h4 bg-{{ $class }} m-0">{{ ucfirst($team) }} - {{ $players->count() }}</div>
            <table class="table table-striped m-0">
                <thead class="table-dark g-0 p-0">
                    <td>Player</td>
                    <td>Points</td>
                    <td>kills | Deaths</td>
                    <td>Time online</td>
                </thead>
                <tbody>
                        @foreach ($players as $player)
                        <tr class="table-{{ $class }}">
                            <td>{{ $player->name }}</td>
                            <td>{{ $player->skill_change }}</td>
                            <td>{{ $player->kills }} | {{ $player->deaths }}</td>
                            <td class="small">{{ $player->time }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endforeach
        </div>
    </div>
    </div>
</div>
