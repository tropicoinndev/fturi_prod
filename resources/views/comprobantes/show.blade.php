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

                            @if (!$comprobante->estado)
                                <button type="button" class="btn btn-light text-danger" data-bs-toggle="modal"
                                    data-bs-target="#comprobantesActivar">
                                    Activar comprobante
                                </button>
                                <div class="modal fade" id="comprobantesActivar" tabindex="-1" data-bs-backdrop="static"
                                    data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm"
                                        role="document">
                                        <form action="{{ route('comprobantes.activarComprobante') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $comprobante->cid }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalTitleId">
                                                        Activación de comprobante
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="confirm"
                                                            value="1" id="activacionAccep" />
                                                        <label class="form-check-label" for="activacionAccep"> Estoy seguro
                                                            que este comprobante debe activarse </label>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">
                                                        Cerrar
                                                    </button>
                                                    <button type="submit" class="btn btn-primary">Activar
                                                        comprobante</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif


                        </div>
                    </div>
                    <div class="accordion my-4" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Ver todo
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                                data-bs-parent="#accordionExample">
                                <div class="accordion-body">


                                    <!-- #Totales-->
                                    <div class="row mb-4">
                                        <div class="col-12 text-uppercase fw-bold my-2">
                                            Información
                                        </div>
                                        <div class="col-4">
                                            ID:
                                        </div>
                                        <div class="col-8">
                                            {{ $comprobante->id }}
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

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12 text-uppercase fw-bold my-2">
                            Edición formas de pago
                        </div>
                        @forelse ($comprobante->pagos as $pg)
                            <div class="col-12 my-2">
                                <div class="card">
                                    <div class="card-body">
                                        <form action="{{ route('comprobantes.editar_forma_pago') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="pago_id" value="{{ $pg->cid }}">
                                            <div class="row">
                                                <div class="col-3 h5 text-uppercase">
                                                    Forma de pago
                                                    {{ $pg->forma_pagos->forma }}
                                                    ${{ number_format($pg->monto, 2) }}
                                                </div>
                                                <div class="col-4">
                                                    <label class="form-check-label" for="confirm{{ $pg->id }}">
                                                        Seleccionar una forma de pago
                                                    </label>
                                                    <select class="form-select" name="forma_pagos_id">
                                                        <option selected>Seleccione una forma de pago
                                                        </option>
                                                        @foreach ($formas as $fp)
                                                            <option value="{{ $fp->cid }}">
                                                                {{ $fp->forma }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-2 d-flex align-items-end">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="confirm"
                                                            value="1" id="confirm{{ $pg->id }}" required>
                                                        <label class="form-check-label" for="confirm{{ $pg->id }}">
                                                            Confirmo el cambio
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-3 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-light">
                                                        <span class="mdi mdi-check"></span>
                                                        Cambiar forma de pago
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
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
                                                        Anticipo Nº {{ $anticipo->anticipos_id }}
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="card-text">
                                                            $ {{ number_format($anticipo->monto, 2) }}
                                                            <a class="btn btn-light text-danger float-end"
                                                                href="{{ route('comprobantes.desaplicar_anticipo', ['id' => $anticipo->cid]) }}"
                                                                role="button">
                                                                <span class="mdi mdi-delete"></span>
                                                                Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-warning" role="alert">
                                                    Esta cuenta no tiene anticipos agregados, debe aplicar
                                                    uno o mas
                                                    anticipos a esta cuenta.
                                                </div>
                                            </div>
                                        @endforelse
                                        @if ($comprobante->cobro->anticipos->sum('monto') < $pg->monto)
                                            <div class="col-4">
                                                <div class="card text-bg-danger">
                                                    <div class="card-header">
                                                        Anticipo faltante
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="card-text">$
                                                            {{ number_format($pg->monto - $comprobante->cobro->anticipos->sum('monto'), 2) }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @if ($comprobante->cobro->anticipos->sum('monto') < $pg->monto)
                                    <div class="col-12">
                                        <div class="card text-bg-info">
                                            <div class="card-body">


                                                <form action="{{ route('comprobantes.aplicar_anticipo') }}"
                                                    method="post">
                                                    @csrf
                                                    <input type="hidden" name="cobros_id"
                                                        value="{{ $comprobante->cobro->cid }}" required>
                                                    <div class="mb-3 fs-4">
                                                        Agregar anticipos
                                                    </div>
                                                    <div class="row">
                                                        <div class="mb-3 col-3">
                                                            <label for="" class="form-label">Numero de
                                                                anticipo</label>
                                                            <input type="number" class="form-control" step="1"
                                                                min="1" name="anticipo" aria-describedby="helpId"
                                                                placeholder="Escriba el numero de anticipo"required />
                                                        </div>
                                                        <div class="mb-3 col-3">
                                                            <label for="" class="form-label">Monto
                                                                a
                                                                aplicar</label>
                                                            <input type="number" class="form-control" step="0.01"
                                                                min="0.01" name="monto" aria-describedby="helpId"
                                                                placeholder="Escriba monto" required />
                                                        </div>
                                                        <div class="mb-3 col-3 d-flex align-items-end">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="1" id="anticipoConfirm"
                                                                    name="confrimAnticipo">
                                                                <label class="form-check-label" for="anticipoConfirm">
                                                                    Confirmar aplicación
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3 col-3 d-flex align-items-end">
                                                            <button class="btn btn-light float-end" type="submit">
                                                                <span class="mdi mdi-check"></span>
                                                                Agregar anticipo
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        @empty
                        @endforelse

                    </div>
                    @if ($comprobante->estado)
                        <div class="row">
                            <div class="col-12">
                                <h3>Edición de detalle de comprobante</h3>
                            </div>
                            <div class="col-12">
                                <div class="card">

                                    <div class="card-body">


                                        <form action="{{ route('comprobantes.updateRegistro') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="comprobante" value="{{ $comprobante->cid }}">
                                            @php
                                                $cambiar = false;
                                            @endphp
                                            @foreach ($comprobante->detalles as $detalle)
                                                @if ($detalle->registro == null || $detalle->tipo_registros == null)
                                                    @php
                                                        $cambiar = true;
                                                    @endphp
                                                    @csrf

                                                    <div class="row my-3">
                                                        <div class="col-6 h5">
                                                            {{ $detalle->cantidad }} - {{ $detalle->concepto }}
                                                            <b>
                                                                ${{ number_format($detalle->total, 2) }}
                                                            </b>
                                                        </div>
                                                        <div class="col-6">
                                                            <select class="form-select"
                                                                aria-label="Default select example"
                                                                name="registro_{{ $detalle->id }}">

                                                                @foreach ($registros as $rgc)
                                                                    <option value="{{ $rgc->id }}">
                                                                        @switch($rgc->tipo_registros)
                                                                            @case(1)
                                                                                Orden
                                                                            @break

                                                                            @case(2)
                                                                                Estadia
                                                                            @break

                                                                            @case(3)
                                                                                Comanda
                                                                            @break
                                                                        @endswitch
                                                                        #{{ $rgc->registro }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="row my-3">
                                                        <div class="col-6 h5 text-uppercase">
                                                            {{ $detalle->cantidad }} - {{ $detalle->concepto }}
                                                            ${{ number_format($detalle->total, 2) }}
                                                        </div>
                                                        <div class="col-6 text-uppercase">
                                                            @switch($detalle->tipo_registros)
                                                                @case(1)
                                                                    Orden
                                                                @break

                                                                @case(2)
                                                                    Estadia
                                                                @break

                                                                @case(3)
                                                                    Comanda
                                                                @break
                                                            @endswitch
                                                            #{{ $detalle->registro }}

                                                            <a href="{{ route('comprobantes.clearRegistro', ['id' => $detalle->cid]) }}"
                                                                class="btn btn-sm btn-light text-danger float-end">
                                                                <span class="mdi mdi-clear h5"></span> Borrar
                                                            </a>
                                                        </div>

                                                    </div>
                                                @endif
                                            @endforeach
                                            @if ($cambiar)
                                                <button class="btn btn-primary" type="submit"
                                                    id="btnCambiarDetalleComprobantes">Guardar cambios</button>
                                                <script>
                                                    // Al cargar la página, enfocar el botón de envío
                                                    window.onload = function() {
                                                        document.getElementById('btnCambiarDetalleComprobantes').focus();
                                                    };
                                                </script>
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
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

                                                    <a href="{{ route('recepciones.print', ['id' => Crypt::encryptString($n?->estadia?->id)]) }}"
                                                        class="btn btn-light">Ver</a>

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
