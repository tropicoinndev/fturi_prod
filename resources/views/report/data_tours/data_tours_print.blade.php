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
        Reporte de datatur
    </div>
@endsection
@section('content')
    @if (isset($data))
        <div class="row">
            <div class="col-6">
                Fechas: {{ $fecha }} - {{ $fecha_final }}
            </div>
            <div class="col-6">
                Sucursal: {{ $sucursalNombre }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-light able-striped table-hover table-bordered table-sm table-responsive-sm">
                    <thead>
                        <tr>
                            <th rowspan="2">Fecha</th>
                            <th rowspan="2">Disponibles</th>
                            <th colspan="2">Cuartos Ocupados (A)</th>
                            <th colspan="2">Llegada de Turistas (B)</th>
                            <th colspan="2">No. de Turistas Noche (C)</th>
                            <th rowspan="2">Total Habitaciones Ocupadas</th>
                            <th rowspan="2">% de Ocupación</th>
                        </tr>
                        <tr>
                            <th>Nacionales</th>
                            <th>Extranjeros</th>
                            <th>Nacionales</th>
                            <th>Extranjeros</th>
                            <th>Nacionales</th>
                            <th>Extranjeros</th>
                        </tr>

                    </thead>
                    <tbody>
                        @php
                            $fechaInicio = Carbon::parse($fecha);
                            $fechaFin = Carbon::parse($fecha_final);
                        @endphp
                        @for ($fechaDia = $fechaInicio->copy(); $fechaDia->lte($fechaFin); $fechaDia->addDay())
                            @php
                                $fechaDiaFormateada = $fechaDia->format('Y-m-d');
                                $sumaNacional = $data
                                    ->where('fecha_ingreso', '<=', $fechaDiaFormateada)
                                    ->where('fecha_salida', '>=', $fechaDiaFormateada)
                                    ->sum('nacional');

                                $sumaExtranjero = $data
                                    ->where('fecha_ingreso', '<=', $fechaDiaFormateada)
                                    ->where('fecha_salida', '>=', $fechaDiaFormateada)
                                    ->sum('extranjero');

                                $totalOcupacion = $sumaNacional + $sumaExtranjero;

                                $sumaLlegadaHuespedesNacional = $data
                                    ->where('fecha_ingreso', $fechaDiaFormateada)
                                    ->sum('huespedes_nacional');

                                $sumaLlegadaHuespedesExtranjero = $data
                                    ->where('fecha_ingreso', $fechaDiaFormateada)
                                    ->sum('huespedes_extranjero');

                                $sumaHuespedesNacional = $data
                                    ->where('fecha_ingreso', '<=', $fechaDiaFormateada)
                                    ->where('fecha_salida', '>=', $fechaDiaFormateada)
                                    ->sum('huespedes_nacional');

                                $sumaHuespedesExtranjero = $data
                                    ->where('fecha_ingreso', '<=', $fechaDiaFormateada)
                                    ->where('fecha_salida', '>=', $fechaDiaFormateada)
                                    ->sum('huespedes_extranjero');

                                $disponiblesDia = $disponibles > $totalOcupacion ? $disponibles : $totalOcupacion;
                            @endphp


                            <tr>

                                <td>
                                    {{ $fechaDia->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ $disponiblesDia }}
                                </td>
                                <td>
                                    {{ $sumaNacional }}
                                </td>
                                <td>
                                    {{ $sumaExtranjero }}
                                </td>
                                <td>
                                    {{ $sumaLlegadaHuespedesNacional }}
                                </td>
                                <td>
                                    {{ $sumaLlegadaHuespedesExtranjero }}
                                </td>
                                <td>
                                    {{ $sumaHuespedesNacional }}
                                </td>
                                <td>
                                    {{ $sumaHuespedesExtranjero }}
                                </td>
                                <td>
                                    {{ $totalOcupacion }}
                                </td>
                                <td>
                                    {{ round(($totalOcupacion / $disponiblesDia) * 100, 2) }}%
                                </td>


                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@endsection
