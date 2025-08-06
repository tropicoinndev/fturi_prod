@extends('layouts.hab')

@section('content-hab')
    <div id="appReporteDataTours" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('recepciones.reporte_tours_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE DATA TOURS
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="sucursal" class="form-label">Sucursal</label>
                            <select name="sucursal" class="form-control" value="{{ $sucursal ?? '' }}">
                                <option value="" selected>Todas las sucursales</option>
                                @foreach ($sucursales as $s)
                                    <option value="{{ $s->id }}"
                                        {{ $sucursal && $sucursal == $s->id ? 'selected' : '' }}>
                                        {{ $s->sucursal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="fecha" class="form-label">Del</label>
                            <input type="date" name="fecha" class="form-control" value="{{ $fecha ?? '' }}" />
                        </div>


                        <div class="col-md-3 mb-3">
                            <label for="fecha_final" class="form-label">Al</label>
                            <input type="date" name="fecha_final" class="form-control"
                                value="{{ $fecha_final ?? '' }}" />
                        </div>


                        <div class="col-md-auto d-flex align-items-end mb-3">
                            <div>
                                <button class="btn btn-light me-2" type="submit" role="button" value="1"
                                    name="opcion">
                                    <span class="mdi mdi-magnify h5"></span> Buscar
                                </button>
                                <button class="btn btn-light me-2" type="submit" role="button" value="2"
                                    name="opcion">
                                    <span class="mdi mdi-file-pdf-box h5"></span> PDF
                                </button>
                                <button class="btn btn-light" type="submit" role="button" value="3" name="opcion">
                                    <span class="mdi mdi-file-excel-outline h5"></span> Excel
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

                @if (isset($data))
                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
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
                                            $disponiblesDia =
                                                $disponibles > $totalOcupacion ? $disponibles : $totalOcupacion;
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
            </div>
        </div>
    </div>
@endsection
