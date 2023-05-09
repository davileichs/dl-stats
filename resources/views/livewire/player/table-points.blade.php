<div wire:init="loadSession">
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
                            @foreach($rewards as $team=>$reward)
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
                            @foreach ($listActions as $k=>$action)
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
</div>
