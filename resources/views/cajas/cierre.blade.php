@extends('layouts.cajas')

@section('panel_caja')
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

    <div id="appPanelCaja">
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                Cierre de turnos
            </div>
        </div>

        <div class="row">
            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted"> Caja:</span>
                {{ $caja->caja }}
            </div>
            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted">Sucursal:</span>
                {{ $caja->sucursales->sucursal }}
            </div>
            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted">Turno:</span>
                {{ $turno->fecha }}
            </div>
        </div>
        <div class="row">
            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted">Turno:</span>
                {{ $turno->opcion->turno }}
            </div>

            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted">Apertura:</span>
                {{ $turno->apertura }}
            </div>

            <div class="col-4 text-uppercase">
                <span class="fw-bolder text-muted">Usuario apertura:</span>
                {{ $turno->uapertura->name }}
            </div>

        </div>

        <div class="row">
            <div class="col-12">
                <table class="table table-light">
                    <thead>
                        <tr>
                            <td>CORRELATIVO</td>
                            <td>TITULAR</td>
                            @foreach ($forma_pagos as $forma)
                                <td>{{ $forma->forma }}</td>
                            @endforeach
                            <td>PROPINA</td>
                            <td>CET</td>
                            <td>AdValorem</td>
                            <td>IVA</td>
                            <td>RETENCIONES</td>
                            <td>TOTAL</td>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tipo_comprobante as $tipo)
                            <tr>
                                <td colspan="{{ $nforma + 8 }}" class="text-uppercase h5 bg-dark text-white text-center">
                                    {{ $tipo->codigo }} - {{ $tipo->tipo }}</td>
                            </tr>
                            @php
                                $comprobantesTipo = $comprobantes->where('tipo_comprobantes_id', $tipo->id);
                            @endphp
                            @foreach ($comprobantesTipo as $comprobante)
                                @php
                                    //Anulaciones
                                    $afecha = '';
                                    $m = 1;
                                    if (!$comprobante->estado) {
                                        $anulacion = $comprobante->anulacion[0] ?? null;
                                        if ($anulacion) {
                                            $afecha = $anulacion->fecha;
                                            if ($anulacion->fecha == $comprobante->fecha) {
                                                $m = 0;
                                            }
                                        }
                                    }
                                @endphp
                                <tr>

                                    <td>

                                        {{ $comprobante->correlativo }}
                                    </td>

                                    <td class="text-uppercase">{{ $m == 1 ? $comprobante->titular : 'ANULADO' }}
                                        {{ $afecha }}</td>
                                    @php
                                        $comprobantePagos = $comprobante->pagos;

                                        $pago = [];
                                        foreach ($forma_pagos as $f) {
                                            if (!isset($pago[$f->id])) {
                                                $pago[$f->id] = 0;
                                            }
                                            $pg = null;
                                            $pg = $comprobantePagos->where('forma_pagos_id', $f->id)->first();

                                            if ($pg != null) {
                                                if ($pg->forma_pagos->token == 6004) {
                                                    $anticipo = $comprobante->cobro->anticipos;
                                                    foreach ($anticipo as $a) {
                                                        if ($comprobante->fecha == $a->anticipos->fecha) {
                                                            if (isset($pago[$a->anticipos->forma_pagos_id])) {
                                                                $pago[$a->anticipos->forma_pagos_id] += $a->monto;
                                                            } else {
                                                                $pago[$a->anticipos->forma_pagos_id] = $a->monto;
                                                            }
                                                        } else {
                                                            $pago[$f->id] += $pg->monto * $m;
                                                        }
                                                    }
                                                } else {
                                                    $pago[$f->id] += $pg->monto * $m;
                                                }
                                            }
                                        }
                                    @endphp


                                    @foreach ($forma_pagos as $forma)
                                        @php
                                            $monto = $pago[$forma->id];
                                            $totalesformas[$tipo->id][$forma->id] += $monto;

                                        @endphp
                                        <td>${{ number_format($monto, 2) }}
                                        </td>
                                    @endforeach

                                    <td>${{ number_format($comprobante->propina * $m, 2) }}</td>
                                    <td>${{ number_format($comprobante->cesc * $m, 2) }}</td>

                                    <td>${{ number_format($comprobante->advalorem * $m, 2) }}</td>

                                    <td>${{ number_format($comprobante->iva * $m, 2) }}</td>

                                    <td>${{ number_format($comprobante->percepcion * $m, 2) }}</td>

                                    <td>${{ number_format($comprobante->total * $m, 2) }}</td>

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
                                <td colspan="2" class="text-uppercase">Totales en {{ $tipo->tipo }}</td>
                                @foreach ($forma_pagos as $forma)
                                    <td>${{ number_format($totalesformas[$tipo->id][$forma->id], 2) }}</td>
                                    @php
                                        $turno[$forma->id] += $totalesformas[$tipo->id][$forma->id];
                                    @endphp
                                @endforeach
                                <td>${{ number_format($totales[$tipo->id]['propina'], 2) }}</td>
                                <td>${{ number_format($totales[$tipo->id]['cesc'], 2) }}</td>
                                <td>${{ number_format($totales[$tipo->id]['advalorem'], 2) }}</td>
                                <td>${{ number_format($totales[$tipo->id]['iva'], 2) }}</td>
                                <td>${{ number_format($totales[$tipo->id]['percepcion'], 2) }}</td>
                                <td>${{ number_format($totales[$tipo->id]['total'], 2) }}</td>
                                @php
                                    $totales['total'] += $totales[$tipo->id]['total'];
                                    $totales['iva'] += $totales[$tipo->id]['iva'];
                                    $totales['propina'] += $totales[$tipo->id]['propina'];
                                    $totales['cesc'] += $totales[$tipo->id]['cesc'];
                                    $totales['advalorem'] += $totales[$tipo->id]['advalorem'];
                                    $totales['percepcion'] += $totales[$tipo->id]['percepcion'];
                                @endphp
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="{{ $nforma + 8 }}" class="text-uppercase h5 bg-dark text-white text-center">
                                Anticipos
                            </td>
                        </tr>
                        @if (isset($anticipos) && count($anticipos) > 0)
                            @foreach ($anticipos as $a)
                                <tr>
                                    <td>{{ $a->id }}</td>

                                    <td class="text-uppercase">{{ $a->clientes->nombre }}</td>
                                    @foreach ($forma_pagos as $forma)
                                        <td>
                                            @if ($forma->id == $a->forma_pagos_id)
                                                @php
                                                    $monto = round($a->monto_historico - $a->sum_aplicado, 2);
                                                    $turno[$forma->id] += $monto;
                                                    $anticipos_total += $monto;
                                                    $anticipos_formas[$forma->id] += $monto;
                                                @endphp
                                                ${{ number_format($monto, 2) }}
                                            @else
                                                $0.00
                                            @endif

                                        </td>
                                    @endforeach
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format($monto, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif

                        <tr class="fw-bolder">
                            <td colspan="2" class="text-uppercase">Totales en anticipos</td>
                            @foreach ($forma_pagos as $forma)
                                <td>${{ number_format($anticipos_formas[$forma->id], 2) }}</td>
                            @endforeach
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format($anticipos_total, 2) }}</td>
                            @php
                                $totales['total'] += $anticipos_total;
                            @endphp
                        </tr>

                        <tr>
                            <td colspan="{{ $nforma + 8 }}" class="text-uppercase h5 bg-dark text-white text-center">
                                INGRESOS A CAJA
                            </td>
                        </tr>
                        @if (isset($abonos) && count($abonos) > 0)
                            @foreach ($abonos as $v)
                                <tr>
                                    <td>{{ $v->id }}</td>

                                    <td class="text-uppercase text-start">{{ $v->clientes->nombre }}</td>
                                    @foreach ($forma_pagos as $forma)
                                        <td>
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
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format(0, 2) }}</td>
                                    <td>${{ number_format($monto, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                        <tr class="fw-bolder">
                            <td colspan="2" class="text-uppercase">Totales en ingreso a caja</td>
                            @foreach ($forma_pagos as $forma)
                                <td>${{ number_format($abonos_formas[$forma->id], 2) }}</td>
                            @endforeach
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format(0, 2) }}</td>
                            <td>${{ number_format($abonos_total, 2) }}</td>
                            @php
                                $totales['total'] += $abonos_total;
                            @endphp
                        </tr>
                        <tr>
                            <td colspan="2" class="text-uppercase bg-dark text-white">
                                TOTAL {{ $turno->opcion->turno }}
                            </td>

                            @foreach ($forma_pagos as $f)
                                <td class="bg-dark text-white">
                                    ${{ number_format($turno[$f->id], 2) }}
                                </td>
                            @endforeach
                            <td class="bg-dark text-white">${{ number_format($totales['propina'], 2) }}</td>
                            <td class="bg-dark text-white">${{ number_format($totales['cesc'], 2) }}</td>
                            <td class="bg-dark text-white">${{ number_format($totales['advalorem'], 2) }}</td>
                            <td class="bg-dark text-white">${{ number_format($totales['iva'], 2) }}</td>
                            <td class="bg-dark text-white">${{ number_format($totales['percepcion'], 2) }}</td>
                            <td class="bg-dark text-white">${{ number_format($totales['total'], 2) }}</td>
                        </tr>
                    </tbody>

                </table>
            </div>

        </div>
        <div class="row">
            <div class="col-12">
                <a class="btn btn-outline-primary float-end" href="{{ route('cajas.cierre_store') }}" role="button">Cerrar
                    turno</a>
            </div>
        </div>
    </div>
@endsection
