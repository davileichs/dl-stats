<div>
    <div class="text-center h5" wire:loading>Loading</div>
    <div wire:loading.remove>

        <livewire:server.statistics-day :server="$server" />

        <x-chart-server-statistics period="lastWeek" :stats="$statisticsWeek">
            <x-slot:title>
                last Week
            </x-slot>
            <x-slot:body>
            </x-slot>
        </x-chart-server-statistics>
        <x-chart-server-statistics period="lastMonth" :stats="$statisticsMonth">
            <x-slot:title>
                last Month
            </x-slot>
            <x-slot:body>
            </x-slot>
        </x-chart-server-statistics>
        <x-chart-server-statistics  type='line' period="lastYear" hideDots="true" :stats="$statisticsYear">
            <x-slot:title>
                last Year
            </x-slot>
            <x-slot:body>
            </x-slot>
        </x-chart-server-statistics>
    </div>
</div>
@section('scripts')
@parent
<script>
    $( function() {
        $( "#dayDatePicker" ).datepicker({
            maxDate: new Date(),
            dateFormat: 'dd-mm-yy'
        });

        $('#dayDatePicker').change(function() {
            Livewire.emit('selectDay', this.value)
        })

    });
</script>
@endsection
