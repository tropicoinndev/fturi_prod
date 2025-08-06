@extends('layouts.app')
@section('style')
    <style>
        body {
            background-color: #B2DFDB;
        }

        .panel {
            min-height: 90vh;
        }

        .evento {
            background: #0277BD;
            color: #FAFAFA;
        }



        .test {
            width: 97%;
            /* Ancho del 70% */
            float: left;
            border-bottom: 1px solid #000;

        }

        .test1 {
            width: 30%;
            /* Ancho del 30% */
            float: left;
            margin: left 0px;


        }

        .mb {
            margin-bottom: 5px;

        }

        .underline {
            text-decoration: underline;
        }

        .float-end {
            float: right;
        }

        .float-start {
            float: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .label {
            display: inline-block;
        }

        .margin-bottom {
            margin-bottom: 3px;
        }
    </style>
@endsection
@section('content')
    <div class="container" id="detalleAutoriza">
        <div class="row justify-content-center">

            <!-- ** Encabezado de index **-->
            <div class="col-md-12">
                <div class="card panel shadow p-4">
                    <div class="card-body">
                        <div class="row mb-2">
                            <!--Mensajes de alerta alerta-->
                            <div class="col-12 row">
                                <x-message></x-message>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 mb-4">
                                    <h3 class="card-title text-uppercase mb-0">Autorización de evento</h3>
                                </div>
                                <div class="col-lg-6 mb-4">
                                    <div class="text-lg-end">
                                        <a type="button" class="btn btn-light m-1"
                                            href="{{ route('eventos.detalle', ['id' => $evento->cid]) }}">
                                            <span class="mdi mdi-arrow-left fs-5"></span>
                                            Volver
                                        </a>

                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="row mt-1">
                            <div class="col-12 mb-2 ">
                                <div>
                                    <a class="btn btn-danger float-end" data-bs-toggle="modal" href="#negarModal"
                                        role="button">
                                        Negar evento
                                    </a>
                                    <a class="btn btn-primary float-end" style="margin-right: 10px;" data-bs-toggle="modal"
                                        href="#AutorizaModal" role="button">
                                        Autorizar evento
                                    </a>

                                </div>

                                <h5>DETALLE DEL EVENTO </h5>
                            </div>
                            <div class=" col-12">
                                <div class="col-12">
                                    <div class="col-11 d-flex justify-content-end">
                                        <strong>No# {{ $evento->id }}</strong>
                                    </div>
                                </div>

                                <div class=" col-12 mb">
                                    <div class="mb col-12">PBX:2682-1000</div>
                                    <div class="mb col-12">FAX:2682-1000</div>

                                    <div class="mb col-12">

                                        <div class="test mb  font-bold text-center">CONTRATO DE EVENTO</div>
                                    </div>

                                </div>


                                <div class=" col-12 mb-0 text-uppercase">
                                    <span class="label">FECHA DE EVENTO:</span>
                                    <span class="label"
                                        style=" width: 615px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd D [de] MMMM [de] YYYY') }}</span>
                                    <span class="label">TIPO DE EVENTO:</span>
                                    <span class="label"
                                        style=" width: 285px; border-bottom: 1px solid black;">{{ $evento->tipo_eventos->evento }}</span>

                                </div>
                                <div class="col-12 mb-0">
                                    <span class="label">HORA:</span>
                                    <span class="label"
                                        style=" width: 751px; border-bottom: 1px solid black;">{{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                                        a
                                        {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}</span>
                                    <span class="label">ANTICIPO:</span>
                                    <span class="label" style=" width: 295px; border-bottom: 1px solid black;">
                                        @if ($evento->getAnticipos->isNotEmpty())
                                            <strong>${{ number_format($evento->getAnticipos->sum('anticipos_sum_monto'), 2) }}
                                                #{{ implode(', ', $evento->getAnticipos->pluck('anticipos.id')->toArray()) }}</strong>
                                        @endif
                                    </span>
                                </div>
                                <div class=" col-12 mb-1">
                                    <div class="row-group ">
                                        <div class="row-item">
                                            <span class="label">SALÓN:</span>
                                            <span class="label"
                                                style=" width: 650px; border-bottom: 1px solid black;">{{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }}</span>
                                            <span class="label">Efectivo:</span>
                                            <span class="label" style=" width:26px;font-size: 10px; ">(
                                                @if ($evento->getAnticipos->isNotEmpty())
                                                    @php
                                                        $efectivo = $evento->getAnticipos
                                                            ->pluck('anticipos')
                                                            ->flatten()
                                                            ->filter(function ($anticipos) {
                                                                return $anticipos->forma_pagos &&
                                                                    $anticipos->forma_pagos->token == 6001;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    @if ($efectivo > 0)
                                                        {{ $efectivo }}#
                                                    @endif
                                                @endif
                                                )

                                            </span>
                                            <span class="label">Cheque No:</span>
                                            <span class="label" style=" width: 108px; border-bottom: 1px solid black;">
                                                @if ($evento->getAnticipos->isNotEmpty())
                                                    @php
                                                        $cheque = $evento->getAnticipos
                                                            ->pluck('anticipos')
                                                            ->flatten()
                                                            ->filter(function ($anticipos) {
                                                                return $anticipos->forma_pagos &&
                                                                    $anticipos->forma_pagos->token == 6005;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    @if ($cheque > 0)
                                                        {{ $cheque }}#
                                                    @endif
                                                @endif

                                            </span>
                                            <span class="label">Bco:</span>
                                            <span class="label" style=" width: 129px; border-bottom: 1px solid black;">
                                                @if ($evento->getAnticipos->isNotEmpty())
                                                    @php
                                                        $bcoCount = $evento->getAnticipos
                                                            ->pluck('anticipos')
                                                            ->flatten()
                                                            ->filter(function ($anticipos) {
                                                                return $anticipos->forma_pagos &&
                                                                    $anticipos->forma_pagos->token == 6003;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    @if ($bcoCount > 0)
                                                        {{ $bcoCount }}#
                                                    @endif
                                                @endif
                                            </span>
                                        </div>
                                        <div class="row-item text-center">
                                            <span class="label" style=" width:500px; "></span>
                                            <span class="label">Tarjeta de credito Tipo:</span>
                                            <span class="label" style=" width: 150px; border-bottom: 1px solid black;">
                                                @if ($evento->getAnticipos->isNotEmpty())
                                                    @php
                                                        $tarjeta = $evento->getAnticipos
                                                            ->pluck('anticipos')
                                                            ->flatten()
                                                            ->filter(function ($anticipos) {
                                                                return $anticipos->forma_pagos &&
                                                                    $anticipos->forma_pagos->token == 6002;
                                                            })
                                                            ->count();
                                                    @endphp

                                                    @if ($tarjeta > 0)
                                                        {{ $tarjeta }}#
                                                    @endif
                                                @endif
                                            </span>
                                            <span class="label">No</span>
                                            <span class="label"
                                                style=" width: 293px; border-bottom: 1px solid black;"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-12">

                                    <span class="label">PEDIDO POR:</span>
                                    <span class="label text-uppercase"
                                        style=" width: 680px; border-bottom: 1px solid black;">{{ $evento->clientes->nombre ?? $evento->titular }}</span>


                                    <span class="label">TELÉFONO:</span>
                                    <span class="label"
                                        style=" width: 310px; border-bottom: 1px solid black;">{{ optional($evento->clientes)->contactos[0]->valor ?? 'no se agregado telefono aun' }}</span>


                                </div>
                                <div class="col-12">
                                    <span class="label">DIRECCIÓN:</span>
                                    <span class="label text-uppercase"
                                        style=" width: 1091px; border-bottom: 1px solid black;">{{ $evento->clientes->direccion ?? 'No se ha asignado un cliente aun' }}</span>
                                </div>
                                <div class="col-12">
                                    <span class="label">FACTURAR A:</span>
                                    <span class="label" style=" width: 1082px; border-bottom: 1px solid black;"><strong
                                            class="text-uppercase font-bold">{{ $evento->clientes->nombre ?? $evento->titular }}</strong>
                                        (@if ($evento->clientes != null)
                                            {{ $evento->clientes->tipo_cliente == 1 ? 'COMPROBANTE CONSUMIDOR FINAL' : 'COMPROBANTE CREDITO FISCAL' }}
                                        @endif )</span>
                                </div>
                                <div class="col-12">
                                    <span class="label">ENCARGADO:</span>
                                    <span class="label" style=" width: 1082px; border-bottom: 1px solid black;"><strong
                                            class="text-uppercase font-bold">{{ $evento->encargado ?? '' }}</strong>
                                        </span>
                                </div>
                                <div class="col-12">
                                    <span class=" label text-uppercase">Forma de pago:</span>
                                    <span class="label" style=" width: 200px; ">{{ $evento->forma_pagos->forma }}</span>
                                    <span class="label">MÍNIMO GARANTIZADO:</span>
                                    <span class="label"
                                        style=" width: 101px; border-bottom: 1px solid black;">{{ $evento->minimo_personas }}</span>
                                    <span class="label">MÁXIMO:</span>
                                    <span class="label"
                                        style="width: 84px; border-bottom: 1px solid black;">{{ $evento->maximo_personas }}</span>
                                </div>

                                <div class="test col-12  mb font-bold "></div>
                                <div class="col-12 row ">
                                    <div class="col-3">
                                        <span class="text-start text-uppercase">Menu:</span>
                                    </div>
                                    <div class="col-5">
                                        <span class="text-center d-block">DOLARES</span>
                                    </div>
                                    <div class="col-4">
                                        <span class="text-center d-block">TIPO DE MONTAJE:</span>
                                    </div>
                                </div>

                                <div class="row  col-12">
                                    <div class="col-8">
                                        <table style="width: 100%; border-collapse: collapse; height:447px;">
                                            @php
                                                $subtotal = 0;
                                                $total = 0;
                                                $iva = 0;
                                                $propina = 0;
                                                $monto = 0;
                                                $tipo_comanda = 0;
                                                $totalAnticipos = 0;
                                                $ordenes = $evento->ordenesTest()->get();
                                                $comandas = $evento->comandasTest()->get();
                                                $reservas = $evento->reservaciones()->get();
                                                $detalle_reserva = $reservas->flatMap(function ($r) {
                                                    return $r->detalleReservaciones;
                                                });
                                                $totalAnticipos = $evento->getAnticipos->sum('anticipos_sum_monto');
                                            @endphp
                                            @foreach ($ordenes as $o)
                                                @php
                                                    $monto += $o->sum_orden;
                                                @endphp
                                                @foreach ($o->detalle_orden as $detalle)
                                                    <tr>

                                                        <td style="width: 50%;">
                                                            <div style="word-wrap: break-word;">
                                                                <span>{{ $detalle->cantidad }}</span> -
                                                                <span>{{ $detalle->servicios->servicio }}</span>
                                                            </div>
                                                        </td>
                                                        <td style="width: 10%;visibility: hidden;">..........$</td>
                                                        <td>${{ number_format($detalle->precio_unitario, 2) }}</td>
                                                        <td>${{ number_format($detalle->total, 2) }}</td>
                                                    </tr>
                                                    @php

                                                        $subtotal += $detalle->total;
                                                        $iva += $detalle->iva;
                                                        $propina += $detalle->propina;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            @foreach ($comandas as $c)
                                                @foreach ($c->detalles_comanda as $detalle)
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div style="word-wrap: break-word;">
                                                                <span>{{ $detalle->cantidad }}</span> -
                                                                <span>{{ $detalle->precios->detalle }} @if ($c->tipo_comanda == 3)
                                                                        (CORTESIA)
                                                                    @endif </span>
                                                                <span>{{ $detalle->observaciones }} </span>

                                                            </div>

                                                        </td>
                                                        <td style="width: 5%;visibility: hidden;">..........$</td>

                                                        <td style="width: 50%;">${{ number_format($detalle->precio, 2) }}
                                                        </td>
                                                        <td style="width: 50%;">
                                                            @if ($c->tipo_comanda == 3)
                                                                ${{ number_format(0, 2) }}
                                                            @else
                                                                ${{ number_format($detalle->total, 2) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @php
                                                        if (
                                                            $evento->getAnticipos &&
                                                            $evento->getAnticipos->isNotEmpty()
                                                        ) {
                                                            // Calcular el total de anticipos

                                                            $totalAnticipos = $evento->getAnticipos->sum(
                                                                'anticipos_sum_monto',
                                                            );
                                                        } else {
                                                            // Si no hay anticipos, el total de anticipos es cero
                                                            $totalAnticipos = 0;
                                                        }
                                                        $subtotal += $detalle->total;
                                                        $total = 0;
                                                        $total = $subtotal - $totalAnticipos;
                                                        if ($detalle->iva) {
                                                            $iva += env('iva', 0.13) * $detalle->precio;
                                                        }
                                                        if ($detalle->propina) {
                                                            $propina += env('propina', 0.1) * $detalle->precio;
                                                        }

                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            @if (isset($detalle_reserva) && count($detalle_reserva) > 0)
                                                @foreach ($detalle_reserva as $r)
                                                    <tr>
                                                        <td style="width: 50%;">
                                                            <div style="word-wrap: break-word;">
                                                                <span>{{ $r->dias }} días</span> -
                                                                <span><b
                                                                        class="text-uppercase">{{ $r->relacionTarifas->tarifa }}</b>
                                                                    ${{ number_format($r->relacionTarifas->precio, 2) }}
                                                                </span>


                                                            </div>

                                                        </td>
                                                        <td style="width: 5%;visibility: hidden;">..........$</td>

                                                        <td style="width: 50%;">${{ number_format($r->total, 2) }} </td>
                                                        <td style="width: 50%;">

                                                        </td>
                                                    </tr>
                                                    @php
                                                        $subtotal += $r->total;
                                                    @endphp
                                                @endforeach
                                            @endif

                                            <tr class="font-monospace">

                                                <td colspan="3"><span class="float-end">Sub-total $</span></td>


                                                <td style="width: 50%;"><span class=" text-secondary">
                                                        {{ number_format($subtotal, 2) }}</span>
                                                </td>

                                            </tr>

                                            <tr class="font-monospace">

                                                <td colspan="3"><span class="float-end">Propina $</span></td>


                                                <td><span class=" text-secondary">{{ number_format($propina, 2) }}</span>
                                                </td>

                                            </tr>
                                            <tr class="font-monospace">

                                                <td colspan="3"><span class="float-end">IVA $</span></td>


                                                <td><span class=" text-secondary">{{ number_format($iva, 2) }}</span>
                                                </td>

                                            </tr>

                                            <tr class="table-light font-monospace">

                                                <td colspan="3"><b class="float-end">TOTAL $</b></td>

                                                <td><span>
                                                        {{ number_format($subtotal, 2) }}</span></td>

                                            </tr>
                                            @if ($evento->getAnticipos && $evento->getAnticipos->isNotEmpty())
                                                <tr class="font-monospace">

                                                    <td colspan="3"><span class="float-end"><b>Total anticipos
                                                                $</b></span></td>


                                                    <td style="width: 50%;"><span class=" text-secondary">
                                                            {{ number_format($totalAnticipos, 2) }}</span>
                                                    </td>

                                                </tr>
                                                @if ($totalAnticipos > $subtotal)
                                                    <tr class="font-monospace">

                                                        <td colspan="3"><span class="float-end  ">Anticipo a fovor del
                                                                cliente $</span></td>


                                                        <td style="width: 50%;"><span class=" text-secondary">
                                                                {{ number_format($totalAnticipos - $subtotal, 2) }}</span>
                                                        </td>

                                                    </tr>
                                                @elseif ($totalAnticipos < $subtotal)
                                                    <tr class="font-monospace">

                                                        <td colspan="3"><span class="float-end text-danger">Pendiente
                                                                de pago $</span></td>


                                                        <td style="width: 50%;"><span class=" text-secondary">
                                                                {{ number_format($subtotal - $totalAnticipos, 2) }}</span>
                                                        </td>

                                                    </tr>
                                                @endif
                                            @endif
                                            @if (isset($pago_anticipado) && $pago_anticipado > 0)
                                                <tr class="font-monospace">

                                                    <td colspan="3"><span class="float-end "><b>Pago anticipado
                                                                $</b></span></td>


                                                    <td style="width: 50%;"><span class=" text-secondary">
                                                            {{ number_format($pago_anticipado, 2) }}</span>
                                                    </td>

                                                </tr>
                                            @endif
                                        </table>
                                    </div>

                                    <div class="col-4 float-end" style="margin-top:0%; position:relative;">

                                        <div class="text-start">
                                            <table>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Tipo de
                                                            mesa:</span>


                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width:180px; border-bottom: 1px solid black;">



                                                            {{ $evento->montajes->montaje ?? '' }}


                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Pista de
                                                            baile:</span>

                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width: 180px; border-bottom:1px solid black;">
                                                            @if ($evento->detalle_montaje->isNotEmpty())
                                                                @php $baile = $evento->detalle_montaje->pluck('pista_baile')->filter()->implode(', '); @endphp
                                                                @if ($baile)
                                                                    {{ $baile }}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Podium:</span>

                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width: 180px; border-bottom:1px solid black;">
                                                            @if ($evento->detalle_montaje->isNotEmpty())
                                                                @php
                                                                    $podium = $evento->detalle_montaje
                                                                        ->pluck('podium')
                                                                        ->filter()
                                                                        ->implode(',');
                                                                @endphp
                                                                @if ($podium)
                                                                    {{ $podium }}
                                                                @endif
                                                            @endif
                                                        </span>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Rotafolio y
                                                            plumon:</span>

                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width: 180px; border-bottom:1px solid black;">
                                                            @if ($evento->detalle_montaje->isNotEmpty())
                                                                @php
                                                                    $r_plumon = $evento->detalle_montaje
                                                                        ->pluck('rotafolio_plumon')
                                                                        ->filter()
                                                                        ->implode(',');
                                                                @endphp
                                                                @if ($r_plumon)
                                                                    {{ $r_plumon }}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Banderas:</span>


                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width: 180px;  border-bottom:1px solid black">
                                                            @if ($evento->detalle_montaje->isNotEmpty())
                                                                @php
                                                                    $b = $evento->detalle_montaje
                                                                        ->pluck('bandera')
                                                                        ->filter()
                                                                        ->implode(',');
                                                                @endphp
                                                                @if ($b)
                                                                    {{ $b }}
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Eq. de Amplif. y
                                                            Microf.:</span>


                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width:180px; border-bottom:1px solid black;">
                                                            {{ $evento->observaciones_sonidos ?? '' }}

                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        <span class="label" style="margin-left:24px;">Otros:</span>

                                                    </td>
                                                    <td>
                                                        <span class="label"
                                                            style="width:178px; border-bottom:1px solid black;">
                                                            @if ($evento->detalle_montaje->isNotEmpty())
                                                                @php
                                                                    $o = $evento->detalle_montaje
                                                                        ->pluck('otros')
                                                                        ->filter()
                                                                        ->implode(',');
                                                                @endphp
                                                                @if ($o)
                                                                    {{ $o }}
                                                                @endif
                                                            @endif
                                                        </span>

                                                    </td>
                                                </tr>
                                            </table>

                                        </div>


                                    </div>

                                </div>
                                <div class="col-4 float-end" style="position:relative;">

                                    <div class="float-start">
                                        <h5 style="margin-left:28px;"><strong>CONTRATACION DEL EVENTO</strong></h5>
                                        <p style="margin-left:28px; width:50%;">
                                            Solicitamos verificar, firmar y enviar a nuestras oficinas este convenio a más
                                            tardar 24 horas
                                            de
                                            haberlo recibido. Los detalles para el evento, como menú, montaje, etc., deberán
                                            ser enviados a
                                            más
                                            tardar 15 días previos al evento.
                                            Bajo ninguna circunstancia se cobrará menos del número de personas mínimo
                                            garantizado.
                                        </p>
                                        <span class="underline" style="margin-left:28px;">SUSPENSION DEL CONTRATO:</span>
                                        <p style="margin-left:28px; width:50%;">
                                            3 días antes de realizar el evento, el cliente se compromete a pagar el 100% del
                                            total de lo
                                            contratado, excepto el 10% de servicio.
                                            Cualquier cambio o modificación al convenio deberá ser comunicado por escrito al
                                            hotel.
                                            <b>El
                                                depósito recibido como garantía no es reembolsable.</b>
                                        </p>


                                    </div>


                                </div>
                                <!-- section para eventos que posee galerias -->
                                <div class="card cuentas w-45 p-4 ">
                                    <div class="row">
                                        <div class="col-8 active">

                                            <h5 class="at text-uppercase">
                                                Galería del evento
                                            </h5>
                                        </div>
                                    </div>
                                    <div>
                                        @if ($evento->galerias_evento->isEmpty())
                                            <div id="alerta_fotos" class="h5 text-center text-muted">No se han agregado
                                                galerías al evento.</div>
                                        @else
                                            <section class="row">
                                                @foreach ($evento->galerias_evento as $eg)
                                                    @php
                                                        $i = asset('img/' . $eg->galerias->foto);
                                                    @endphp
                                                    <article class="col-12 col-sm-6 col-md-4 mb-2"
                                                        data-categoria="{{ $eg->galerias->categoria_fotos_id }}">
                                                        <div class="card h-100">
                                                            <div class="card-img-top"
                                                                style="background-image: url('{{ $i }}'); background-size: cover; background-position: center; height: 20vh;"
                                                                aria-label="Foto de la galería del evento"></div>
                                                        </div>
                                                    </article>
                                                @endforeach
                                            </section>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12"
                                    style=" display: flex; justify-content: space-between;margin-top:31%;">
                                    <div class="col-7 float-start" style="width: 60%; margin-right:5px; ">
                                        <span class="text-uppercase">OBSERVACIONES</span>
                                        <p
                                            style="border: 1px solid #000; padding: 2px; height: 51px; overflow-y: auto; word-wrap: break-word;">
                                            {{ $evento->observaciones }}
                                        </p>
                                    </div>

                                    <div class="float-end col-5" style="width: 40%;">
                                        <div style="text-align:start; width: 90%;">
                                            <div style=" font-size: 10pt;">
                                                <span class="label margin-bottom">Fecha:</span>
                                                <span class="label"
                                                    style="width: 345px; border-bottom:1px solid black;"><strong>{{ \Carbon\Carbon::parse($evento->created_at)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</strong></span>
                                            </div>

                                            <span class="label"
                                                style="width: 345px; border-bottom:1px solid black;margin-left: 45px;"><strong></span>
                                            <div style="margin-right: 5px; font-size: 8px;" class="text-center">Firma
                                                cliente</div>


                                            <div class="text-uppercase text-center" style="margin-bottom: 1px;">
                                                {{ $evento->usuarios->name }}
                                            </div>
                                            <span class="label"
                                                style="width: 345px; border-bottom:1px solid black;margin-left: 45px;"><strong></span>



                                            <div style="margin-right: 5px; font-size: 9px;" class="text-center">Ejecutiv@
                                                de eventos</div>

                                            <span class="label"
                                                style="width: 345px; border-bottom:1px solid black;margin-left: 45px;"><strong></span>
                                            <div style="margin-right: 15px; font-size: 9px;" class="text-center">
                                                @if (isset($evento->autoriza))
                                                    <span class="text-uppercase font-bold"
                                                        style="font-size: 8px;">Autorizado</span>
                                                @else
                                                    <span class="text-uppercase text-center font-bold "
                                                        style="color:red;font-size: 8px;">Sin
                                                        autorización</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal negar evento -->
        <div class="modal fade" id="negarModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">
                            Negar la autorizacion del evento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('eventos.negar_evento') }}" method="post">
                        @csrf
                        <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">Ingrese su contraseña</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Escriba aquí su contraseña" required />
                            </div>
                            <div class="mb-3">
                                <label for="observacion_negacion" class="form-label">Observaciones o motivos de la
                                    negacion :
                                </label>
                                <textarea class="form-control h-100" id="observacion_negacion" name="observacion_negacion"
                                    placeholder="Escriba las observaciones o motivos de la negacion del evento No. {{ $evento->id }} (max. 200 caracteres) aqui ..."
                                    rows="6" style="resize: vertical;">{{ $evento->observacion_negacion }}</textarea>

                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirm" value="1"
                                    id="confirm" required>
                                <label class="form-check-label" for="confirm">
                                    Confirmo la negacion de este evento.
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" type="submit">
                                Negar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- modal autorizar evento -->
        <div class="modal fade" id="AutorizaModal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
            tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalToggleLabel">
                            Autorizar evento
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('eventos.autorizarEvento') }}" method="post">
                        @csrf
                        <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($evento->id) }}">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="password" class="form-label">Ingrese su contraseña</label>
                                <input type="password" class="form-control" name="password" id="password"
                                    placeholder="Escriba aquí su contraseña" required />
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="modificacion" value="1"
                                    id="modificacion"
                                    title="Al seleccionarlo el evento permitira modificaciones en las cuentas">
                                <label class="form-check-label" for="modificacion">
                                    Permitir que las cuentas sean modificadas posterior a la aprobación
                                </label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="confirm" value="1"
                                    id="confirmar" required>
                                <label class="form-check-label" for="confirmar">
                                    Confirmo la autorización de este evento.
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">
                                Autorizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
