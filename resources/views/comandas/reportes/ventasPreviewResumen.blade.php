@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #7986CB;
        }

        .table tr,
        .table td,
        .table th {
            background: #FFF;
        }

        .bt {
            border-top: 1px solid #000 !important;
        }

        .bb {
            border-bottom: 1px solid #000 !important;
        }

        .bt-2 {
            border-top: 2.5px solid #000 !important;
        }
    </style>
@endsection
@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa ventas por comandas
                </div>
                <div class="col-12 my-2">
                    REPORTE RESUMIDO
                </div>

                <div class="col-12 my-1 text-uppercase">
                    @php
                        $cj = $cajas->pluck('caja');
                        $all = $cj->join(', ', ' y ');
                    @endphp
                    <strong>
                        Cajas:
                    </strong>
                    <br>
                    {{ $all }}
                </div>
                <div class="col-12 my-2 text-uppercase">
                    <strong>
                        Fecha:
                    </strong>
                    {{ $inicio->format('d-m-Y') }} al {{ $fin->format('d-m-Y') }}
                </div>

                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('comandas.reporteVentas') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Empleados</th>
                                <th scope="col" class="text-end">Productos</th>
                                <th scope="col" class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                                $cantidad = 0;
                            @endphp


                            @foreach ($usuarios as $u)
                                @php
                                    $utotal = 0;
                                    $ucantidad = 0;
                                    $utotal = $venta->where('users_comanda_id', $u->users_id)->sum('venta');
                                    $ucantidad = $venta->where('users_comanda_id', $u->users_id)->sum('cantidad');
                                    $total += $utotal;
                                    $cantidad += $ucantidad;
                                @endphp


                                @if ($utotal > 0)
                                    <tr>
                                        <td class="text-uppercase">
                                            {{ $u->users->name }}
                                        </td>
                                        <td class="text-end">{{ $ucantidad }}</td>
                                        <td class="text-end">${{ number_format($utotal, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td class="text-uppercase fw-bold bt-2">
                                    TOTAL:
                                </td>
                                <td class="text-end fw-bold bt-2">{{ $cantidad }}</td>
                                <td class="text-end fw-bold bt-2">${{ number_format($total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
