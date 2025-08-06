<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FTuri') }}</title>
    @yield('scripts-before')
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{ asset('js/vue-2.7.14.js') }}"></script>
    @yield('style')

    <style>
        .table-responsive {
            min-height: 50vh;
        }

        .lista {
            position: fixed;
            width: 40%;
            z-index: 10;
            background: #fff;
        }

        .lista .list-group .list-group-item {
            border: 0;
            border-radius: 0;
            cursor: pointer;
        }

        .lista .list-group .list-group-item:hover {
            background: #0097A7;
            color: #fff;
        }

        .pointer {
            cursor: pointer;
        }

        .breadcrumb {
            color: #263238;
        }

        .breadcrumb .breadcrumb-item {
            color: #263238;
        }

        .breadcrumb .breadcrumb-item a {
            color: #263238;
        }

        .breadcrumb-item+.breadcrumb-item:before {

            color: #263238;
        }

        .breadcrumb .breadcrumb-item.active {
            color: #455A64;
            font-weight: 400;
        }

        @media print {
            .navbar {
                display: none;
            }
        }
    </style>
</head>

<body>
    @yield('main_content')
</body>

</html>
