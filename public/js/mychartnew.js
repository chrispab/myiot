// global vars
var numZones = 2;
var chart = [];
//var zone = 1;
var hours = 3;
var tempmin;
var tempmax;
var tempnow;
var tmaxgraph;
var tmingraph;
var updateInterval = 5000;

// window.numZones = 2;
window.tSPLo = 21;
window.tSPHi = 24;
window.lightState = 0;
//window.chart = [];
window.intervalTimerHandle = [];


function showStuff(id) {
    document.getElementById(id).style.display = '';
}

function hideStuff(id) {
    document.getElementById(id).style.display = 'none';
}

console.log('window.numZones:  ' + window.numZones);

//do when document loaded
$(document).ready(function () { // when doc loaded loop round graphs, create and popuplate
    //init the charts
    for (let i = 1; i < window.numZones + 1; i++) {
        //initData();
        //generate charts
        window.chart[i] = c3.generate(window.options);
        $(window.chart[i].element).appendTo("#chart" + i);
        //clear data already in charts
        window.chart[i].unload();

        //attach buttons to manually fire updates
        $('#reloadchart' + i).click(function () {
            getgraphdata(i, i);
        });
        //get data and refresh graphs
        setTimeout(GetData(i, i), updateInterval);
        //getgraphdata(i, i);
    }
});

function initData() {
    for (var i = 0; i < totalPoints; i++) {
        var temp = [now += updateInterval, 0];

        cpu.push(temp);
        cpuCore.push(temp);
        disk.push(temp);
    }
}

function GetData(chartid = 1, zone = 1, hours = 0.5) {
    //generate url for zone and time
    //get last param from url- hours as may be diff from default of 0.5
    var pathArray = window.location.pathname.split('/');
    hours = pathArray[pathArray.length - 1];
    //url to post to get graphdata
    var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();

    // Using the core $.ajax() method
    request = $.ajax({
        //disable cache
        //cache: false, - not reqd cos using POST
        // The URL for the request
        url: postAddr,
        type: "POST",
        dataType: "json"
    });

    // Callback handler that will be called on success
    request.done(function (response, textStatus, jqXHR) {
        // Log a message to the console
        console.log("Hooray, it worked!");
        update(response, chartid, zone, hours);
    });

    // Callback handler that will be called on failure
    request.fail(function (jqXHR, textStatus, errorThrown) {
        // Log the error to the console
        console.error(
                "The following error occurred: " +
                textStatus, errorThrown
                );
        setTimeout(GetData, updateInterval);
    });
}

function update(response, chartid, zone, hours) {
    //console.log(postAddr);
    var millisecondsLoading;
    var startTime;
    var endTime;

    startTime = new Date();

    //show loading overlay
    //display loader spinner over chart
    // $("#chart" + chartid).LoadingOverlay("show", {
    //     color: "rgba(255, 255, 255, 0.4)",
    //     maxSize: "100px",
    //     minSize: "20px",
    //     size: "10%"
    // });
    // 

    //window.chart[zone].unload();
    console.log(response.samples.length);

    var obj = {}; //obect to jold objs for graph data/options

    //get temps from response oblect
    window.tSPLo = parseFloat(response.settings.tSPlo);
    window.tSPHi = parseFloat(response.settings.tSPhi);

    lightState = response.settings.lightState;

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
    //console.log(time);
    //convert to array of numbers
    temperaturenumbers = tempsstring.split(',').map(parseFloat); // [1, 2, 5, 4, 3]
    //console.log("titlechart" + chartid);

    //update graph texts
    tempmin = Math.min(...temperaturenumbers);
    tempmax = Math.max(...temperaturenumbers);
    tempnow = temperaturenumbers[temperaturenumbers.length - 1];
    temps = "Temp " + " Max: " + tempmax.toString() + ", Min: " + tempmin.toString() + ", Now: " + tempnow.toString();
    //document.getElementById("tempschart" + chartid).innerHTML = temps;

    tempSettings = "Temp SP Hi: " + tSPHi + ", Lo: " + tSPLo;
    processUptimeTxt = ". Process up: " + response.settings.processUptime;
    document.getElementById("tempSettings" + chartid).innerHTML = tempSettings;
    //fill chart titlechart
    titleTxt = "<h6>Zone: " + zone + ", " + hours + " hours. " + temps +
            ",<br>System: " + response.settings.systemMessage + processUptimeTxt + "</h6>";
    document.getElementById("titlechart" + chartid).innerHTML = titleTxt;

    var totalsamples = temperaturenumbers.length;
    //document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + '<span class="badge">' + totalsamples + '</span>';
    document.getElementById("totalsampleschart" + chartid).innerHTML = 'Samples: ' + totalsamples;
    var samples_length = response.samples.length - 1
    document.getElementById("lastsampletimechart" + chartid).innerHTML = "Last sample time: " + response.samples[samples_length].sample_dt;

    //document.getElementById("processUptime" + chartid).innerHTML = processUptimeTxt;
    // document.getElementById("systemMessage" + chartid).innerHTML = "System: " + response.settings.systemMessage;

    if (lightState == 0) {
        showStuff("lightOffSpan" + zone);
        hideStuff("lightOnSpan" + zone);
    } else {
        showStuff("lightOnSpan" + zone);
        hideStuff("lightOffSpan" + zone);
    }


    tmaxgraph = tSPHi + 1.0;
    tmingraph = tSPLo - 1.5;

    if (tempmax > tSPHi) {
        tmaxgraph = tempmax + 1.0;
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



    //hide loader overlay#
    //$("#chart" + chartid).LoadingOverlay("hide");

    //eval(chartid.substring(1)).load(columnsobj);
    console.log(columnsobj);
    //window.chart[zone].unload();
    window.chart[zone].internal.loadConfig(axisobj);
    window.chart[zone].internal.loadConfig(gridobj);

    window.chart[zone].load(columnsobj);
    // console.log(columnsobj);
    //
    // window.chart[zone].resize();
    //
    // window.chart[zone].flush();
    //console.log(columnsobj);

    //window.chart[zone].load(columnsobj);

    //console.log('     loaded');

    //$("#chart" + chartid).LoadingOverlay("hide");
    endTime = new Date();
    millisecondsLoading = endTime.getTime() - startTime.getTime();
    //convert to seconds
    secondsLoading = millisecondsLoading / 1000;
    //update load time text
    //    $('.loadtimechart' + chartid).html('<span class="badge">' + secondsLoading + '</span>');
    $('.loadtimechart' + chartid).html(secondsLoading);

    // var reload_call = "getgraphdata(chartid, zone)";
    //console.log(' reload call:  ' + reload_call);
    //clear current interval
    window.clearInterval(window.intervalTimerHandle[zone]);
    //create string to pass into setinterval call
    var interval_t = "getgraphdata('" + chartid + "'," + zone + ")";
    //set new interval based on last reload time
    reloadInterval = ((3 * 1000) + (millisecondsLoading * 6));
    var reloadIntervalSeconds = reloadInterval / 1000;
    window.intervalTimerHandle[zone] = window.setInterval(interval_t, reloadInterval);

    //document.getElementById("reloadInterval" + chartid).innerHTML = 'Reload Interval: ' + '<span class="badge">' + reloadIntervalSeconds + '</span>' + ' seconds';
    // document.getElementById("reloadInterval" + chartid).innerHTML = 'Reload Interval: ' + reloadIntervalSeconds + ' seconds';

    //countdown code
    var count = Math.round(reloadIntervalSeconds); //reloadIntervalSeconds;
    var interval = setInterval(function () {
        count--;
        //       document.getElementById("countdown" + chartid).innerHTML = 'Countdown: ' + '<span class="badge">' + count + '</span>' + ' seconds';
        //document.getElementById("countdown" + chartid).innerHTML = 'Countdown: ' + count  + ' seconds';
        reloadInfoTxt = 'Reload Interval: ' + reloadIntervalSeconds + ' secs. ' + ' Countdown: ' + count;
        document.getElementById("reloadInfo" + chartid).innerHTML = reloadInfoTxt;

        if (count <= 0) {
            clearInterval(interval);
            return;
        }
    }, 1000);
    //
    //window.chart[zone].load(columnsobj);
    //$.plot($("#flot-placeholder1"), dataset, options);
    setTimeout(GetData, updateInterval);
}


function getgraphdata(chartid = 1, zone = 1, hours = 0.5) {


}
;


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
};
