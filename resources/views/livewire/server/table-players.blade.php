<div wire:poll>
    <x-table search="hide">
    <x-slot:thead>
        <td>Player</td>
        <td>Score</td>
        <td>Time</td>
        <td>Total Points</td>
    </x-slot>
    <x-slot:tbody>
        @foreach ($players as $player)
        <tr wire:poll>
            <td>{{ $player->nickname }}</td>
            <td>{{ $player->nickname }}</td>
            <td>{{ $player->time }}</td>
            <td>{{ $player->skill }}</td>
        </tr>
    @endforeach
    </x-slot>
    <x-slot:pagination>

    </x-slot>
</x-table>

</div>
