<div>
    <x-table search="hide">
    <x-slot:thead>
        <td>Name</td>
        <td>online players</td>
        <td>Best Player</td>
        <td>Total players</td>
    </x-slot>
    <x-slot:tbody>
        @foreach ($games as $game)

        <tr>
            <td><a href="{{ route('players', $game->code ) }}" class="link-secondary">{{ $game->name }}</a></td>
            <td>{{ $game->online_players->active }}</td>
            <td>{{ $game->best_player }}</td>
            <td>{{ $game->players()->count() }}</td>
        </tr>
        @endforeach
    </x-slot>
    <x-slot:pagination>

    </x-slot>
</x-table>

</div>
