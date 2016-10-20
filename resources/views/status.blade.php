<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href='/static/style.css' />
    {{-- <link rel="shortcut icon" href="{{ url_for('static', filename='favicon.ico') }}"> --}}
    <title>RPi2 - Current Status</title>
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.css" />
    {{-- <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script> --}}
    <script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
</head>

<style>
h3, h4 {text-align: center;}
span {font-weight: bold;}
<!--
.containing-element .ui-slider-switch { width: 10em }
-->
</style>


<script type=text/javascript>
    $(
    // When the LED button is pressed (change)
    // do an ajax request to server to change LED state
    function()
    {
        $('#flip-1').change(function()
        {
        $.getJSON('/_led', {state: $('#flip-1').val()});
        });
    }
    );

    $(
    // periodically (500ms) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_heaterRelay', function(data)
            {
                $("#heaterRelayState").text(data.heaterRelayState);
                setTimeout(function(){button();},1000);
            });
    }
    );

    $(
    // periodically (500ms) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_ventRelay', function(data)
            {
                $("#ventRelayState").text(data.ventRelayState);
                setTimeout(function(){button();},3000);
            });
    }
    );

    $(
    // periodically (500ms) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_fanRelay', function(data)
            {
                $("#fanRelayState").text(data.fanRelayState);
                setTimeout(function(){button();},1000);
            });
    }
    );

    $(
    // periodically (5s) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_fanSpeedRelay', function(data)
            {
                $("#fanSpeedRelayState").text(data.fanSpeedRelayState);
                setTimeout(function(){button();},5000);
            });
    }
    );

    $(
    // periodically (500ms) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_getTemp', function(data)
            {
                $("#tempRelayState").text(data.tempRelayState);
                setTimeout(function(){button();},3000);
                if (data.tempRelayState > 24) {
                    $("#tempRelayState").css('color', "#dd0000")
                }
                else {
                    $("#tempRelayState").css('color', "#00dd00")
                }
                    ;
            });
    }
    );
    $(
    // periodically (500ms) do an ajax request to get the relay state
    // modify the span tag to reflect the state (pressed or not)
    // the state text comes from the JSON string returned by the server
    function button()
    {
        $.getJSON('/_getHumi', function(data)
            {
                $("#humiRelayState").text(data.humiRelayState);
                setTimeout(function(){button();},1000);
            });
    }
    );

</script>
<!-- Simple JQuery Mobile page that display the button state on the breadoard -->
<!-- You can also change the LED state with the slider switch -->
<!-- The Raspberry Pi uptime is displayed in the footer (Jinja2 expands the template tag) -->


<body>

<div data-role="page" data-theme="b">
  <div data-role="header">
    <div><h3>RPi2 Web Status/Control</h3></div>
  </div>

  <div data-role="content">
    <form>
        <p>Temperature: <span id="tempRelayState"></span></p>

        <p>Humidity   : <span id="humiRelayState"></span></p>

        <p>The heater is <span id="heaterRelayState"></span></p>

        <p>The Vent is <span id="ventRelayState"></span></p>

        <p>The Fan is <span id="fanRelayState"></span></p>

        <p>The Fan Speed is <span id="fanSpeedRelayState"></span></p>
    </form>
<!--
    <form>
        <div class="ui-field-contain">
        <label for="flip-min">Relay 4:</label>
        <select name="flip-1" id="flip-1" data-role="slider" style="align: left;">
            <option value="off">OFF</option>
            <option value="on">ON</option>
        </select>
        </div>
    </form>
-->

  </div>
 <div data-role="footer">
    <div><h4>RPi2 uptime : {{uptime}}</h4></div>
  </div>
</div><!-- /page -->
</body>

</html>
<!--
<form>
    <div class="ui-field-contain">
        <label for="flip-6">Flip toggle switch:</label>
        <select name="flip-6" id="flip-6" data-role="slider">
            <option value="off">Off</option>
            <option value="on">On</option>
        </select>
    </div>
</form>
-->
<!--
<div class="containing-element">
  <label for="flip-min">Flip switch:</label>
  <select name="flip-min" id="flip-min" data-role="slider">
    <option value="off">Switch Off</option>
    <option value="on">Switch On</option>
  </select>
</div>
.containing-element .ui-slider-switch { width: 9em } -->
