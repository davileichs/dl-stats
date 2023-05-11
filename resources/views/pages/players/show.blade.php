@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:body>
            <div class="container text-left">

                <livewire:player.info :player="$player->get()" :also_name="$player->alsoAsName()" />

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
