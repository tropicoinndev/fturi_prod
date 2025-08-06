@extends('layouts.pdf.dte')

@section('dte_tipo')
    COMPROBANTE DE CRÉDITO FISCAL
@endsection

@section('version')
    {{ $json->identificacion->version }}
@endsection

@section('style')
    <style>
        /*body {
                                                                                                                                                                                                                                                                            margin-top: 13.3cm !important;
                                                                                                                                                                                                                                                                        }*/

        table thead {
            background-color: #f2f2f2;
            /*Color para cada comprobante*/
            color: #546E7A;
        }
    </style>
@endsection

@section('dte-header')
    <div class="row ml-02 mr-n06">
        @component('mail.emisor', ['json' => $json])
        @endcomponent

        <div style="height: 4.29cm;" class="col-5 estilo-emisor-receptor">
            <p><b>NOMBRE:</b> {{ $json->receptor->nombre }}</p>
            <p class="mt-n015"><b>NIT:</b> {{ $json->receptor->nit ?? '---' }}</p>
            <p class="mt-n015"><b>NRC:</b> {{ $json->receptor->nrc ?? '---' }}</p>
            <p class="mt-n015"><b>ACTIVIDAD:</b> {{ $json->receptor->descActividad ?? '---' }}</p>
            <p class="mt-n015"><b>DIRECCIÓN:</b>
                {{ $json->receptor->direccion != null ? $json->receptor->direccion->complemento : '---' }}</p>
            <p class="mt-n015 lowerCaseEmail"><b>CORREO:</b> {{ $json->receptor->correo ?? '---' }}</p>
        </div>
    </div>

    {{-- @component('mail.relacionado', ['json' => $json])
    @endcomponent --}}
@endsection

@section('dte-main')
    <table>
        <thead>
            <tr>
                <th scope="col" class="borde-b-show w-05">#</th>
                <th scope="col" class="borde-b-show w-05">CANT.</th>
                <th scope="col" class="borde-b-show w-1">MEDIDA</th>
                <th scope="col" class="borde-b-show">DESCRIPCIÓN</th>
                <th scope="col" class="borde-b-show w-2">PRECIO UNITARIO</th>
                <th scope="col" class="borde-b-show w-2">VENTAS NO GRAVADAS</th>
                <th scope="col" class="borde-b-show" style="width: 3.5cm;">VENTAS EXENTAS</th>
                <th scope="col" class="borde-b-show w-3">VENTAS GRAVADAS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($json->cuerpoDocumento as $i)
                <tr>
                    <td class="text-center bbl-radius" scope="row">{{ $loop->index + 1 }}</td>
                    <td class="text-center">{{ $i->cantidad }}</td>
                    <td class="text-center">
                        @php
                            $tipoItems = [
                                59 => 'UNIDAD',
                                99 => 'OTROS',
                            ];
                            $descripcion = $tipoItems[$i->uniMedida] ?? 'OTROS';
                        @endphp
                        {{ $descripcion }}
                    </td>
                    <td>{{ $i->descripcion }}</td>
                    <td class="text-right">${{ number_format($i->precioUni, 4) }}</td>
                    <td class="text-right">${{ number_format($i->noGravado, 4) }}</td>
                    <td class="text-right">${{ number_format($i->ventaExenta, 4) }}</td>
                    <td class="text-right">${{ number_format($i->ventaGravada, 4) }}</td>
                </tr>
            @endforeach

            {{-- Campos de factura --}}
            <tr>
                <th colspan="4" class="borde-t-show"></th>
                <th class="text-right estilo-campos-factura borde-t-show bbl-radius">SUMAS</th>
                <th class="text-right estilo-campos-factura borde-t-show">
                    ${{ number_format($json->resumen->totalNoGravado, 2) }}</th>
                <th class="text-right estilo-campos-factura borde-t-show">
                    ${{ number_format($json->resumen->totalExenta, 2) }}</th>
                <th class="text-right estilo-campos-factura borde-t-show">
                    ${{ number_format($json->resumen->totalGravada, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" rowspan="2" class="borde-l-none borde-r-none">
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
                <th style="margin-bottom: 0 !important;" class="text-right estilo-campos-factura">(+)
                    {{ env('cesc') * 100 }}% TURISMO</th>
                <th style="margin-bottom: 0 !important;" class="text-right estilo-campos-factura">
                    ${{ number_format($dte->comprobante->cesc, 2) }}</th>
            </tr>
            <tr>
                <th class="text-right estilo-campos-factura">(+) {{ env('iva') * 100 }}% IVA</th>
                <th class="text-right estilo-campos-factura">${{ number_format($dte->comprobante->iva, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        <b>EXTENSIÓN</b>
                    @endif
                </td>
                <th class="text-right estilo-campos-factura">SUB-TOTAL</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->montoTotalOperacion, 2) }}
                </th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        <b>ENTREGA:</b><br>
                    @endif
                </td>
                <th class="text-right estilo-campos-factura">VENTAS EXENTAS</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->totalExenta, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        {{ $json->extension?->nombEntrega ?? '---' }} ·
                        {{ $json->extension?->docuEntrega ?? '---' }}
                    @endif
                </td>
                <th class="text-right estilo-campos-factura">VENTAS NO GRAVADAS</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->totalNoGravado, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        <b>RECIBE:</b>
                    @endif
                </td>
                <th class="text-right estilo-campos-factura">VENTAS NO SUJETAS</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->totalNoSuj, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        {{ $json->extension?->nombRecibe ?? '---' }} ·
                        {{ $json->extension?->docuRecibe ?? '---' }}
                    @endif
                </td>
                <th class="text-right estilo-campos-factura">(-) {{ env('percepcion') * 100 }}% IVA</th>
                <th class="text-right estilo-campos-factura">${{ number_format($json->resumen->ivaRete1, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    @if ($json?->extension != null)
                        <b>OBSERVACIONES: </b>
                        {{ $json->extension?->observaciones ?? '---' }}
                    @endif
                </td>
                <th class="text-right estilo-campos-factura fs-10pt">TOTAL</th>
                <th style="background: #f2f2f2; padding: 2.98px;" class="text-right fs-10pt">
                    ${{ number_format($json->resumen->totalPagar, 2) }}</th>
            </tr>
            <tr>
                <td colspan="6" class="borde-l-none borde-r-none">
                    <p>CONSULTA DE COMPROBANTE ELECTRÓNICO</p>
                </td>
                <th class="text-right estilo-campos-factura borde-t-show bbl-radius">(I) {{ env('advalorem') * 100 }}%
                    AD-VALOREM</th>
                <th style="background: #f2f2f2; padding: 2.98px;" class="text-right borde-t-show bbr-radius">
                    ${{ number_format($dte->comprobante->advalorem, 2) }}</th>
            </tr>


            <tr>
                <td colspan="8" class="borde-l-none borde-r-none">
                    <img src="{{ $qr }}" alt="Codigo QR" class="imgQR">
                </td>
            </tr>
            @if (isset($dte->complemento) && $dte->complemento != null)
                <tr>
                    <td colspan="8" class="borde-l-none borde-r-none">
                        <br>
                        <strong>Información complementaria solicitada por {{ $json->receptor->nombre }} (este apartado fue
                            agregado después de generar el comprobante)
                        </strong>
                        <p>
                            {{ $dte->complemento }}
                        </p>

                    </td>
                </tr>
            @endif
        </tbody>
    </table>
@endsection
