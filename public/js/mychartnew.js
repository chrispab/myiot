// global vars
var numZones = 3;
var chart = [];
//var zone = 1;
var hours = 3;
var tempmin;
var tempmax;
var tempnow;
var tmaxgraph = 18;
var tmingraph = 17;
var updateInterval = 20000;

var myVar;

// window.numZones = 2;
var tSPLo = 17;
var tSPHi = 18;
var lightState = 0;
//window.chart = [];
var intervalTimerHandle = [];

var axisobj = {};
var gridobj = {};

var startTimeChartUpdate = [];
var endTimeChartUpdate = [];

console.log('numZones:  ' + numZones);

//do when document loaded
$(document).ready(function() { // when doc loaded loop round graphs, create and popuplate
  //init the charts
  for (let i = 1; i < numZones + 1; i++) {
    //initData();
    //generate charts
    chart[i] = c3.generate(options);
    $(chart[i].element).appendTo("#chart" + i);

    //attach buttons to manually fire updates
    $('#reloadchart' + i).click(function() {
      GetData(i, i);
    });
    //get data and refresh graphs
    console.log("on load z & interval: " + ", " + updateInterval);
    //chart[i].flush();

    GetData(i, i);
  }
});


function initData() {}

function GetData(chartid = 1, zone = 1, hours = 0.5) {
  //show loading overlay
  //display loader spinner over chart
  $("#chart" + chartid).LoadingOverlay("show", {
    color: "rgba(255, 255, 255, 0.4)",
    maxSize: "100px",
    minSize: "20px",
    size: "10%"
  });
  //get update start time for chart
  startTimeChartUpdate[chartid] = new Date();

  //generate url for zone and time
  //get last param from url- hours as may be diff from default of 0.5
  var pathArray = window.location.pathname.split('/');
  hours = pathArray[pathArray.length - 1];
  //url to post to get graphdata
  var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();

  // Using the core $.ajax() method
  request = $.ajax({
    // The URL for the request
    url: postAddr,
    type: "POST",
    dataType: "json"
  });

  // Callback handler that will be called on success
  request.done(function(response, textStatus, jqXHR) {
    // Log a message to the console
    console.log("ajax data got for zone: " + zone);
    updateChart(response, chartid, zone, hours);
  });

  // Callback handler that will be called on failure
  request.fail(function(jqXHR, textStatus, errorThrown) {
    // Log the error to the console
    console.error(
      "The following error occurred: " +
      textStatus, errorThrown
    );
    intervalTimerHandle[zone] = setTimeout(function() {
      GetData(chartid, zone);
    }, updateInterval);
  });
}

//called when succesful ajax call done - to processdata returned and update zone chart
function updateChart(response, chartid, zone, hours) {
  var millisecondsLoading;

  console.log(response.samples.length);
  console.log(response);

  //get temps from response oblect
  tSPLo = parseFloat(response.settings.tSPlo);
  tSPHi = parseFloat(response.settings.tSPhi);
  console.log(tSPLo);
  console.log(tSPHi);

  //populate data arrays for graph
  var i;
  var time = [];
  time.push("time"); //1st array element content
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

  tempstrimmed = temperature.slice(0); //copy string to new string
  tempstrimmed.shift(); //remove first element - eg "temperature"
  tempsstring = tempstrimmed.toString();

  //convert to array of numbers
  temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]

  //update graph texts
  tempmin = Math.min(...temperaturenumbers);
  tempmax = Math.max(...temperaturenumbers);
  tempnow = temperaturenumbers[temperaturenumbers.length - 1];

  temps = "Min:" + tempmin.toString() + ", Max:" + tempmax.toString() + ", Now:" + tempnow.toString();
  document.getElementById("lblTemps" + chartid).innerHTML = temps;


  systemUpTimeTxt = response.settings.systemUpTime;
  document.getElementById("systemUpTime" + chartid).innerHTML = systemUpTimeTxt;


  controllerMessage = "" + response.settings.controllerMessage;
  document.getElementById("controllerMessage" + chartid).innerHTML = controllerMessage;

  miscMessage = "" + response.settings.miscMessage;
  document.getElementById("miscMessage" + chartid).innerHTML = miscMessage;

  processUpTimeTxt = response.settings.processUptime;
  document.getElementById("processUpTime" + chartid).innerHTML = processUpTimeTxt;

  tempSettings = "Temp SP Hi: " + tSPHi + ", Lo: " + tSPLo;
  document.getElementById("tempSettings" + chartid).innerHTML = tempSettings;

  //fill chart titlechart
  //titleTxt = response.settings.miscMessage + ", "  + "Zone " + zone + ", " + hours + " hours. " + temps;
  // document.getElementById("titlechart" + chartid).innerHTML = titleTxt;

  locationTxt = response.settings.miscMessage + ", " + "Zone " + zone + ", " + hours + " hrs";
  document.getElementById("lblLocation" + chartid).innerHTML = locationTxt;

  //+",<br>System: " + response.settings.systemMessage + "</h6>";

  var totalsamples = temperaturenumbers.length;
  //document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + '<span class="badge">' + totalsamples + '</span>';
  //document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + totalsamples;
  var samples_length = response.samples.length - 1;
  var last_sample_time = response.samples[samples_length].sample_dt;
  document.getElementById("lastsampletimechart" + chartid).innerHTML = "Last : " + "(" + totalsamples + ") " + last_sample_time;

  lightState = response.settings.lightState;
  console.log("lstate: ", lightState);
  if (lightState == 0) {
    showStuff("lightOffSpan" + zone);
    hideStuff("lightOnSpan" + zone);
  } else {
    showStuff("lightOnSpan" + zone);
    hideStuff("lightOffSpan" + zone);
  }
  staleMinutes = response.settings.staleMinutes;
  if (staleMinutes == 0) {
    showStuff("staleMinutesFalseSpan" + zone);
    hideStuff("staleMinutesTrueSpan" + zone);
  } else {
    document.getElementById("staleMinutesTrueSpan" + chartid).innerHTML = "Stale: " + staleMinutes;
    showStuff("staleMinutesTrueSpan" + zone);
    hideStuff("staleMinutesFalseSpan" + zone);
  }

  tmaxgraph = tSPHi + 1.0;
  tmingraph = tSPLo - 1.5;

  if (tempmax > tSPHi) {
    tmaxgraph = tempmax + 1.0;
  }
  if (tempmin < tSPLo) {
    tmingraph = tempmin - 1.5;
  }

  //setup chart vars from response
  axisobj.axis.y2.min = tmingraph;
  axisobj.axis.y2.max = tmaxgraph;
  gridobj.grid.y.lines[0].value = tSPLo;
  gridobj.grid.y.lines[1].value = tSPHi;
  gridobj.grid.y.lines[0].text = 'Low SP: ' + tSPLo;
  gridobj.grid.y.lines[1].text = 'High SP: ' + tSPHi;

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

  console.log(columnsobj);
  //hide loader overlay now chart update processed
  $("#chart" + chartid).LoadingOverlay("hide");
  //load the new chart data
  chart[zone].internal.loadConfig(axisobj);
  chart[zone].internal.loadConfig(gridobj);

  chart[zone].flush();
  chart[zone].load(columnsobj);

  endTimeChartUpdate[chartid] = new Date();
  millisecondsLoading = endTimeChartUpdate[chartid].getTime() - startTimeChartUpdate[chartid].getTime();
  secondsLoading = millisecondsLoading / 1000;
  //update load time text
  //    $('.loadtimechart' + chartid).html('<span class="badge">' + secondsLoading + '</span>');
  $('.loadtimechart' + chartid).html(secondsLoading);

  //    //set new interval based on last reload time
  reloadInterval = ((5 * 1000) + (millisecondsLoading * 5));
  var reloadIntervalSeconds = reloadInterval / 1000;

  //countdown code
  var count = Math.round(reloadIntervalSeconds); //reloadIntervalSeconds;
  var interval = setInterval(function() {
    count--;
    reloadInfoTxt = 'Reload t: ' + reloadIntervalSeconds + ' secs. ' + ' Countdown: ' + count;
    document.getElementById("reloadInfo" + chartid).innerHTML = reloadInfoTxt;
    if (count <= 0) {
      clearInterval(interval);
      return;
    }
  }, 1000);

  updateInterval = reloadInterval;
  console.log("update - set timeout for next call. zone: " + zone + ", interval: " + updateInterval);

  intervalTimerHandle[zone] = setTimeout(function() {
    GetData(chartid, zone);
  }, updateInterval);
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
        format: '%H:%M:%S'
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
      }
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


options = {
  //   size: {
  //     height: 240,
  //     width: 480
  // },
  data: {
    x: 'time',
    xFormat: '%Y-%m-%d %H:%M:%S',
    columns: [
      ['time', '2016-12-15 00:00:00'],
      ['temperature', 18],
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
      fan: '#f000dd'
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
        format: '%H:%M:%S'
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
      //max: 19,
      min: tmingraph,
      padding: {
        top: 0,
        bottom: 0
      }
    }
  },
  grid: {
    x: {
      show: true
    },
    y: {
      lines: [{
        value: 18,
        text: 'Low SP',
        axis: 'y2'
      }, {
        value: 19,
        text: 'High SP',
        axis: 'y2'
      }]
    }
  }
};


function showStuff(id) {
  document.getElementById(id).style.display = '';
}

function hideStuff(id) {
  document.getElementById(id).style.display = 'none';
}
