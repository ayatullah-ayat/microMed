<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Authorization</title>

    <!-- Styles -->
    <link href="/vendor/authorize/css/app.css" rel="stylesheet">
@stack('styles')
<!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};
    </script>
</head>
<body>
<div id="app">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/' . Config("authorization.route-prefix")) }}">
                    Laravel Authorization
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                           aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Menu</h3>
                    </div>
                    <div class="list-group">
                        <a href="{{ url('/' . Config("authorization.route-prefix") . '/users') }}"
                           class="list-group-item">Users</a>
                        <a href="{{ url('/' . Config("authorization.route-prefix") . '/roles') }}"
                           class="list-group-item">Role</a>
                        <a href="{{ url('/' . Config("authorization.route-prefix") . '/permissions') }}"
                           class="list-group-item">Permission</a>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                @yield('content')
            </div>
        </div>
    </div>

</div>

<!-- Scripts -->
<script src="/vendor/authorize/js/app.js"></script>
@stack('scripts')
</body>
</html>
