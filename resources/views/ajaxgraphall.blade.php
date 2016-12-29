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
  font-size: 0.8em
}
</style>

@stop

@section('content')
<div class="row">
  @for ($i = 1; $i < 3; $i++)
  <div class="col-md-6">
    <div class="panel panel-success">
      <div class="panel-heading " id="titlechart{{$i}}" >Chart Info</div>
      <div class="panel-body" >
        <div id="chart{{$i}}" class="text-center" ></div>
        <div class="panel-footer">
          <div class="row">
            <div class="col-sm-5">
              <h6 class="text-left graph-text" id="lastsampletimechart{{$i}}">Last sample time: </h6>
              <h6 class="text-left graph-text" id="totalsampleschart{{$i}}"  >Samples: </h6>
              <h6 class="text-left graph-text">Render Time: <div class="loadtimechart{{$i}}"style="display: inline-block;"></div> seconds </h6>
              <!-- <h6  class="text-left graph-text" id="countdown{{$i}}">countdown</h6> -->
              <!-- <h6 class="text-left graph-text" id="processUptime{{$i}}">Process Up Time: </h6> -->
              <!-- <h6 class="text-left graph-text" id="systemMessage1">System Message: </h6> -->
            </div>
            <div class="col-sm-5">
              <!-- <h6  class="text-left graph-text" id="tempschart{{$i}}">Temperatures</h6> -->
              <h6  class="text-left graph-text" id="tempSettings{{$i}}">Temperature Settings</h6>
              <div>
                <!-- <h6   class="text-left graph-text" id="reloadInterval{{$i}}">Reload Interval</h6> -->
                <!-- <h6   class="text-right graph-text" id="countdown{{$i}}">countdown</h6> -->
              </div>
              <h6  class="text-left graph-text" id="reloadInfo{{$i}}">Reload Info</h6>

              <button type="button" class="btn btn-primary btn-xs" id="reloadchart{{$i}}" >Reload graph data</button>
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
@stop
