
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
       <h3>Where To Find Me?</h3>
       <div class="row">
         <div class="col-sm-4">
           <p>Lorem ipsum..</p>
           <img src="birds1.jpg" alt="Image">
         </div>
         <div class="col-sm-4">
           <p>Lorem ipsum..</p>
           <img src="birds2.jpg" alt="Image">
         </div>
         <div class="col-sm-4">
           <p>Lorem ipsum..</p>
           <img src="birds3.jpg" alt="Image">
         </div>
       </div>
     </div>

     <div class="flex-center position-ref full-height">
     
      <div class="content">
          <div class="title m-b-md">

          </div>

          <div class="links">
              <a href="graph/0.5">Graph</a>

          </div>
      </div>
  </div>
  <div class="row">
  <div class="col-sm-4">
    <p><strong>Name</strong></p><br>
    <img src="bandmember.jpg" alt="Random Name">
  </div>
  <div class="col-sm-4">
    <p><strong>Name</strong></p><br>
    <img src="bandmember.jpg" alt="Random Name">
  </div>
  <div class="col-sm-4">
    <p><strong>Name</strong></p><br>
    <img src="bandmember.jpg" alt="Random Name">
  </div>
</div>
@stop
