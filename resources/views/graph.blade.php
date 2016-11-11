
@extends('layouts.app')

@section('head')
    <script type="text/JavaScript">
    var startTime = (new Date()).getTime();
    var timeoutPeriod = 45000;
</script>
<script type="text/JavaScript">
    function timedRefresh(timeoutPeriod) {
        setTimeout("location.assign(location.href);",timeoutPeriod);
    }
</script>
<script>
$(document).ready(function(){
    $("button").click(function(){
        $.get('/test', function(){
            alert('response');
        });
    });
});
</script>

  <title>MyIoT - {{$settings['zone']}} Graph - {{$hours}} Hours</title>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
</div>


<h4 class="text-center">{{$settings['zone']}} - {{$hours}} hours</h4>
<p id="chart" class="text-center">Just a moment...Processing Data</p>

<h6 class="text-center">Samples: {{count($samples)}}</h6>
<h6 class="text-center">Load Time:<div class="loadtime"style="border: solid 1px #ccc; display: inline-block;"></div>
     seconds </h6>

<h6 class="text-center">Last sample time: {{$settings['tlast_sample']}} </h6>

<h6 class="text-center">temp - min: {{$settings['tmin']}}, max: {{$settings['tmax']}}, now: {{$settings['temp_now']}}, - tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h6>
@if ( session()->has('message') )
    <h6 class="text-center"><span class="glyphicon glyphicon-refresh"></span> {{ session()->get('message') }} <span class="glyphicon glyphicon-refresh"></span>
    </h6>
@endif

<?php
$humibase = 10;
$height = 10;
$hval = 1;
$tspan = 2;
$heaterOFFval = 0 + $humibase;
$heaterONheight = $height+(1*$hval);
$ventOFFval = 0 + $humibase;
$ventONheight = ($height+(2*$hval)) / 1.5;
$fanOFFval = 0 + $humibase;
$fanONheight = $height/2;
?>

<script>
var humibase = 10;

var timeFormat = 'YYYY-MM-DD HH:mm:ss';

var chart = c3.generate({
    bindto: '#chart',
    data: {
        x : 'time',
        xFormat : '%Y-%m-%d %H:%M:%S',
        columns: [
            ['time' @foreach ($samples as $line)
                      ,"{{$line->sample_dt}}"
                  @endforeach
            ],
            ['temperature'@foreach ($samples as $line)
                          ,{{$line->temperature}}
                        @endforeach
            ],
            ['humidity'@foreach ($samples as $line)
                          ,{{$line->humidity}}
                        @endforeach
            ],
            ['heater'@foreach ($samples as $line)
                          ,{{ $heaterOFFval + ($line->heaterstate * $heaterONheight) }}
                        @endforeach
            ],

            ['vent'@foreach ($samples as $line)
                          ,{{ $ventOFFval + ($line->ventstate * $ventONheight) }}
                        @endforeach
            ],
            ['fan'@foreach ($samples as $line)
                          ,{{ $fanOFFval + ($line->fanstate * $fanONheight) }}
                        @endforeach
            ]

        ],
        colors: {
            temperature: '#ff0000',
            humidity: '#663399',
            vent: '#3eb308',
            heater: '#bf0d0d',
            fan: '#f000dd',
            proctemp: '#000000'
        },
        color: function (color, d) {
            // d will be 'id' when called for legends
            return d.id && d.id === 'data3' ? d3.rgb(color).darker(d.value / 150) : color;
        },
        axes: {
            humidity: 'y',
            temperature: 'y2',
            proctemp: 'y2'
        }
    },
    legend: {
        position: 'bottom'
    },
    zoom: {
        enabled: false
    },
    point: {
        show: false
    },
    axis : {
        x : {
            type : 'timeseries',
            tick : {
                rotate: 45,
                multiline: false,
                //              format : '%Y-%m-%d %H:%M:%S',
                count : 60,
                fit: true,
                format : '%H:%M:%S',
                //format : '%H:%M',
            }
        },
        y: {
            label: {
                text: 'Humidity',
                position: 'outer-middle'
            },
            max: 90,
            min: {{$humibase}},
            padding: {top:0, bottom:0}
        },
        y2: {
            show: true,
            label: {
                text: 'Temperature',
                position: 'outer-middle'
            },
            @if ($settings['tmax'] > $settings['tSPhi'])
                max: {{$settings['tmax'] +0.5}},
            @else
                max: {{$settings['tSPhi'] + 0.5}},
            @endif
            @if ($settings['tmin'] < $settings['tSPlo'])
                min: {{$settings['tmin'] - 0.5}},
            @else
                min: {{$settings['tSPlo'] - 0.5}},
            @endif



            padding: {top:0, bottom:0},
        }
    },
    grid: {
        x: {
            show: true
        },
        y: {
                lines: [
                  {value: {{$settings['tSPlo']}}, text: 'Low SP {{$settings['tSPlo']}}', axis: 'y2'},
                  {value: {{$settings['tSPhi']}}, text: 'High SP {{$settings['tSPhi']}}', axis: 'y2'}
                ]
        }
    }
});

</script>
<script>
    $(window).load(function () {
        var endTime = (new Date()).getTime();
        var millisecondsLoading = endTime - startTime;
        $('.loadtime').html(millisecondsLoading/1000);
        timedRefresh((millisecondsLoading) + 15000);
    });
</script>
@stop
