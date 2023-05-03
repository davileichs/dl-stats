<div>
    <x-table>
    <x-slot:thead>
        <td>#</td>
        <td>Player</td>
        <td>Points</td>
        <td>Activity</td>
        <td>Connection Time</td>
        <td>Game</td>
    </x-slot>
    <x-slot:tbody>
        @forelse ($players as $player)
        <tr>
            <td>{{ $player->ranking }}</td>
            <td><a href="{{ route('player.show', $player->playerId ) }}" class="link-secondary">{{ $player->nickname }}</a></td>
            <td>{{ $player->skill }}</td>
            <td><div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                <div class="progress-bar" style="width: {{ $player->activity }}%"></div>
            </div></td>
            <td>{{ $player->connection_time }}</td>

            <td>{{ $player?->games()->first()->name ?? 'none' }}</td>

        </tr>
        @empty
        <tr>
            <td colspan="4" class="fs-5">No player found...</td>
        </tr>
    @endforelse
    </x-slot>
    <x-slot:pagination>
        {{ $players->links() }}
    </x-slot>
</x-table>

</div>
