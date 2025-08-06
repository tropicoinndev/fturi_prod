@extends('layouts.hab')

@section('content-hab')
    <style>
        body {
            background: #FFCCBC;
        }
    </style>
    <div id="appReporteHab" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('recepciones.salidasAcciones') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE SALIDAS
                            <div style="width: 160px;" class="text-end float-end">
                                <p class="mb-0 mt-3 text-success"><small>Facturadas:
                                        {{ $data->where('facturada', true)->count() }}</small></p>
                                <p class="mb-0 text-danger"><small>Sin Facturar:
                                        {{ $data->where('facturada', false)->count() }}</small></p>
                                <hr class="mt-0 mb-0">
                                <p class="mb-0 text-muted"><small>Total: {{ $data->count() }}</small></p>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Sucursal
                                </label>
                                <select name="sucursal" class="form-control">
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
                                <button class="btn btn-light" type="submit" role="button" value="1" name="accion">
                                    <span class="mdi mdi-magnify h5"></span>
                                    Vista previa
                                </button>
                                <button class="btn btn-light" type="submit" role="button" value="2" name="accion">
                                    <span class="mdi mdi-file-pdf-box h5"></span>
                                    PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (isset($data))
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-light table-hover table-lg table-responsive-lg">
                                <thead>
                                    <tr>
                                        <th scope="col">Nº Registro</th>
                                        <th scope="col">FECHA INGRESO</th>
                                        <th scope="col">FECHA SALIDA</th>
                                        <th scope="col">HABITACIÓN</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">DIAS</th>
                                        <th scope="col">TARIFA</th>
                                        <th scope="col">TOTAL</th>
                                        <th scope="col">Estado</th>
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
                                                <span class="text-truncate">
                                                    {{ $r->clientes_id > 0 ? $r->clientes->nombre : $r->titular }}
                                                </span>
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
                                                <span class="badge h4 {{ $r->facturada ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $r->facturada ? 'Facturada' : 'Sin facturar' }}
                                                </span>
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
