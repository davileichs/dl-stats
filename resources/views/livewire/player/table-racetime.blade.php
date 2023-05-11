<div wire:init="loadSession">

        <div class="text-center h5" wire:loading>Loading</div>
        @if(!empty($playerRaceTime))
        <div class="border border-2 border-warning rounded-1 p-3">
            <p class="my-3 ">Position: <span class="h4">#{{ $playerRaceTime['Rank'] }}</span></p>
            <p class="my-3 ">Points: <span class="h5">{{ $playerRaceTime['PlayerPoints'] }}</a></p>
            <p class="my-3 ">Times: <span class="h5">{{ $playerRaceTime['Times'] }}</a></p>
        </div>
        @endif
        @if($mapsRaceTime)
        <x-table search="hide" wire:loading.remove>
            <x-slot:thead>
                    <th scope="col">Map</th>
                    <th scope="col">Position</th>
                    <th scope="col">Stage</th>
                    <th scope="col">Points</th>
                    <th scope="col">Time</th>
            </x-slot>
            <x-slot:tbody>
                @foreach ($mapsRaceTime as $k=>$map)
                <tr>
                    <tr>
                        <td>{{ $map['mapname'] }}</td>
                        <td>#{{ $map['position'] }}</td>
                        <td>{{ $map['mapstage'] }}</td>
                        <td>{{ $map['mapPoint'] }}</td>
                        <td>{{ $map['mapTime'] }}</td>
                        </tr>
                    </tr>
                @endforeach
            </x-slot>
            <x-slot:pagination>

            </x-slot>
        </x-table>
        @endif

</div>
