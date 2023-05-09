<div wire:init="loadSession">
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
                            @foreach ($weapons as $k=>$weapon)
                            <tr>
                                <td>{{ $k+1 }}</td>
                                <td><a href="{{ route('weapon.show', [$player->game, $weapon->weapon]) }}" class="link-secondary"><img src="/images/weapons/{{ $weapon->weapon }}.png" width="110" height="30"></a></td>
                                <td>{{ number_format($weapon->shots) }}</td>
                                <td>{{ number_format($weapon->hits) }}</td>
                                <td>{{ number_format($weapon->damage) }}</td>
                                <td>{{ $weapon->accuracy }}%</td>
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
                                <div class="progress-bar progress-bar-striped" role="progressbar" style="width: {{ percent($player->hits, $player->shots) }}%" aria-valuenow="{{ percent($player->hits, $player->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent($player->hits, $player->shots) }}% - Hits</div>
                                <div class="progress-bar progress-bar-striped bg-secondary" role="progressbar" style="width: {{ percent_inverse($player->hits, $player->shots) }}%" aria-valuenow="{{ percent_inverse($player->hits, $player->shots) }}" aria-valuemin="0" aria-valuemax="100">{{ percent_inverse($player->hits, $player->shots) }}% - Miss</div>
                            </div>
                        </p>
                    <canvas class="bg-white" id="shotsChart" ></canvas>
                </div>
            </div>
    </x-card>
</div>
