@extends('layouts.myiot') @section('head')
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

@stop @section('content')
<div class="row">
  @for ($i = 1; $i
  < 4; $i++) <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <div class="row">

          <div class="col-sm-10">
            <h6 class="text-left" id="lblLocation{{$i}}" style="float:left;">Location</h6>
            <h6 class="text-right" id="lblTemps{{$i}}" style="float:right;">Temps</h6>
          </div>

          <div class="col-sm-2">
            <h6>
              <span id="lightOffSpan{{$i}}" style="display: none" class="badge badge-pill badge-dark">OFF</span>
              <span id="lightOnSpan{{$i}}" style="display: none" class="badge badge-pill badge-danger">ON</span>
            </h6>
          </div>

        </div>

        <div class="row">

          <div class="col-sm-6">
            <h6 class="text-left" id="lastsampletimechart{{$i}}">Last sample: </h6>
            <h6>
              <span id="staleMinutesTrueSpan{{$i}}" style="display: none;" class="badge badge-pill badge-warning">Stale</span>
            <span id="staleMinutesFalseSpan{{$i}}" style=" display: none;" class="badge badge-pill badge-success">Fresh</span>
          </h6>
            <!-- <h6 class="text-left" id="tempSettings{{$i}}">Temperature Settings  </h6> -->
          </div>

          <div class="col-sm-6">
            <div>
              <h6 class="text-left" id="reloadInfo{{$i}}">Reload Info</h6>
              <h6 class="text-left">Refreshed in: <div class="loadtimechart{{$i}}"style="display: inline-block;"></div> secs </h6>

            </div>
          </div>

        </div>

      </div>
    </div>

    <div class="card-body ">
      <div id="chart{{$i}}"></div>
    </div>

    <div class="card-footer">
      <div class="row">
        <div class="col-sm-6">
          <h6 class="text-left" id="controllerMessage{{$i}}">controller message</h6>
          <h6 class="text-left" id="miscMessage{{$i}}">misc message</h6>
        </div>
        <div class="col-sm-6">
          <h6 class="text-left" id="processUpTime{{$i}}">Process Up Time</h6>
          <h6 class="text-left" id="systemUpTime{{$i}}">System Up Time</h6>
        </div>
      </div>
    </div>

</div>
@endfor
</div>

@if ( session()->has('message') )
<h6 class="text-center">
    <span class="glyphicon glyphicon-refresh"></span> {{ session()->get('message') }} <span class="glyphicon glyphicon-refresh"></span>
</h6> @endif
<script>
</script>
@stop
