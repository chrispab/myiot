
@extends('layout')

@section('head')



  <title>MyIoT - Dashboard</title>
@stop

@section('content')


      {{-- @if (Route::has('login'))
          <div class="top-right links">
              <a href="{{ url('/login') }}">Login</a>
              <a href="{{ url('/register') }}">Register</a>
          </div>
      @endif --}}
      <div class="container-fluid bg-3 text-center">
       <h3>Where to?</h3>
       <div class="row">
         <div class="col-sm-3">
           <p>Heating</p>
           <img src="/images/radiator200.svg" alt="Heating" class="img-rounded" width="128" height="128">
         </div>
         <div class="col-sm-3">
           <p>Lighting</p>
           <img src="/images/bulb512x512.png" alt="Lighting" class="img-rounded" width="128" height="128">
         </div>
         <div class="col-sm-3">
           <p>Utilities</p>
           <img src="/images/home128.png" alt="Utilities" class="img-rounded" width="128" height="128">
         </div>
         <div class="col-sm-3">
           <p>Cameras</p>
           <img src="/images/video_camera128.png" alt="Cameras" class="img-rounded" width="128" height="128">
         </div>
       </div>

     </div>

     {{-- <div class="flex-center position-ref full-height">

      <div class="content">
          <div class="title m-b-md">

          </div>

          <div class="links">
              <a href="graph/0.5">Graph</a>

          </div>
      </div>
  </div> --}}

@stop
