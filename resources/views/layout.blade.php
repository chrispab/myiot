<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet" type="text/css">

        <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>

        <!-- bootstrap Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <!-- Optional theme -->
        {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> --}}
        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

       <!-- c3 stuff -->
       <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.js"></script>
       <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js"></script>
       <link href="//cdnjs.cloudflare.com/ajax/libs/c3/0.1.29/c3.css" rel="stylesheet" type="text/css">

       <!-- Styles -->
       <link rel="stylesheet" type="text/css" href="/css/origstyles.css">

       @yield('head')
       </head>
        <body>
            <nav class="navbar navbar-inverse">
             <div class="container-fluid">
               <div class="navbar-header">
                 <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                   <span class="icon-bar"></span>
                 </button>
                 <a class="navbar-brand" href="/">MyIoT</a>
               </div>

               <div class="collapse navbar-collapse" id="myNavbar">
                 <ul class="nav navbar-nav">
                   <li ><a href="/">Home</a></li>
                   <li><a href="/dashboard">Dashboard</a></li>
                   <li class="dropdown">
                      <a class="dropdown-toggle" data-toggle="dropdown" href="#">Graphs <span class="caret"></span></a>
                      <ul class="dropdown-menu">
                        <li><a href="./0.25">15 Mins</a></li>
                        <li><a href="./0.5">30 Mins</a></li>
                        <li><a href="./1.0">1 Hour</a></li>
                        <li><a href="./2.0">2 Hours</a></li>
                        <li><a href="./4.0">4 Hours</a></li>
                        <li><a href="./8.0">8 Hours</a></li>
                        <li><a href="./12.0">12 Hours</a></li>
                        <li><a href="./24.0">24 Hours</a></li>
                      </ul>
                    </li>
                   <li><a href="#">Settings</a></li>
                   <li><a href="#">Help</a></li>
                 </ul>
                 <ul class="nav navbar-nav navbar-right">
                     @if (Auth::guest())
                       <li><a href="#"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                       <li><a href="#"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
                   @else
                       <li class="dropdown">
                           <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                               {{ Auth::user()->name }} <span class="caret"></span>
                           </a>

                           <ul class="dropdown-menu" role="menu">
                               <li>
                                   <a href="{{ url('/logout') }}"
                                       onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                       Logout
                                   </a>

                                   <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                       {{ csrf_field() }}
                                   </form>
                               </li>
                           </ul>
                       </li>
                   @endif
                 </ul>
               </div>
             </div>
           </nav>


         <div class="container">
           @yield('content')
          </div>
</body>
</html>
