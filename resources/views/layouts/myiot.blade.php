<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- <title>{{ config('app.name') }}</title> -->
  <link href="https://fonts.googleapis.com/css?family=Orbitron" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Exo" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Mako" rel="stylesheet" type="text/css">

  <!-- Styles -->

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">


  <!-- <link href="/css/app.css" rel="stylesheet"> -->
  <!-- <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script> -->
  <!-- <script src="https://code.jquery.com/jquery-3.1.1.min.js"
        integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
        crossorigin="anonymous">
    </script> -->

  <!-- note although jquery slim recommended - myiot requires jquery.min -->
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <!-- Scripts -->
  <script>
    window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
  </script>

  <!-- bootstrap Latest compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
    integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
    crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
    integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
    crossorigin="anonymous"></script> -->


  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

  <link rel="stylesheet" type="text/css" href="/css/myiotstyles.css">
  <link rel="icon" type="image/png" href="/favicon.png" /> @yield('head')
</head>

<body>
  <div id="app">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <a class="navbar-brand" href="{{ url('/') }}">TESTING {{ config('app.name') }}</a>

      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <!-- <div class="collapse navbar-collapse" id="app-navbar-collapse"> -->
        <!-- Left Side Of Navbar -->
        <ul class="navbar-nav mr-auto">
          @if (Auth::guest())
          <li class="nav-item"><a class="nav-link" href="/">Welcome Guest</a></li>
          <!-- <li ><a href="/">Welcome Guest</a></li> -->
          @else
          <!-- <li><a href="/dashboard">Dashboard</a></li> -->
          <li class="nav-item"><a class="nav-link" href="/dashboard">Dashboard</a></li>
          @if (\Request::is('*graph*/*'))
          <!-- <li class="dropdown"> -->
          <!-- <a class="dropdown-toggle" data-toggle="dropdown" href="#">Graphs <span class="caret"></span></a> -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Graph Time</a>

            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="./0.017">1 Min</a>
              <a class="dropdown-item" href="./0.1">6 Mins</a>
              <a class="dropdown-item" href="./0.25">15 Mins</a>
              <a class="dropdown-item" href="./0.5">30 Mins</a>
              <a class="dropdown-item" href="./1.0">1 Hour</a>
              <a class="dropdown-item" href="./2.0">2 Hours</a>
              <a class="dropdown-item" href="./4.0">4 Hours</a>
              <a class="dropdown-item" href="./8.0">8 Hours</a>
              <a class="dropdown-item" href="./12.0">12 Hours</a>
              <a class="dropdown-item" href="./24.0">24 Hours</a>
              <a class="dropdown-item" href="./48.0">48 Hours</a>
            </div>
          </li>
          @endif
          <li class="nav-item"><a class="nav-link" href="#">Settings</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Help</a></li>

          <!-- <li><a href="#">Settings</a></li> -->
          <!-- <li><a href="#">Help</a></li> -->
          @endif
        </ul>
        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav mr-auto navbar-right">
          <!-- Authentication Links -->
          @if (Auth::guest())
          <li><a href="{{ url('/login') }}">Login</a></li>
          <li><a href="{{ url('/register') }}">Register</a></li>
          @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="{{ url('/logout') }}" onclick="event.preventDefault();
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
      <!-- </div> -->
    </nav>

    <div class="container-fluid">
      @yield('content')
    </div>
  </div>

  <!-- Scripts -->
  <!-- <script src="/js/app.js"></script> -->
</body>

</html>
