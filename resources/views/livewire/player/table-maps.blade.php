<div wire:init="loadSession">
    <div class="row justify-content-center">
        <div class="col-md-2 text-left align-self-center">
            <label for="datepicker" class="form-label">Date</label>
            <input class="form-control text-center" placeholder="{{ $date }}" name="datepicker" type="text" id="datepicker" wire:bind autocomplete="off">
      </div>
    </div>
    <x-card>
        <x-slot:title>
            Maps session date: {{ $date }}
        </x-slot>
            <div class="text-center h5" wire:loading>Loading</div>
            <div wire:loading.remove>
            <x-table search="hide">
                <x-slot:thead>
                    <th>Map</th>
                    <th>Session</th>
                    <th>WIN</th>
                    <th>Points Earned</th>
                </x-slot>
                <x-slot:tbody>
                    @forelse ($maps as $time=>$items)
                        @foreach($items['players']->sortByDesc('points') as $k=>$player)
                            <tr>
                                <td>{{  $items['map'] }}</td>
                                <td>{{  'from ' . $time . ' to ' . $items['end_at'] . ' (UTC) | '. $items['total_time']  }}</td>
                                <td>{{ $player->mapwin }}</td>
                                <td>{{ $player->points }}</td>
                            </tr>
                        @endforeach
                    @empty
                    <tr>
                        <td colspan="4" class="text-center h5">No session for this day</td>
                    </tr>
                    @endforelse
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
