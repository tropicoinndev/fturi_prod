@extends('layouts.app')

@section('style')
    <style>
        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            min-height: 91vh;
            padding: 2rem;
            border-radius: 6px;
            background-color: #ffffff;
            color: #37474F;
        }

        .btn-light {
            background: #DAE0E5;
        }
    </style>
@endsection

@section('content')
    <div id="facturaEvento">
        <div class="container">
            <div class="row justify-content-center">
                <x-message></x-message>

                <div class="col-md-12">
                    <div class="card panel-body shadow py-5">
                        <div class="row mb-3 text-uppercase">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <div>
                                        <h2 class="text-muted mb-0">Configuración de cobros anticipados del evento
                                            #{{ $p->id }}</h2>
                                    </div>
                                    <div>
                                        <a class="btn btn-light d-flex align-items-center"
                                            href="{{ route('eventos.detalle', ['id' => $p->cid]) }}" role="button">
                                            <span class="mdi mdi-arrow-left me-2"></span> Volver
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if (isset($p->titular))
                                <div class="col-2 mb-1">
                                    <b>Titular:</b>
                                </div>
                                <div class="col-10">
                                    {{ $p->titular }}
                                </div>
                            @endif
                            @if ($p->clientes_id > 0)
                                <div class="col-2">
                                    <b>Cliente:</b>
                                </div>
                                <div class="col-10 mb-1">
                                    {{ $p->clientes->nombre }}
                                </div>
                                @if (!$p->clientes->tipo_cliente)
                                    <div class="col-2">
                                        <b>Nombre jurídico:</b>
                                    </div>
                                    <div class="col-10 mb-1">
                                        {{ $p->clientes->detalle->juridico }}
                                    </div>
                                @endif
                            @endif
                            <div class="col-2">
                                <b>Tipo comprobante:</b>
                            </div>
                            <div class="col-10 mb-1">
                                {{ $p->tipo_comprobante == 7001 ? 'CREDITO FISCAL' : 'CONSUMIDOR FINAL' }}
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12 text-uppercase fw-bold mb-2">
                                Agregar a pago anticipado
                            </div>
                            @php $total = 0; @endphp
                            @if ((isset($ordenes) && count($ordenes) > 0) || (isset($comandas) && count($comandas) > 0))
                                <div class="col-12 mb-3">
                                    Seleccione una o más cuentas del evento para agregar a este pago anticipado, y presione
                                    <strong>Guardar</strong>.
                                </div>
                                <div class="col-12 text-uppercase mb-2 mt-1 fw-bold">
                                    Cuentas de el evento #{{ $p->id }}
                                </div>
                                <form action="{{ route('pago_anticipados.pago') }}" method="post" id="pagoAnticipadoForm">
                                    @csrf
                                    <input type="hidden" name="eventos_id" value="{{ Crypt::encryptString($p->id) }}">
                                    <input type="hidden" name="clientes_id"
                                        value="{{ Crypt::encryptString($p->clientes_id) }}">
                                    <input type="hidden" name="forma_pago"
                                        value="{{ Crypt::encryptString($p->forma_pagos_id) }}">
                                    <input type="hidden" name="monto" id="monto">

                                    @if (isset($ordenes) && count($ordenes) > 0)
                                        <div class="col-12">
                                            <div class="row">
                                                @foreach ($ordenes as $orden)
                                                    <div class="col-md-4 mb-2">
                                                        <input class="btn-check" type="checkbox" name="ordenes[]"
                                                            value="{{ $orden->id }}"
                                                            data-monto="{{ $orden->getSumDetalleOrden() }}"
                                                            id="ordenes_{{ $orden->orden }}">
                                                        <label class="card btn btn-primary text-start"
                                                            for="ordenes_{{ $orden->orden }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Orden No. {{ $orden->orden }}</h5>
                                                                <p class="card-text">
                                                                <div>
                                                                    {{ $orden->detalle_orden->count() }}
                                                                    {{ $orden->detalle_orden->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                                                    · ${{ number_format($orden->getSumDetalleOrden(), 2) }}
                                                                </div>
                                                                <div>
                                                                    <span>{{ $orden->cajas->caja }}</span>
                                                                    <span
                                                                        class="float-end">{{ \Carbon::parse($orden->created_at)->diffForHumans() }}</span>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    @php
                                                        $total += $orden->getSumDetalleOrden();
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if (isset($comandas) && count($comandas) > 0)
                                        <div class="col-12">
                                            <div class="row">
                                                @foreach ($comandas as $comanda)
                                                    <div class="col-md-4 mb-2">
                                                        <input class="btn-check" type="checkbox" name="comandas[]"
                                                            value="{{ Crypt::encryptString($comanda->id) }}"
                                                            id="comanda_{{ $comanda->id }}">
                                                        <label class="card btn btn-primary text-start"
                                                            for="comanda_{{ $comanda->id }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Comanda No. {{ $comanda->id }}</h5>
                                                                <p class="card-text">
                                                                <div>${{ number_format($comanda->getTotal()->total, 2) }}
                                                                </div>
                                                                <div>
                                                                    <span
                                                                        class="float-end">{{ \Carbon::parse($comanda->created_at)->diffForHumans() }}</span>
                                                                    <span>Mesa #{{ $comanda->mesa }}</span>
                                                                </div>
                                                                </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                    @php
                                                        $total += $comanda->detalles_comanda->sum('total');
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12 mt-3 text-uppercase">
                                        <input type="hidden" name="monto" value="{{ Crypt::encryptString($total) }}">
                                        Total del pago anticipado $: <b >{{ number_format($total, 2) }}</b>

                                    </div>
                                    <div class="col-12 mt-3">
                                        @if ((isset($ordenes) && count($ordenes) > 0) || (isset($comandas) && count($comandas) > 0))
                                            <button class="btn btn-light text-primary" id="btnGuardar"
                                                type="submit">Guardar</button>
                                        @endif

                                    </div>
                                    @if (isset($anticipos) && count($anticipos) > 0)


                                        <div class="col-12 text-uppercase mb-2 mt-1 fw-bolder">
                                            seleccione los anticipos que desea asignar al cobro
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                @foreach ($anticipos as $a)
                                                    <div class="col-4 mb-2 text-capitalize">
                                                        <input class="btn-check" type="checkbox" name="anticipos[]"
                                                            value="{{ Crypt::encryptString($a->anticipos->id) }}"
                                                            id="anticipo_{{ $a->anticipos->id }}" multiple>
                                                        <label class="card btn btn-primary text-start"
                                                            for="anticipo_{{ $a->anticipos->id }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Anticipo No. {{ $a->anticipos->id }}
                                                                </h5>
                                                                <p class="card-text">
                                                                <div>
                                                                    <span style="margin-right:5px;">
                                                                        Evento #{{ $p->id }}
                                                                    </span>

                                                                    <span> monto:
                                                                        ${{ number_format($a->anticipos->monto, 2) }}</span>
                                                                </div>
                                                                <div>
                                                                    <span class="float-end">
                                                                        {{ \Carbon::parse($a->created_at)->diffForHumans() }}
                                                                    </span>

                                                                </div>
                                                                </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    @endif



                        </div>
                        </form>
                    @else
                        <div class="col-12 mt-3">
                            <p class="alert alert-info">No hay órdenes, comandas o estadías activas para este evento.</p>
                        </div>
                        @endif
                  </div>
                </div>
            </div>
        </div>
   </div>
    </div>
@endsection

