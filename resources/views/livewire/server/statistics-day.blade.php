<div>
    <x-chart-server-statistics type='line' period="lastDay" :stats="$statisticsDay">
        <x-slot:title>
            Day Statistic : {{ $day ?? $today }}
        </x-slot>
        <x-slot:body>
        </x-slot>
    </x-chart-server-statistics>
</div>
