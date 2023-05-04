<div>
    <x-table>
    <x-slot:thead>
        <td></td>
        <td>Name</td>
        <td>Modifier</td>
        <td>Total Shots</td>
        <td>Total Hits</td>
        <td>Total Damage</td>
        <td>Avg. Accuracy</td>
    </x-slot>
    <x-slot:tbody>
    @forelse ($weapons as $weapon)
        <tr>
            <td class="text-center"><img src="/images/weapons/{{ $weapon->code }}.png" width="110" height="30"></td>
            <td><a href="{{ route('weapon.show', [$server->game, $weapon->code]) }}" class="link-secondary">{{ $weapon->name }}</a></td>
            <td>{{ $weapon->modifier }}</td>
            <td>{{ number_format($weapon->shots) }}</td>
            <td>{{ number_format($weapon->hits) }}</td>
            <td>{{ number_format($weapon->damage) }}</td>
            <td>{{ $weapon->accuracy }}%</td>
        </tr>

        @empty
        <tr>
            <td colspan="4" class="fs-5">No Weapon found...</td>
        </tr>
    @endforelse
    </x-slot>
    <x-slot:pagination>

    </x-slot>
</x-table>

</div>
