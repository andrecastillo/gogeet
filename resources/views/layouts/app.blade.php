<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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

<body>
    <div id="app" class="container-fluid h-100">

        <div class="container-fluid h-100">
            <div class="row pt-3 h-100">
                <!-- sidebar -->
                @include('partials.sidebar')
                <!-- /sidebar -->

                <!-- main content -->
                <main class="col-md-9 ml-sm-auto col-lg-11 px-md-4 h-100">
                    <!-- top bar -->
                    @include('partials.topnav')

                    <!-- main content -->
                    @yield('content')
                </main>
                <!-- /main content -->
            </div>
        </div>

    </div>
</body>
</html>
