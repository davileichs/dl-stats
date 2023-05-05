<div>
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
                    @foreach ($mapUsage as $time=>$items)
                        <tr class="table-primary text-center h5">
                            <td colspan="5">{{  $items['map'] . ' - session from ' . $time . ' to ' . $items['end_at'] }}</td>
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
