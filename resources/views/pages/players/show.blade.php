@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:body>
            <div class="container text-left">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="{{ $player->get()->avatar }}" class="img-thumbnail border border-warning border-2">
                            </div>
                            <div class="col-md-9">
                                <div class="nav-item dropdown mb-2">
                                    <a class="dropdown-toggle  text-decoration-none text-white" href="#" id="dropPlayerName" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class='h2 text-white text-decoration-none'>{{ $player->get('nickname') }}</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropPlayerName">
                                        <li class="mx-3 my-1 text-secondary">Played also as</li>
                                        @foreach($player->alsoAsName() as $time => $name)
                                        <li class="mx-3 my-1">{{ $time }}{{ $name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <p class="mt-0"><img src="/images/flags/{{ strtolower($player->get()->flag) }}.gif"> {{ $player->get()->country }}</p>
                                <p class="mt-4"><a class="btn btn-light btn-sm" href="http://steamcommunity.com/profiles/{{ $player->get()->steam_id }}" role="button" target="_blank">Steam profile</a></p>
                                @if ($player->get()->is_online)
                                    <p class="text-success h6">Online</p>
                                @else
                                    <p class="text-danger">Last Access: {{ $player->get()->last_event }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border border-2 border-warning rounded-1 p-3 bg-dark">
                            <p class="mb-3 h2">Score: {{ $player->get()->skill }}</p>
                            <p class="my-3 ">Position: <span class="h4">#{{ $player->ranking() }}</span></p>
                            <p class="my-3 ">First access time: <span class="h5">{{ $player->get()->createdate }}</a></p>
                            <p class="my-3 ">Total Connection: <span class="h6">{{ $player->get()->connection_time }}</a></p>
                        </div>
                    </div>
                </div>

                <livewire:player.table-points :playerId="$player->get('playerId')" />

                <livewire:player.table-weapons :playerId="$player->get('playerId')" />

                <livewire:player.table-maps :playerId="$player->get('playerId')" />
            </div>
    </x-slot>

</x-container>
@endsection
@section('styles')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
@endsection
@section('scripts')
@parent
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


<script>
    (async function() {

        const sessionLabels = [
            @foreach(getDaysLastMonth() as $day)
            '{{ $day }}',
            @endforeach
        ];

        const sessionData = [
            @foreach(getDaysLastMonth() as $day)
            {{ $player->sessionPoints()[$day]  ?? 0 }},
            @endforeach
        ];
        const sessionConnections = [
            @foreach(getDaysLastMonth() as $day)
            {{ $player->sessionConnections()[$day]  ?? 0 }},
            @endforeach
        ]

        const sessionChart = new Chart(document.getElementById('sessionChart'), {
            data: {
                datasets: [{
                    type: 'line',
                    label: 'Time (minutes)',
                    data: sessionConnections
                }, {
                    type: 'line',
                    label: 'Points',
                    data:  sessionData,
                }],
                labels: sessionLabels
            }
        });

        const shotsData = [
            @foreach($player->weaponsShots() as $hit=>$shot)
                {{ $shot }},
            @endforeach
        ];
        const shotsLabel = [
            @foreach($player->weaponsShots() as $hit=>$shot)
                '{{ $hit }} ',
            @endforeach

        ];

        var radarChart = new Chart(document.getElementById('shotsChart'), {
            type: 'radar',
            data: {
                    labels: shotsLabel,
                    datasets: [{
                        label: "Hits",
                        data: shotsData,
                        fill: true,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgb(54, 162, 235)',
                        pointBackgroundColor: 'rgb(54, 162, 235)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(54, 162, 235)'

                    }]
            }
        });

        })();
  </script>

@endsection
