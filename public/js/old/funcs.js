
    setInterval(getgraphdata, 10 * 1000);

function getgraphdata() {
    //get last param - hours
    var pathArray = window.location.pathname.split('/');
    var zone = pathArray[pathArray.length - 2];
    var hours = pathArray[pathArray.length - 1];
    var postAddr = '/getajaxgraphdata/' + zone.toString() + '/' + hours.toString();
    console.log(postAddr);
    var startTime = (new Date()).getTime();

    $.post(postAddr, function(response) {

        var obj = {};
        var time = [];
        time.push("time");
        var i;
        for (i = 0; i < response.samples.length; i++) {
            time.push(response.samples[i].sample_dt);
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
            vent.push(response.samples[i].ventstate * 17);
        }
        var fan = [];
        fan.push("fan");
        for (i = 0; i < response.samples.length; i++) {
            fan.push(response.samples[i].fanstate * 15);
        }
        obj["time"] = time;
        obj["temperature"] = temperature;
        obj["humidity"] = humidity;
        obj["heater"] = heater;

        console.dir(obj);

        //update last sample time text
        document.getElementById("lastsampletime").innerHTML = "Last sample time: " + response.samples[response.samples.length - 1].sample_dt;
        //update min and max temp readings
        //get string of numbers from array
        tempstrimmed = temperature.slice();
        tempstrimmed.shift(); //remove first elem - eg "temperature"
        tempsstring = tempstrimmed.toString();
        console.log(tempsstring);
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

        console.log(pathArray);
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
    }, "JSON");

    var endTime = (new Date()).getTime();
    var millisecondsLoading = endTime - startTime;
    $('.loadtime').html(millisecondsLoading / 1000);


};
