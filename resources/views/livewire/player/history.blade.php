<div>
    <div class="card bg-dark mb-3 mt-5">
        <div class="card-header text-white py-3 h3">
            Connection time
        </div>
        <div class="card-body text-black bg-light">
            <canvas class="bg-white" id="chartTime"></canvas>
        </div>
    </div>
</div>
@section('scripts')
<script>
    (async function() {

        const sessionLabels = [
            @foreach($history as $day=>$session)
            '{{ $day }}',
            @endforeach
        ];

        const sessionData = [
            @foreach($history as $day=>$session)
            {{ $session  ?? 0 }},
            @endforeach
        ];

        options = {
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(chartTime) {
                            label =  [' time: ' + toHoursAndMinutes(chartTime.raw)];
                            return label;
                        }
                    }
                }
            }
        };

        new Chart(chartTime, {
            data: {
                datasets: [{
                    type: 'line',
                    label: 'Total played time (minutes)',
                    backgroundColor: 'rgba(55, 115, 219, 1)',
                    fill: false,
                    borderColor: 'rgba(55, 115, 219)',
                    data: sessionData,
                    tension: 0,
                    pointRadius: 1
                }],
                labels: sessionLabels
            },
            options: options
        });

        })();

        function toHoursAndMinutes(totalMinutes) {
            const hours = Math.floor(totalMinutes / 60);
            const minutes = totalMinutes % 60;

            return hours + 'h ' + minutes + 'min';
        }
  </script>
@endsection
