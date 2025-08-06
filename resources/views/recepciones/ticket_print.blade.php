<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <style>
        @page {
            margin-top: 1.5cm;
            margin-left: 2.5cm;
            width: 26cm;
            height: 21cm;
            border: 1px solid #000;
        }

        .logo {
            width: 60px;
        }

        .row .ticket {
            display: inline-block;
            width: 7.2cm;
            height: 2.65cm;
            padding-top: 0.3cm;
            padding-left: 0.05cm;
            padding-right: 0.05cm;
            border: 1px dashed #000;
            margin-bottom: 0.1cm;
            text-align: center;
        }

        .ticket .col-2,
        .ticket .col-10 {
            display: inline-block;
        }

        .ticket .col-2 {
            width: 1.9cm;
        }

        .ticket .col-10 {
            width: 4.8cm;


        }

        .ticket .col-12 {
            width: 7.1cm;
        }

        .fz-9 {
            font-size: 9pt;
        }

        .fz-8 {
            font-size: 8pt;
        }

        .child {
            color: #00796B;
            font-weight: bolder;
            text-transform: uppercase;
        }

        .parent {
            color: #0288D1;
            font-weight: bolder;
            text-transform: uppercase;
        }

        .cumple {
            font-weight: bolder;
            color: #FF5722;
        }
    </style>
</head>

<body>
    <main>
        <div class="row">

            @foreach ($huespedes as $h)
                <div class="ticket">
                    <div class="col-2">
                        <img src="{{ asset(env('logo', 'images/logo.jpg')) }}" class="logo">
                    </div>
                    <div class="col-10 fz-9">
                        <span class="fz-9">
                            DESAYUNO TIPO BUFFET
                        </span>
                        <br>
                        <span class="fz-8 text-danger">
                            Valido unicamente en El Rancho
                        </span>

                    </div>

                    @php
                        $manana = Carbon::now()->addDay();
                        $nacimiento = Carbon::parse($h->huesped->nacimiento);
                    @endphp
                    <div class="col-12 fz-8 border content">
                        <b class="text-uppercase text-truncate">{{ substr($h->huesped->nombre, 0, 30) }}</b>
                        <br>
                        FECHA:<b>{{ $manana->format('d-m-Y') }}</b> ·
                        HABITACIÓN: <b>{{ $h->recepcion->habitaciones->numero_habitacion }}</b>
                        <br>
                        @if (env('infantil', 10) >= $h->huesped->edad)
                            <span class="child">infantil</span>
                        @else
                            <span class="parent">Adulto</span>
                        @endif

                        <span class="cumple">
                            {{ $nacimiento->day == $manana->day && $nacimiento->month == $manana->month ? '¡Feliz Cumpleaños!' : '' }}
                        </span>
                    </div>
                    <div class="col-12">
                        {{ Auth::user()->user }} {{ date('Y-m-d H:i:s') }}
                    </div>
                </div>
            @endforeach
        </div>
    </main>

</body>

</html>
