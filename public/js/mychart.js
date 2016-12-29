// global vars
var zone = 11;
var hours = 333;
var tempmin;
var tempmax;
var tempnow;
var tmaxgraph;
var tmingraph;


window.tSPLo = 21;
window.tSPHi = 24;
window.chart = [];
window.intervalTimerHandle = [];

//on document loaded
$(function() {
  //generate charts
  window.chart[1] = c3.generate(window.options);
  $(window.chart[1].element).appendTo("#chart1");
  window.chart[2] = c3.generate(window.options);
  $(window.chart[2].element).appendTo("#chart2");
  //clear data already in charts
  window.chart[1].unload();
  window.chart[2].unload();
  //get data and refresh graphs
  getgraphdata(1, 1);
  getgraphdata(2, 2);
  //set the graph refresh ajax call interval time initially
  window.intervalTimerHandle[1] = setInterval("getgraphdata('#chart1',1)", 10 * 1000);
  window.intervalTimerHandle[2] = setInterval("getgraphdata('#chart2',2)", 10 * 1000);
  //attach buttons to manually fire updates
  $("#reloadchart1").click(function() {
    getgraphdata(1, 1);
  });
  $("#reloadchart2").click(function() {
    getgraphdata(2, 2);
  });
});

function getgraphdata(chartid = 1, zone = 1, hours = 0.5) {
  //get last param from url- hours
  var pathArray = window.location.pathname.split('/');
  hours = pathArray[pathArray.length - 1];
  //url to post to get graphdata
  var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();
  //console.log(postAddr);
  var millisecondsLoading;
  var startTime;
  var endTime;

  startTime = new Date();
  //display loader spinner over chart
  $("#chart" + chartid).LoadingOverlay("show", {
    color: "rgba(255, 255, 255, 0.4)",
    maxSize: "100px",
    minSize: "20px",
    size: "10%"
  });

  $.post(postAddr, function(response) {
    var obj = {}; //obect to jold objs for graph data/options

    //get temps from response oblect
    window.tSPLo = parseFloat(response.settings.tSPlo);
    window.tSPHi = parseFloat(response.settings.tSPhi);

    //populate data arrays for graph
    var i;
    var time = [];
    time.push("time");  //1st array element content
    for (i = 0; i < response.samples.length; i++) { // now fill with data points
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
      heater.push(response.samples[i].heaterstate * 19);
    }
    var vent = [];
    vent.push("vent");
    for (i = 0; i < response.samples.length; i++) {
      vent.push(response.samples[i].ventstate * 16);
    }
    var fan = [];
    fan.push("fan");
    for (i = 0; i < response.samples.length; i++) {
      fan.push(response.samples[i].fanstate * 13);
    }
    obj["time"] = time;
    obj["temperature"] = temperature;
    obj["humidity"] = humidity;
    obj["heater"] = heater;
    obj["vent"] = vent;
    obj["fan"] = fan;

    //update last sample time text
    //update min and max temp readings
    //get string of numbers from array
    tempstrimmed = temperature.slice(0); //copy string to new string
    tempstrimmed.shift(); //remove first element - eg "temperature"
    tempsstring = tempstrimmed.toString();
    //console.log(tempsstring);
    //convert to array of numbers
    temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]
    //console.log("titlechart" + chartid);

    //update graph texts
    tempmin = Math.min(...temperaturenumbers);
    tempmax = Math.max(...temperaturenumbers);
    tempnow = temperaturenumbers[temperaturenumbers.length - 1];
    temps = "Temp " + " Max: " + tempmax.toString()+ ", Min: " + tempmin.toString()  + ", Now: " + tempnow.toString();
    //document.getElementById("tempschart" + chartid).innerHTML = temps;

    tempSettings = "Temp SP Hi: " + tSPHi + ", Lo: " + tSPLo;
    processUptimeTxt = ".    Process uptime: " + response.settings.processUptime
    document.getElementById("tempSettings" + chartid).innerHTML = tempSettings;
    //fill chart titlechart
    titleTxt = "Zone: " + zone + ", " + hours + " hours. " + temps +
    ",<br> System: " + response.settings.systemMessage + processUptimeTxt
    document.getElementById("titlechart" + chartid).innerHTML = titleTxt;

    var totalsamples = temperaturenumbers.length;
    //document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + '<span class="badge">' + totalsamples + '</span>';
    document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + totalsamples;

    document.getElementById("lastsampletimechart" + chartid).innerHTML = "Last sample time: " + response.samples[response.samples.length - 1].sample_dt;

    //document.getElementById("processUptime" + chartid).innerHTML = processUptimeTxt;
    // document.getElementById("systemMessage" + chartid).innerHTML = "System: " + response.settings.systemMessage;

    tmaxgraph = tSPHi + 0.5;
    tmingraph = tSPLo - 1.5;

    if (tempmax > tSPHi) {
      tmaxgraph = tempmax + 0.3;
    }
    if (tempmin < tSPLo) {
      tmingraph = tempmin - 1.5;
    }
    //global
    axisobj = {
      axis: {
        x: {
          type: 'timeseries',
          tick: {
            rotate: 45,
            multiline: false,
            //              format : '%Y-%m-%d %H:%M:%S',
            count: 60,
            fit: true,
            format: '%H:%M:%S',
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
          padding: {
            top: 0,
            bottom: 0
          }
        },
        y2: {
          show: true,
          label: {
            text: 'Temperature',
            position: 'outer-middle'
          },

          max: tmaxgraph,
          min: tmingraph,
          padding: {
            top: 0,
            bottom: 0
          },
        }
      }
    };

    //global
    gridobj = {
      grid: {
        x: {
          show: true
        },
        y: {
          lines: [{
            value: tSPLo,
            text: 'Low SP: ' + tSPLo,
            axis: 'y2'
          }, {
            value: tSPHi,
            text: 'High SP: ' + tSPHi,
            axis: 'y2'
          }]
        }
      }
    };

    //global
    columnsobj = {
      columns: [
        time,
        temperature,
        humidity,
        heater,
        vent,
        fan
      ]
    };

  }, "JSON")

  .done(function() {
    //eval(chartid.substring(1)).load(columnsobj);
    //console.log(chartid.substring(1));
    window.chart[zone].internal.loadConfig(axisobj);
    window.chart[zone].internal.loadConfig(gridobj);
    window.chart[zone].load(columnsobj);
    //console.log('     loaded');

    $("#chart" + chartid).LoadingOverlay("hide");
    endTime = new Date();
    millisecondsLoading = endTime.getTime() - startTime.getTime();
    //convert to seconds
    secondsLoading = millisecondsLoading / 1000;
    //update load time text
//    $('.loadtimechart' + chartid).html('<span class="badge">' + secondsLoading + '</span>');
    $('.loadtimechart' + chartid).html(secondsLoading);

    // var reload_call = "getgraphdata(chartid, zone)";
    // console.log(' reload call:  ' + reload_call);
    //clear current interval
    window.clearInterval(window.intervalTimerHandle[zone]);
    //create string to pass into setinterval call
    var interval_t = "getgraphdata('" + chartid + "'," + zone + ")";
    //set new interval based on last reload time
    reloadInterval = ((5 * 1000) + (millisecondsLoading * 5));
    var reloadIntervalSeconds =  reloadInterval/1000;
    window.intervalTimerHandle[zone] = window.setInterval(interval_t, reloadInterval);

    //document.getElementById("reloadInterval" + chartid).innerHTML = 'Reload Interval: ' + '<span class="badge">' + reloadIntervalSeconds + '</span>' + ' seconds';
    // document.getElementById("reloadInterval" + chartid).innerHTML = 'Reload Interval: ' + reloadIntervalSeconds + ' seconds';

    //countdown code
     var count = Math.round(reloadIntervalSeconds); //reloadIntervalSeconds;
     var interval = setInterval(function(){
       count--;
//       document.getElementById("countdown" + chartid).innerHTML = 'Countdown: ' + '<span class="badge">' + count + '</span>' + ' seconds';
       //document.getElementById("countdown" + chartid).innerHTML = 'Countdown: ' + count  + ' seconds';
       reloadInfoTxt = 'Reload Interval: ' + reloadIntervalSeconds + ' seconds' + ' Countdown: ' + count;
       document.getElementById("reloadInfo" + chartid).innerHTML = reloadInfoTxt;

       if (count <= 0) {
         clearInterval(interval);
         return;
       }
     }, 1000);
     //


  });
};


window.options = {
  //   size: {
  //     height: 240,
  //     width: 480
  // },
  data: {
    x: 'time',
    xFormat: '%Y-%m-%d %H:%M:%S',
    columns: [
      ['time', '2016-12-15 00:00:00'],
      ['temperature', 20],
      ['humidity', 50],
      ['heater', 0],
      ['vent', 0],
      ['fan', 0]
    ],
    colors: {
      temperature: '#ff0000',
      humidity: '#663399',
      vent: '#3eb308',
      heater: '#bf0d0d',
      fan: '#f000dd',
      proctemp: '#000000'
    },
    color: function(color, d) {
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
  axis: {
    x: {
      type: 'timeseries',
      tick: {
        rotate: 45,
        multiline: false,
        //              format : '%Y-%m-%d %H:%M:%S',
        count: 60,
        fit: true,
        format: '%H:%M:%S',
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
      padding: {
        top: 0,
        bottom: 0
      }
    },
    y2: {
      show: true,
      label: {
        text: 'Temperature',
        position: 'outer-middle'
      },
      max: tmaxgraph,
      min: tmingraph,
      padding: {
        top: 0,
        bottom: 0
      },
    }
  },
  grid: {
    x: {
      show: true
    },
    y: {
      lines: [{
        value: 0,
        text: 'Low SP',
        axis: 'y2'
      }, {
        value: 0,
        text: 'High SP',
        axis: 'y2'
      }]
    }
  }
}
