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
<script src="/js/mychart.js"></script>
<title id="title">MyIoT - Graph All - Hours</title>

<style>
.graph-text{
  font-size: 0.7em
}
</style>

@stop

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-success">
      <div class="panel-heading " id="titlechart1" >Chart Info</div>
      <div class="panel-body" >
        <div id="chart1" class="text-center" ></div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-5">
              <h6 class="text-left graph-text" id="lastsampletimechart1">Last sample time: </h6>
              <h6 class="text-left graph-text" id="totalsampleschart1"  >Samples: </h6>
              <h6 class="text-left graph-text">Render Time: <div class="loadtimechart1"style="display: inline-block;"></div> seconds </h6>
              <h6 class="text-left graph-text" id="processUptime1">Process Up Time: </h6>
              <!-- <h6 class="text-left graph-text" id="systemMessage1">System Message: </h6> -->

            </div>

            <div class="col-sm-5">
              <h6  class="text-left graph-text" id="tempschart1">Temperatures</h6>
              <h6  class="text-left graph-text" id="tempSettings1">Temperature Settings</h6>
              <h6  class="text-left graph-text" id="reloadInterval1">Reload Interval</h6>
              <h6  class="text-left graph-text" id="countdown1">countdown</h6>
              <button type="button" class="btn btn-primary btn-xs" id="reloadchart1" >Reload graph data</button>

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
            <div class="col-sm-6">
              <h6 class="text-left" id="lastsampletimechart2">Last sample time:  </h6>
              <h6 class="text-left" id="totalsampleschart2"  >Samples: </h6>
              <h6 class="text-left">Render Time: <div class="loadtimechart2"style="display: inline-block;"></div> seconds </h6>
              <button type="button" class="btn btn-primary btn-xs" id="reloadchart2" >Reload graph data</button>
              <h6 class="text-left" id="processUptime2">Process Up Time: </h6>
              <h6 class="text-left" id="systemMessage2">System Message: </h6>  </div>
              <div class="col-sm-6">

                <h6  class="text-left" id="tempschart2">Temperatures</h6>
                <h6  class="text-left" id="tempSettings2">Temperature Settings</h6>
                <h6  class="text-left" id="reloadInterval2">Refresh Interval</h6>
                <h6  class="text-left" id="countdown2">countdown</h6>

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
  @stop
