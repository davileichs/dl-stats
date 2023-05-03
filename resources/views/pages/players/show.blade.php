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
                                        @foreach($player->alsoAsName() as $name)
                                        <li class="mx-3 my-1">{{ $name }}</li>
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
            </div>

            <div class="container text-left py-5">
                <x-card>
                    <x-slot:title>
                        Session
                    </x-slot>
                        <canvas class="bg-white" id="sessionChart"></canvas>
                </x-card>

                <x-card>
                    <x-slot:title>
                        Points
                    </x-slot>
                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link link-dark active" id="nav-statistics-tab" data-bs-toggle="tab" data-bs-target="#nav-statistics" type="button" role="tab" aria-controls="nav-statistics" aria-selected="true">Statistics</button>
                                <button class="nav-link link-dark" id="nav-data-tab" data-bs-toggle="tab" data-bs-target="#nav-data" type="button" role="tab" aria-controls="nav-data" aria-selected="false">Points table</button>
                            </div>
                        </nav>
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active pt-4" id="nav-statistics" role="tabpanel" aria-labelledby="nav-statistics-tab">
                                <h5 class="card-title mt-4">Points earned</h5>
                                <p class="card-text">
                                    <div class="progress">
                                        @foreach($player->rewards() as $team=>$reward)
                                        <div class="progress-bar progress-bar-striped @if($team == 'human') bg-primary @elseif($team == 'zombie') bg-danger @else bg-secondary @endif" role="progressbar" style="width: {{ $reward['percent'] }}%" aria-valuenow="{{ $reward['percent'] }}" aria-valuemin="0" aria-valuemax="100">{{ $reward['points'] }} - {{ ucfirst($team) }}</div>
                                        @endforeach
                                    </div>
                                </p>
                            </div>
                            <div class="tab-pane fade" id="nav-data" role="tabpanel" aria-labelledby="nav-data-tab">
                                <x-table search="hide">
                                    <x-slot:thead>
                                        <th scope="col">#</th>
                                            <th scope="col">Action</th>
                                            <th scope="col">Team</th>
                                            <th scope="col">Accumulated Points</th>
                                    </x-slot>
                                    <x-slot:tbody>
                                        @foreach ($player->listActions() as $k=>$action)
                                        <tr>
                                            <tr class="@if ($action->team == 'ZOMBIE') table-danger @elseif ($action->team == 'HUMAN') table-primary @else table-secondary @endif ">
                                                <td>{{ $k+1 }}</td>
                                                <td>{{ $action->description }}</td>
                                                <td>{{ $action->team }}</td>
                                                <td> {{ $action->points }}</td>
                                                </tr>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                    <x-slot:pagination>

                                    </x-slot>
                                </x-table>
                            </div>
                        </div>
                </x-card>

                <x-card>
                    <x-slot:title>
                        Weapons
                    </x-slot>
                        <div class="row">
                            <div class="col-md-6">
                                <x-table search="hide">
                                    <x-slot:thead>
                                        <th>Rank</th>
                                        <th>Weapon</th>
                                        <th>Shots</th>
                                        <th>Hits</th>
                                        <th>Damage</th>
                                        <th>Accuracy</th>
                                    </x-slot>
                                    <x-slot:tbody>
                                        @foreach ($player->listWeapons() as $k=>$weapon)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td><img src="/images/weapons/{{ $weapon->weapon }}.png" width="110" height="30"></td>
                                            <td>{{ number_format($weapon->shots) }}</td>
                                            <td>{{ number_format($weapon->hits) }}</td>
                                            <td>{{ number_format($weapon->damage) }}</td>
                                            <td>{{ percent($weapon->hits, $weapon->shots) }}%</td>
                                            </tr>
                                        @endforeach
                                    </x-slot>
                                    <x-slot:pagination>
                                    </x-slot>
                                </x-table>
                            </div>
                            <div class="col-md-6 pt-5">
                                    <p class="card-text">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ percent($player->get()->hits, $player->get()->shots) }}%" aria-valuenow="{{ percent($player->get()->hits, $player->get()->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent($player->get()->hits, $player->get()->shots) }}% - Hits</div>
                                            <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: {{ percent_inverse($player->get()->hits, $player->get()->shots) }}%" aria-valuenow="{{ percent_inverse($player->get()->hits, $player->get()->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent_inverse($player->get()->hits, $player->get()->shots) }}% - Miss</div>
                                        </div>
                                    </p>
                                <canvas class="bg-white" id="shotsChart" ></canvas>
                            </div>
                        </div>
                </x-card>

                <x-card>
                    <x-slot:title>
                        Maps
                    </x-slot>

                    <a class="link-dark" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        Show
                    </a>
                    <div class="collapse" id="collapseExample">
                            <x-table search="hide">
                                <x-slot:thead>
                                    <th>Map</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                </x-slot>
                                <x-slot:tbody>
                                    @foreach ($player->listMaps() as $k=>$map)
                                        <tr class="@isset ($map['ipAddress']) table-primary @else table-danger @endisset ">
                                            <td>{{ $map['map'] }}</td>
                                            <td>{{ $map['eventTime'] }}</td>
                                            <td>@isset ($map['ipAddress']) Connect @else Disconnect @endisset</td>
                                        </tr>
                                    @endforeach

                                </x-slot>
                                <x-slot:pagination>
                                </x-slot>
                            </x-table>
                      </div>
                </x-card>
            </div>
    </x-slot>

</x-container>
@endsection
@section('scripts')
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
