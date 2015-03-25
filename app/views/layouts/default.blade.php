<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.0/css/bootstrap.min.css">
    {{ HTML::style('css/documents.css'); }}
    {{ HTML::script('javascripts/application.js') }}
    <style>
        body { padding-top: 20px; }
    </style>
</head>

<body>

<div class="container" >
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Lifelie</a>
    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
            @if (Auth::check())
                <li><a href="/admin_users">Admin Users</a></li>
                <li><a href="/admin/documents">Api Documents</a></li>
                <li><a href="/admin/logout">Log Out</a></li>
                <li><a href="#">{{ Auth::user()->name }}</a></li>
            @else
                <li><a href="/admin/login">Login</a></li>
            @endif
        </ul>

    </div>
    <div class="row">
        <div class="col-md-12">

            @if (Session::has('message'))
                <div class="flash alert">
                    <p>{{ Session::get('message') }}</p>
                </div>
            @endif

            @yield('main')

        </div>
    </div>

    <div id="footer" class="" style="margin-top:20px; text-align: center;">
        <nav class="navbar navbar-default ">
            <div class="navbar-inner navbar-content-center">
                <p class="text-muted credit">@copyright <a href="#">LifeLie</a>.</p>
            </div>
        </nav>
    </div>
</div>

</body>
</html>