@extends('layouts.pdf.dte')

@section('dte_tipo')
    SUJETO EXCLUIDO
@endsection

@section('version')
    {{ $json->identificacion->version }}
@endsection

@section('style')
    <style>
        table thead {
            background-color: #00b47e;/*Color para cada comprobante*/
            color: #ffffff;
        }
    </style>
@endsection

@section('dte-header')
    @inject('utils', 'App\Utils')

    <div class="row ml-02 mr-n06">
        @component('mail.emisor', ['json' => $json])
        @endcomponent

        @if($json->sujetoExcluido != null)
            <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
                <p><b>NOMBRE:</b> {{ $json->sujetoExcluido->nombre }}</p>
                <p class="mt-n015"><b>IDENTIFICACIÓN:</b> {{ $json->sujetoExcluido->numDocumento ?? '---' }}</p>
                <p class="mt-n015"><b>ACTIVIDAD:</b> {{ $json->sujetoExcluido->descActividad ?? '---' }}</p>
                <p class="mt-n015"><b>DIRECCIÓN:</b> {{ $json->sujetoExcluido->direccion != null ? $json->sujetoExcluido->direccion->complemento : '---' }}</p>
                <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b> {{ $json->sujetoExcluido->correo ?? '---' }}</p>
            </div>
        @else
            <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
                <p><b>NOMBRE:</b> CLIENTE SIN REGISTRO</p>
                <p class="mt-n015"><b>NIT:</b></p>
                <p class="mt-n015"><b>ACTIVIDAD:</b></p>
                <p class="mt-n015"><b>DIRECCIÓN:</b></p>
                <p class="mt-n015"><b>TELÉFONO:</b></p>
                <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b></p>
            </div>
        @endif
    </div>
@endsection

@section('dte-main')
    <table class="mb-15px">
        <thead>
            <tr>
                <th scope="col" class="borde-b-show w-05">#</th>
                <th scope="col" class="borde-b-show w-05">CANT.</th>
                <th scope="col" class="borde-b-show w-1">MEDIDA</th>
                <th scope="col" class="borde-b-show">DESCRIPCIÓN</th>
                <th scope="col" style="width: 2.29cm;" class="borde-b-show">PRECIO UNITARIO</th>
                <th scope="col" class="borde-b-show w-2">COMPRA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($json->cuerpoDocumento as $i)
                <tr>
                    <td class="text-center bbl-radius" scope="row">{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $i->cantidad }}</td>
                    <td class="text-center">
                        @php
                           $descripcion = $utils->unidades($i->uniMedida);
                        @endphp
                        {{ $descripcion }}
                    </td>
                    <td>{{ $i->descripcion }}</td>
                    <td class="text-right">${{ number_format($i->precioUni, 4) }}</td>
                    <td class="text-right">${{ number_format($i->compra, 4) }}</td>
                </tr>
            @endforeach
            {{--Campos de factura--}}
            <tr>
                <td colspan="4" rowspan="2" class="borde-l-none borde-r-none borde-t-show">
                    <p>
                        <b>VALOR EN LETRAS:</b>
                        <small>
                            {{ $json->resumen->totalLetras }}
                        </small>
                    </p>
                    <p>
                        <b>CONDICIÓN DE LA OPERACIÓN:</b>
                        {{ $json->resumen->condicionOperacion == 1 ? 'Contado' : ($json->resumen->condicionOperacion == 2 ? 'Crédito' : ($json->resumen->condicionOperacion == 3 ? 'Otros' : 'No clasificado')) }}
                    </p>
                </td>
                <th class="text-right estilo-campos-factura borde-t-show">TOTAL COMPRA</th>
                <th class="text-right estilo-campos-factura borde-t-show">${{ number_format($json->resumen->totalCompra, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura">(i) {{ env('renta', 0.1) * 100 }}% RENTA</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->reteRenta, 2) }}</th>
            </tr>
            <tr>
                <td colspan="4" class="borde-l-none borde-r-none">
                    <p>CONSULTA DE COMPROBANTE ELECTRÓNICO</p>
                </td>
                <th class="text-right estilo-campos-factura fs-10pt bbl-radius">TOTAL</th>
                <th style="background: #f2f2f2; padding: 2.98px;" class="text-right fs-10pt bbr-radius">${{ number_format($json->resumen->totalPagar, 2) }}</th>
            </tr>
            <tr>
                <td colspan="8" class="borde-l-none borde-r-none">
                    <img src="{{ $qr }}" alt="Codigo QR" class="imgQR">
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col-12">
            <p class="mb-15px"><b>RECIBE:</b>                 {{ $json->sujetoExcluido->nombre }}</p>
            <p><b>RECIBE FIRMA:</b>     _________________________________________________</p>
            <br><br>
            <p class="mb-15px"><b>ENTREGA:</b>             _________________________________________________</p>
            <p><b>ENTREGA FIRMA:</b> _________________________________________________</p>
        </div>
    </div>
@endsection
