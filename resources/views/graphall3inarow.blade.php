@extends('layouts.app')

@section('head')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


<!-- c3 stuff -->
<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.css" rel="stylesheet" type="text/css">-->
<link href="/css/c3.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.13/d3.js"></script>
<!-- <script type="text/javascript" src="/js/d3v3_5_17.js"></script> -->

<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.0/d3.min.js"></script> -->
<!-- <script type="text/javascript" src="/js/d3.js"></script> -->
<!-- <script type="text/javascript" src="https://d3js.org/d3.v4.min.js"></script> -->
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script> -->
<script type="text/javascript" src="/js/c3.js"></script>


<script src="/js/loadingoverlay.min.js"></script>
<script src="/js/mychartnew.js"></script>
<!-- bootstrap slider -->
<!-- <script src="/js/bootstrap-slider.min.js"></script> -->
<!-- <link rel="stylesheet" type="text/css" href="/css/bootstrap-slider.min.css"> -->

<title id="title">MyIoT - Graph All 3 in a row - Hours..</title>

<style>
    .graph-text{
        font-size: 0.8em
    }
</style>

@stop

@section('content')
<div class="row">
    @for ($i = 1; $i < 4; $i++)
    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading " >

              <div class="row">
                  <div class="col-sm-5">
                      <h6 class="text-center graph-text" id="lblLocation{{$i}}" >Location</h6>
                     <!-- <span id="lightOffSpan{{$i}}" style="background-color:black; display: none; float: right" class="badge">OFF</span>
                      <span id="lightOnSpan{{$i}}" style="background-color:red; display: none; float: right" class="badge">ON</span> -->

                </div>

                <div class="col-sm-2">
                                      <!-- <h6 style="display: inline" class="text-center graph-text" id="titlechart{{$i}}" >ON/OFF</h6> -->
                                     <!-- <h6 class="text-center graph-text"> -->
                                     <span id="lightOffSpan{{$i}}" style="background-color:black; display: none" class="badge">OFF</span>
                                      <span id="lightOnSpan{{$i}}" style="background-color:red; display: none" class="badge">ON</span>
                                    <!-- </h6> -->
                </div>
                                
                <div class="col-sm-5">
                    <h6  class="text-center graph-text" id="lblTemps{{$i}}" >Temps</h6>
                   <!-- <span id="lightOffSpan{{$i}}" style="background-color:black; display: none; float: right" class="badge">OFF</span>
                    <span id="lightOnSpan{{$i}}" style="background-color:red; display: none; float: right" class="badge">ON</span> -->

              </div>


              </div>

                <div class="row">
                    <div class="col-sm-6">
                      <h6 class="text-left graph-text" id="lastsampletimechart{{$i}}">Last sample: </h6>
                        <!-- <h6 class="text-left graph-text" id="totalsampleschart{{$i}}">Samples: </h6> -->
                            <h6  class="text-left graph-text" id="tempSettings{{$i}}">Temperature Settings</h6>
                            <span id="staleMinutesTrueSpan{{$i}}" style="background-color:brown; display: none;" class="badge">Stale</span>
                            <!--<span id="lightOffSpan{{$i}}" style="color:black; display: none;" class="glyphicon glyphicon-certificate">OFF</span>-->

                            <span id="staleMinutesFalseSpan{{$i}}" style="background-color:green; display: none;" class="badge">Fresh</span>

                        <!-- <h6  class="text-left graph-text" id="countdown{{$i}}">countdown</h6> -->
                        <!-- <h6 class="text-left graph-text" id="processUptime{{$i}}">Process Up Time: </h6> -->
                        <!-- <h6 class="text-left graph-text" id="systemMessage1">System Message: </h6> -->
                    </div>
                    <div class="col-sm-6">
                        <!-- <h6  class="text-left graph-text" id="tempschart{{$i}}">Temperatures</h6> -->
                        <div>
                            <!-- <h6   class="text-left graph-text" id="reloadInterval{{$i}}">Reload Interval</h6> -->
                            <!-- <h6   class="text-right graph-text" id="countdown{{$i}}">countdown</h6> -->
                        </div>
                        <h6  class="text-left graph-text" id="reloadInfo{{$i}}">Reload Info</h6>
                        <h6 class="text-left graph-text">Refreshed in: <div class="loadtimechart{{$i}}"style="display: inline-block;"></div> secs </h6>

                        <button type="button" class="btn btn-primary btn-xs" id="reloadchart{{$i}}" >Reload graph data</button>
                        <!-- <span id="lightOffSpan{{$i}}" style="background-color:black; display: none;" class="badge">OFF</span>
                        <span id="lightOnSpan{{$i}}" style="background-color:red; display: none;" class="badge">ON</span> -->
                        <!--<span id="lightOffSpan{{$i}}" style="color:black; display: none;" class="glyphicon glyphicon-certificate">OFF</span>-->
            <!-- <input id="ex4" type="text" data-slider-min="14" data-slider-max="25" data-slider-step="0.1" data-slider-value="20" data-slider-orientation="vertical"/> -->
                    </div>
                </div>
            </div>
            <div class="panel-body" >
                <div id="chart{{$i}}"  ></div>
               <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6">
                          <h6 class="text-left graph-text" id="controllerMessage{{$i}}" >controller message</h6>
                          <h6 class="text-left graph-text" id="miscMessage{{$i}}" >misc message</h6>

                        </div>
                        <div class="col-sm-6">
                          <h6  class="text-left" id="processUpTime{{$i}}">Process Up Time</h6>
                          <h6  class="text-left" id="systemUpTime{{$i}}">System Up Time</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endfor
</div>

@if ( session()->has('message') )
<h6 class="text-center">
    <span class="glyphicon glyphicon-refresh"></span> {{ session()->get('message') }} <span class="glyphicon glyphicon-refresh"></span>
</h6>
@endif
<script>
// $("#ex4").slider({
//
// reversed : true
//
// });
</script>
@stop
