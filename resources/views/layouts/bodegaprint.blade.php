<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FTuri') }}</title>
    <link rel="stylesheet"  media="print" href="{{ asset('css/bootstrap.min.css') }}">


    @yield('style')
    <style>
        @page {
            margin: 105px 0.5cm;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0px;
            right: 0px;
            height: 60px;
            line-height: 35px;
            color: rgb(88, 88, 88);

        }

        .logo {
            width: 90px;
        }

        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
            color: rgb(88, 88, 88);
            text-align: center;
            line-height: 35px;
        }

        main {
            margin-right: 0.5cm;
        }

        .page-number:after {
            content: "Página " counter(page);
        }

        .row {
            width: 100vh;
            margin-bottom: 5px;
        }

        .col-4 {
            width: 33%;
            display: inline-block;
        }

        .fl {
            text-align: left;
        }

        .fr {
            text-align: right;
        }

        .fc {
            text-align: center;
        }

        .fz-12 {
            font-size: 12pt;
        }

        .mb-15 {
            margin-bottom: 15px;
        }.mb-13 {
            margin-bottom: 2px;
        }
    </style>
</head>

<body>
    <header class="row">
        <div class="col-4">
            <img src="{{ asset(env('logo', 'images/logo.jpg')) }}" alt="" class="logo">
        </div>
          <div class="col-4 fc mb-15">
            <div class="mb-13">
                @yield('titulo')
            </div>
            <div class="mb-13">
                @yield('subtitulo')
            </div>
        </div>
    </header>
    <footer>
        <div class="page-number"></div>
        <div class=" col-4 ">

            <small> {{ date('d-m-Y h:i:s a') }}</small>

        </div>
    </footer>
    <main>
        @yield('content')
    </main>
</body>

</html>
