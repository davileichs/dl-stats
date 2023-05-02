@aware(['color' => "warning", 'title' => '', ])
@if($topPlayers->isNotEmpty())
<div class="border border-2 border-{{ $color }} rounded-1 p-3 bg-dark mb-2">
    <p class="mb-3 h2"><a class="link-warning" href="{{ route('player.show', $topPlayers->first()->playerId) }}"> {{ $topPlayers->first()->lastName }}</a></p>
    <p class="my-3 ">{{ $title }}</p>
</div>
<x-table search="hide">
    <x-slot:thead>
        <th scope="col">#</th>
            <th scope="col">Nickname</th>
            <th scope="col">Points</th>
    </x-slot>
    <x-slot:tbody>
        @foreach($topPlayers as $k=>$player)
            <tr>
                <td>{{ $k+1 }}</td>
                <td><a href="{{ route('player.show', $player->playerId) }}" class="link-dark text-decoration-none">{{ $player->lastName }}</a></td>
                <td>{{ $player->points }}</td>
                </tr>
            </tr>
        @endforeach
    </x-slot>
    <x-slot:pagination>
    </x-slot>
</x-table>
@endif
