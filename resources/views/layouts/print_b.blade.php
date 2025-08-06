<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'FTuri') }}</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    @yield('style')
    <style>
        @page {
            margin-top: 105px;
            margin-left: 1.75cm;

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
            bottom: -10px;
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
        }

        td {
            min-height: 45px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #25282b;
            border-collapse: collapse;
        }

        table thead tr {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #dee2e6;

        }

        .bg-total {
            background: #cfd8dcd4 !important;
            color: #1c1c1c;
            font-weight: 600;
        }

        .bg-total-final {
            background: rgb(203, 203, 203) !important;
            color: #1c1c1c;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <header class="row">
        <div class="col-4">
            <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo de empresa" class="logo">
        </div>
        <div class="col-4 fc mb-15">
            @yield('titulo')
        </div>

        <div class="col-4 fr fz-12 mb-15">
            TURÍSTICAS DE ORIENTE S.A. DE C.V.
        </div>
    </header>
    <footer>
        <small style="float: right;">{{ date('Y-m-d H:i:s') }}</small>
        <div class="page-number"></div>
    </footer>
    <main>
        @yield('content')
    </main>
</body>

</html>
