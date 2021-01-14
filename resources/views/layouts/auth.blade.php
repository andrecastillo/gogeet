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

            <div class="row h-100">

                <div class="col">
                    <div class="row">
                        <!-- top bar -->
                        @include('partials.topbar')
                        <!-- /top bar-->
                    </div>

                    <div class="row h-100">
                        <!-- main content -->
                        <main class="h-100 col pt-5">
                            @yield('content')
                        </main>
                        <!-- /main content -->

                    </div>
                </div>

            </div>

    </div>
</body>
</html>
