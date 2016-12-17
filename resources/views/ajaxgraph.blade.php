
@extends('layouts.app')

@section('head')

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

<!-- <script src="//code.jquery.com/jquery.min.js"></script> -->
<script src="/js/loadingoverlay.min.js"></script>

<script>

$(document).ready("#mybutton").click(function() {
      getgraphdata();
});

$(function() {
  getgraphdata();

});
 setInterval(getgraphdata, 15 * 1000);

function getgraphdata() {
    //get last param - hours
    var pathArray = window.location.pathname.split('/');
    var zone = pathArray[pathArray.length - 2];
    var hours = pathArray[pathArray.length - 1];
    var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();
    //console.log(postAddr);
    var millisecondsLoading;
    var startTime;
    var endTime;


    startTime = new Date();
    //$('#loaderImage').show();
    $("#chart").LoadingOverlay("show", {
    color           : "rgba(255, 255, 255, 0.0)"
});
    $.post(postAddr, function(response) {

        var obj = {};
        var time = [];
        time.push("time");
        var i;
        for (i = 0; i < response.samples.length; i++) {
            time.push(response.samples[i].sample_dt);
        }
        //console.log(time);

        var temperature = [];
        temperature.push("temperature");
        for (i = 0; i < response.samples.length; i++) {
            temperature.push(response.samples[i].temperature);
        }

        var humidity = [];
        humidity.push("humidity");
        for (i = 0; i < response.samples.length; i++) {
            humidity.push(response.samples[i].humidity);
        }

        var heater = [];
        heater.push("heater");
        for (i = 0; i < response.samples.length; i++) {
            heater.push(response.samples[i].heaterstate * 20);
        }

        var vent = [];
        vent.push("vent");
        for (i = 0; i < response.samples.length; i++) {
            vent.push(response.samples[i].ventstate * 19);
        }
        var fan = [];
        fan.push("fan");
        for (i = 0; i < response.samples.length; i++) {
            fan.push(response.samples[i].fanstate * 18);
        }
        obj["time"] = time;
        obj["temperature"] = temperature;
        obj["humidity"] = humidity;
        obj["heater"] = heater;

        //console.dir(obj);

        //update last sample time text
        document.getElementById("lastsampletime").innerHTML = "Last sample time: " + response.samples[response.samples.length - 1].sample_dt;
        //update min and max temp readings
        //get string of numbers from array
        tempstrimmed = temperature.slice();
        tempstrimmed.shift(); //remove first elem - eg "temperature"
        tempsstring = tempstrimmed.toString();
        //console.log(tempsstring);
        //convert to array of numbers
        temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]
        //console.log(temperaturenumbers);

        var tempmin = Math.min(...temperaturenumbers);
        var tempmax = Math.max(...temperaturenumbers);
        var tempnow = temperaturenumbers[temperaturenumbers.length - 1];
        temps = "Temp min: " + tempmin.toString() + ", Max: " + tempmax.toString() + ", Now: " + tempnow.toString();
        document.getElementById("temps").innerHTML = temps;
        //  document.getElementById("tempmax").innerHTML = ", Max: " + tempmax;
        //  document.getElementById("tempnow").innerHTML = ", Now: " + tempnow;
        var totalsamples = temperaturenumbers.length;
        document.getElementById("totalsamples").innerHTML = 'Total Samples: ' + totalsamples;

        //console.log(pathArray);
        //chart.unload();
        chart.load({
            columns: [
                time,
                temperature,
                humidity,
                heater,
                vent,
                fan
            ]
        });
    }, "JSON")

    .done(function() {
  //alert( "second success" );
              //$('#loaderImage').hide();
              $("#chart").LoadingOverlay("hide");

  endTime = new Date();
  millisecondsLoading = endTime.getTime() - startTime.getTime();
  $('.loadtime').html(millisecondsLoading);
});

};
</script>


<title>MyIoT - {{$settings['zone']}} Graph - {{$hours}} Hours</title>
@stop

@section('content')
<button type="button" id="mybutton" class="mybutton">Force reload</button>


<div class="row">
    <div class="col-md-12">
</div>

<h4 class="text-center">{{$settings['zone']}} - {{$hours}} hours</h4>

<div id="chart" class="text-center">Just a moment...Processing Data</div>

<h6 class="text-center" id="totalsamples">Samples: {{count($samples)}}</h6>
<h6 class="text-center">Render Time: <div class="loadtime"style="border: solid 1px #ccc; display: inline-block;"></div> milliseconds </h6>
<h6 class="text-center" id="lastsampletime">Last sample time: {{$settings['tlast_sample']}} </h6>
<h6 class="text-center" id="temps">temp - min: {{$settings['tmin']}}, max: {{$settings['tmax']}}, now: {{$settings['temp_now']}}, - tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h6>
@if ( session()->has('message') )
    <h6 class="text-center"><span class="glyphicon glyphicon-refresh"></span> {{ session()->get('message') }} <span class="glyphicon glyphicon-refresh"></span>
    </h6>
@endif

<?php
$base = 10;
$height = 4;
$hval = 1;
$tspan = 2;
$heaterOFFval = $base;
$heaterONheight = $height+(1*$hval)+1;
$ventOFFval = $base;
$ventONheight = ($height+(1*$hval))*0.8;
$fanOFFval = $base;
$fanONheight = $height/2;
?>

<script>
//var base = 10;

var timeFormat = 'YYYY-MM-DD HH:mm:ss';

var chart = c3.generate({
    bindto: '#chart',
    data: {
        x : 'time',
        xFormat : '%Y-%m-%d %H:%M:%S',
        columns: [
            ['time', '2016-12-15 00:00:00'],
            ['temperature', 20 ],
            ['humidity', 50 ],
            ['heater',0 ],
            ['vent', 0 ],
            ['fan', 0 ]
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
            temperature: 'y2'
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
            min: {{$base}},
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


@stop
