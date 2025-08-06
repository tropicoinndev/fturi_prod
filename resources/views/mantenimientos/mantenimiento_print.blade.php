@extends('layouts.print')

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
            width: 6cm;

        }
        .w-10 {
            width: 1.5cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: {
                    {
                    21.94 / 7
                }
            }cm;
            overflow: hidden;
            text-align:justify;
        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 900;

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
            background:rgb(209,209,209);
            color: #000000;
            min-width: 1cm;
        }

        .space {
            height: 20px;
        }

         td {
            min-height:45px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
         .table th,
         .table td {
                padding: 0.1rem;
                vertical-align: top;
                border-top: 1px solid #dee2e6;

            }
        .table thead th {
            vertical-aling: bottom;
            border-bottom: 2px solid #dee2e6;
        }
        .table tbody+tbody {
            border-top: 2px solid #000;
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

        .observacion{
            max-width: 200px;
             max-height: 50px;
            white-space: nowrap;
            overflow: hidden;

             white-space: normal;
        }

    </style>
@endsection

@section('titulo')
    <div class="titulo">
        REPORTE DE MANTENIMIENTOS
    </div>
@endsection

@section('content')
    <div>

        <div class="row">
            <div class="col-12">
                <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <th class="tb-title"><b>#</b></th>
                            <th class="tb-title"><b>Mantenimiento</b></th>
                            <th class="tb-title"><b>Habitación</b></th>
                            <th class="tb-title"><b>Solicitó</b></th>
                            <th class="tb-title"><b>Solicitud</b></th>
                            <th class="tb-title"><b>Asignado</b></th>
                            <th class="tb-title"><b>Bitácora Mant.</b></th>
                            <th class="tb-title"><b>Supervisado</b></th>
                            <th class="tb-title"><b>Estado</b></th>
                            <th class="tb-title"><b>Iniciado</b></th>
                            <th class="tb-title"><b>Finalizado</b></th>
                            <th class="tb-title"><b>Tiempo</b></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mantenimientos as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $t->tipo_mantenimientos->mantenimiento }}</td>
                                <td>{{ $t->habitaciones->numero_habitacion }}</td>
                                <td >{{ $t->creador ? $t->creador->name : 'N/A' }}</td>
                                <td class="observacion">{{ $t->observacion}}</td>
                                <td >{{ $t->asignado ? $t->asignado->name : 'No se asignado' }}</td>
                                <td class="observacion">{{ $t->bitacora_asignado }}</td>
                                <td >{{ $t->supervisor ? $t->supervisor->name : 'No supervisado' }}</td>
                                <td>{{ $t->estado }}</td>
                                <td>{{ $t->inicio }}</td>
                                <td>{{ $t->finalizacion }}</td>

                                <td>{{ $t->transcurrido }}</td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            </div>
        </div>
    </div>
@endsection
