@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #FFAB91 !important;
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

        .titular {
            width: 14cm;
        }
    </style>
@endsection
@section('panel_reportes')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Vista previa de reporte de ordenes de servicio
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


                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('ordenes.reporte') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12 table-responsive">
                    <table class="table table-borderless table-white">
                        <thead>
                            <tr>
                                <th scope="col">Caja</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Orden</th>
                                <th scope="col">cliente</th>
                                <th scope="col">Estado</th>
                                <th scope="col" class="text-end">Total</th>
                                <th scope="col" class="text-end">Ver detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp


                            @foreach ($data as $d)
                                @php

                                    $total += $d->sum_orden;
                                @endphp
                                <tr class="{{ $detalle ? 'bt' : '' }}">
                                    <td>{{ $d->cajas->caja }}</td>
                                    <td>{{ $d->fecha }}</td>
                                    <td>{{ $d->orden }}</td>
                                    <td class="text-uppercase titular">
                                        {{ $d->clientes?->nombre ?? $d->titular }}
                                    </td>
                                    <td>
                                        @if ($d->facturada)
                                            Facturada
                                        @elseif ($d->estado)
                                            Activa
                                        @elseif ($d->anulada)
                                            Anulada
                                        @elseif (!$d->estado)
                                            Sin facturar
                                        @endif
                                    </td>
                                    <td class="text-end">${{ number_format($d->sum_orden, 2) }}</td>
                                    <td class="text-end">
                                        <a class="btn btn-light" href="{{ route('ordenes.container', ['id' => $d->id]) }}"
                                            role="button" target="_blank">Ver orden</a>
                                    </td>
                                </tr>
                                @if ($detalle)
                                    <tr>
                                        <td></td>
                                        <td scope="col">Empleado</td>
                                        <td scope="col">Cantidad</td>
                                        <td scope="col" colspan="2">Servicio</td>
                                        <td scope="col" class="text-end">Unitario</td>
                                        <td></td>
                                    </tr>
                                    @forelse ($d->detalle_orden as $o)
                                        <tr>
                                            <td></td>
                                            <td>{{ $o->user_detalle?->user }}</td>
                                            <td>{{ $o->cantidad }}</td>
                                            <td colspan="2">{{ $o->servicios->servicio }}</td>
                                            <td class="text-end">${{ number_format($o->precio_unitario, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                Sin servicios agregados
                                            </td>
                                        </tr>
                                    @endforelse
                                @endif
                            @endforeach
                            <tr>
                                <td class="text-uppercase fw-bold bt-2" colspan="5">
                                    TOTAL:
                                </td>
                                <td class="text-end fw-bold bt-2">${{ number_format($total, 2) }}</td>
                                <td class="bt-2"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
