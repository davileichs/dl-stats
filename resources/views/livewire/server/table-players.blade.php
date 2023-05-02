<div>
    <x-table search="hide">
    <x-slot:thead>
        <td>Player</td>
        <td>kills | Deaths</td>
        <td>Time</td>
        <td>Points</td>
    </x-slot>
    <x-slot:tbody>
        @foreach ($players as $player)
        <tr>
            <td>{{ $player->name }}</td>
            <td>{{ $player->kills }} | {{ $player->deaths }}</td>
            <td>{{ $player->time }}</td>
            <td>{{ $player->skill_change }}</td>
        </tr>
    @endforeach
    </x-slot>
    <x-slot:pagination>

    </x-slot>
</x-table>

</div>
