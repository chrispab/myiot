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
              <h6 class="text-left" id="totalsampleschart1"  >Samples: {{count($samples)}} </h6>
              <h6 class="text-left">Render Time: <div class="loadtimechart1"style="display: inline-block;"></div> seconds </h6>
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
              <h6 class="text-left">Render Time: <div class="loadtimechart2"style="display: inline-block;"></div> seconds </h6>
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



@stop
