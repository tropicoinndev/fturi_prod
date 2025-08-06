@extends('layouts.print_bootstrap')

@section('content')
    <style>
        body {
            background: #ffffff;
            text-transform: uppercase;
        }

        .card {
            background: #ffffff;

        }

        .table-light tr td {
            background: #ffffff !important;
            padding: 5px;
        }

        .table-light .bg-gray {
            background: #ECEFF1 !important;
        }
    </style>

    <div class="card border-0">
        <div class="card-body">
            <h1 class="text-center mb-4">Reporte de anticipos activos</h1>

            <div class="row text-uppercase mb-4">
                <div class="col-12">
                    <b class="text-decoration-underline">Cajas:</b>
                    @foreach($cajas as $caja)
                        {{ $caja->caja }},
                    @endforeach
                </div>
                <div class="col-12">
                    <b class="text-decoration-underline">Se muestran todos los anticipos con fecha de aplicación menores o iguales a:</b> {{ $fecha_aplicacion }}
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <table class="table table-light table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">Anticipo Nº</th>
                                <th scope="col" class="text-truncate">Titular</th>
                                <th scope="col">Monto</th>
                                {{-- <th scope="col">Monto histórico</th> --}}
                                <th scope="col">Caja</th>
                                <th scope="col">Usuario realiza</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Fecha aplicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($anticipos as $a)
                                <tr>
                                    <td scope="row">{{ $a->id }}</td>
                                    <td>
                                        <b class="mb-0">{{ $a->clientes->nombre }}</b>
                                        <p class="mb-0">
                                            <i class="text-secondary">{{ $a->concepto }}</i>
                                        </p>
                                    </td>
                                    <td class="text-end">${{ number_format($a->monto, 2) }}</td>
                                    {{-- <td class="text-end">${{ number_format($a->monto_historico, 2) }}</td> --}}
                                    <td>{{ $a->turnos->cajas->caja }}</td>
                                    <td>{{ $a->users->name }}</td>
                                    <td>{{ $a->fecha }}</td>
                                    <td>{{ $a->fecha_aplicacion }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
