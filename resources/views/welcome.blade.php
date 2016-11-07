@extends('layouts.app')

@section('head')
  <title>MyIoT</title>
@stop

@section('content')

        <div class="flex-center position-ref full-height">

            {{-- @if (Route::has('login'))
                <div class="top-right links">
                    <a href="{{ url('/login') }}">Login</a>
                    <a href="{{ url('/register') }}">Register</a>
                </div>
            @endif --}}

            <div class="content">
                <div class="title">
                    MyIoT
                </div>

                {{-- <div class="links">
                    <a href="graph/0.5">Graph</a>

                </div> --}}
            </div>
        </div>
@stop
