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
            margin-left: 0.7cm;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
            height: 60px;
            line-height: 35px;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 600;
        }

        .logo {
            width: 85px;
            margin-top: 15px;
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
            width: 20cm;
            margin: 0px !important;
            padding: 0px !important;
            font-size: 0;
        }

        [class*="col-"] {
            display: inline-block;
            margin: 0 !important;
            padding: 0 !important;
            font-size: 10pt;
        }

        .col-1 {
            width: 8.33%;
        }

        .col-2 {
            width: 16.67%;
        }

        .col-3 {
            width: 25%;
        }

        .col-4 {
            width: 33.33%;
        }

        .col-5 {
            width: 41.67%;
        }

        .col-6 {
            width: 50%;
        }

        .col-7 {
            width: 58.33%;
        }

        .col-8 {
            width: 66.67%;
        }

        .col-9 {
            width: 75%;
        }

        .col-10 {
            width: 83.33;
        }

        .col-11 {
            width: 91.67%;
        }

        .col-12 {
            width: 100%;
        }

        .col-2-title {
            width: 16.6%;
            margin: 0px;
        }

        .col-4-title {
            width: 32.3%;
            margin: 0px;
        }

        .col-6-title {
            width: 49.9%;
            margin: 0px;
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

        .fz-9 {
            font-size: 9pt;
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
        <div class="col-2-title">
            <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo de empresa" class="logo">
        </div>
        <div class="col-6-title">
            @yield('titulo')
        </div>
        <div class="col-4-title fr fz-9">
            TURÍSTICAS DE ORIENTE S.A. DE C.V.
        </div>
    </header>
    <footer class="row">
        <div class="col-12">
            <div class="page-number"></div>
            <small style="float: right; margin-top: -20px;">{{ date('Y-m-d H:i:s') }}</small>
        </div>
    </footer>
    <main>
        @yield('content')
    </main>
</body>

</html>
