
@extends('layouts.app')

@section('head')

<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

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
        $("#mybutton").click(function(){
          // alert("mybutton clicked");
                   $.post('/getajaxgraphdata/1/0.5', function(response){


                     console.log("my object: %o", response);
                     console.dir(response);

                     var info = response.samples[0].sample_dt;
                     //get all sample_dt as string
                     var sample_dt_all = "'time'";
                     var i;
                     for (i = 0; i < response.samples.length; i++) {
                        sample_dt_all += ', \''+ response.samples[i].sample_dt + '\'';
                      }
                      //sample_dt_all = sample_dt_all.substring(1, sample_dt_all.length-1);
                      //sample_dt_all = 'time' + sample_dt_all;
                      console.log(sample_dt_all.toString());

                      //try creating obect and passing to chart
                      var obj = {};
                      var time = [];
                      time.push("time");
                      var i;
                      for (i = 0; i < response.samples.length; i++) {
                         time.push( response.samples[i].sample_dt);
                       }
                       console.log(time);

                       var temperature = [];
                                             temperature.push("temperature");
                       for (i = 0; i < response.samples.length; i++) {
                          temperature.push(response.samples[i].temperature);
                        }

                        var humidity = [];
                        humidity.push("humidity");
                        for (i = 0; i < response.samples.length; i++) {
                           humidity.push( response.samples[i].humidity);
                         }


                         obj["time"]=time;
                         obj["temperature"]=temperature;
                         obj["humidity"]=humidity;

                      //get all temperature as string
                      var temperature_all = "'temperature'";
                      var i;
                      for (i = 0; i < response.samples.length; i++) {
                         temperature_all += ', '+ response.samples[i].temperature;
                       }
                       //temperature_all = temperature_all.substring(1, temperature_all.length-1);

                       //get all humidity as string
                       var humidity_all = "'humidity'";
                       var i;
                       for (i = 0; i < response.samples.length; i++) {
                          humidity_all += ', '+ response.samples[i].humidity;
                        }
                        //humidity_all = humidity_all.substring(1, humidity_all.length-1);

                        //get all heaterstate as string
                        var heaterstate_all = "";
                        var i;
                        for (i = 0; i < response.samples.length; i++) {
                           heaterstate_all += response.samples[i].heaterstate + ", ";
                         }
                         //get all heaterstate as string
                         var ventstate_all = "";
                         var i;
                         for (i = 0; i < response.samples.length; i++) {
                            ventstate_all += response.samples[i].ventstate + ", ";
                          }
                          //get all heaterstate as string
                          var fanstate_all = "";
                          var i;
                          for (i = 0; i < response.samples.length; i++) {
                             fanstate_all += response.samples[i].fanstate + ", ";
                           }

                       //alert('response field: ' + sample_dt_all + temperature_all + humidity_all + heaterstate_all + ventstate_all+ fanstate_all);
                      //  console.log(sample_dt_all, temperature_all, humidity_all,heaterstate_all+ventstate_all+fanstate_all);
                      //  //get data and populate chart info data
                      //  //process time from sample_dt fields
                      //  //var times = response.samples.sample_dt;
                      //  console.log(sample_dt_all.toString());
                      //  console.log(temperature_all.toString());
                      //  console.log(humidity_all.toString());
                       //console.log(substring(1, humidity_all.toString(), -1));
                       console.dir(obj);

                       chart.load({
                         columns: [
                           time,
                           temperature,
                           humidity
                         ]
                       });
                   },"JSON");
        });
    });

    setInterval( getgraphdata, 10*1000 );

    function getgraphdata(){
      // alert("mybutton clicked");
               $.post('/getajaxgraphdata/1/0.5', function(response){


                 //console.log("my object: %o", response);
                 //console.dir(response);

                 //var info = response.samples[0].sample_dt;
                 //get all sample_dt as string
                //  var sample_dt_all = "'time'";
                //  var i;
                //  for (i = 0; i < response.samples.length; i++) {
                //     sample_dt_all += ', \''+ response.samples[i].sample_dt + '\'';
                //   }
                //   //sample_dt_all = sample_dt_all.substring(1, sample_dt_all.length-1);
                //   //sample_dt_all = 'time' + sample_dt_all;
                //   console.log(sample_dt_all.toString());

                  //try creating obect and passing to chart
                  var obj = {};
                  var time = [];
                  time.push("time");
                  var i;
                  for (i = 0; i < response.samples.length; i++) {
                     time.push( response.samples[i].sample_dt);
                   }
                   console.log(time);

                   var temperature = [];
                   temperature.push("temperature");
                   for (i = 0; i < response.samples.length; i++) {
                      temperature.push(response.samples[i].temperature);
                    }

                    var humidity = [];
                    humidity.push("humidity");
                    for (i = 0; i < response.samples.length; i++) {
                       humidity.push( response.samples[i].humidity);
                     }

                     var heater = [];
                     heater.push("heater");
                     for (i = 0; i < response.samples.length; i++) {
                        heater.push( response.samples[i].heaterstate * 20);
                      }

                      var vent = [];
                      vent.push("vent");
                      for (i = 0; i < response.samples.length; i++) {
                         vent.push( response.samples[i].ventstate * 17);
                       }
                       var fan = [];
                       fan.push("fan");
                       for (i = 0; i < response.samples.length; i++) {
                          fan.push( response.samples[i].fanstate * 15);
                        }
                     obj["time"]=time;
                     obj["temperature"]=temperature;
                     obj["humidity"]=humidity;
                     obj["heater"]=heater;

                  // //get all temperature as string
                  // var temperature_all = "'temperature'";
                  // var i;
                  // for (i = 0; i < response.samples.length; i++) {
                  //    temperature_all += ', '+ response.samples[i].temperature;
                  //  }
                  //  //temperature_all = temperature_all.substring(1, temperature_all.length-1);
                  //
                  //  //get all humidity as string
                  //  var humidity_all = "'humidity'";
                  //  var i;
                  //  for (i = 0; i < response.samples.length; i++) {
                  //     humidity_all += ', '+ response.samples[i].humidity;
                  //   }
                  //   //humidity_all = humidity_all.substring(1, humidity_all.length-1);
                  //
                  //   //get all heaterstate as string
                  //   var heaterstate_all = "";
                  //   var i;
                  //   for (i = 0; i < response.samples.length; i++) {
                  //      heaterstate_all += response.samples[i].heaterstate + ", ";
                  //    }
                  //    //get all heaterstate as string
                  //    var ventstate_all = "";
                  //    var i;
                  //    for (i = 0; i < response.samples.length; i++) {
                  //       ventstate_all += response.samples[i].ventstate + ", ";
                  //     }
                  //     //get all heaterstate as string
                  //     var fanstate_all = "";
                  //     var i;
                  //     for (i = 0; i < response.samples.length; i++) {
                  //        fanstate_all += response.samples[i].fanstate + ", ";
                  //      }

                   //alert('response field: ' + sample_dt_all + temperature_all + humidity_all + heaterstate_all + ventstate_all+ fanstate_all);
                  //  console.log(sample_dt_all, temperature_all, humidity_all,heaterstate_all+ventstate_all+fanstate_all);
                  //  //get data and populate chart info data
                  //  //process time from sample_dt fields
                  //  //var times = response.samples.sample_dt;
                  //  console.log(sample_dt_all.toString());
                  //  console.log(temperature_all.toString());
                  //  console.log(humidity_all.toString());
                   //console.log(substring(1, humidity_all.toString(), -1));
                   console.dir(obj);

                   //update last sample time text
                   document.getElementById("lastsampletime").innerHTML = "Last sample time: " + response.samples[response.samples.length -1 ].sample_dt;
                   //update min and max temp readings
                   //get string of numbers from array
                   tempstrimmed=temperature.slice();
                   tempstrimmed.shift();//remove first elem - eg "temperature"
                   tempsstring = tempstrimmed.toString();
                   console.log(tempsstring);
                   //convert to array of numbers
                   temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]
                   //console.log(temperaturenumbers);

                   var tempmin = Math.min(...temperaturenumbers);
                   var tempmax = Math.max(...temperaturenumbers);
                   var tempnow = temperaturenumbers[temperaturenumbers.length-1];
                     temps = tempmin.toString() + tempmax.toString() + tempnow.toString();
                   document.getElementById("temps").innerHTML = temps;
                  //  document.getElementById("tempmax").innerHTML = ", Max: " + tempmax;
                  //  document.getElementById("tempnow").innerHTML = ", Now: " + tempnow;


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
               },"JSON");
    }
    // $(document).ready(function(){
    //     $("mybutton").click(function(){
    //         $.get('/test', function(){
    //             alert('response');
    //         });
    // });

    $(document).ready(function(){
    $("p").click(function(){
        alert("The paragraph was clicked.");
    });
});
</script>

<title>MyIoT - {{$settings['zone']}} Graph - {{$hours}} Hours</title>
@stop

@section('content')
<button type="button" id="mybutton" class="mybutton">Load data via AJAX</button>


<div class="row">
    <div class="col-md-12">
</div>

<h4 class="text-center">{{$settings['zone']}} - {{$hours}} hours</h4>
<p id="chart" class="text-center">Just a moment...Processing Data</p>
<h6 class="text-center">Samples: {{count($samples)}}</h6>
<h6 class="text-center">Render Time:<div class="loadtime"style="border: solid 1px #ccc; display: inline-block;"></div> seconds </h6>
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
            // ['time', '2016-12-15 16:10:28'
            // ],
            // ['temperature', 6
            // ],
            // ['humidity', 7
            // ],
            // ['heater',1
            // ],
            //
            // ['vent', 2
            // ],
            // ['fan', 1
            // ]

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
<script>
    $(window).load(function () {
        var endTime = (new Date()).getTime();
        var millisecondsLoading = endTime - startTime;
        $('.loadtime').html(millisecondsLoading/1000);
        // timedRefresh((millisecondsLoading) + 15000);
    });
</script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
@stop
