@extends('template')

@section('content')
@include('parts.server-nav')
<x-container>
    <x-slot:body>
            <div class="container text-left">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <img src="/images/weapons/{{ $weapon->get()->code }}.png" class="img-thumbnail border border-warning border-2">
                            </div>
                            <div class="col-md-9">
                                <div class="nav-item dropdown mb-2">
                                    <span class='h2 text-white text-decoration-none'>{{ $weapon->get()->code }}</span>
                                </div>
                            </div>
                            <div class="col-md-12 pt-5">
                                <p class="card-text">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ percent($weapon->get()->hits, $weapon->get()->shots) }}%" aria-valuenow="{{ percent($weapon->get()->hits, $weapon->get()->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent($weapon->get()->hits, $weapon->get()->shots) }}% - Hits</div>
                                        <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: {{ percent_inverse($weapon->get()->hits, $weapon->get()->shots) }}%" aria-valuenow="{{ percent_inverse($weapon->get()->hits, $weapon->get()->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent_inverse($weapon->get()->hits, $weapon->get()->shots) }}% - Miss</div>
                                    </div>
                                </p>
                                <x-card>
                                    <x-slot:title>
                                        Top Players
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
                                                @foreach ($weapon->topPlayers(20) as $k=>$player)
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
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <x-card>
                        <x-slot:title>
                            Today Session
                        </x-slot>
                            <x-table search="hide">
                                <x-slot:thead>
                                    <th>Rank</th>
                                    <th>Player</th>
                                    <th>Shots</th>
                                    <th>Hits</th>
                                    <th>Damage</th>
                                </x-slot>
                                <x-slot:tbody>
                                    @foreach ($weapon->mapUsage() as $time=>$items)
                                        <tr class="table-primary text-center h5">
                                            <td colspan="5">{{  $items['map'] . ' - session at ' . $time }}</td>
                                        </tr>
                                        @foreach($items['players'] as $k=>$player)
                                            <tr>
                                                <td>{{ $k+1 }}</td>
                                                <td><a href="{{ route('player.show', $player->playerId ) }}" class="link-secondary">{{ $player->nickname }}</a></td>
                                                <td>{{ number_format($player->shots) }}</td>
                                                <td>{{ number_format($player->hits) }}</td>
                                                <td>{{ number_format($player->damage) }}</td>

                                            </tr>
                                        @endforeach
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
                            Top Map
                        </x-slot>
                            <x-table search="hide">
                                <x-slot:thead>
                                    <th>Rank</th>
                                    <th>Map</th>
                                    <th>Shots</th>
                                    <th>Hits</th>
                                    <th>Damage</th>
                                </x-slot>
                                <x-slot:tbody>
                                    @foreach ($weapon->topMaps(20) as $k=>$map)
                                    <tr>
                                        <td>{{ $k+1 }}</td>
                                        <td>{{ $map->map }}</td>
                                        <td>{{ number_format($map->shots) }}</td>
                                        <td>{{ number_format($map->hits) }}</td>
                                        <td>{{ number_format($map->damage) }}</td>
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
                <div class="col-md-6">
                    <canvas class="bg-white" id="shotsChart"></canvas>
                </div>
                <div class="col-md-6">
                    <canvas class="bg-white" id="usageChart"></canvas>
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
            @foreach($weapon->weaponShots() as $hit=>$shot)
                {{ $shot }},
            @endforeach
        ];
        const shotsLabel = [
            @foreach($weapon->weaponShots() as $hit=>$shot)
                '{{ $hit }} ',
            @endforeach

        ];

        var radarChart = new Chart(document.getElementById('shotsChart'), {
            type: 'radar',
            data: {
                    labels: shotsLabel,
                    datasets: [{
                        label: "Shots",
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
