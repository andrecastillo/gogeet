<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'goGeet') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="h-100">
    <div id="app" class="container-fluid h-100">

            <div class="row h-100">
                <!-- left sidebar -->
                <nav id="sidebarMenu" class="col navbar-dark bg-dark">
                    @include('partials.leftsidebar')
                </nav>
                <!-- /left sidebar -->

                <div class="col-11">
                    <div class="row">
                        <!-- top bar -->
                        @include('partials.topbar')
                        <!-- /top bar-->
                    </div>

                    <div class="row">
                        <!-- main content -->
                        <main class="col-7 h-100">
                            @yield('content')
                        </main>
                        <!-- /main content -->

                        <!-- right sidebar -->
                        <div id="rightsidebar" class="col-5">
                            @include('partials.rightsidebar')
                        </div>
                        <!-- /right sidebar -->
                    </div>
                </div>

            </div>

    </div>
</body>
</html>
