<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Imprimir evento</title>
    @vite(['resources/sass/app.scss'])
    <!-- Enlaces a las hojas de estilos de Bootstrap no son necesarios para Dompdf -->
    <style>
        @page {
            size: letter;
            margin: 0;
        }

        .page {
            width: 100%;
            min-height: 100%;
            padding: 5mm;
            font-family: Arial, sans-serif;
            font-size: 12pt;
            border: 1px solid #0000004b;
            border-radius: 12px;
            padding-bottom: 2cm;
            background: white;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            font-family: Arial, sans-serif;
            font-size: 12pt;
            background: transparent;
        }

        .logo {
            width: 95px;
            height: 75px;
            margin-right: 10px;
            margin-bottom: 10px;
            z-index: 1000;
        }

        .subraya {
            flex-grow: 1;
            margin-left: 3px;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }

        .bt-1 {
            border-top: 1px solid #00000051;
        }

        li {
            margin: 0px;
        }

        .mt-firmas {
            margin-top: 2.5cm;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <div class="page">
        <div class="row d-flex align-items-center">
            <div class="col-2">
                <img src="{{ asset(env('logo', '/images/logo.jpg')) }}" alt="Logo de empresa" class="logo">
                <br>
                <small>
                    PBX:2682-1000
                </small>
            </div>
            <div class="col-8 text-uppercase text-center">
                CONTRATO DE EVENTO
            </div>
            <div class="col-2 text-end">
                <strong>#{{ $evento->id }}</strong>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6 d-flex">
                <span>FECHA DE EVENTO:</span>
                <span class="subraya">
                    {{ \Carbon\Carbon::parse($evento->fecha)->isoFormat('dddd D [de] MMMM [de] YYYY') }}
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>TIPO DE EVENTO: </span>
                <span class="subraya">{{ $evento->tipo_eventos->evento }}</span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-6 d-flex">
                <span>HORA:</span>
                <span class="subraya">
                    {{ \Carbon\Carbon::parse($evento->inicio)->format('h:i A') }}
                    a
                    {{ \Carbon\Carbon::parse($evento->finalizacion)->format('h:i A') }}
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>ANTICIPO:</span>
                <span class="subraya">
                    @if ($evento->getAnticipos->isNotEmpty())
                        <strong>${{ number_format($evento->getAnticipos->sum('anticipos_sum_monto'), 2) }}
                            #{{ implode(', ', $evento->getAnticipos->pluck('anticipos.id')->toArray()) }} </strong>
                    @endif
                </span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-12 d-flex">
                <span>SALÓN:</span>
                <span class="subraya">{{ implode(', ', $evento->salones->pluck('salones.salon')->toArray()) }},
                    @if (count($evento->salones) > 1)
                        @if ($evento->salones->pluck('separado')->contains(true))
                            Separados
                        @else
                            Unidos
                        @endif

                    @endif
                </span>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-8 d-flex">
                <span>CLIENTE:</span>
                <span class="subraya text-truncate">{{ $evento->clientes->nombre ?? $evento->titular }}</span>
            </div>
            <div class="col-4 d-flex">
                <span>TELÉFONO:</span>
                <span class="subraya text-center">{{ $evento->clientes->contactos[0]->valor ?? '' }}</span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12 d-flex">
                <span>ENCARGADO DEL EVENTO:</span>
                <span class="subraya">
                    {{ $evento->encargado ?? '' }}
                </span>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-6 d-flex">
                <span>Forma de pago:</span>
                <span class="subraya">{{ $evento->forma_pagos->forma }}</span>
            </div>
            <div class="col-2 fw-medium">
                GARANTIZADO
            </div>
            <div class="col-2 d-flex">
                <span>MÍNIMO:</span>
                <span class="subraya text-center">{{ $evento->minimo_personas }}</span>
            </div>
            <div class="col-2 d-flex">
                <span>MÁXIMO:</span>
                <span class="subraya text-center">{{ $evento->maximo_personas }}</span>
            </div>
        </div>


        <div class="row mt-4 bt-1 pt-3">
            <div class="col-1 fw-bold text-center">
                CANT.
            </div>
            <div class="col-7 fw-bold">
                DETALLES
            </div>
            <div class="col-2 fw-bold text-end">
                UNITARIO
            </div>
            <div class="col-2 fw-bold text-end">
                TOTAL
            </div>
        </div>
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
                <div class="row mt-1">
                    <div class="col-1">
                        {{ $detalle->cantidad }}
                    </div>
                    <div class="col-7">
                        <span>{{ $detalle->servicios->servicio }}</span>
                    </div>
                    <div class="col-2 text-end">
                        ${{ number_format($detalle->precio_unitario, 2) }}
                    </div>
                    <div class="col-2 text-end">
                        ${{ number_format($detalle->total, 2) }}
                    </div>
                </div>
                @php

                    $subtotal += $detalle->total;
                    $iva += $detalle->iva;
                    $propina += $detalle->propina;
                @endphp
            @endforeach
        @endforeach

        @foreach ($comandas as $c)
            @foreach ($c->detalles_comanda as $detalle)
                <div class="row mt-1">
                    <div class="col-1">
                        {{ $detalle->cantidad }}
                    </div>
                    <div class="col-7">
                        <span>{{ $detalle->precios->detalle }}
                            @if ($c->tipo_comanda == 3)
                                (CORTESIA)
                            @endif
                        </span>
                        <p class="text-justify">
                            {!! $detalle->observaciones !!}
                        </p>
                    </div>
                    <div class="col-2 text-end">
                        @if ($c->tipo_comanda == 3)
                            ${{ number_format(0, 2) }}
                        @else
                            ${{ number_format($detalle->precio, 2) }}
                        @endif
                    </div>
                    <div class="col-2 text-end">
                        @if ($c->tipo_comanda == 3)
                            ${{ number_format(0, 2) }}
                        @else
                            ${{ number_format($detalle->total, 2) }}
                        @endif
                    </div>
                </div>

                @php

                    $subtotal += $detalle->total;

                    if ($detalle->iva) {
                        $iva += $detalle->precio * env('iva', 0.13);
                    }

                    if ($detalle->propina) {
                        $propina += $detalle->precio * env('propina', 0.1);
                    }

                @endphp
            @endforeach
        @endforeach

        @php
            $totalAnticipos = $evento->getAnticipos->sum('anticipos_sum_monto');
            $total = $subtotal - $totalAnticipos - $pago_anticipado;
        @endphp
        <div class="row">
            <div class="col-8 text-uppercase">
                información de facturación: <strong>{{ $evento->observaciones_factura }}</strong>
            </div>
            <div class="col-2 text-end bt-1">
                SUB-TOTAL
            </div>
            <div class="col-2 text-end bt-1">
                ${{ number_format($subtotal, 2) }}
            </div>
        </div>
        <div class="row">
            <div class="col-8"></div>
            <div class="col-2 text-end">
                (-) ANTICIPOS
            </div>
            <div class="col-2 text-end">
                ${{ number_format($totalAnticipos, 2) }}
            </div>
        </div>
        <div class="row">
            <div class="col-8"></div>
            <div class="col-2 text-end">
                (-) FACTURADO
            </div>
            <div class="col-2 text-end">
                ${{ number_format($pago_anticipado, 2) }}
            </div>
        </div>
        <div class="row">
            <div class="col-8"></div>
            <div class="col-2 text-end">
                (=) TOTAL
            </div>
            <div class="col-2 text-end">
                ${{ number_format($total, 2) }}
            </div>
        </div>

        <div class="row mt-4 bt-1">
            <div class="col-12 text-uppercase text-muted mt-4 h3">
                DETALLES DEL EVENTO
            </div>
            <div class="col-12">
                <span class="text-uppercase">
                    <b>
                        Indicaciones generales:
                    </b>
                </span>
                <p>
                    {{ $evento->observaciones }}
                </p>
            </div>
        </div>
        <div class="row mt-2 mb-4">
            <div class="col-12">
                <span class="text-uppercase">
                    <b>
                        INDICACIONES DEL MONTAJE
                    </b>
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Tipo de mesas:</span>
                <span class="subraya">
                    {{ $evento->montajes->montaje ?? '' }}
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Pista de baile:</span>
                <span class="subraya">
                    @if ($evento->detalle_montaje->isNotEmpty())
                        @php $pista = $evento->detalle_montaje->pluck('pista_baile')->filter()->implode(', '); @endphp
                        @if ($pista)
                            {{ $pista }}
                        @endif
                    @endif
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Podium:</span>
                <span class="subraya">
                    @if ($evento->detalle_montaje->isNotEmpty())
                        @php
                            $podium = $evento->detalle_montaje->pluck('podium')->filter()->implode(',');
                        @endphp
                        @if ($podium)
                            {{ $podium }}
                        @endif
                    @endif
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Rotatorio y plumones:</span>
                <span class="subraya">
                    @if ($evento->detalle_montaje->isNotEmpty())
                        @php
                            $rotafolio = $evento->detalle_montaje->pluck('rotafolio_plumon')->filter()->implode(',');
                        @endphp
                        @if ($rotafolio)
                            {{ $rotafolio }}
                        @endif
                    @endif
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Banderas:</span>
                <span class="subraya">
                    @if ($evento->detalle_montaje->isNotEmpty())
                        @php
                            $banderas = $evento->detalle_montaje->pluck('bandera')->filter()->implode(',');
                        @endphp
                        @if ($banderas)
                            {{ $banderas }}
                        @endif
                    @endif
                </span>
            </div>
            <div class="col-6 d-flex">
                <span>Eq. sonido:</span>
                <span class="subraya">
                    {{ $evento->sonidos->sonido ?? '' ? 'XXX' : 'No es requerido' }}
                </span>
            </div>
            <div class="col-12 d-flex">
                <span>Otros:</span>
                <span class="subraya">
                    @if ($evento->detalle_montaje->isNotEmpty())
                        @php
                            $otros = $evento->detalle_montaje->pluck('otros')->filter()->implode(',');
                        @endphp
                        @if ($otros)
                            {{ $otros }}
                        @endif
                    @endif
                </span>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="row mt-4">
            <div class="col-12 mt-4 text-justify">
                <h3><strong>CONTRATACIÓN DEL EVENTO</strong></h3>
                Solicitamos verificar, firmar y enviar a nuestras oficinas este convenio a más tardar 24
                horas de haberlo recibido. Los detalles para el evento, como menú, montaje, etc., deberán ser
                enviados a más tardar 15 días previos al evento.
                Bajo ninguna circunstancia se cobrará menos del número de personas mínimo garantizado.
            </div>
            <div class="col-12 mt-2 text-justify">
                <h3><strong>SUSPENSIÓN DEL CONTRATO:</strong></h3>
                3 días antes de realizar el evento, el cliente se compromete a pagar el 100% del total
                de lo contratado, excepto el 10% de servicio. Cualquier cambio o modificación al convenio deberá ser
                comunicado por escrito al hotel.
                <b>El depósito recibido como garantía no es reembolsable.</b>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <span>Fecha:</span>
                <span>
                    <strong>
                        {{ \Carbon\Carbon::parse($evento->created_at)->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                    </strong>
                </span>
            </div>
            <div class="col-12">
                <br><br>
                <strong>
                    Aceptación de términos y condiciones del contrato #{{ $evento->id }}.
                </strong>
                <br><br><br>
            </div>
        </div>
        <div class="row mt-firmas">
            <div class="col-8 offset-2 bt-1 text-center">
                {{ $evento->clientes->nombre ?? $evento->titular }}
                <p>
                    <b>
                        <small>
                            CLIENTE
                        </small>
                    </b>
                </p>
            </div>

        </div>
        <div class="row mt-firmas">

            <div class="col-5 bt-1 text-uppercase text-center">
                @if (isset($evento->autoriza))
                    {{ $evento->autorizacion->name }}
                    <p>
                        <b>
                            <small>
                                Autoriza
                            </small>
                        </b>
                    </p>
                @else
                    <span class="text-uppercase font-bold ">
                        Sin autorización
                    </span>
                @endif
            </div>
            <div class="col-5 bt-1 offset-2 text-uppercase text-center">
                {{ $evento->usuarios->name }}
                <p>
                    <b>
                        <small>EJECUTIVO/A DE VENTAS</small>
                    </b>
                </p>
            </div>

        </div>
    </div>



    {{-- @if (count($evento->galerias_evento) > 0)
        <div class="row">
            <p>GALERÍA DE EVENTOS</p>
            <div class="container">
                @foreach ($evento->galerias_evento as $eg)
                    @php
                        $imagePath = asset('img/' . $eg->galerias->foto);
                    @endphp

                    <div class="card grid"> <!-- Contenedor de la tarjeta -->
                        <img src="{{ $imagePath }}" class="card-img-top">
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    </div>


    </main>
    --}}
</body>

</html>
