@extends('layouts.hab')

@section('content-hab')
    <div id="appReporteHab" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('reservaciones.reporte_ingreso') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE INGRESO POR RESERVACIONES ({{ $reservaciones->count() }})
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Habitación
                                </label>
                                <select class="form-select" name="sucursal">
                                    <option selected value="0">Todas las sucursales</option>
                                    @foreach ($sucursales as $s)
                                        <option value="{{ $s->id }}"
                                            {{ $sucursal && $sucursal == $s->id ? 'selected' : '' }}>
                                            {{ $s->sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Fecha ingreso
                                </label>
                                <input type="date" name="fecha" class="form-control" value="{{ $fecha ?? '' }}" />
                            </div>
                        </div>

                        <div class="col-4 row align-items-center">
                            <div class="col ">
                                <button class="btn btn-light" type="submit" role="button" value="1" name="opcion">
                                    <span class="mdi mdi-magnify h5"></span>
                                    Buscar
                                </button>
                                <button class="btn btn-light" type="submit" role="button" value="2" name="opcion">
                                    <span class="mdi mdi-file-pdf-box h5"></span>
                                    PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (isset($reservaciones))
                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">FECHA INGRESO</th>
                                        <th scope="col">FECHA SALIDA</th>
                                        <th scope="col">HABITACIÓN</th>
                                        <th class="text-center" scope="col">DETALLE</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">DIAS</th>
                                        <th scope="col">TARIFA</th>
                                        <th scope="col">TOTAL</th>
                                        <th scope="col">Detalle</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservaciones as $r)
                                        @php
                                            $total = round($r->relacionTarifas->precio * $r->dias, 2);
                                            if ($r->ingreso) {
                                                $totalIngreso = $total;
                                            } else {
                                                $totalSinIngreso = $total;
                                            }
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $r->fecha_ingreso }}
                                            </td>
                                            <td>
                                                {{ $r->fecha_salida }}
                                            </td>
                                            <td>
                                                {{ $r->relacionHabitaciones->numero_habitacion }}
                                            </td>
                                            <td>
                                                {{ $r->descripcion }}
                                            </td>
                                            <td>
                                                {{ $r->relacionReservaciones->clientes_id > 0 ? $r->relacionReservaciones->relacionClientes->nombre : $r->relacionReservaciones->titular }}
                                            </td>
                                            <td class="text-end">
                                                {{ $r->dias }}
                                            </td>
                                            <td class="text-end">
                                                ${{ number_format($r->relacionTarifas->precio, 2) }}
                                            </td>
                                            <td class="text-end">
                                                ${{ number_format($r->relacionTarifas->precio * $r->dias, 2) }}
                                            </td>
                                            <td>
                                                {{ $r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
