@extends('layouts.excel')

@section('content')
    <table>
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
@endsection
