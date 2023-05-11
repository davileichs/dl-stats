@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:body>
            <h1 class='text-center'>{{ $map->get()->map }}</h1>
            <div class="row">
                <div class="col-md-6">
                    <x-card>
                        <x-slot:title>
                            Top Players
                        </x-slot>
                            <x-table search="hide">
                                <x-slot:thead>
                                    <th>Rank</th>
                                    <th>Player</th>
                                    <th>Points Earned</th>
                                </x-slot>
                                <x-slot:tbody>
                                    @php $i=0; @endphp
                                    @foreach ($map->topPlayers(20) as $k=>$player)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td><a href="{{ route('player.show', $player->playerId ) }}" class="link-secondary">{{ $player->nickname }}</a></td>
                                            <th>{{ $player->points }}</th>
                                        </tr>
                                    @endforeach
                                </x-slot>
                                <x-slot:pagination>
                                </x-slot>
                            </x-table>
                    </x-card>
                </div>

                <div class="col-md-6">
                    <x-card>
                            <x-slot:title>
                                Top Shooters
                            </x-slot>
                                <x-table search="hide">
                                    <x-slot:thead>
                                        <th>Rank</th>
                                        <th>Player</th>
                                        <th>Shots</th>
                                        <th>Hits</th>
                                        <th>Damage</th>
                                        <th>Accuracy</th>
                                    </x-slot>
                                    <x-slot:tbody>
                                        @foreach ($map->topShootPlayers(20) as $k=>$player)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td><a href="{{ route('player.show', $player->playerId ) }}" class="link-secondary">{{ $player->nickname }}</a></td>
                                            <td>{{ number_format($player->shots) }}</td>
                                            <td>{{ number_format($player->hits) }}</td>
                                            <td>{{ number_format($player->damage) }}</td>
                                            <td>{{ $player->accuracy }}%</td>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                    <x-slot:pagination>
                                    </x-slot>
                                </x-table>
                        </x-card>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <x-card>
                        <x-slot:title>
                            Racetime
                        </x-slot>
                        <livewire:map.table-racetime :mapId="$map->get()->rowId" />
                    </x-card>
                </div>
                <div class="col-md-12">
                    <canvas class="bg-white" id="entriesChart"></canvas>
                </div>
            </div>


        </div>
    </x-slot>

</x-container>
@endsection
@section('scripts')
<script>
    (async function() {

        const shotsData = [
            @foreach($map->playTime() as $time=>$entries)
                {{ $entries }},
            @endforeach
        ];
        const shotsLabel = [
            @foreach($map->playTime() as $time=>$entries)
                '{{ $time }}:00 ',
            @endforeach

        ];

        var radarChart = new Chart(document.getElementById('entriesChart'), {
            type: 'line',
            data: {
                    labels: shotsLabel,
                    datasets: [{
                        label: "Popularity last Month",
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
