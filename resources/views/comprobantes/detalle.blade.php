@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="card" style="min-height: 85vh;">
            <div class="card-body">
                <h4 class="card-title text-uppercase">Detalle de comprobante</h4>

                <div class="card-body">
                    <div class="row my-2">
                        <x-message></x-message>
                        <div class="col-4 text-uppercase ">
                            Estado:
                        </div>
                        <div class="col-8 ">
                            <span class="{{ $comprobante->estado ? 'text-success' : 'text-warning' }}">

                                {{ $comprobante->estado ? 'VIGENTE' : 'INVALIDADO' }}
                            </span>
                        </div>
                        <div class="col-12 mt-3">
                            <a class="btn btn-light" href="{{ route('comprobantes.result', ['id' => $comprobante->cid]) }}"
                                role="button" target="_blank">
                                <span class="mdi mdi-link"></span>
                                Resultado
                            </a>

                            @if (isset($comprobante->dteOne?->id) && $comprobante->dteOne?->id != null)
                                <a class="btn btn-light"
                                    href="{{ route('comprobantes.api_pdfDte', ['id' => $comprobante->dteOne?->cid]) }}"
                                    role="button" target="_blank">
                                    <span class="mdi mdi-file-document"></span>
                                    Imprimir DTE
                                </a>
                                @can('dtes.update')
                                    <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#complemento">
                                        <span class="mdi mdi-file-document-edit"></span>
                                        Complemento DTE
                                    </button>


                                    <div class="modal fade" id="complemento" tabindex="-1" data-bs-backdrop="static"
                                        data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg"
                                            role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-dark text-uppercase" id="modalTitleId">
                                                        Agregar información complementaria en DTE
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('dte.complemento') }}" method="post">
                                                    <div class="modal-body">
                                                        @csrf
                                                        <input type="hidden" name="id"
                                                            value="{{ $comprobante->dteOne?->cid }}">
                                                        <div class="mb-3">
                                                            <label for="complemento" class="form-label">Complemento</label>
                                                            <textarea class="form-control" name="complemento" id="complemento" rows="3" placeholder="Escriba aquí...">{{ $comprobante->dteOne?->complemento }}</textarea>
                                                            <small>La información proporcionada en este apartado no forma parte
                                                                del DTE. Este contenido no tiene validez fiscal y se incluye
                                                                únicamente para cumplir con los requerimientos específicos del
                                                                cliente. (Clasificación: información empresarial).</small>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                            Cerrar
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endcan
                            @endif
                        </div>
                    </div>
                    <!-- #Totales-->
                    <div class="row mb-4">
                        <div class="col-12 text-uppercase fw-bold my-2">
                            Información
                        </div>
                        <div class="col-4">
                            Titular:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->titular }}
                        </div>
                        <div class="col-4">
                            Correlativo interno:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->correlativo }}
                        </div>
                        @php
                            $round = 4;
                        @endphp
                        <div class="col-4">
                            Neto:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->neto, $round) }}
                        </div>
                        <div class="col-4">
                            IVA:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->iva, $round) }}
                        </div>
                        <div class="col-4">
                            Ad-Valorem:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->advalorem, $round) }}
                        </div>
                        <div class="col-4">
                            CESC:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->cesc, $round) }}
                        </div>
                        <div class="col-4">
                            Propina:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->propina, $round) }}
                        </div>
                        <div class="col-4">
                            Gravadas:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->gravado, $round) }}
                        </div>
                        <div class="col-4">
                            Exento:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->exento, $round) }}
                        </div>
                        <div class="col-4">
                            Total:
                        </div>
                        <div class="col-8">
                            ${{ number_format($comprobante->total, $round) }}
                        </div>
                    </div>
                    <!-- #Creación-->
                    <div class="row mb-4">
                        <div class="col-12 text-uppercase fw-bold my-2">
                            Creación
                        </div>
                        <div class="col-4">
                            Creado por:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->users->name }} ({{ $comprobante->users->user }})
                        </div>
                        <div class="col-4">
                            Caja:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->turnosCajas->cajas->caja }}
                        </div>
                        <div class="col-4">
                            Sucursal:
                        </div>
                        <div class="col-8">

                            {{ $comprobante->turnosCajas->cajas->sucursales->sucursal }}
                        </div>
                        <div class="col-4">
                            Fecha y hora:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->created_at }}
                        </div>
                        <div class="col-4">
                            Ultima modificación:
                        </div>
                        <div class="col-8">
                            {{ $comprobante->updated_at }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-uppercase fw-bold my-2">
                            Formas de pago
                        </div>
                        @forelse ($comprobante->pagos as $pg)
                            <div class="col-12 my-2">
                                <div class="card">
                                    <div class="card-body">
                                        {{ $pg->forma_pagos->forma }}
                                        ${{ number_format($pg->monto, 2) }}
                                    </div>

                                </div>

                            </div>

                            @if ($pg->forma_pagos->token == 6004)
                                <div class="col-12 my-4">
                                    <div class="row">

                                        @forelse ($comprobante->cobro->anticipos  as $anticipo)
                                            <div class="col-4">
                                                <div class="card">
                                                    <div class="card-header">
                                                        Anticipo Nº{{ $anticipo->anticipos_id }}
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="card-text">
                                                            ${{ number_format($anticipo->monto, 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning" role="alert">
                                                    Esta cuenta no tiene anticipos agregados.
                                                </div>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                        @empty
                            <div class="alert alert-danger" role="alert">
                                No se encontró ninguna forma de pago
                            </div>
                        @endforelse

                    </div>
                    <!-- Cuentas -->
                    <div class="row">
                        <div class="col-12 text-uppercase fw-bold my-2">
                            Cuentas
                        </div>
                        @foreach ($registros as $n)
                            <div class="col-12 mb-4">

                                @switch($n->tipo_registros)
                                    @case(1)
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Orden Nº{{ $n->orden->orden }}

                                                </h5>
                                                <div class="card-text">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            Caja:
                                                            {{ $n->orden->cajas->caja }}
                                                        </div>
                                                        <div class="col-4">
                                                            {{ $n->orden->created_at }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Cantidad</th>
                                                                        <th scope="col">Concepto</th>
                                                                        <th scope="col">Unitario</th>
                                                                        <th scope="col">Total</th>
                                                                        <th scope="col">Usuario</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($n->orden->detalle_orden as $d)
                                                                        <tr>
                                                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                                                            <td>
                                                                                {{ $d->cantidad }}
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                {{ $d->servicios->servicio }}
                                                                            </td>
                                                                            <td>
                                                                                ${{ number_format($d->precio_unitario, 4) }}
                                                                            </td>
                                                                            <td>
                                                                                ${{ number_format($d->cantidad * $d->precio_unitario, 4) }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $d->user_detalle->user }}
                                                                                {{ $d->created_at }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case(2)
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Estadía Nº{{ $n?->estadia?->id }}

                                                </h5>
                                                <div class="card-text">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            Habitación:
                                                            {{ $n?->estadia?->habitaciones?->numero_habitacion }}
                                                        </div>
                                                        <div class="col-4">
                                                            Sucursal:
                                                            {{ $n?->estadia?->habitaciones?->sucursales?->sucursal }}
                                                        </div>
                                                        <div class="col-4">
                                                            {{ $n?->estadia?->created_at }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Cantidad</th>
                                                                        <th scope="col">Concepto</th>
                                                                        <th scope="col">Unitario</th>
                                                                        <th scope="col">Total</th>
                                                                        <th scope="col">Usuario</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    <tr>
                                                                        <th scope="row">{{ $loop->index + 1 }}</th>
                                                                        <td>
                                                                            {{ $n?->estadia?->dias }}
                                                                        </td>
                                                                        <td class="text-uppercase">
                                                                            {{ $n?->estadia?->tarifas?->tarifa }} <br>
                                                                            {{ $n?->estadia?->fecha_ingreso }} -
                                                                            {{ $n?->estadia?->fecha_salida }}
                                                                        </td>
                                                                        <td>
                                                                            ${{ number_format($n?->estadia?->tarifas?->monto, 4) }}
                                                                            /
                                                                            día
                                                                        </td>
                                                                        <td>
                                                                            ${{ number_format($n?->estadia?->dias * $n?->estadia?->tarifas?->monto, 4) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $n?->estadia?->usuarios?->user }}
                                                                            {{ $n?->estadia?->created_at }}
                                                                        </td>
                                                                    </tr>
                                                                    @if ($n->estadia?->cargos && count($n->estadia?->cargos) > 0)
                                                                        <tr>
                                                                            <td colspan="6">
                                                                                -- Cargos --
                                                                            </td>
                                                                        </tr>
                                                                        @foreach ($n->estadia->cargos as $s)
                                                                            <tr>
                                                                                <th scope="row">{{ $loop->index + 1 }}</th>
                                                                                <td>
                                                                                    {{ $s->cantidad }}
                                                                                </td>
                                                                                <td class="text-uppercase">
                                                                                    {{ $s->cargos->cargo }}

                                                                                </td>
                                                                                <td>
                                                                                    ${{ number_format($s->precio, 4) }}

                                                                                </td>
                                                                                <td>
                                                                                    ${{ number_format($s->precio * $s->cantidad, 4) }}
                                                                                </td>
                                                                                <td>
                                                                                    {{ $s->usuarios->user }}
                                                                                    {{ $s->created_at }}
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @case(3)
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    Comanda Nº{{ $n->comanda->id }}

                                                </h5>
                                                <div class="card-text">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            Caja:
                                                            {{ $n->comanda->cajas->caja }}
                                                        </div>
                                                        <div class="col-4">
                                                            Usuario:
                                                            {{ $n->comanda->usuarios->name }}
                                                        </div>
                                                        <div class="col-4">
                                                            {{ $n->comanda->created_at }}
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th scope="col">#</th>
                                                                        <th scope="col">Cantidad</th>
                                                                        <th scope="col">Concepto</th>
                                                                        <th scope="col">Unitario</th>
                                                                        <th scope="col">Total</th>
                                                                        <th scope="col">Usuario</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($n->comanda->detalles_comanda as $d)
                                                                        <tr>
                                                                            <th scope="row">{{ $loop->index + 1 }}</th>
                                                                            <td>
                                                                                {{ $d->cantidad }}
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                {{ $d->precios->detalle }}
                                                                            </td>
                                                                            <td>
                                                                                ${{ number_format($d->precio, 4) }}
                                                                            </td>
                                                                            <td>
                                                                                ${{ number_format($d->cantidad * $d->precio, 4) }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $d->user_comanda->user }}
                                                                                {{ $d->created_at }}
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @break

                                    @default
                                @endswitch
                            </div>
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
