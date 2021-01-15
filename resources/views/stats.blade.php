@extends('layout')
@section('content')
    <div><a href="{{\Illuminate\Support\Facades\URL::to('/')}}">&larr;Go back</a></div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    @if($data->isNotEmpty())
    <canvas id="stats"></canvas>

    <script>
        let stats = <?php echo $data ?>;
        console.log(stats);
        let labels = [];
        let data = [];
        let datasets = [];

        $.each(stats ,function(index,value){
            labels.push(index);
            data.push(value.length);
        });

        var ctx = document.getElementById('stats').getContext('2d');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Hits',
                    backgroundColor: 'rgb(255, 99, 132)',
                    borderColor: 'rgb(255, 99, 132)',
                    data: data
                }]
            },

            // Configuration options go here
            options: {
                hover: {
                    // Overrides the global setting
                    mode: 'index'
                }
            }
        });
    </script>
    @else
        <div class="col-sm">
            <div class="text-center">
                <h3>No hits yet...</h3>
            </div>
        </div>
        <div class="col-sm text-center">
            <img src="{{asset('img/gdun.jpg')}}">
        </div>

    @endif
@endsection
