@extends('layouts.detalle_ordenes_print')

@section('numero-orden', $orden->orden)

@section('fecha-orden', $orden->fecha)

@section('cliente-orden')
    <div class="row text-center mt-2">
        <div class="col-12">
            <p><b class="fs-10pt">CLIENTE</b></p>
        </div>
    </div>

    <div class="row ml-02" style="margin-right: 24px;">
        <div style="height: 1.2cm;" class="col-12 estilo-emisor-receptor">
            <p><b>NOMBRE:</b> {{ $orden->clientes->nombre ?? '---' }}</p>
            <p class="mt-n015"><b>{{ $orden->clientes->identificacion->identificaciones->identificacion ?? '---' }}:</b> {{ $orden->clientes->identificacion->numero ?? '---' }}</p>
        </div>
    </div>
@endsection

@section('style')
    <style>
        table thead {
            background-color: #F57F17;
            color: #ffffff;
        }
    </style>
@endsection

@section('main-orden')
    <table>
        <thead>
            <tr>
                <th scope="col" class="borde-b-show w-05">#</th>
                <th scope="col" class="borde-b-show w-05">CANT.</th>
                <th scope="col" class="borde-b-show" style="width: 200px;">DESCRIPCIÓN</th>
                <th scope="col" class="borde-b-show" style="width: 70px;">PRECIO UNITARIO</th>
                <th scope="col" class="borde-b-show" style="width: 70px;">PRECIO TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @php
                #Declaración e inicialización de variables
                $subTotal = $totalIva = $totalCesc = $totalAdval = $propina = $total = 0;
            @endphp

            @foreach($orden->detalle_orden as $do)
                @php
                    #Acumuladores
                    $subTotal   += round($do->neto * $do->cantidad, 4);
                    $totalIva   += round($do->iva * $do->cantidad, 4);
                    $totalCesc  += round($do->cesc * $do->cantidad, 4);
                    $totalAdval += round($do->advalorem * $do->cantidad, 4);
                    $propina    += round($do->propina * $do->cantidad, 4);
                @endphp

                <tr>
                    <td class="text-center bbl-radius" scope="row">{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $do->cantidad }}</td>
                    <td>{{ $do->servicios->servicio }}</td>
                    <td class="text-right">${{ number_format(round($do->neto, 4), 2) }}</td>
                    <td class="text-right">${{ number_format(round($do->neto * $do->cantidad, 4), 2) }}</td>
                </tr>
            @endforeach
            {{--Campos de factura--}}
            <tr>
                <td colspan="3" class="borde-l-none borde-r-none borde-t-show">
                    <p><b>USUARIO:</b> {{ $detalleOrdenes[0]->user_detalle->name ?? '---' }}</p>
                    <p><b>OBSERVACIONES:</b> {{ $orden->descripcion }}</p>
                </td>
                <th class="text-right estilo-campos-factura borde-t-show">SUB-TOTAL</th>
                <th class="text-right estilo-campos-factura borde-t-show">${{ number_format($subTotal, 2) }}</th>
            </tr>
            <tr>
                <th colspan="3" rowspan="5" class="borde-l-none borde-r-none"></th>
                <th class="text-right estilo-campos-factura">IVA</th>
                <th class="text-right estilo-campos-factura">${{ number_format($totalIva, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura">CESC</th>
                <th class="text-right estilo-campos-factura">${{ number_format($totalCesc, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura">AD-VALOREM (+)</th>
                <th class="text-right estilo-campos-factura">${{ number_format($totalAdval, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura">PROPINA (+)</th>
                <th class="text-right estilo-campos-factura">${{ number_format($propina, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura fs-10pt borde-t-show bbl-radius">TOTAL</th>

                @php
                    #El total es la sumatoria del sub-total más todos los impuestos
                    $total = round($subTotal + $totalIva + $totalCesc + $totalAdval + $propina, 4)
                @endphp
                <th style="background: #f2f2f2; padding: 2.98px;" class="text-right fs-10pt borde-t-show bbr-radius">${{ number_format($total, 2) }}</th>
            </tr>
        </tbody>
    </table>
@endsection
