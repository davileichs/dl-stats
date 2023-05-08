<div wire:init="loadSession">
    <div class="row justify-content-center">
        <div class="col-md-2 text-left align-self-center">
            <label for="datepicker" class="form-label">Date</label>
            <input class="form-control text-center" placeholder="{{ $date }}" name="datepicker" type="text" id="datepicker" wire:bind autocomplete="off">
      </div>
    </div>
    <x-card>
        <x-slot:title>
            Session date: {{ $date }}
        </x-slot>
            <div class="text-center h5" wire:loading>Loading</div>
            <div wire:loading.remove>
            <x-table search="hide">
                <x-slot:thead>
                    <th>id</th>
                    <th>Player</th>
                    <th>WIN</th>
                    <th>Points Earned</th>
                </x-slot>
                <x-slot:tbody>
                    @foreach ($mapUsage as $time=>$items)
                        <tr class="table-primary text-center h5">
                            <td colspan="5">{{  $items['map'] . ' - session from ' . $time . ' to ' . $items['end_at'] . ' (UTC) | '. $items['total_time']  }}</td>
                        </tr>
                        @foreach($items['players']->sortByDesc('points') as $k=>$player)
                            <tr>
                                <td>{{ $k }}</td>
                                <td><a href="{{ route('player.show', $player->playerId ) }}" class="link-secondary">{{ $player->nickname }}</a></td>
                                <th>{{ $player->mapwin }}</th>
                                <th>{{ $player->points }}</th>
                            </tr>
                        @endforeach
                    @endforeach
                </x-slot>
                <x-slot:pagination>
                </x-slot>
            </x-table>
            </div>
    </x-card>
</div>

</div>
@section('scripts')
@parent
<script>
    $( function() {
        $( "#datepicker" ).datepicker({
            maxDate: new Date(),
            dateFormat: 'dd-mm-yy'
        });

        $('#datepicker').change(function() {
            Livewire.emit('selectSession', this.value)
        })

    });
</script>
@endsection
