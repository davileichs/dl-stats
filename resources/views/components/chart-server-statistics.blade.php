@aware(['type' => "bar", 'period' => 'none', 'hideDots' => false])
<div class="card bg-dark mb-3 mt-5">
    <div class="card-header text-white py-3 h3">
        {{ $title }}
    </div>
    <div class="card-body text-black bg-light">
        <canvas class="bg-white" id="chart{{ $period }}"></canvas>
    </div>
</div>


<script>
    (async function() {

        chart{{ $period }} = document.getElementById('chart{{ $period }}');
        const playersData{{ $period }} = [
            @foreach($stats as $load) {{ $load['act_players'] }}, @endforeach
        ];
        const mapsData{{ $period }} = [
            @foreach($stats as $load) '{{ $load['map'] }}', @endforeach
        ];
        const loadLabels{{ $period }} = [
            @foreach($stats as $load) '{{ $load['day'] . ' ' . $load['hour'] }}', @endforeach
        ];
        const maxPlayersData{{ $period }} = [
            @foreach($stats as $load) 65, @endforeach
        ];
        let options{{ $period }} = null;
        @if($type == 'line')
        options{{ $period }} = {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(chart{{ $period }}) {
                            map = mapsData{{ $period }}[chart{{ $period }}.dataIndex];
                            label =  [' players: ' + chart{{ $period }}.raw, map];
                            return label;
                        }
                    }
                }
            }
        }@endif

        new Chart(chart{{ $period }}, {
            data: {
                datasets: [{
                    type: '{{ $type }}',
                    label: 'Players',
                    backgroundColor: 'rgba(55, 115, 219, 1)',
                    fill: false,
                    borderColor: 'rgba(55, 115, 219)',
                    data: playersData{{ $period }},
                    tension: 1,
                    @if ($hideDots) pointRadius: 0, @endif
                },
                {
                    type: 'line',
                    label: 'max Players',
                    fill: false,
                    borderColor: 'rgba(255, 55, 55, 1)',
                    data: maxPlayersData{{ $period }},
                    pointRadius: 0,
                }],
                labels: loadLabels{{ $period }}
            },
            options: options{{ $period }}
        });

        })();
  </script>
