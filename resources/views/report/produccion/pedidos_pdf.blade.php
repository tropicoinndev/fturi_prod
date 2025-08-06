@extends('layouts.print')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 8pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            width: 6cm;
        }

        .w-10 {
            width: 1.5cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: {{ 21.94 / 7 }}cm;
            overflow: hidden;
            text-align: right;
        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 600;
            width: 100vh;
        }

        .b {
            font-weight: 500;
            color: #555;
        }

        .b1 {
            font-weight: 100;

        }

        .bt-1 {
            border-top: 1px #000 solid;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
            min-width: 1cm;

        }

        .space {
            height: 20px;
        }

        td {
            min-height: 45px;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
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
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .text-center {
            text-align: center;
        }

        small {
            font-size: 7pt;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        {{ $title }}
    </div>
@endsection

@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <span class="b"> Caja:</span>
                {{ $caja }}
            </div>
            <div class="col-4">
                <span class="b">Del:</span>
                {{ $inicio }}
            </div>

            <div class="col-4">
                <span class="b">Al:</span>
                {{ $fin }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Caja</th>
                            <th>Usuarios</th>
                            <th>Solicitado</th>
                            <th>Respuesta</th>
                            <th>Tiempo</th>
                            <th>Extra</th>
                            <th>Total</th>
                            <th>Estado</th>

                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($data as $d)
                            <tr>
                                <td class="text-uppercase"><small>{{ $d->dprecio->detalle }}</small></td>
                                <td>{{ $d->cantidad }}</td>
                                <td class="text-uppercase"><small>{{ $d->comandawtcaja->cajas->caja }}</small></td>
                                <td class="text-uppercase">
                                    [S:{{ $d->user_solicita->user }}]<br>
                                    [R:{{ $d->user_acepta->user ?? '-' }}]<br>
                                    [P:{{ $d->user_asignado->user ?? '-' }}]
                                </td>
                                <td><small>{{ $d->solicitud }}</small></td>
                                <td title="{{ $d->aceptacion }}">
                                    {{ $d->aceptaciontime }}
                                    @isset($d->aceptacion)
                                        <small>[{{ $d->aceptacion }}]</small>
                                    @endisset
                                </td>
                                <td>{{ $d->esperatime }}</td>
                                <td>{{ $d->incrementotime }}</td>
                                <td>
                                    @isset($d->aceptacion)
                                        {{ \Carbon::parse($d->entregado)->diffForHumans(\Carbon::parse($d->aceptacion)) }}
                                    @endisset
                                </td>
                                <td>
                                    {{ $d->status }}
                                </td>
                            </tr>
                        @empty
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                S: Usuario solicitante, R: Usuario responde/asigna solicitud, P: Usuario encargado de producir
            </div>
        </div>
    </div>
@endsection
