
@extends('layout')

@section('head')
    <script type="text/JavaScript">
    var startTime = (new Date()).getTime();
    var timeoutPeriod = 45000;
</script>
<script type="text/JavaScript">
    function timedRefresh(timeoutPeriod) {
        setTimeout("location.reload(true);",timeoutPeriod);
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

  <title>MyIoT - Graphs</title>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">

        <div class="dropdown">
         <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select Time Period
         <span class="caret"></span></button>
         <ul class="dropdown-menu">
             <li><a href="0.25" hours-range='0.25'>15 Mins</a></li>
           <li><a href="0.5" hours-range='0.5'>30 Mins</a></li>
           <li><a href="1.0" hours-range='1.0'>1 Hour</a></li>
           <li><a href="2.0" hours-range='2.0'>2 Hours</a></li>
           <li><a href="12.0" hours-range='12.0'>12 Hours</a></li>
           <li><a href="24" hours-range='24'>1 Day</a></li>
           <li><a href="99999" hours-range='99999'>All</a></li>
         </ul>
        </div

      {{-- <ul class="nav nav-pills ranges">
          <li><a href="0.25" hours-range='0.25'>15 Mins</a></li>
        <li><a href="0.5" hours-range='0.5'>30 Mins</a></li>
        <li><a href="1.0" hours-range='1.0'>1 Hour</a></li>
        <li><a href="2.0" hours-range='2.0'>2 Hours</a></li>
        <li><a href="12.0" hours-range='12.0'>12 Hours</a></li>
        <li><a href="24" hours-range='24'>1 Day</a></li>
        <li><a href="99999" hours-range='99999'>All</a></li>

      </ul> --}}
</div>
<h4 class="text-center">{{$hours}} hours</h4>
<p id="chart" class="text-center">Just a moment...Processing Data</p>
<h6>Samples: {{count($samples)}}, Load Time:
    <div class="loadtime"style="border: solid 1px #ccc; display: inline-block;"></div>
     seconds, last sample time: {{$settings['tlast_sample']}}</h6>
<h6>tmax: {{$settings['tmax']}}, tmin: {{$settings['tmin']}} - tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h6>

<button>Send an HTTP GET request to a page and get the result back</button>

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



{{-- @foreach ($samples as $line)
  {{$line->id}} :
  {{$line->sample_dt}} :
  {{$line->temperature}} :
{{$line->humidity}} :
{{$line->heaterstate}} :
{{$line->ventstate}} :
{{$line->fanstate}}
<br />
@endforeach --}}
{{-- {{ $samples->links() }} --}}

{{-- @foreach ($samples as $line)
  {{$line->sample_dt}}
@endforeach
@foreach ($samples as $line)
    ,{{$line->temperature}}
@endforeach
@foreach ($samples as $line)
            ,{{$line->humidity}}
            @endforeach
@foreach ($samples as $line)
              ,{{$line->heaterstate}}
            @endforeach
@foreach ($samples as $line)
              ,{{$line->ventstate}}
            @endforeach
@foreach ($samples as $line)
              ,{{$line->fanstate}}
            @endforeach --}}
{{-- columns: [
    ['time'{% for item in labels %},"{{item}}"{% endfor %}],
    ['temperature'{% for item in tempvalues %},{{item}}{% endfor %}],
    ['humidity'{% for item in humivalues %},{{item}}{% endfor %}],
    ['vent'{% for item in ventvalues %},ventOFFval + (ventONheight * {{item}}){% endfor %}],
    ['heater'{% for item in heatervalues %},heaterOFFval + (heaterONheight * {{item}}){% endfor %}],
    ['fan'{% for item in fanvalues %},fanOFFval + (fanONheight * {{item}}){% endfor %}],
    //['proctemp'{% for item in proctempvalues %},( {{item}} ){% endfor %}]
], --}}

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
        // Put millisecondsLoading in a hidden form field
        // or Ajax it back to the server or whatever.
        $('.loadtime').html(millisecondsLoading/1000);
        timedRefresh((millisecondsLoading) + 10000);
    });
</script>
@stop
