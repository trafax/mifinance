@extends('layouts.app')

@section('content')

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<div class="container">
    <div class="row ">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Overzicht inkomsten</div>

                <div class="card-body">
                    <script type="text/javascript">

                        @php
                            $years = [date('Y')-1, date('Y')];
                            $months = ['jan','feb','mar','apr','mei','jun','jul','aug','sep','okt','nov','dec'];
                        @endphp

                        google.charts.load('current', {'packages':['corechart']});

                        google.charts.setOnLoadCallback(drawInkomsten);

                        function drawInkomsten() {
                            var data = google.visualization.arrayToDataTable([
                                ["Maand", "@php echo implode('","',$years) @endphp"],
                                @foreach ($months as $key => $month)
                                    ['{{ $months[$key] }}',
                                        @foreach ($years as $year)
                                            {{ App\Income::whereRaw('month(date) = ?', ($key+1))->whereRaw('year(date) = ?', $year)->get()->sum('price') }},
                                        @endforeach
                                    ],
                                @endforeach
                            ]);

                            // var data = google.visualization.arrayToDataTable([
                            //     "Maand"
                            // ]);

                            var options = {
                            title : 'Inkomsten',
                            vAxis: {title: 'Bedrag'},
                            hAxis: {title: 'Maanden'},
                            seriesType: 'bars',
                            series: {5: {type: 'line'}}        };

                            var chart = new google.visualization.ComboChart(document.getElementById('inkomsten'));
                            chart.draw(data, options);
                        }
                    </script>
                    <div id="inkomsten" style="width: 100%;"></div>

                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Overzicht uitgaven</div>

                <div class="card-body">
                    <script type="text/javascript">

                        @php
                            $years = [date('Y')-1, date('Y')];
                            $months = ['jan','feb','mar','apr','mei','jun','jul','aug','sep','okt','nov','dec'];
                        @endphp

                        google.charts.load('current', {'packages':['corechart']});

                        google.charts.setOnLoadCallback(drawUitgaven);

                        function drawUitgaven() {
                            var data = google.visualization.arrayToDataTable([
                                ["Maand", "@php echo implode('","',$years) @endphp"],
                                @foreach ($months as $key => $month)
                                    ['{{ $months[$key] }}',
                                        @foreach ($years as $year)
                                            {{ App\Receipt::whereRaw('month(date) = ?', ($key+1))->whereRaw('year(date) = ?', $year)->get()->sum('price') }},
                                        @endforeach
                                    ],
                                @endforeach
                            ]);

                            var options = {
                            title : 'Uitgaven',
                            vAxis: {title: 'Bedrag'},
                            hAxis: {title: 'Maanden'},
                            seriesType: 'bars',
                            series: {5: {type: 'line'}}        };

                            var chart = new google.visualization.ComboChart(document.getElementById('uitgaven'));
                            chart.draw(data, options);
                        }
                    </script>
                    <div id="uitgaven" style="width: 100%;"></div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
