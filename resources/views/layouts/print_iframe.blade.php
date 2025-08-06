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
            margin: 1in;
            size: 11in 8.5in;
        }

        .page-number:after {
            content: "Página " counter(page);
        }

        .row {
            width: 100%;
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

        .text-center {
            text-align: center;
        }

        .text-truncate {
            max-width: 200px;/*ancho máximo de la columna hasta donde podrá llegar el texto*/
            overflow: hidden;
            text-overflow: ellipsis;/*Muestra "..." al final si el texto es demasiado largo*/
            white-space: nowrap;/*Evita que el texto se rompa en varias líneas*/
        }
    </style>
</head>

<body>
    <main>
        @yield('content')
    </main>
</body>

</html>
