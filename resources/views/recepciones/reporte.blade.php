@extends('layouts.hab')

@section('content-hab')
    <div id="appReporteHab" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('recepciones.reporte_estadia_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE ESTADÍA
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Sucursal
                                </label>
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
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Fecha
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
                                <button class="btn btn-light" type="submit" role="button" value="3" name="opcion">
                                    <span class="mdi mdi-file-excel-box h5"></span>
                                    Excel
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
                                        <th scope="col">Nº Registro</th>
                                        <th scope="col">FECHA INGRESO</th>
                                        <th scope="col">FECHA SALIDA</th>
                                        <th scope="col">HABITACIÓN</th>
                                        <th scope="col">Nº HUESPEDES</th>
                                        <th scope="col">ESTADO</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">DIAS</th>
                                        <th scope="col">TARIFA</th>
                                        <th scope="col">TOTAL</th>
                                        <th scope="col">USUARIO</th>
                                        <th scope="col">Facturada</th>
                                        <th scope="col">Anulada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $r)
                                        <tr>
                                            <td>
                                                {{ $r->id }}
                                            </td>
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
                                            <td class="text-end">

                                                ${{ number_format($r->tarifas->monto * $r->dias, 2) }}
                                            </td>
                                            <td>
                                                {{ $r->usuarios->user }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $r->facturada ? 'bg-success' : 'bg-warning' }}">

                                                    {{ $r->facturada ? 'Facturada' : 'Sin facturar' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                    data-bs-placement="right" data-bs-custom-class="custom-popover"
                                                    data-bs-title="Observaciones" data-bs-content="{{ $r->descripcion }}">
                                                    {{ $r->eliminado ? 'Anulada' : 'Vigente' }}
                                                </button>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            Información:
                            <ul>
                                <li>Sin facturar: No se realizo factura por medio del registro de estadía. (Debe confirmarse
                                    si se realizo por medio de orden de servicio)</li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
