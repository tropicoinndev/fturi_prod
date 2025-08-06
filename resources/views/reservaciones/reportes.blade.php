@extends('layouts.hab')

@section('content-hab')
    <style>
        .column,
        .dia {
            position: relative;
            width: 14.28%;
        }

        .dia {

            max-height: 100px;
            height: 100px;
            cursor: pointer;
        }

        .dia-content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .ocupado {
            background: #455A64;
            color: #fafafa;
        }

        .dia .tooltip {
            visibility: hidden;
            width: 180px;
            background-color: #2979FF;
            color: #fff;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            position: absolute;
            z-index: 1;
            bottom: 110%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .ocupado:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .ocupado:hover {
            background: #009688;
            color: #fafafa;
        }

        .flecha {
            position: absolute;
            width: 0px;
            height: 0px;
            margin-left: 20px;
            border-top: 15px solid #2979FF;
            border-right: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 15px solid transparent;
        }

        .head {
            background: #CFD8DC;
            padding: 10px;
        }

        .panelCalendar {
            min-height: 85vh;
        }

        body {
            background: #E1F5FE;
        }
    </style>
    <div id="appReporteHab" class="container">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('reservaciones.reporte_venta_opcion') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE RESERVACIONES</div>

                        <div class="col-3">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Habitación
                                </label>
                                <select class="form-select" name="habitacion">
                                    <option selected value="0">Todas las habitaciones</option>
                                    @foreach ($hab as $h)
                                        <option value="{{ $h->id }}"
                                            {{ isset($habitacion) && $habitacion == $h->id ? 'selected' : '' }}>
                                            Habitación {{ $h->numero_habitacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Inicio
                                </label>
                                <input type="date" name="inicio" class="form-control" value="{{ $inicio ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Finalización
                                </label>
                                <input type="date" name="fin" class="form-control" value="{{ $fin ?? '' }}" />
                            </div>
                        </div>
                        <div class="col-3 row align-items-center">
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
                                    <span class="mdi mdi-file-excel h5"></span>
                                    XLS
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
                                    @foreach ($vendedor as $v)
                                        @php
                                            $rVendedor = $reservaciones->where('users_id', $v->id);
                                            $reservasVendedor = $reservas->whereIn('reservaciones_id', $rVendedor->pluck('id'));
                                            $totalIngreso = 0;
                                            $totalSinIngreso = 0;
                                        @endphp
                                        <tr>
                                            <th colspan="9" class="text-uppercase">{{ $v->name }}</th>
                                        </tr>

                                        @foreach ($reservasVendedor as $r)
                                            @php
                                                $total = round($r->relacionTarifas->precio * $r->dias, 2);
                                                if ($r->ingreso) {
                                                    $totalIngreso += $total;
                                                } else {
                                                    $totalSinIngreso += $total;
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
                                        <tr>
                                            <td colspan="7" class="text-uppercase">{{ $v->name }}: Total sin registro de ingreso</td>
                                            <td class="text-end">${{ number_format($totalSinIngreso, 2) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="7" class="text-uppercase">{{ $v->name }}: Total con registro de ingreso</td>
                                            <td class="text-end"><b>${{ number_format($totalIngreso, 2) }}</b></td>
                                            <td></td>
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
