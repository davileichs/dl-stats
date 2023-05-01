<div>
    <x-table search="hide">
    <x-slot:thead>
        <td>Server</td>
        <td>Address</td>
        <td>Map</td>
        <td>Played</td>
    </x-slot>
    <x-slot:tbody>
    @forelse ($servers as $server)
        <tr wire:poll>
            <td><a href="{{ route('server.show', $server) }}" class="link-secondary">{{ $server->name }}</a></td>
            <td><a href="steam://connect/{{ $server->publicaddress }}\" class="link-secondary">{{ $server->publicaddress }} (join)</a></td>
            <td>{{ $server->act_map }}</td>
            <td>{{ $server->played }}</td>
        </tr>

        @empty
        <tr>
            <td colspan="4" class="fs-5">No Server found...</td>
        </tr>
    @endforelse
    </x-slot>
    <x-slot:pagination>

    </x-slot>
</x-table>

</div>
