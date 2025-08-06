@extends('layouts.print_b')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            /*width: 200px;*/
            width: 6cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: 2cm;
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
        }

        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }

        .bg-total {
            background: #d8ffde;
            color: #1c1c1c;
            font-weight: 600;
        }

        tr td {
            padding: 2px 0px;
        }

        .row {
            width: 25cm;
            display: inline-block;
        }

        .col-6 {
            width: 12.5cm;
            float: left;
            margin-bottom: 0.25cm;
        }

        .text-end {
            text-align: right;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de estadía
    </div>
@endsection
@section('content')
    @if (isset($data))
        <div class="row">
            <div class="col-6">
                Fecha: {{ $fecha }}
            </div>
            <div class="col-6">
                Sucursal: {{ $sucursalNombre }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th>FECHA INGRESO</th>
                            <th>FECHA SALIDA</th>
                            <th>HABITACIÓN</th>
                            <th>Nº HUESPEDES</th>
                            <th>ESTADO</th>
                            <th>CLIENTE</th>
                            <th>DIAS</th>
                            <th>TARIFA</th>
                            <th>USUARIO</th>
                        </tr>
                    </thead>
                    <tbody>


                        @foreach ($data as $r)
                            <tr>
                                <td>
                                    {{ $r->fecha_ingreso }}
                                </td>
                                <td>
                                    {{ $r->fecha_salida }}
                                </td>
                                <td>
                                    {{ $r->habitaciones->numero_habitacion }}
                                </td>
                                <td>
                                    {{ count($r->huespedes) }}
                                </td>
                                <td>
                                    {{ $r->habitaciones->relacionEstadoHabitaciones->estado_habitacion }}
                                </td>
                                <td>
                                    {{ $r->clientes_id > 0 ? $r->clientes->nombre : $r->titular }}
                                </td>
                                <td class="text-end">
                                    {{ $r->dias }}
                                </td>
                                <td class="text-end">
                                    ${{ number_format($r->tarifas->precio, 2) }}
                                </td>
                                <td>
                                    {{ $r->usuarios->user }}:


                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
