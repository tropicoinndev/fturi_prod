@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa ventas por comandas
                </div>

                <div class="col-12 my-2">
                    REPORTE DETALLADO
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
                                <th scope="col">Nº Cuenta</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Detalle</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Total</th>
                                <th scope="col">Factura</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php

                                $total = 0;
                                $cantidad = 0;
                                $col = 7;
                            @endphp

                            @foreach ($usuarios as $u)
                                @php
                                    $data = $venta->where('users_comanda_id', $u->users_id);
                                    $utotal = 0;
                                    $ucantidad = 0;
                                @endphp

                                @if ($data->count() > 0)
                                    <tr>
                                        <td colspan="{{ $col }}" class="text-uppercase">
                                            Empleado: {{ $u->users->name }}
                                        </td>
                                    </tr>
                                    @foreach ($data as $d)
                                        @php
                                            $utotal += $d->venta;
                                            $ucantidad += $d->cantidad;
                                        @endphp
                                        <tr>
                                            <td>{{ $d->id }} / {{ $d->comanda_detalles_id }}</td>
                                            <td>{{ $d->fecha }}</td>
                                            <td>{{ $d->detalle }}</td>
                                            <td>{{ $d->cantidad }}</td>
                                            <td>${{ number_format($d->precio, 2) }}</td>
                                            <td>${{ number_format($d->venta, 2) }}</td>
                                            <td>{{ $d->correlativo }}</td>
                                        </tr>
                                    @endforeach
                                    @php
                                        $total += $utotal;
                                        $cantidad += $ucantidad;
                                    @endphp
                                    <tr>
                                        <td class="text-uppercase" colspan="3">
                                            TOTAL COMANDADO POR: {{ $u->users->name }}
                                        </td>
                                        <td>{{ $ucantidad }}</td>
                                        <td></td>
                                        <td>${{ number_format($utotal, 2) }}</td>
                                        <td></td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr>
                                <td class="text-uppercase" colspan="3">
                                    TOTAL:
                                </td>
                                <td>{{ $cantidad }}</td>
                                <td></td>
                                <td>${{ number_format($total, 2) }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
