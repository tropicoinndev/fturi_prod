@extends('layouts.cajas')
@section('panel_caja')
<h3 class="card-title">Resultado del pago anticipado # {{$pago->id}}</h3>
<div class="container h-100">
    <div class="row mt-4">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body text-uppercase">
                    <section>
                        <header class="mb-3">
                            <h5 class="text-muted">Información del Pago</h5>
                        </header>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Cobro #:</div>
                            <div class="col-10">{{ $pago->cobros_id }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Cliente:</div>
                            <div class="col-10">{{ $pago->clientes ? $pago->clientes->nombre : 'Sin cliente registrado' }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Concepto:</div>
                            <div class="col-10">{{ $pago->concepto }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Turno:</div>
                            <div class="col-10">{{ $pago->turnos->opcion->turno }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Caja:</div>
                            <div class="col-10">{{ $pago->cajas->caja }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Realizado por:</div>
                            <div class="col-10">{{ $pago->usuarios->name }}</div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-2 text-muted">Total:</div>
                            <div class="col-10">${{ number_format($pago->monto, 2) }}</div>
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-body text-uppercase">
                    <header class="mb-3">
                        <h5 class="text-muted">Forma de Pago</h5>
                    </header>
                    <div class="row mb-2">
                        <div class="col-2">{{ $pago->forma_pagos->forma }}</div>
                        <div class="col-10">${{ number_format($pago->monto, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header text-uppercase">
                    Detalle de cuentas por el pago anticipado
                </div>
                <div class="card-body">
                    <section>
                        <div class="row">
                            @foreach ($pago->cobros->detalleCobros as $d)
                                <div class="col-3 mb-3">
                                    @switch($d->origen)
                                        @case(1)
                                            <article class="card border border-dark-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title">Orden</h5>
                                                    <p class="card-text">No. {{ $d->origen_id }}</p>
                                                </div>
                                            </article>
                                        @break

                                        @case(2)
                                            <article class="card border border-dark-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title">Estadía</h5>
                                                    <p class="card-text">No. {{ $d->origen_id }}</p>
                                                </div>
                                            </article>
                                        @break

                                        @case(3)
                                            <article class="card border border-dark-subtle">
                                                <div class="card-body">
                                                    <h5 class="card-title">Comanda</h5>
                                                    <p class="card-text">No. {{ $d->origen_id }}</p>
                                                </div>
                                            </article>
                                        @break
                                    @endswitch
                                </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
