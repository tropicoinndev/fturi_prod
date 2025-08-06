@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Detalle del cliente
            </h5>

            <div class="row mb-3">
                <div class="col-12 mb-4">
                    <form action="/clientes/comprobantes/" method="get" id="form-mes">
                        <input type="hidden" name="id" value="{{ $p->cid }}">
                        <div class="row">
                            <div class="col-3">
                                <label for="fecha" class="form-label">Mes</label>
                                <input type="month" class="form-control" name="mes" id="fecha"
                                    placeholder="Seleccione un mes" value="{{ $mes }}"
                                    onchange="document.getElementById('form-mes').submit()" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-2">Nombre:</div>
                <div class="col-10">{{ $p->nombre }}</div>
            </div>
            <div class="row mb-1">
                <div class="col-2">Correo electrónico:</div>
                <div class="col-10">{{ $p->email }}</div>
            </div>

            @if ($p->municipios_id > 0)
                <div class="row mb-1">
                    <div class="col-2">Dirección:</div>
                    <div class="col-10"><b>Municipio:</b> {{ $p->municipios?->municipio }}
                        <b>Depto.:</b>{{ $p->municipios?->departamentos?->departamento }}
                        {{ $p->municipios?->departamentos?->paises?->pais }}
                    </div>
                </div>
            @elseif($p->extranjeros_id > 0)
            @else
                <div class="row mb-1">
                    <div class="col-2">Dirección:</div>
                    <div class="col-10">{{ $p->extranjero->pais }} (Configurado como extranjero)</div>
                </div>
            @endif
            <div class="row mb-1">
                <div class="col-2">Complemento de dirección:</div>
                <div class="col-10">{{ $p->direccion }}</div>
            </div>
            <div class="row mb-1">
                <div class="col-2">Actividad economica:</div>
                <div class="col-10">{{ $p?->actividades?->actividad ?? 'No se agrego actividad económica' }}</div>
            </div>

            @if (!$p->tipo_cliente)
                <div class="row mb-1">
                    <div class="col-2">NRC:</div>
                    <div class="col-10">
                        {{ $p->detalle->nrc }}
                    </div>
                </div>
            @endif
            <div class="row mb-4">
                <div class="col-2">Identificaciones:</div>
                <div class="col-10">
                    @forelse ($p->identificaciones as $i)
                        <p>
                            <b>
                                {{ $i->identificaciones->identificacion }}:
                            </b>
                            {{ $i->numero }}
                        </p>
                    @empty
                        No se agrego ninguna identificación
                    @endforelse
                </div>
            </div>
            <div class="row">
                <div class="col-12 h4 fw-light text-uppercase">
                    Comprobantes de {{ $mes }}
                </div>

                <div class="col-12 my-4">
                    <button id="filter-all" class="btn btn-secondary">Todos</button>
                    <button id="filter-contado" class="btn btn-primary">Solo Contado</button>
                    <button id="filter-tarjeta" class="btn btn-primary">Solo Tarjetas</button>
                </div>

                @forelse ($data as $d)
                    @php
                        $hasContado = $d->pagos->contains(function ($fp) {
                            return $fp?->forma_pagos?->token == 6001;
                        });
                        $hasTarjeta = $d->pagos->contains(function ($fp) {
                            return $fp?->forma_pagos?->token == 6003;
                        });
                    @endphp
                    <div
                        class="col-12 mb-3 card-container {{ $hasContado ? 'contado' : '' }} {{ $hasTarjeta ? 'tarjeta' : '' }}">
                        <div class="card border-success mb-3">
                            <div class="card-body">
                                <h5 class="card-title">
                                    {{ $d->titular }}
                                    <span class="badge badge-pill text-bg-primary float-end">
                                        {{ $d->tipoComprobantes->tipo }}
                                    </span>
                                </h5>
                                <div class="card-text">
                                    <div class="row">

                                        @forelse ($d->pagos as $fp)
                                            <div class="col-12">
                                                @if ($fp?->forma_pagos?->token == 6001 || $fp?->forma_pagos?->token == 6003)
                                                    <span class="text-success fw-bold">
                                                        {{ $fp?->forma_pagos?->forma }}:
                                                    </span>
                                                @else
                                                    {{ $fp?->forma_pagos?->forma }}:
                                                @endif
                                                ${{ number_format($fp->monto, 2) }}
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                No se encontró el registro de pagos realizados
                                            </div>
                                        @endforelse
                                        <div class="col-3">
                                            <b>Fecha:</b> {{ $d->fecha }}
                                        </div>
                                        <div class="col-3">
                                            <b>
                                                Correlativo:
                                            </b>
                                            {{ $d->correlativo }}
                                        </div>
                                        <div class="col-5">
                                            <b>
                                                DTE
                                            </b>
                                            {{ $d->dteOne?->codigo_generacion }}
                                        </div>
                                        <div class="col-12 mt-3">
                                            <a class="btn btn-outline-success"
                                                href="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($d->dteOne?->id)]) }}"
                                                role="button" target="_blank">
                                                <span class="mdi mdi-file"></span>
                                                Representación gráfica</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            No se encontró ninguna factura en la fecha seleccionada.
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterContadoButton = document.getElementById('filter-contado');
            const filterTarjetaButton = document.getElementById('filter-tarjeta');
            const filterAllButton = document.getElementById('filter-all');
            const cards = document.querySelectorAll('.card-container');

            filterContadoButton.addEventListener('click', () => {
                cards.forEach(card => {
                    if (card.classList.contains('contado')) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
            filterTarjetaButton.addEventListener('click', () => {
                cards.forEach(card => {
                    if (card.classList.contains('tarjeta')) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            filterAllButton.addEventListener('click', () => {
                cards.forEach(card => {
                    card.style.display = '';
                });
            });
        });
    </script>
@endsection
