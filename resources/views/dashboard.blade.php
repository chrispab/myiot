
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


  <title>MyIoT - Graphs</title>
@stop

@section('content')
  <div class="row">
    <div class="col-md-12">
      <ul class="nav nav-pills ranges">
          <li><a href="0.25" hours-range='0.25'>15 Mins</a></li>
        <li><a href="0.5" hours-range='0.5'>30 Mins</a></li>
        <li><a href="1.0" hours-range='1.0'>1 Hour</a></li>
        <li><a href="2.0" hours-range='2.0'>2 Hours</a></li>
        <li><a href="12.0" hours-range='12.0'>12 Hours</a></li>
        <li><a href="24" hours-range='24'>1 Day</a></li>
      </ul>
</div>
{{-- <h4 class="text-center">{{$hours}} hours</h4> --}}
<p id="chart">Just a moment...Processing Data</p>
{{-- <h6>Samples: {{count($samples)}}, Load Time: <div class="loadtime"style="border: solid 1px #ccc; display: inline-block;"></div> seconds</h6>
<h3>tmax: {{$settings['tmax']}}, tmin; {{$settings['tmin']}}</h3>
<h3>tSPhi: {{$settings['tSPhi']}}, tSPlo; {{$settings['tSPlo']}}</h3> --}}



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
