<div wire:init="loadSession">

    <div class="text-center h5" wire:loading>Loading</div>
    @if($playersRaceTime)
    <x-table search="hide" wire:loading.remove>
        <x-slot:thead>
                <th scope="col">Nickname</th>
                <th scope="col">Position</th>
                <th scope="col">Points</th>
                <th scope="col">Time</th>
        </x-slot>
        <x-slot:tbody>
            @foreach ($playersRaceTime as $k=>$player)
            <tr>
                <tr>
                    <td>{{ $player['name'] }}</td>
                    <td>#{{ $player['position'] }}</td>
                    <td>{{ $player['mapPoint'] }}</td>
                    <td>{{ $player['mapTime'] }}</td>
                    </tr>
                </tr>
            @endforeach
        </x-slot>
        <x-slot:pagination>

        </x-slot>
    </x-table>
    @else
    <p class="h5 text-center"  wire:loading.remove>No time race registered for this map</p>
    @endif

</div>
