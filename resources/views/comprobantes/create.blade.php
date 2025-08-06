@extends('layouts.cajas')
@section('panel_caja')

    <div id="appComprobantes">
        <div class="container">
            <h3>Creando comprobante</h3>
            <div class="row mt-4">
                <div class="col-12 col-lg-5 mb-4">
                    <div
                        class="card border {{ $data->cliente != null && $data->cliente->nivel_cautela > 0 ? 'border-' . $data->cliente?->cautela?->color() : 'border-secondary' }}">
                        <div class="card-body">
                            <div class="card-title h4 mb-3">
                                Opciones de cuentas
                            </div>
                            <div class="card-text row mb-4">
                                <div class="col-12">

                                    @php

                                        $hasFine = false;
                                        $codigos = [];
                                        $isValidCl = true;
                                    @endphp
                                    @if ($data->cliente != null && $data->cliente->nivel_cautela > 0)
                                        @php
                                            if ($data->cliente?->nivel_cautela == 3) {
                                                $codigos =
                                                    $data->cliente?->identificaciones
                                                        ->map(fn($i) => $i->identificaciones->codigo)
                                                        ->toArray() ?? [];
                                                $hasFine = in_array(13, $codigos) || in_array(36, $codigos);
                                                if (!$hasFine || $data->cliente->actividades_economicas_id == null) {
                                                    $isValidCl = false;
                                                }
                                            }
                                        @endphp
                                        <div class="alert alert-{{ $data->cliente?->cautela?->color() }} text-uppercase"
                                            role="alert">
                                            Nivel de riesgo:
                                            <strong>
                                                {{ $data->cliente?->cautela?->value() }}
                                            </strong>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-light text-dark fs-4 p-1 w-100" title="Editar cliente"
                                        data-bs-target="#clienteEdit" data-bs-toggle="modal" type="button">
                                        <span class="mdi mdi-account-cog"></span>
                                    </button>
                                </div>
                                <div class="col-10 text-uppercase">
                                    <div class="mb-1">
                                        Titular: {{ $data->titular }}
                                    </div>
                                    <div class="mb-1">
                                        Cliente:
                                        {{ $data->cliente != null ? $data->cliente->nombre : 'Cliente no registrado' }}
                                    </div>
                                    @if ($data->cliente != null && !$data->cliente->tipo_cliente && $data->cliente->detalle->percepcion)
                                        <div class="mb-1 fw-bolder text-success">
                                            Aplica retencion.
                                        </div>
                                    @endif


                                </div>
                                @if (!$isValidCl)
                                    <div class="alert alert-danger mt-4" role="alert">
                                        <strong>NO PUEDE CREAR COMPROBANTES PARA ESTE CLIENTE HASTA TENER:</strong>
                                        <ul>
                                            @if (!$hasFine)
                                                <li>DUI o NIT</li>
                                            @endif
                                            @if ($data->cliente->actividades_economicas_id == null)
                                                <li>Actividad económica.</li>
                                            @endif
                                        </ul>

                                        <a class="btn btn-primary"
                                            href="{{ route('clientes.show', ['id' => $data->cliente->cid]) }}"
                                            role="button" target="_blank">Editar cliente</a>

                                    </div>
                                    <p class="fw-lighter">

                                        <i>*Debe actualizar posterior a agregar todos los datos requeridos.</i>
                                    </p>
                                @endif
                            </div>

                            @if ($data->totales->total >= env('monto_comprobante', 100) && $data->cliente == null)
                                <p class="card-text">
                                    No se puede continuar con el comprobante,
                                    es requerido registrar/asignar un cliente para montos mayores
                                    a ${{ number_format(env('monto_comprobante', 100), 2) }}.
                                    Presione sobre el boton editar cliente.
                                </p>
                            @else
                                @if ($isValidCl)
                                    <div class="col-12 mb-3">
                                        @if ($data->cliente != null)
                                            <form action="{{ route('comprobantes.descuento') }}" method="post">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <div class="input-group">
                                                    <div class="form-floating">

                                                        <select class="form-select" name="descuentos_id" id="descuentos_id">
                                                            <option value="">Quitar descuentos</option>
                                                            @foreach ($descuentos as $ds)
                                                                <option value="{{ $ds->id }}">
                                                                    {{ $ds->descuento }}
                                                                    {{ $ds->porcentaje }}%</option>
                                                            @endforeach
                                                        </select>
                                                        <label for='descuentos_id' class="text-uppercase">
                                                            Aplicar descuento
                                                        </label>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary">Aplicar</button>
                                                </div>
                                            </form>
                                            @if ($data->cliente->empleado)
                                                <span class="text-success">
                                                    <div class="spinner-grow text-success align-middle" role="status"
                                                        style="width: 6px; height: 6px;">
                                                        <span class="visually-hidden"></span>
                                                    </div>
                                                    Este cliente esta configurado como empleado. Aplique el descuento de
                                                    beneficio de empleados.
                                                </span>
                                            @endif
                                        @endif
                                        @{{ sumEfectivo }}
                                    </div>
                                    <div class="col-12 mt-4">
                                        <div class="card-title h4 mb-3">
                                            Formas de pago
                                        </div>
                                        <div class="card-text row mb-3">
                                            <div class="col-3">
                                                Anticipos:
                                            </div>
                                            <div class="col-3">
                                                $@{{ parseFloat(getSumAnticipo).toFixed(2) }}
                                            </div>
                                            <div class="col-6">
                                                <a data-bs-toggle="modal" href="#asignarAnticipos" role="button"
                                                    class="btn btn-light float-end">
                                                    Asignar anticipos
                                                </a>
                                            </div>
                                        </div>

                                        <form id="comprobantesCreate" action="{{ route('comprobantes.store') }}"
                                            method="post" @submit.prevent="enviarComprobante">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <input type="hidden" name="tipo" value="{{ $tipo }}">
                                            <input type="hidden" name="tipo_comprobante" value="{{ $tipoComprobante }}">
                                            <input type="hidden" name="numero_pagos" v-model="pagos.length">
                                            <input type="hidden" name="regulada"
                                                :value="getProcedencia || getBancoReq ? 1 : 0">

                                            <div class="input-group mb-3" v-for="(pago, i) in getPagos">
                                                <div class="input-group-text">
                                                    <select class="form-select" aria-label="Default select example"
                                                        id="selectPagos" name="selectPagos" v-model="pagos[i].forma_pago"
                                                        v-if="pagos[i].forma_pago == ''" @click="getFormaPago()">
                                                        <option selected value="">F. Pago</option>
                                                        <option :value="fp" v-for="fp in formas_pagos">
                                                            @{{ fp.forma }}
                                                        </option>
                                                    </select>
                                                    <input type="hidden" :name=`forma_pago_${i}`
                                                        value="{{ $id }}" v-model="pagos[i].forma_pago.id">
                                                    <span v-if="pagos[i].forma_pago.id">@{{ pagos[i].forma_pago.forma }} <span
                                                            class="mdi mdi-close btn btn-sm"
                                                            @click="deletePagoId(i)"></span></span>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="number" :id="'fp' + i" step="any"
                                                        min="0.01" class="form-control"
                                                        placeholder="Ingrese el valor" v-model="pagos[i].valor" required
                                                        value="" :name=`valor-${i}`
                                                        :disabled="pagos[i].forma_pago == null || pagos[i].forma_pago.id == null">
                                                    <label :for="'fp' + i" class="text-uppercase">Cantidad recibida
                                                        en
                                                        @{{ pagos[i].forma_pago.forma || " " }}</label>
                                                </div>
                                                <div class="input-group-text">
                                                    <button class="btn btn-light" v-if="i+1 == pagos.length"
                                                        type="button" @click="newPago()"
                                                        :disabled="isValidGen == total"><span
                                                            class="mdi mdi-plus"></span></button>
                                                    <button class="btn btn-light" v-else-if="pagos.length > (i+1)"
                                                        type="button" @click="deletePago(i)"><span
                                                            class="mdi mdi-delete"></span></button>
                                                </div>

                                            </div>
                                            <div class="row" v-if="getProcedencia">
                                                <div class="input-group mb-3">
                                                    <div class="form-floating">
                                                        <input type="text" id='procedencia' min="5"
                                                            class="form-control" placeholder="" name='procedencia'
                                                            required maxlength="200">
                                                        <label for='procedencia' class="text-uppercase">
                                                            Procedencia de fondos
                                                        </label>
                                                    </div>
                                                </div>
                                                <small>
                                                    Debe consultar al cliente sobre la <strong>procedencia de los
                                                        fondos</strong> utilizados para pagar esta transacción.
                                                    Este requisito aplica en los siguientes casos:
                                                    <ul>
                                                        <li>Para pagos en efectivo: <strong>mayores o iguales a
                                                                ${{ number_format(env('monto_efectivo'), 2) }}</strong>.
                                                        </li>
                                                    </ul>
                                                </small>
                                            </div>
                                            <div class="row" v-if="getBancoReq">
                                                <div class="input-group mb-3">

                                                    <div class="form-floating col-12">
                                                        <input type="text" id='banco' min="2"
                                                            class="form-control" placeholder="" name='banco' required
                                                            maxlength="200">
                                                        <label for='banco' class="text-uppercase">
                                                            Nombre del banco
                                                        </label>
                                                    </div>
                                                </div>

                                                <small>

                                                    Este requisito aplica en los siguientes casos:
                                                    <ul>
                                                        <li>Para transacciones bancarias: <strong>mayores o iguales a
                                                                ${{ number_format(env('monto_banco'), 2) }}</strong>.
                                                        </li>
                                                    </ul>
                                                </small>

                                            </div>
                                            <div class="alert alert-warning mb-3" role="alert"
                                                v-if="(getProcedencia || getBancoReq)  && actividadEconomica == 0">
                                                <h4 class="alert-heading">Cliente sin actividad económica</h4>
                                                <p>Debe agregar la actividad económica de este cliente, después
                                                    actualice para que le permita crear el comprobante.</p>
                                                <hr />
                                                <p class="mb-0">
                                                    @if ($data->cliente != null && $data->cliente->id > 0)
                                                        <a class="btn btn-light"
                                                            href="{{ route('clientes.show', ['id' => $data->cliente->cid]) }}"
                                                            target="_blank" role="button">Editar cliente</a>
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="input-group mb-3">
                                                <div class="form-floating">
                                                    <input type="number" id='recibe' step="any" min="0.01"
                                                        class="form-control" placeholder="" name='recibe'>
                                                    <label for='recibe' class="text-uppercase">
                                                        Ingrese la cantidad que recibe (Calcular cambio)
                                                    </label>
                                                </div>
                                            </div>
                                            @if ($data->cliente != null)
                                                <div class="input-group mb-3">
                                                    <div class="form-floating">
                                                        <input type="text" id='observaciones' class="form-control"
                                                            name='observaciones' maxlength="200">
                                                        <label for='observaciones' class="text-uppercase">
                                                            Observaciones (Opcional)
                                                        </label>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="form-group mb-3">

                                                <button
                                                    class="btn {{ $data->cliente != null && $data->cliente->nivel_cautela > 0 ? 'btn-' . $data->cliente?->cautela?->color() : 'btn-primary' }}"
                                                    type="submit" id="btnEnviarComprobante"
                                                    :disabled="!btnState || (parseFloat(isValidGen).toFixed(2) != parseFloat(total)
                                                        .toFixed(2)) || ((getProcedencia || getBancoReq) &&
                                                        actividadEconomica == 0)">
                                                    <span v-if="btnState">
                                                        Generar comprobante $@{{ parseFloat(isValidGen ?? 0).toFixed(2) }}
                                                    </span>
                                                    <span v-if="!btnState">
                                                        <div class="spinner-border text-success spinner-border-sm"
                                                            role="status">
                                                            <span class="visually-hidden">Loading...</span>
                                                        </div>
                                                        Enviando...
                                                    </span>
                                                </button>
                                                <span class="text-danger float-end" v-show="isValidGen != total">
                                                    $@{{ getFaltante }}
                                                </span>
                                                <a class="btn btn-light"
                                                    href="{{ route('cobros.create_config', ['id' => Crypt::encryptString($p->id)]) }}">
                                                    Configurar cobro
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <!-- #COMPROBANTES# -->
                <div class="col-12 col-lg-7">
                    <div class="card border border-success">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="row">
                                        <div class="col-12 fs-4 text-uppercase">
                                            {{ session('caja')->sucursales->sucursal }}
                                        </div>
                                        <div class="col-12 text-uppercase">
                                            {{ session('caja')->sucursales->giro }}
                                        </div>
                                        <div class="col-12 text-uppercase">
                                            {{ session('caja')->sucursales->direccion }}
                                        </div>
                                        <div class="col-12 text-uppercase">
                                            {{ session('caja')->sucursales->telefono }}
                                        </div>
                                        <div class="col-12">
                                            {{ session('caja')->sucursales->correo }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4 rounded-2 border-2 border-dark p-0">
                                    <div class="w-100 text-center fs-5 bg-dark overflow-hidden text-white">
                                        {{ $tipo_comprobante->tipo }}
                                    </div>
                                    <div class="p-1">
                                        <div class="fs-3 fw-bold w-100">
                                            No. <span class="text-danger float-end">{{ $correlativo->actual + 1 }}</span>
                                        </div>
                                        <small>
                                            NRC: {{ session('caja')->sucursales->nrc }}<br>
                                            NIT: {{ session('caja')->sucursales->nit }}
                                        </small>

                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 offset-7">

                                    <div class="form-check-inline float-right">
                                        <label class="form-check-label" for="pef">
                                            EF
                                        </label>
                                        <input class="form-check-input" disabled type="checkbox" value=""
                                            id="pef" :checked="getEF">
                                    </div>
                                    <div class="form-check-inline float-right">
                                        <label class="form-check-label" for="pcr">
                                            CR
                                        </label>
                                        <input class="form-check-input" disabled type="checkbox" value=""
                                            id="pcr" :checked="getCR">
                                    </div>
                                    <div class="form-check-inline float-right">
                                        <label class="form-check-label" for="ptc">
                                            TC
                                        </label>
                                        <input class="form-check-input" disabled type="checkbox" value=""
                                            id="ptc" :checked="getTC">
                                    </div>
                                    <div class="form-check-inline float-right">
                                        <label class="form-check-label" for="pck">
                                            CK
                                        </label>
                                        <input class="form-check-input" disabled type="checkbox" value=""
                                            id="pck" :checked="getCK">
                                    </div>
                                    <div class="form-check-inline float-right">
                                        <label class="form-check-label" for="pant">
                                            ANT
                                        </label>
                                        <input class="form-check-input" disabled type="checkbox" value=""
                                            id="pant" :checked="getANT">
                                    </div>

                                </div>
                                <div class="col-12 border border-1 border-dark rounded mt-2 p-2">
                                    <div class="row">
                                        <div class="col-4 col-lg-2 text-uppercase fw-bolder">
                                            Titular:
                                        </div>
                                        <div class="col-8 col-lg-10 text-uppercase">
                                            {{ $data->titular }}
                                        </div>
                                        <div class="col-4 col-lg-2 text-uppercase fw-bolder">
                                            Dirección:
                                        </div>
                                        <div class="col-8 col-lg-10 text-uppercase">
                                            {{ $data->cliente->direccion ?? '' }}
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="row mt-4 text-uppercase bg-dark text-white fw-semibold text-center rounded-top border border-1 border-dark"
                                style="font-size: 9pt;">
                                <div class="col-2 m-auto">
                                    Cant.
                                </div>
                                <div class="col-4 m-auto">
                                    Concepto
                                </div>
                                <div class="col-2 m-auto">
                                    Precio Unitario
                                </div>
                                <div class="col-2 m-auto">
                                    Exento
                                </div>
                                <div class="col-2 m-auto">
                                    Gravado
                                </div>
                            </div>
                            <div class="row border border-1 border-dark rounded-bottom" style="min-height: 220px;">
                                <div class="col-12">

                                    @if (isset($data->detalle))
                                        @foreach ($data->detalle as $detalle)
                                            @foreach ($detalle as $d)
                                                <div class="row mt-1 text-uppercase h6">
                                                    <div class="col-2 text-center">
                                                        {{ $d->cantidad }}
                                                    </div>
                                                    <div class="col-4">
                                                        {{ $d->concepto }}
                                                        @if ($d->descuento)
                                                            (-${{ $d->descuento }})
                                                        @endif

                                                    </div>
                                                    <div class="col-2 text-end">
                                                        ${{ number_format($d->gravado + $d->exento, 4) }}
                                                    </div>
                                                    <div class="col-2 text-end">
                                                        {{ $d->exento > 0 ? '$' . number_format($d->exento * $d->cantidad, 4) : '' }}
                                                    </div>
                                                    <div class="col-2 text-end">
                                                        {{ $d->gravado > 0 ? '$' . number_format($d->gravado * $d->cantidad, 4) : '' }}
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-12">
                                            SON:
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-4 text-end">
                                            SUMAS:
                                        </div>
                                        <div class="col-4 text-end border border-1 border-dark bg-dark text-white"
                                            style="border-radius: 0 0 0 12px;">
                                            ${{ number_format($data->totales->exento, 4) }}
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->gravado, 4) }}
                                        </div>
                                    </div>
                                    @if ($token == 7001)
                                        <div class="row">
                                            <div class="col-6 offset-2 text-">
                                                (+) IVA {{ env('iva', 0.13) * 100 }}%
                                            </div>
                                            <div class="col-4 text-end bg-dark text-white">
                                                ${{ number_format($data->totales->iva, 4) }}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            (+) CET {{ env('cesc', 0.05) * 100 }}%
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->cesc, 4) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            (+) AD-VALOREM {{ env('advalorem', 0.05) * 100 }}%
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->advalorem, 4) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            (+) PROPINA {{ env('propina', 0.1) * 100 }}%
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->propina, 4) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            SUB-TOTAL
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->subtotal, 4) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            VENTAS EXCENTAS
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->exento, 4) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            VENTAS NO SUJETAS
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">$0.00</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 offset-2 text-">
                                            (-) {{ env('retencion', 0.01) * 100 }}% IVA RETENIDO
                                        </div>
                                        <div class="col-4 text-end bg-dark text-white">
                                            ${{ number_format($data->totales->percepcion, 2) }}</div>
                                    </div>
                                    <div class="row p-0">
                                        <div class="col-6 offset-2 fw-bold">
                                            VENTA TOTAL
                                        </div>
                                        <div class="col-4 text-end fw-bold bg-dark text-white"
                                            style="border-radius: 0 0 12px 12px">
                                            ${{ number_format($data->totales->total, 2) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EDITAR CLIENTES -->
        <div class="modal fade" id="clienteEdit" aria-hidden="true" aria-labelledby="modalEditCliente" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalEditCliente">EDICION DE CLIENTE</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="cliente-tab" data-bs-toggle="tab"
                                data-bs-target="#cliente" type="button" role="tab" aria-controls="cliente"
                                aria-selected="true">Editar
                                cliente</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="titular-tab" data-bs-toggle="tab" data-bs-target="#titular_tab"
                                type="button" role="tab" aria-controls="titular" aria-selected="false">Editar
                                titular</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="cliente" role="tabpanel"
                            aria-labelledby="cliente-tab">
                            <form action="{{ route('comprobantes.cliente_update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cobro" value="{{ $p->cid }}">
                                <input type="hidden" name="clientes_id" :value="clientesSelected.id">

                                <div class="modal-body" style="min-height: 60vh">
                                    <div class="mb-3">
                                        <label for="titular" class="form-label">Cliente:</label>

                                        <div v-if="clientesSelected.id >= 0">
                                            <div class="mb-2">
                                                @{{ clientesSelected.nombre }} |
                                                @{{ clientesSelected.identificacion }}:
                                                @{{ clientesSelected.numero }}
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-light text-danger" type="button"
                                                    @click="clientesSelected = []">
                                                    <span class="mdi mdi-close text-danger"></span>
                                                    Cancelar seleccion
                                                </button>
                                            </div>
                                            <div class="mb-3">
                                                <label for="titularE">Titular en el comprobante</label>
                                                <input type="text" id="titularE" name="titular" class="form-control"
                                                    placeholder="Escriba un titular">
                                                <small>Escriba un titular para el comprobante solo si es requerido un nombre
                                                    diferente al del cliente. Por defecto tomara el titular <span
                                                        class="text-uppercase">@{{ clientesSelected.nombre }}</span></small>
                                            </div>
                                        </div>

                                        <div class="input-group mb-3" v-if="!(clientesSelected.id >= 0)">
                                            <input type="text" class="form-control" id="cliente" name="cliente"
                                                autocomplete="off" placeholder="Buscar cliente..." v-model="txtCliente"
                                                @keyup="getClientes">
                                            <button class="btn btn-outline-secondary" v-if="clientes.length > 0"
                                                @click="clearSearch()">
                                                Cancelar
                                            </button>
                                            <a v-else class="btn btn-outline-secondary"
                                                href="{{ route('clientes.create') }}" target="_blank"
                                                style="text-decoration: none;">
                                                Crear cliente
                                            </a>
                                        </div>

                                        <div class="result shadow"
                                            style="position: absolute;z-index: 10;margin-top: 0px; left: 1%; right: 1%; background:white; padding:2px;"
                                            v-if="clientes.length > 0 && clientesSelected.length == 0">
                                            <ul class="list-group" v-for="cliente in clientes">
                                                <li class="list-group-item d-flex justify-content-between align-items-center listCliente"
                                                    style="border-radius: 0px; border: none; cursor:pointer;"
                                                    @click="setClientesSelected(cliente)">
                                                    @{{ cliente.nombre }} - @{{ cliente.identificacion }} -
                                                    @{{ cliente.numero }}
                                                    <small>
                                                        <span v-if="cliente.tipo_cliente"
                                                            class="badge bg-primary rounded-pill">Natural</span>
                                                        <span v-else
                                                            class="badge bg-secondary rounded-pill">Juridico</span>
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary"
                                        :disabled="clientesSelected.length == 0">Guardar</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="titular_tab" role="tabpanel" aria-labelledby="titular-tab">
                            <form action="{{ route('comprobantes.cliente_update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cobro" value="{{ $p->cid }}">

                                <input type="hidden" name="clientes_id" :value="clientesSelected.id">

                                <div class="modal-body" style="min-height: 60vh">
                                    <div class="mb-3">
                                        <label for="titular">Titular en el comprobante</label>
                                        <input type="text" id="titular" name="titular" class="form-control"
                                            placeholder="Escriba un titular">
                                        <small>Escriba un titular para el comprobante solo si es requerido un nombre
                                            diferente al del cliente. Por defecto tomara el titular <span
                                                class="text-uppercase">@{{ clientesSelected.nombre }}</span></small>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ASIGNAR ANTICIPOS -->
        <div class="modal fade" id="asignarAnticipos" aria-hidden="true" aria-labelledby="asignarAnticiposModal"
            tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="asignarAnticiposModal">Asignar anticipos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert" :class="type" role="alert" v-show="message.length > 0">
                                    @{{ message }}
                                </div>
                                <div class="form-group">
                                    <label for="txtNumeroAnticipo">Buscar</label>
                                    <input type="text" class="form-control" name="txtNumeroAnticipo"
                                        id="txtNumeroAnticipo" aria-describedby="helpId"
                                        placeholder="Escriba el numero de anticipo">

                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <!-- Nav tabs -->
                                <ul class="nav nav-tabs" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="activos-tab" data-bs-toggle="tab"
                                            data-bs-target="#activos" type="button" role="tab"
                                            aria-controls="activos" aria-selected="true">Anticipos activos</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="aplicados-tab" data-bs-toggle="tab"
                                            data-bs-target="#aplicados" type="button" role="tab"
                                            aria-controls="aplicados" aria-selected="false">Anticipos Aplicados</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="reservados-tab" data-bs-toggle="tab"
                                            data-bs-target="#reservados" type="button" role="tab"
                                            aria-controls="reservados" aria-selected="false">Anticipos reservados</button>
                                    </li>

                                </ul>


                                <div class="tab-content">
                                    <div class="tab-pane active" id="activos" role="tabpanel"
                                        aria-labelledby="activos-tab">
                                        <div class="row mt-3">
                                            <div class="col-12 mb3" v-show="anticipos.length == 0 || anticipos == null">
                                                No hay anticipos activos de este cliente.
                                            </div>
                                            <div class="col-12 mb-3" v-for="a in anticipos"
                                                v-show="anticipos.length > 0">
                                                <div class="card border-success">
                                                    <div class="card-body">
                                                        <h5 class="card-title">No.@{{ a.id }} ·
                                                            $@{{ parseFloat(a.monto).toFixed(2) }}</h5>
                                                        <p class="card-text">
                                                        <div class="row" v-show="conf.length == 0 || conf != a.id">
                                                            <div class="col-12">
                                                                <span class="text-uppercase">
                                                                    @{{ a.concepto }}
                                                                </span>
                                                                <br>
                                                                Fecha de aplicacion desde: @{{ a.fecha_aplicacion }}
                                                            </div>
                                                            <div class="col-12">
                                                                <button class="btn btn-light" type="button"
                                                                    @click="setAnticipoCobro(a.cid, a.monto)"
                                                                    :disabled="!(a.monto <= getFaltante) || getAplicable(a
                                                                        .fecha_aplicacion)">
                                                                    Aplicar completo
                                                                </button>
                                                                <button class="btn btn-light" type="button"
                                                                    @click="conf = a.id"
                                                                    :disabled="getAplicable(a.fecha_aplicacion)">
                                                                    Aplicar un monto
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <div class="row"
                                                            v-show="conf != null && conf == a.id && !getAplicable(a.fecha_aplicacion)">
                                                            <anticipos-component
                                                                @cancel="cancelAnticipo"
                                                                @mensage="setAnticipo" :cobro="cobro"
                                                                :anticipo="a.cid"
                                                                :max="a.monto"
                                                                :total="getFaltante"></anticipos-component>
                                                        </div>

                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="reservados" role="tabpanel"
                                        aria-labelledby="reservados-tab">
                                        <div class="row mt-3">
                                            <div class="col-12 mb-3" v-for="b in anticiposReserva">
                                                <div class="card border-dark">
                                                    <div class="card-body">
                                                        <h5 class="card-title">
                                                            No.@{{ b.id }} · $@{{ parseFloat(b.monto).toFixed(2) }}
                                                        </h5>
                                                        <p class="card-text text-uppercase">
                                                            @can('anticipo_reservas.delete')
                                                                <button class="btn btn-light text-danger float-end h4" @click="deleteAnticipoReserva(b.cid)">
                                                                    <span class="mdi mdi-delete"></span>
                                                                </button> @endcan
                                                                @{{ b.concepto }} <br>
                                                                Fecha de aplicacion desde: @{{ b.fecha_aplicacion }}
                                                                </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane" id="aplicados" role="tabpanel"
                                            aria-labelledby="aplicados-tab">
                                            <div class="row mt-3">
                                                <div class="col-12 mb-3" v-show="anticiposAplicados.length == 0">
                                                    Aun no hay anticipos agregados.
                                                </div>
                                                <div class="col-12 mb-3" v-for="c in anticiposAplicados"
                                                    v-show="anticiposAplicados.length > 0">
                                                    <div class="card border-dark">
                                                        <div class="card-body">

                                                            <h5 class="card-title">No.@{{ c.anticipos.id }} ·
                                                                $@{{ parseFloat(c.monto).toFixed(2) }} de
                                                                $@{{ parseFloat(c.anticipos.monto_historico).toFixed(2) }}
                                                                <div class="float-end">
                                                                    <button class="btn"
                                                                        @click="eliminarAnticipo(c.cid)">
                                                                        <span class="mdi mdi-delete text-danger h3"></span>
                                                                    </button>
                                                                </div>
                                                            </h5>
                                                            <p class="card-text text-uppercase">

                                                                @{{ c.anticipos.concepto }}
                                                                <br>
                                                                Fecha de aplicacion desde: @{{ c.anticipos.fecha_aplicacion }}
                                                            </p>
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
                </div>
            </div>
        </div>
    </div>
    <script>
        Vue.component('anticipos-component', {
            props: [
                'cobro', 'anticipo', 'max', 'total'
            ],
            data: function() {
                return {
                    monto: null,
                }
            },
            methods: {
                cancel: function() {
                    this.$emit('cancel');
                },
                setAnticipoCobro() {
                    if (this.isValid)
                        axios.post("{{ route('cobros.anticipos') }}", {
                            cobro: this.cobro,
                            anticipo: this.anticipo,
                            monto: this.monto
                        }).then(r => {
                            if (r.data)
                                this.$emit('mensage', r.data);


                        })
                    else alert(
                        'Accion no es valida, generando reporte de uso inadecuado del sistema por usuario {{ Auth::user()->name }}'
                    )
                },

            },
            computed: {
                isValid() {
                    return parseFloat(this.monto) <= parseFloat(this.max) && this.monto > 0 &&
                        parseFloat(this.total) >= parseFloat(this.monto);
                }
            },
            template: `
            <div class="col-12">
                <div class="form-group mb-2">
                    <label :for="'monto-'+ anticipo">Monto</label>
                    <input :id="'monto-'+ anticipo" :name="'monto-'+ anticipo" class="form-control"
                        type="number" min="0"
                        :max="max"
                        placeholder="Escriba el monto a aplicar"
                        v-model="monto"> Faltante:$ @{{ this.total }}
                </div>
                <div class="form-group mb-2">
                    <button class="btn btn-primary"
                        @click="setAnticipoCobro()" :disabled="!isValid">
                        Guardar
                    </button>
                    <button class="btn btn-light"
                        @click="cancel()">
                        Cancelar
                    </button>
                </div>
            </div>
            `
        });
        var app = new Vue({
            el: '#appComprobantes',
            data: {
                formas_pagos: @json($formas_pagos),
                formasList: @json($formas_pagos),
                pagos: [{
                    forma_pago: "",
                    valor: 0
                }],
                total: {{ round($data->totales->total, 2) }},
                clientes: [],
                clientesSelected: [],
                txtCliente: '',
                anticipos: @json($p->clientes_id > 0 ? $p->clientes->anticipos : []),
                anticiposReserva: @json($p->clientes_id > 0 ? $p->clientes->anticipos_reservados : []),
                anticiposAplicados: @json($p->anticipos),
                conf: '',
                cobro: '{{ Crypt::encryptString($p->id) }}',
                message: '',
                type: '',
                btnState: true,
                efectivo: parseFloat({{ $efectivo ?? 0 }}),
                banco: parseFloat({{ $banco ?? 0 }}),
                limiteEfectivo: parseFloat({{ env('monto_efectivo', 10000) }}),
                limiteBanco: parseFloat({{ env('monto_banco', 25000) }}),
                actividadEconomica: {{ $p->clientes_id > 0 && $data->cliente->actividades_economicas_id > 0 ? 1 : 0 }}
            },
            methods: {
                enviarComprobante: function() {
                    this.btnState = false;
                    document.getElementById('comprobantesCreate').submit();
                },
                newPago: function() {
                    console.log(this.isValid)
                    if (this.isValid) {
                        this.pagos.push({
                            forma_pago: "",
                            valor: 0
                        })
                        this.getFormaPago()
                    }
                },
                deletePago: function(i) {
                    this.pagos = this.pagos.filter((h, j) => j != i);
                },
                deletePagoId: function(i) {
                    console.log("ID", i)
                    this.pagos[i].forma_pago = "";
                    this.pagos[i].valor = 0;
                    this.getFormaPago()
                },
                getFormaPago: function() {
                    this.formas_pagos = this.formasList.filter(
                        fp => !this.pagos.find(p => p.forma_pago.id == fp.id));
                },
                getClientes: function() {
                    if (this.txtCliente.length > 2)
                        axios.post("{{ route('clientes.api_search') }}", {
                            busqueda: (this.txtCliente).toUpperCase(),
                        })
                        .then((resp) => {
                            this.clientes = resp.data.clientes || [];
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    else this.clientes = [];
                },
                clearSearch: function() {
                    this.clientes = [];
                    this.txtCliente = '';
                },
                setClientesSelected: function(cliente) {
                    this.clientesSelected = cliente;
                    this.clearSearch();
                },
                setAnticipo: function(data) {
                    if (data.anticipos)
                        this.anticiposAplicados = data.anticipos;
                    if (data.activos)
                        this.anticipos = data.activos;
                    if (data.reserva)
                        this.anticiposReserva = data.reserva;

                    this.message = data.message;
                    this.type = 'alert-' + (data.type ?? 'success');
                    this.conf = '';
                    setTimeout(() => {
                        this.message = "";
                        this.type = "";
                    }, 6 * 1000);
                },
                setAnticipoCobro: function(anticipo, monto) {
                    if (confirm("¿Esta seguro de aplicar este anticipo?"))
                        axios.post("{{ route('cobros.anticipos') }}", {
                            cobro: this.cobro,
                            anticipo: anticipo,
                            monto: monto
                        }).then(r => {

                            if (r.data)
                                this.setAnticipo(r.data);


                        });
                },
                eliminarAnticipo: function(id) {

                    if (confirm("¿Esta seguro de eliminar este anticipo?"))
                        axios.post("{{ route('cobros.anticipos_delete') }}", {
                            id: id,
                        }).then(r => {
                            console.log(r);
                            if (r.data)
                                this.setAnticipo(r.data);


                        });
                },
                cancelAnticipo: function() {
                    this.conf = '';
                },
                deleteAnticipoReserva: function(id) {
                    if (confirm("¿Esta seguro de quitar la reserva de este anticipo?"))
                        axios.post("{{ route('cobros.anticipos_reserva_delete') }}", {
                            id: id,
                            cobro: this.cobro,
                        }).then(r => {
                            console.log(r);
                            if (r.data)
                                this.setAnticipo(r.data);
                        });
                },
                getAplicable: function(fecha) {
                    return (new Date(fecha)) >= (new Date());
                }
            },
            computed: {
                isValid() {
                    let valid = this.pagos.filter((pago, i) => pago.forma_pago.length == 0 || pago.valor
                        .length ==
                        0 || parseInt(pago.valor) == 0);
                    return valid.length == 0;
                },
                isValidGen() {
                    var t = this.pagos.reduce((acc, crr) => acc + parseFloat(crr.valor), 0);
                    t = t + this.getSumAnticipo;
                    return t > 0 ? t : 0;
                },
                getSumAnticipo() {
                    return this.anticiposAplicados.reduce((acc, crr) => acc + parseFloat(crr.monto), 0);
                },
                getPagosInValid() {

                    return this.getSumAnticipo != this.total && this.pagos.filter(r => r.forma_pago == null || r
                        .forma_pago == "").length > 0;
                },
                getFaltante() {
                    return parseFloat(this.total - this.isValidGen).toFixed(2)
                },
                getPagos() {
                    return this.pagos.filter(p => p);
                },
                getEF() {
                    return this.pagos.filter(p => p.forma_pago.token == 6001).length > 0;
                },
                getCR() {
                    return this.pagos.filter(p => p.forma_pago.token == 6002).length > 0;
                },
                getTC() {
                    return this.pagos.filter(p => p.forma_pago.token == 6003).length > 0;
                },
                getANT() {
                    return this.pagos.filter(p => p.forma_pago.token == 6004).length > 0;
                },
                getCK() {
                    return this.pagos.filter(p => p.forma_pago.token == 6005).length > 0;
                },
                sumEfectivo() {
                    let total = this.pagos
                        .filter(p => p.forma_pago.token == 6001)
                        .reduce((sum, p) => sum + (parseFloat(p.valor) || 0), 0);
                    return parseFloat((total).toFixed(2));
                },
                sumBanco() {
                    let total = this.pagos
                        .filter(p => p.forma_pago.token == 6003)
                        .reduce((sum, p) => sum + (parseFloat(p.valor) || 0), 0);
                    return parseFloat((total).toFixed(2));
                },
                getProcedencia() {
                    if (this.sumEfectivo == 0)
                        return false;
                    let total = parseFloat(this.sumEfectivo) + parseFloat(this.efectivo);
                    return total >= this.limiteEfectivo;
                },
                getBancoReq() {
                    if (this.sumBanco == 0)
                        return false;
                    let total = parseFloat(this.sumBanco) + parseFloat(this.banco);
                    return total >= this.limiteBanco;
                },
            }
        });
    </script>
@endsection
