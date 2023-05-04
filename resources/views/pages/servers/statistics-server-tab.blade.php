<x-chart-server type='line' period="lastDay" :stats="$server->statisticsDay()">
    <x-slot:title>
        Last Day
    </x-slot>
    <x-slot:body>
    </x-slot>
</x-chart-server>
<x-chart-server period="lastWeek" :stats="$server->statisticsWeek()">
    <x-slot:title>
        last Week
    </x-slot>
    <x-slot:body>
    </x-slot>
</x-chart-server>
<x-chart-server period="lastMonth" :stats="$server->statisticsMonth()">
    <x-slot:title>
        last Month
    </x-slot>
    <x-slot:body>
    </x-slot>
</x-chart-server>
<x-chart-server  type='line' period="lastYear" hideDots="true" :stats="$server->statisticsYear()">
    <x-slot:title>
        last Year
    </x-slot>
    <x-slot:body>
    </x-slot>
</x-chart-server>
