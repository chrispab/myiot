@extends('layouts.app')

@section('head')

<script>
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});
</script>
<script src="/js/loadingoverlay.min.js"></script>
<script>

$(function() {
  chart1.unload();
  chart2.unload();
  getgraphdata('#chart1',1);
  getgraphdata('#chart2',2);

  intID = [0,1,2];
  window.intID[1] = setInterval("getgraphdata('#chart1',1)", 20 * 1000);
  window.intID[2] = setInterval("getgraphdata('#chart2',2)", 20 * 1000);

  $("#reloadchart1").click(function(){
    getgraphdata('#chart1',1);
  });
  $("#reloadchart2").click(function(){
    getgraphdata('#chart2',2);
  });
});

var zone=11;
var hours=333;
var tempmin;
var tempmax;
var tempnow;
var tmaxgraph;
var tmingraph;
var tSPlo;
var tSPHi;


function getgraphdata(chartid='#chart1', zone=1, hours=0.5) {
  //get last param - hours
  var pathArray = window.location.pathname.split('/');
  //zone = pathArray[pathArray.length - 2];
  hours = pathArray[pathArray.length - 1];
  var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();
  //console.log(postAddr);
  var millisecondsLoading;
  var startTime;
  var endTime;

  startTime = new Date();
  //$('#loaderImage').show();
  console.log(chartid);

  $(chartid).LoadingOverlay("show", {
    color : "rgba(255, 255, 255, 0.4)",
    maxSize         : "100px",
    minSize         : "20px",
    size            : "10%"
  });

  $.post(postAddr, function(response) {
    //console.log(response);
    //console.log('+++++ ' + response.settings["tSPhi"] + '+++++ ');
    var obj = {};

    var tSPLo = parseFloat(response.settings.tSPlo);
    var tSPHi = parseFloat(response.settings.tSPhi);




    var time = [];
    time.push("time");
    var i;
    for (i = 0; i < response.samples.length; i++) {
      time.push(response.samples[i].sample_dt);
    }
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

    document.getElementById("title" + chartid.substring(1)).innerHTML = "Zone: "+ zone +", "+hours+" hours";
    //update last sample time text
    //update min and max temp readings
    //get string of numbers from array
    tempstrimmed = temperature.slice();
    tempstrimmed.shift(); //remove first elem - eg "temperature"
    tempsstring = tempstrimmed.toString();
    //console.log(tempsstring);
    //convert to array of numbers
    temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]
    //console.log(temperaturenumbers);

    //update graph texts
    tempmin = Math.min(...temperaturenumbers);
    tempmax = Math.max(...temperaturenumbers);
    tempnow = temperaturenumbers[temperaturenumbers.length - 1];
    temps = "Temp min: " + tempmin.toString() + ", Max: " + tempmax.toString() + ", Now: " + tempnow.toString();
    document.getElementById("temps" + chartid.substring(1)).innerHTML = temps;
    //  document.getElementById("tempmax").innerHTML = ", Max: " + tempmax;
    //  document.getElementById("tempnow").innerHTML = ", Now: " + tempnow;
    var totalsamples = temperaturenumbers.length;
    document.getElementById("totalsamples" + chartid.substring(1)).innerHTML = 'Total Samples: ' + totalsamples;
    document.getElementById("lastsampletime" + chartid.substring(1)).innerHTML = "Last sample time: " + response.samples[response.samples.length - 1].sample_dt;


    tmaxgraph = tSPHi +0.5;
    tmingraph = tSPLo -0.5;
    console.log(tmaxgraph);
    console.log(tmingraph);
    if (tempmax > tSPHi){
      tmaxgraph = tempmax +0.3;
    }
    if (tempmin < tSPLo){
      tmingraph = tempmin - 1.5;
    }
    //console.log(chartid.substring(1));
    //chart.unload();

    var axisobj = {
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
          min: 10,
          padding: {top:0, bottom:0}
        },
        y2: {
          show: true,
          label: {
            text: 'Temperature',
            position: 'outer-middle'
          },

          max: tmaxgraph,
          min: tmingraph,
          padding: {top:0, bottom:0},
        }
      }
    };

    var gridobj = {
      grid: {
        x: {
          show: true
        },
        y: {
          lines: [
            {value: tSPLo, text: 'Low SP: ' + tSPLo, axis: 'y2'},
            {value: tSPHi, text: 'High SP: ' + tSPHi, axis: 'y2'}
          ]
        }
      }
    };

    console.log(gridobj);

    var columnsobj = {
      columns: [
        time,
        temperature,
        humidity,
        heater,
        vent,
        fan
      ]
    };

    var dataobj = {
      data: columnsobj, gridobj
    };

    //eval(chartid.substring(1)).load(columnsobj);
    eval(chartid.substring(1)).internal.loadConfig(axisobj);

    eval(chartid.substring(1)).internal.loadConfig(gridobj);

    eval(chartid.substring(1)).load(columnsobj);
    //console.log('     loaded');


  }, "JSON")

  .done(function() {
    $(chartid).LoadingOverlay("hide");
    endTime = new Date();
    millisecondsLoading = endTime.getTime() - startTime.getTime();
    $('.loadtime' + chartid.substring(1) ).html(millisecondsLoading);

    var reload_call = "getgraphdata('" + chartid + "'" + ",zone)";
    console.log(' reload call:  '+ reload_call);
    window.clearInterval(window.intID[zone]);
    var interval_t = "getgraphdata('" + chartid + "',"+ zone + ")";
    window.intID[zone] = window.setInterval(interval_t, (10 * 1000) + millisecondsLoading);

  });

};
</script>

<title id="title">MyIoT - Graph All - Hours</title>
@stop

@section('content')

<div class="row">
  <div class="col-md-6">
    <div class="panel panel-success">
      <div class="panel-heading" id="titlechart1">Panel Heading</div>
      <div class="panel-body" >
        <div id="chart1" class="text-center" ></div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-left" id="totalsampleschart1"  >Samples: {{count($samples)}}</h6>
              <h6 class="text-left">Render Time: <div class="loadtimechart1"style="border: solid 1px #ccc; display: inline-block;"></div> milliseconds </h6>
              <button type="button" class="btn btn-primary btn-xs" id="reloadchart1" >Reload graph data</button>
            </div>
            <div class="col-md-6">
              <h6 class="text-left" id="lastsampletimechart1">Last sample time: {{$settings['tlast_sample']}} </h6>
              <h6  class="text-left" id="tempschart1">temp - min: {{$settings['tmin']}}, max: {{$settings['tmax']}}, now: {{$settings['temp_now']}}, - tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <div class="panel panel-success">
      <div class="panel-heading" id="titlechart2">Panel Heading</div>
      <div class="panel-body">
        <div id="chart2" class="text-center"></div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-md-6">
              <h6 class="text-left" id="totalsampleschart2"  >Samples: {{count($samples)}}</h6>
              <h6 class="text-left">Render Time: <div class="loadtimechart2"style="border: solid 1px #ccc; display: inline-block;"></div> milliseconds </h6>
              <button type="button" class="btn btn-primary btn-xs" id="reloadchart2" >Reload graph data</button>
            </div>
            <div class="col-md-6">
              <h6 class="text-left" id="lastsampletimechart2">Last sample time: {{$settings['tlast_sample']}} </h6>
              <h6  class="text-left" id="tempschart2">temp - min: {{$settings['tmin']}}, max: {{$settings['tmax']}}, now: {{$settings['temp_now']}}, - tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


@if ( session()->has('message') )
<h6 class="text-center">
  <span class="glyphicon glyphicon-refresh"></span> {{ session()->get('message') }} <span class="glyphicon glyphicon-refresh"></span>
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
var options = {
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

      max: tmaxgraph,
      min: tmingraph,
      padding: {top:10, bottom:10},
    }
  },
  grid: {
    x: {
      show: true
    },
    y: {
      lines: [
        {value: {{$settings['tSPlo']}}, text: 'Low SP!! {{$settings['tSPlo']}}', axis: 'y2'},
        {value: {{$settings['tSPhi']}}, text: 'High SP!! {{$settings['tSPhi']}}', axis: 'y2'}
      ]
    }
  }
}

var chart1 = c3.generate(options);
$(chart1.element).appendTo("#chart1");

var chart2 = c3.generate( options );
$(chart2.element).appendTo("#chart2");

var timeFormat = 'YYYY-MM-DD HH:mm:ss';

</script>

@stop
