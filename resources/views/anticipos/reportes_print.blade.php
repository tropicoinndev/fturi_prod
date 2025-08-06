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
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de anticipos disponibles
    </div>
@endsection
@section('content')
    @if (isset($anticipos) && count($anticipos) > 0)
        <div class="row">
            @foreach ($anticipos as $anticipo)
                <div class="col-12 mb-4">
                    <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Caja/Turno</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Aplicación</th>
                                <th scope="col">Monto</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Separacion</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Creado</th>

                            </tr>
                        </thead>
                        <tbody>
                            <tr class="{{ $anticipo->anulado ? 'row-anulado' : '' }}">
                                <td>{{ $anticipo->id }}</td>
                                <td>{{ $anticipo->turnos->cajasSucursales->caja }}/{{ $anticipo->turnos->opcion->turno }}
                                </td>
                                <td>{{ $anticipo->fecha }}</td>
                                <td>{{ $anticipo->fecha_aplicacion }}</td>
                                <td>${{ number_format($anticipo->monto_historico, 2, ',', '.') }}</td>
                                <td>{{ $anticipo->clientes->nombre ?? 'N/A' }}</td>
                                <td>
                                    @if ($anticipo->estado)
                                        <span class="text-success">Anticipo sin aplicar</span>
                                    @else
                                        <span class="text-info">Anticipo aplicado</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($anticipo->separado)
                                        <span class="text-danger">Este anticipo fue separado</span>
                                    @else
                                        <span class="text-success">Anticipo normal</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-muted">{{ $anticipo->users->name }}</span>
                                </td>
                                <td>{{ $anticipo->creacion }}</td>
                            </tr>
                            <tr>
                                <td colspan="11">
                                    <strong>Concepto:</strong> {{ $anticipo->concepto }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @else
        <div class="row">
            <div class="col-12">
                <p class="text-center text-uppercase">No hay anticipos para el cliente seleccionado en la fecha
                    seleccionada</p>
            </div>
        </div>
    @endif
@endsection
