<div>
    <x-table>
    <x-slot:thead>
        <td>Name</td>
        <td>Popularity</td>
    </x-slot>
    <x-slot:tbody>
    @forelse ($maps as $map)
        <tr>
            <td><a href="{{ route('map.show', [$server->game, $map->map]) }}" class="link-secondary">{{ $map->map }}</a></td>
            <td>{{ $map->popularity }}</td>
        </tr>

        @empty
        <tr>
            <td colspan="4" class="fs-5">No Map found...</td>
        </tr>
    @endforelse
    </x-slot>
    <x-slot:pagination>
        {{ $maps->links() }}
    </x-slot>
</x-table>

</div>
