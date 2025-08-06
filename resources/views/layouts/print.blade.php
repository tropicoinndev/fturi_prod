<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FTuri') }}</title>
    @yield('style')
    <style>
        @page {
            margin: 90px 0.5cm;
        }

        header {
            position: fixed;
            top: -60px;
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
            width: 30%;
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
        }
    </style>
</head>

<body>
    <header class="row">
        <div class="col-4">

        </div>
        <div class="col-4 fc mb-15">
            @yield('titulo')
        </div>

        <div class="col-4 fr fz-12 mb-15">
            TURÍSTICAS DE ORIENTE S.A. DE C.V.
        </div>
    </header>
    <footer>
        @yield('footer')
        <small style="float: right;">{{ date('Y-m-d H:i:s') }}</small>
        <div class="page-number"></div>
    </footer>
    <main>
        @yield('content')
    </main>
</body>

</html>
