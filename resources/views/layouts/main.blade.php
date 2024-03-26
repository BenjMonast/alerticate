<!DOCTYPE html>
<html lang='en'>
    <head>
        <title>@yield('title')</title>
        <meta charset='UTF-8'>
        <script src='{{asset("js/jquery-3.3.1.min.js")}}'></script>
        <link href='{{asset("css/fontawesome-all.min.css")}}' rel="stylesheet" type="text/css" media="all">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/smoothness/jquery-ui.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
        <script src="http://code.jquery.com/jquery-migrate-3.0.0.js"></script>
        <link rel="stylesheet" href="{{asset('css/style.css')}}">
        @yield('head')
    </head>
    <body>
        <nav class='navbar navbar-expand-sm bg-dark navbar-dark'>
            <div class="container">
                <a href="{{url('/')}}" class="navbar-brand">Alerticate</a>
@if (Session::has('login'))
                <ul class="navbar-nav mr-auto"></ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="{{url("/profile/edit")}}" class='nav-link'>{{Session::get('name')}}</a></li>
                    <li class="nav-item"><a href='{{url('/logout')}}' class="nav-link">Logout</a></li>
                </ul>
@else
                <ul class="navbar-nav mr-auto"></ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a href="{{url('/register')}}" class="nav-link">Sign Up</a></li>
                    <li class="nav-item"><a href="{{url('/')}}" class="nav-link">Login</a></li>
                </ul>
@endif
            </div>
        </nav>

        @yield('body')
    </body>
</html>