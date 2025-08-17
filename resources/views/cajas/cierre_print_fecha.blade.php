@extends('layouts.print_iframe')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            /*width: 200px;*/
            width: 10cm;
        }

        .fp-title {
            font-size: 8.5pt;
            width: {{ 21.94 / ($nforma + 8) }}cm;
            overflow: hidden;
            text-align: right;
        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 600;
            width: 100vh;
        }

        .b {
            font-weight: 500;
            color: #555;
        }

        .b1 {
            font-weight: 100;

        }

        .bt-1 {
            border-top: 1px #000 solid !important;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .space {
            height: 20px;
        }

        .bg-turno {
            background: #d1d1d1;
            color: #343434;
        }

        .bg-total {
            background: #d8ffde;
            color: #1c1c1c;
            font-weight: 600;
        }

        tr td {
            padding: 2px 0px;
        }
    </style>
@endsection

@section('content')
    @php
        $ttotales;
        $tturno;
        foreach ($forma_pagos as $f) {
            $tturno[$f->id] = 0;
        }
        $ttotales['total'] = 0;
        $ttotales['iva'] = 0;
        $ttotales['propina'] = 0;
        $ttotales['cesc'] = 0;
        $ttotales['advalorem'] = 0;
        $ttotales['percepcion'] = 0;
    @endphp

    <div class="pages">

        <div class="row">
            <h3 class="text-center">
                TURÍSTICAS DE ORIENTE S.A. DE C.V.
            </h3>
            <h5 class="text-center">
                Reporte de turnos
            </h5>
        </div>


        <div class="row">
            <div class="col-4">
                <span class="b">Sucursal:</span>
                {{ $caja->sucursales->sucursal }}
            </div>
            <div class="col-4">
                <span class="b"> Caja:</span>
                {{ $caja->caja }}
            </div>
            <div class="col-4">
                <span class="b">Fechas: </span>
                {{ $fecha_inicio }} - {{ $fecha_fin }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-light" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title fp-title fl">No.</td>
                            <td class="tb-title w-15">TITULAR</td>
                            @foreach ($forma_pagos as $forma)
                                <td class="tb-title fp-title">{{ $forma->forma }}</td>
                            @endforeach
                            <td class="tb-title fp-title">PROP.</td>
                            <td class="tb-title fp-title">CET</td>
                            <td class="tb-title fp-title">AdVal.</td>
                            <td class="tb-title fp-title">IVA</td>
                            <td class="tb-title fp-title">RETEN.</td>
                            <td class="tb-title fp-title">TOTAL</td>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($turnos as $turno)
                            @php
                                $totales;
                                $totalesformas;
                                $turno;
                                $anticipos_formas;
                                $anticipos_total = 0;
                                $abonos_formas;
                                $abonos_total = 0;
                                foreach ($tipo_comprobante as $t) {
                                    $totales[$t->id]['total'] = 0;
                                    $totales[$t->id]['iva'] = 0;
                                    $totales[$t->id]['propina'] = 0;
                                    $totales[$t->id]['cesc'] = 0;
                                    $totales[$t->id]['advalorem'] = 0;
                                    $totales[$t->id]['percepcion'] = 0;
                                    foreach ($forma_pagos as $f) {
                                        $totalesformas[$t->id][$f->id] = 0;
                                        $turno[$f->id] = 0;
                                        $anticipos_formas[$f->id] = 0;
                                        $abonos_formas[$f->id] = 0;
                                    }
                                }
                                $totales['total'] = 0;
                                $totales['iva'] = 0;
                                $totales['propina'] = 0;
                                $totales['cesc'] = 0;
                                $totales['advalorem'] = 0;
                                $totales['percepcion'] = 0;
                            @endphp

                            <tr>
                                <td colspan="{{ $nforma + 8 }}" class="title-table">**** {{ $turno->opcion->turno }} ·
                                    {{ $turno->fecha }} ****</td>
                            </tr>
                            @php
                                $anulacionesTurno = $anulaciones
                                    ->where('turnos_id', $turno->id)
                                    ->pluck('comprobantes_id');
                            @endphp
                            @foreach ($tipo_comprobante as $tipo)
                                @php

                                    $comprobantesTurnoAnulados = $comprobantes
                                        ->whereIn('id', $anulacionesTurno)
                                        ->where('tipo_comprobantes_id', $tipo->id);

                                    $comprobantesTurnoReg = $comprobantes
                                        ->where('tipo_comprobantes_id', $tipo->id)
                                        ->where('turnos_id', $turno->id);

                                    $comprobantesTipo = $comprobantesTurnoReg->merge($comprobantesTurnoAnulados);
                                @endphp
                                @if ($comprobantesTipo->count() > 0)
                                    <tr>
                                        <td colspan="{{ $nforma + 8 }}" class="title-table">{{ $tipo->tipo }}</td>
                                    </tr>

                                    @foreach ($comprobantesTipo as $comprobante)
                                        @php
                                            //Anulaciones
                                            $m = 1;
                                            if (!$comprobante->estado) {
                                                $anulacion = $comprobante->anulacion[0] ?? null;
                                                if (
                                                    $comprobante->turnos_id !== $anulacion->turnos_id &&
                                                    $comprobante->turnos_id === $turno->id
                                                ) {
                                                    $m = 1;
                                                } elseif (
                                                    $anulacion != null &&
                                                    isset($anulacion->response) &&
                                                    json_validate($anulacion->response)
                                                ) {
                                                    $responseAnulacion = json_decode($anulacion->response);

                                                    if (
                                                        $responseAnulacion?->estado == 'PROCESADO' &&
                                                        $responseAnulacion?->descripcionMsg ==
                                                            'Invalidación Recibida y Procesada'
                                                    ) {
                                                        if (
                                                            $anulacion->fecha == $comprobante->fecha &&
                                                            $comprobante->turnos_id === $anulacion->turnos_id
                                                        ) {
                                                            $m = 0;
                                                        } elseif ($comprobante->turnos_id !== $anulacion->turnos_id) {
                                                            $m = -1;
                                                        }
                                                    }
                                                } elseif (
                                                    $anulacion->fecha != $comprobante->fecha &&
                                                    $anulacion->aceptado
                                                ) {
                                                    $m = 0;
                                                    //Omitir CCF anulados con notas de créditos
                                                    if (
                                                        $comprobante?->tipoComprobantes?->token === 7001 &&
                                                        $comprobante->turnos_id !== $turno->id
                                                    ) {
                                                        break;
                                                    }
                                                }

                                                /* Refactorizado para el manejo de invalidaciones por turnos y NC
                                                if ($anulacion) {
                                                    if ($anulacion->fecha == $comprobante->fecha) {
                                                        $m = 0;
                                                    } elseif ($anulacion->fecha == $turno->fecha) {
                                                        $m = -1;
                                                    }
                                                }*/
                                            }

                                            $cliente = $m == 1 ? $comprobante->titular : 'ANULADO';
                                            $limite = 30;
                                            $cliente =
                                                strlen($cliente) > $limite
                                                    ? substr($cliente, 0, $limite) . '...'
                                                    : $cliente;
                                        @endphp
                                        <tr>
                                            <td>{{ $comprobante->correlativo }}</td>

                                            <td class="text-uppercase">
                                                <b>{{ $cliente }}</b>
                                                <br><small>{{ $comprobante->dteOne?->correlativo }}</small>
                                                <br> </small>{{ $comprobante->dteOne?->codigo_generacion }}</small>
                                            </td>
                                            @php
                                                $comprobantePagos = $comprobante->pagos;

                                                $pago = [];
                                                foreach ($forma_pagos as $f) {
                                                    if (!isset($pago[$f->id])) {
                                                        $pago[$f->id] = 0;
                                                    }
                                                    $pg = null;
                                                    $pg = $comprobantePagos->where('forma_pagos_id', $f->id)->first();
                                                    $mismoDia = false;

                                                    if ($pg != null) {
                                                        if ($pg->forma_pagos->token == 6004) {
                                                            $anticipo = $comprobante->cobro->anticipos;
                                                            foreach ($anticipo as $a) {
                                                                if (
                                                                    $comprobante->fecha == $a->anticipos->fecha &&
                                                                    $comprobante->turnos_id == $a->anticipos->turnos_id
                                                                ) {
                                                                    $mismoDia = true;
                                                                    if (isset($pago[$a->anticipos->forma_pagos_id])) {
                                                                        $pago[$a->anticipos->forma_pagos_id] +=
                                                                            $a->monto;
                                                                    } else {
                                                                        $pago[$a->anticipos->forma_pagos_id] =
                                                                            $a->monto;
                                                                    }
                                                                }
                                                            }
                                                            if (!$mismoDia) {
                                                                $pago[$f->id] = $pg->monto;
                                                            }
                                                        } else {
                                                            $pago[$f->id] += $pg->monto;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @foreach ($forma_pagos as $forma)
                                                @php
                                                    /*
                                                $monto =
                                                    $comprobantePagos->where('forma_pagos_id', $forma->id)->count() > 0
                                                        ? $comprobantePagos
                                                            ->where('forma_pagos_id', $forma->id)
                                                            ->first()->monto
                                                        : 0;
                                                        */
                                                    $monto = $pago[$forma->id] * $m;
                                                    $totalesformas[$tipo->id][$forma->id] += $monto;

                                                @endphp
                                                <td class="dollar">${{ number_format($monto, 2) }}</td>
                                            @endforeach

                                            <td class="dollar">${{ number_format($comprobante->propina * $m, 2) }}</td>
                                            <td class="dollar">${{ number_format($comprobante->cesc * $m, 2) }}</td>

                                            <td class="dollar">${{ number_format($comprobante->advalorem * $m, 2) }}</td>

                                            <td class="dollar">${{ number_format($comprobante->iva * $m, 2) }}</td>

                                            <td class="dollar">${{ number_format($comprobante->percepcion * $m, 2) }}</td>

                                            <td class="dollar">${{ number_format($comprobante->total * $m, 2) }}</td>

                                        </tr>
                                        @php
                                            $totales[$tipo->id]['propina'] += $comprobante->propina * $m;
                                            $totales[$tipo->id]['cesc'] += $comprobante->cesc * $m;
                                            $totales[$tipo->id]['advalorem'] += $comprobante->advalorem * $m;
                                            $totales[$tipo->id]['iva'] += $comprobante->iva * $m;
                                            $totales[$tipo->id]['percepcion'] += $comprobante->percepcion * $m;
                                            $totales[$tipo->id]['total'] += $comprobante->total * $m;
                                        @endphp
                                    @endforeach
                                    <tr class="fw-bolder">
                                        <td colspan="2" class="text-uppercase b1 bt-1">Totales en {{ $tipo->tipo }}
                                        </td>
                                        @foreach ($forma_pagos as $forma)
                                            <td class="dollar b1 bt-1">
                                                ${{ number_format($totalesformas[$tipo->id][$forma->id], 2) }}</td>
                                            @php
                                                $turno[$forma->id] += $totalesformas[$tipo->id][$forma->id];
                                            @endphp
                                        @endforeach
                                        <td class="dollar b1 bt-1">${{ number_format($totales[$tipo->id]['propina'], 2) }}
                                        </td>
                                        <td class="dollar b1 bt-1">${{ number_format($totales[$tipo->id]['cesc'], 2) }}
                                        </td>
                                        <td class="dollar b1 bt-1">
                                            ${{ number_format($totales[$tipo->id]['advalorem'], 2) }}
                                        </td>
                                        <td class="dollar b1 bt-1">${{ number_format($totales[$tipo->id]['iva'], 2) }}</td>
                                        <td class="dollar b1 bt-1">
                                            ${{ number_format($totales[$tipo->id]['percepcion'], 2) }}
                                        </td>
                                        <td class="dollar b1 bt-1">${{ number_format($totales[$tipo->id]['total'], 2) }}
                                        </td>


                                        @php
                                            $totales['total'] += $totales[$tipo->id]['total'];
                                            $totales['iva'] += $totales[$tipo->id]['iva'];
                                            $totales['propina'] += $totales[$tipo->id]['propina'];
                                            $totales['cesc'] += $totales[$tipo->id]['cesc'];
                                            $totales['advalorem'] += $totales[$tipo->id]['advalorem'];
                                            $totales['percepcion'] += $totales[$tipo->id]['percepcion'];
                                        @endphp
                                    </tr>
                                    <tr>
                                        <td colspan="{{ $nforma + 8 }}" class="fc space"></td>
                                    </tr>
                                @endif
                            @endforeach
                            @php
                                $anticiposTipo = null;
                                $anticiposTipo = $anticipos->where('turnos_id', $turno->id);
                            @endphp
                            @if ($anticiposTipo->count() > 0)
                                <tr>
                                    <td colspan="{{ $nforma + 8 }}" class="title-table">
                                        Anticipos
                                    </td>
                                </tr>

                                @if (isset($anticiposTipo) && count($anticiposTipo) > 0)
                                    @foreach ($anticiposTipo as $a)
                                        <tr>
                                            <td>{{ $a->id }}</td>

                                            <td class="text-uppercase">
                                                {{ !$a->anulado ? $a->clientes->nombre : 'ANULADO' }}
                                            </td>
                                            @foreach ($forma_pagos as $forma)
                                                @php
                                                    $monto = 0;
                                                    if (!$a->anulado) {
                                                        if ($forma->id == $a->forma_pagos_id) {
                                                            $monto = round($a->monto_historico - $a->sum_aplicado, 2);
                                                            $turno[$forma->id] += $monto;
                                                            $anticipos_total += $monto;
                                                            $anticipos_formas[$forma->id] += $monto;
                                                        }
                                                    }

                                                @endphp
                                                <td class="dollar">
                                                    ${{ number_format($monto, 2) }}
                                                </td>
                                            @endforeach
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format($monto, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr class="fw-bolder bt-1">
                                    <td colspan="2" class="text-uppercase">Totales en anticipos</td>
                                    @foreach ($forma_pagos as $forma)
                                        <td class="dollar">${{ number_format($anticipos_formas[$forma->id], 2) }}</td>
                                    @endforeach
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format($anticipos_total, 2) }}</td>
                                    @php
                                        $totales['total'] += $anticipos_total;
                                    @endphp
                                </tr>
                                <tr>
                                    <td colspan="{{ $nforma + 8 }}" class="fc space"></td>
                                </tr>
                            @endif

                            @php
                                $abonosTipo = null;
                                $abonosTipo = $abonos->where('turnos_id', $turno->id);
                            @endphp
                            @if ($abonosTipo->count() > 0)
                                <tr>
                                    <td colspan="{{ $nforma + 8 }}" class="title-table">
                                        INGRESOS A CAJA
                                    </td>
                                </tr>

                                @if (isset($abonosTipo) && count($abonosTipo) > 0)
                                    @foreach ($abonosTipo as $v)
                                        <tr>
                                            <td>{{ $v->id }}</td>

                                            <td class="text-uppercase text-start">{{ $v->clientes->nombre }}</td>
                                            @foreach ($forma_pagos as $forma)
                                                <td class="dollar">
                                                    @if ($forma->id == $v->forma_pagos_id)
                                                        @php
                                                            $monto = round($v->monto, 2);
                                                            $turno[$forma->id] += $monto;
                                                            $abonos_total += $monto;
                                                            $abonos_formas[$forma->id] += $monto;
                                                        @endphp
                                                        ${{ number_format($monto, 2) }}
                                                    @else
                                                        $0.00
                                                    @endif

                                                </td>
                                            @endforeach
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format(0, 2) }}</td>
                                            <td class="dollar">${{ number_format($monto, 2) }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr class="fw-bolder bt-1">
                                    <td colspan="2" class="text-uppercase">Totales en ingreso a caja</td>
                                    @foreach ($forma_pagos as $forma)
                                        <td class="dollar">${{ number_format($abonos_formas[$forma->id], 2) }}</td>
                                    @endforeach
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format(0, 2) }}</td>
                                    <td class="dollar">${{ number_format($abonos_total, 2) }}</td>
                                    @php
                                        $totales['total'] += $abonos_total;
                                    @endphp
                                </tr>
                                <tr>
                                    <td colspan="{{ $nforma + 8 }}" class="fc space"></td>
                                </tr>
                            @endif


                            <tr class="bg-turno">
                                <td colspan="2">
                                    TOTAL {{ $turno->opcion->turno }} · {{ $turno->fecha }}
                                </td>

                                @foreach ($forma_pagos as $f)
                                    <td class="dollar">
                                        ${{ number_format($turno[$f->id], 2) }}
                                    </td>
                                    @php
                                        $tturno[$f->id] += $turno[$f->id];
                                    @endphp
                                @endforeach
                                <td class="dollar">${{ number_format($totales['propina'], 2) }}</td>
                                <td class="dollar">${{ number_format($totales['cesc'], 2) }}</td>
                                <td class="dollar">${{ number_format($totales['advalorem'], 2) }}</td>
                                <td class="dollar">${{ number_format($totales['iva'], 2) }}</td>
                                <td class="dollar">${{ number_format($totales['percepcion'], 2) }}</td>
                                <td class="dollar">${{ number_format($totales['total'], 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="{{ $nforma + 8 }}" class="fc space"></td>
                            </tr>

                            @php
                                $ttotales['total'] += $totales['total'];
                                $ttotales['iva'] += $totales['iva'];
                                $ttotales['propina'] += $totales['propina'];
                                $ttotales['cesc'] += $totales['cesc'];
                                $ttotales['advalorem'] += $totales['advalorem'];
                                $ttotales['percepcion'] += $totales['percepcion'];

                            @endphp
                        @endforeach
                        <tr class="bg-total">
                            <td colspan="2">
                                TOTAL {{ $fecha_inicio }} - {{ $fecha_fin }}
                            </td>

                            @foreach ($forma_pagos as $f)
                                <td class="dollar">
                                    ${{ number_format($tturno[$f->id], 2) }}
                                </td>
                            @endforeach
                            <td class="dollar">${{ number_format($ttotales['propina'], 2) }}</td>
                            <td class="dollar">${{ number_format($ttotales['cesc'], 2) }}</td>
                            <td class="dollar">${{ number_format($ttotales['advalorem'], 2) }}</td>
                            <td class="dollar">${{ number_format($ttotales['iva'], 2) }}</td>
                            <td class="dollar">${{ number_format($ttotales['percepcion'], 2) }}</td>
                            <td class="dollar">${{ number_format($ttotales['total'], 2) }}</td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>
    </div>
@endsection
