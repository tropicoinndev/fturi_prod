@extends('layouts.app')
@section('content')
    <style>
        body {
            background: #9FA8DA;
        }

        .card-content {
            min-height: 90vh;
        }
    </style>
    <div class="container">
        <div class="card p-4 card-content" id="operacionesReguladas">
            <div class="card-body">
                <h3 class="card-title">
                    FORMULARIO DE OPERACIONES REGULADAS
                    </h2>
                    <p class="card-text">
                    <div class="row">
                        <x-message></x-message>
                        <div class="col-12 mb-3">
                            <div class="alert alert-light shadow-sm" role="alert">
                                <div class="row">
                                    <div class="col-2 fw-bolder">
                                        Nombre:
                                    </div>
                                    <div class="col-10">
                                        {{ $p->comprobante->clientes->nombre }}
                                        <div class="dropdown float-end">
                                            <button class="btn btn-light" type="button" id="triggerId"
                                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <span class="mdi mdi-dots-vertical"></span>
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="triggerId">
                                                <a class="dropdown-item" target="_blank"
                                                    href="{{ route('clientes.show', ['id' => Crypt::encryptString($p->comprobante->clientes_id)]) }}">
                                                    <span class="mdi mdi-account-check h5"></span>
                                                    Cliente
                                                </a>
                                                <a class="dropdown-item" target="_blank"
                                                    href="{{ route('comprobantes.detalles', ['id' => $p->comprobante->cid]) }}">
                                                    <span class="mdi mdi-file-check h5"></span>
                                                    Comprobante
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-2 fw-bolder">
                                        Comprobante:
                                    </div>
                                    <div class="col-10">
                                        Nº {{ $p->comprobante->correlativo }}
                                        @if ($p->comprobante->dteOne != null)
                                            <strong>
                                                Codigo de generacion:
                                            </strong>
                                            {{ $p?->comprobante->dteOne?->codigo_generacion }}
                                        @endif
                                    </div>

                                    @if ($p->forma_pagos_id > 0)
                                        <div class="col-2 fw-bolder">
                                            Forma de pago regulada:
                                        </div>
                                        <div class="col-10">
                                            {{ $p->pago->forma }} · SUMATORIA: ${{ number_format($p->monto_sumatoria, 2) }}
                                            <span class="mdi mdi-information-variant-circle h5"
                                                title="El monto de sumatoria aplica solamente para este comprobante, en el caso que hayan comprobantes posteriores a este se tomaran en cuenta en otros formularios de operaciones reguladas"></span>

                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$p->completado)
                            @if ($p->forma_pagos_id == null)
                                <div class="alert alert-warning" role="alert">
                                    <h4 class="alert-heading">
                                        <span class="mdi mdi-alert-circle-outline"></span>
                                        Antes de continuar
                                    </h4>
                                    <p>
                                        {{ $p->comprobante->pagos->count() > 1 ? 'Confirme cual de las forma de pago a continuación fue la utilizada para este comprobante' : 'Confirme que la forma de pago a continuación fue la utilizada para este comprobante' }}
                                        (Presione sobre la forma de pago regulada, confirme que no se agrego por
                                        equivocación)
                                        @foreach ($p->comprobante->pagos as $pago)
                                            <br>
                                            <a class="btn btn-light"
                                                href="{{ route('operaciones_reguladas.configurar', ['id' => $p->cid, 'forma_pago' => $pago->forma_pagos->cid]) }}"
                                                role="button">
                                                {{ $pago->forma_pagos->forma }} ${{ $pago->monto }}
                                            </a>
                                        @endforeach
                                    </p>

                                </div>
                            @else
                                <div class="col-12 text-uppercase mb-2">
                                    Parte I - Personas involucradas en la transacción
                                </div>


                                <div class="accordion" id="secciones">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="flush-headingOne">
                                            <button class="accordion-button collapsed text-uppercase" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#seccionA" aria-expanded="true"
                                                aria-controls="flush-collapseOne">
                                                Sección A: Persona que realiza la transacción.
                                            </button>

                                        </h2>

                                        <div id="seccionA"
                                            class="accordion-collapse collapse {{ $p->distinto === null ? 'show' : '' }}"
                                            aria-labelledby="flush-headingOne" data-bs-parent="#secciones">
                                            <div class="accordion-body">
                                                @if ($p->distinto === null)
                                                    <form action="{{ route('operaciones_reguladas.seccionA') }}"
                                                        method="post">
                                                        @csrf
                                                        <input type="hidden" name="operacion" value="{{ $p->cid }}">
                                                        <div class="row mb-4">
                                                            <div class="col-12">
                                                                <div class="mb-2">Distinta al cliente:</div>

                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                        id="si" value="1" name="distinto"
                                                                        v-model="distinto">
                                                                    <label class="form-check-label"
                                                                        for="si">Si</label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input" type="radio"
                                                                        id="no" value="0" name="distinto"
                                                                        v-model="distinto">
                                                                    <label class="form-check-label"
                                                                        for="no">No</label>
                                                                </div>
                                                                <div class="mt-1">
                                                                    <small>
                                                                        Debe seleccionarse Si: cuando la persona que realiza
                                                                        la
                                                                        transacción es un tercero (esposa, familiar, amigos,
                                                                        o
                                                                        empleados)
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-4">
                                                            <div class="mb-1 h5">
                                                                Persona natural
                                                            </div>
                                                            <div class="mb-4">
                                                                <small>
                                                                    Seleccione una persona natural, en el caso de no existir
                                                                    ninguna
                                                                    para este cliente agregue una antes.
                                                                </small>
                                                            </div>
                                                            <div class="col-4">
                                                                <button type="button"
                                                                    class="card btn btn-light w-100 h-100"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#personaNaturalModal">
                                                                    <div class="card-body">
                                                                        <h4 class="card-title">
                                                                            <span class="mdi mdi-plus"></span> Agregar
                                                                            persona
                                                                            natural
                                                                        </h4>
                                                                    </div>
                                                                </button>
                                                            </div>
                                                            @foreach ($personas as $ps)
                                                                <div class="col-4">
                                                                    <input type="radio" class="btn-check"
                                                                        name="a_personas_naturales_id"
                                                                        id="persona_{{ $ps->personas_naturales_id }}"
                                                                        value="{{ $ps->personas_naturales_id }}"
                                                                        autocomplete="off" v-model="apersonan">
                                                                    <label class="card text-start btn btn-outline-primary"
                                                                        for="persona_{{ $ps->personas_naturales_id }}">
                                                                        <div class="card-body">
                                                                            <h4 class="card-title">
                                                                                {{ $ps->persona->nombres }}
                                                                                {{ $ps->persona->apellidos }}</h4>
                                                                            <p class="card-text">
                                                                                {{ $ps->persona->identificacion }}
                                                                            </p>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <button type="submit" class="btn btn-primary"
                                                                    :disabled="apersonan == null">
                                                                    Guardar cambios
                                                                </button>

                                                            </div>
                                                        </div>
                                                    </form>
                                                @elseif ($p->distinto !== null)
                                                    <div class="row">
                                                        <div class="col-2 text-uppercase">
                                                            APELLIDOS:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p?->apersona?->apellidos }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Nombres:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p?->apersona?->nombre }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Lugar de nacimiento:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p?->apersona?->nacimiento }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Departamentos:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p?->apersona?->departamentos?->departamento }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Fecha de nacimiento:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p?->apersona?->fecha_nacimiento }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Nacionalidad:
                                                        </div>
                                                        <div class="col-10">
                                                            @if ($p->apersona?->departamentos_id != null)
                                                                {{ $p->apersona?->departamentos?->paises?->nacionalidad }}
                                                            @elseif ($p->apersona?->paises_id != null)
                                                                {{ $p->apersona?->paises?->nacionalidad }}
                                                            @else
                                                                No se agrego departamento o país.
                                                            @endif
                                                        </div>

                                                        <div class="col-2 text-uppercase">
                                                            Estado civil:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p->apersona?->estado_civil }}
                                                        </div>

                                                        <div class="col-2 text-uppercase">
                                                            Tipo de documento:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p->apersona?->identificaciones?->identificacion }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Nº de documento:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p->apersona?->identificacion }}
                                                        </div>
                                                        <div class="col-2 text-uppercase">
                                                            Domicilios:
                                                        </div>
                                                        <div class="col-10">
                                                            {{ $p->apersona?->domicilio }}
                                                        </div>
                                                        <div class="col-12  mt-4">
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input" type="checkbox"
                                                                    value="1" id="editarA" v-model="editarA">
                                                                <label class="form-check-label" for="editarA">
                                                                    Confirmo que quiero borrar la información agregada y
                                                                    editarla según la información correspondiente.
                                                                </label>
                                                            </div>
                                                            <a class="btn btn-warning " :class="{ 'disabled': !editarA }"
                                                                href="{{ route('operaciones_reguladas.deleteSeccionA', ['id' => $p->cid]) }}"
                                                                role="button">Editar</a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        La transacción fue realizada por la misma persona.
                                                    </div>
                                                    <div class="col-12  mt-4">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="1" id="editarA" v-model="editarA">
                                                            <label class="form-check-label" for="editarA">
                                                                Confirmo que quiero borrar la información agregada y
                                                                editarla según la información correspondiente.
                                                            </label>
                                                        </div>
                                                        <a class="btn btn-warning " :class="{ 'disabled': !editarA }"
                                                            href="{{ route('operaciones_reguladas.deleteSeccionA', ['id' => $p->cid]) }}"
                                                            role="button">Editar</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($p->distinto !== null)
                                            <h2 class="accordion-header" id="flush-headingOne">

                                                <button class="accordion-button collapsed text-uppercase" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#seccionB"
                                                    aria-expanded="true" aria-controls="flush-collapseOne">
                                                    Sección B: Persona o personas a cuyo nombre se realiza la transacción.
                                                </button>
                                            </h2>
                                            <div id="seccionB"
                                                class="accordion-collapse collapse {{ $p->distinto !== null && $p->tipo_persona == null && $p->seccion_b_persona_id == null && $p->seccion_b_juridico_id == null ? 'show' : '' }}"
                                                aria-labelledby="flush-headingOne" data-bs-parent="#secciones">
                                                <div class="accordion-body">
                                                    @if ($p->tipo_persona == null && $p->seccion_b_persona_id == null && $p->seccion_b_juridico_id == null)
                                                        <form action="{{ route('operaciones_reguladas.seccionB') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="operacion"
                                                                value="{{ $p->cid }}">
                                                            <div class="row mb-2">
                                                                <div class="col-12">
                                                                    <div class="mb-2">Tipo de persona:</div>

                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="n" value="1"
                                                                            name="tipo_persona" v-model='tipo_persona'>
                                                                        <label class="form-check-label" for="n">
                                                                            Persona Natural
                                                                        </label>
                                                                    </div>

                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="j" value="0"
                                                                            name="tipo_persona" v-model='tipo_persona'
                                                                            :disabled="{{ $p->comprobante->clientes->tipo_cliente }}">
                                                                        <label class="form-check-label" for="j">
                                                                            Jurídica
                                                                        </label>
                                                                    </div>
                                                                    <div class="form-check form-check-inline">
                                                                        <input class="form-check-input" type="radio"
                                                                            id="m" value="2"
                                                                            name="tipo_persona" v-model='tipo_persona'
                                                                            :disabled="{{ $p->comprobante->clientes->tipo_cliente }}">
                                                                        <label class="form-check-label" for="m">
                                                                            Misma persona Sección A
                                                                        </label>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <!--
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            *** Persona natural sección B
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            -->
                                                            <div class="row mb-4"
                                                                v-show="tipo_persona !=null && tipo_persona == 1">
                                                                <div class="mb-1 h5">
                                                                    B-1 Persona natural
                                                                </div>
                                                                <div class="mb-4">
                                                                    <small>
                                                                        Seleccione una persona natural, en el caso de no
                                                                        existir
                                                                        ninguna
                                                                        para este cliente agregue una antes.
                                                                    </small>
                                                                </div>
                                                                <div class="col-4">
                                                                    <button type="button"
                                                                        class="card btn btn-light w-100 h-100"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#personaNaturalModal">
                                                                        <div class="card-body">
                                                                            <h4 class="card-title">
                                                                                <span class="mdi mdi-plus"></span>
                                                                                Agregar persona natural
                                                                            </h4>
                                                                        </div>
                                                                    </button>
                                                                </div>
                                                                @foreach ($personas as $ps)
                                                                    <div class="col-4">
                                                                        <input type="radio" class="btn-check"
                                                                            name="b_personas_naturales_id"
                                                                            id="bpersona_{{ $ps->personas_naturales_id }}"
                                                                            value="{{ $ps->personas_naturales_id }}"
                                                                            autocomplete="off" v-model="bpersonan">
                                                                        <label
                                                                            class="card text-start btn btn-outline-primary"
                                                                            for="bpersona_{{ $ps->personas_naturales_id }}">
                                                                            <div class="card-body">
                                                                                <h4 class="card-title">
                                                                                    {{ $ps->persona->nombres }}
                                                                                    {{ $ps->persona->apellidos }}
                                                                                </h4>
                                                                                <p class="card-text">
                                                                                    {{ $ps->persona->identificacion }}
                                                                                </p>
                                                                            </div>
                                                                        </label>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <!--
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            *** Persona jurídica sección B
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            -->
                                                            <div class="row mb-4"
                                                                v-show="tipo_persona != null && tipo_persona == 0">
                                                                <div class="col-4">
                                                                    <input type="radio" class="btn-check"
                                                                        name="b_personas_juridica_id" id="bjuridico"
                                                                        value="{{ $p->comprobante->clientes_id }}"
                                                                        autocomplete="off" v-model="bjuridico" checked>
                                                                    <label class="card text-start btn btn-outline-primary"
                                                                        for="">
                                                                        <div class="card-body">
                                                                            <h4 class="card-title">
                                                                                {{ $p->comprobante->clientes->nombre }}
                                                                            </h4>
                                                                        </div>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="row mt-4">
                                                                <div class="col-12">
                                                                    <button type="submit" class="btn btn-primary"
                                                                        :disabled="(tipo_cliente == 1 && bpersonan == null) ||
                                                                        (tipo_cliente == 0 && bjuridico == null)">
                                                                        Guardar cambios
                                                                    </button>

                                                                </div>
                                                            </div>

                                                        </form>
                                                    @else
                                                        @if ($p->tipo_persona == 1)
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h5>PERSONA NATURAL</h5>
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    APELLIDOS:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bpersona?->apellidos }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Nombres:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bpersona?->nombre }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Lugar de nacimiento:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bpersona?->nacimiento }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Departamentos:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bpersona?->departamentos?->departamento }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Fecha de nacimiento:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bpersona?->fecha_nacimiento }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Nacionalidad:
                                                                </div>
                                                                <div class="col-10">
                                                                    @if ($p->bpersona?->departamentos_id != null)
                                                                        {{ $p->bpersona?->departamentos?->paises?->nacionalidad }}
                                                                    @elseif ($p->bpersona?->paises_id != null)
                                                                        {{ $p->bpersona?->paises?->nacionalidad }}
                                                                    @else
                                                                        No se agrego departamento o país.
                                                                    @endif
                                                                </div>

                                                                <div class="col-2 text-uppercase">
                                                                    Estado civil:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p->bpersona?->estado_civil }}
                                                                </div>

                                                                <div class="col-2 text-uppercase">
                                                                    Tipo de documento:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p->bpersona?->identificaciones?->identificacion }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Nº de documento:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p->bpersona?->identificacion }}
                                                                </div>
                                                                <div class="col-2 text-uppercase">
                                                                    Domicilios:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p->bpersona?->domicilio }}
                                                                </div>
                                                            </div>
                                                        @elseif($p->tipo_persona == 0)
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <h5>PERSONA JURÍDICA</h5>
                                                                </div>
                                                                <div class="col-2">
                                                                    Nombre o Razón Social:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bjuridico?->nombre }}
                                                                </div>
                                                                <div class="col-2">
                                                                    Dirección Comercial:
                                                                </div>
                                                                <div class="col-10">
                                                                    {{ $p?->bjuridico?->direccion }}
                                                                </div>
                                                                <div class="col-2">
                                                                    Actividad Económica:
                                                                </div>
                                                                <div class="col-10">
                                                                    @if ($p->bjuridico?->actividades?->actividad)
                                                                        {{ $p->bjuridico?->actividades?->codigo }} -
                                                                        {{ $p->bjuridico?->actividades?->actividad }}
                                                                    @else
                                                                        Sin registro de actividad económica
                                                                    @endif
                                                                </div>
                                                                <div class="col-2">
                                                                    Identificación Tributario:
                                                                </div>
                                                                <div class="col-10">
                                                                    <ul>
                                                                        <li>
                                                                            <b>NRC:</b>
                                                                            {{ $p->bjuridico?->detalle->nrc }}
                                                                        </li>
                                                                        @if ($p->bjuridico?->identificaciones)
                                                                            @foreach ($p->bjuridico?->identificaciones as $i)
                                                                                <li>
                                                                                    <b>
                                                                                        {{ $i->identificaciones->identificacion }}:
                                                                                    </b>
                                                                                    {{ $i->numero }}
                                                                                </li>
                                                                            @endforeach
                                                                        @else
                                                                            <li>No se encontraron mas identificaciones</li>
                                                                        @endif
                                                                    </ul>
                                                                </div>

                                                            </div>
                                                        @else
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    Misma persona de la Sección A
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="row">
                                                            <div class="col-12  mt-4">
                                                                <div class="form-check mb-2">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        value="1" id="editarB" v-model="editarB">
                                                                    <label class="form-check-label" for="editarB">
                                                                        Confirmo que quiero borrar la información agregada y
                                                                        editarla según la información correspondiente.
                                                                    </label>
                                                                </div>
                                                                <a class="btn btn-warning "
                                                                    :class="{ 'disabled': !editarB }"
                                                                    href="{{ route('operaciones_reguladas.deleteSeccionB', ['id' => $p->cid]) }}"
                                                                    role="button">Editar</a>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @if ($p->distinto !== null && isset($p->tipo_persona))
                                            <h2 class="accordion-header" id="flush-headingOne">

                                                <button class="accordion-button collapsed text-uppercase" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#parte2"
                                                    aria-expanded="true" aria-controls="flush-collapseOne">
                                                    PARTE II - DETALLE DE LA TRANSACCION EN EFECTIVO U OTRO MEDIO
                                                </button>
                                            </h2>
                                            <div id="parte2"
                                                class="accordion-collapse collapse {{ $p->distinto !== null && isset($p->tipo_persona) ? 'show' : '' }}"
                                                aria-labelledby="flush-headingOne" data-bs-parent="#secciones">
                                                <div class="accordion-body">
                                                    <div class="edicion" v-if="editarII">
                                                        <form action="{{ route('operaciones_reguladas.parteII') }}"
                                                            method="post">
                                                            @csrf
                                                            <input type="hidden" name="operacion"
                                                                value="{{ $p->cid }}">
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="mb-3">
                                                                        <label for="clase_servicio" class="form-label">
                                                                            Clase de servicio
                                                                        </label>
                                                                        <textarea class="form-control" name="clase_servicio" id="clase_servicio" rows="2"
                                                                            placeholder="Escriba la clase de servicio en este comprobante" required>{{ $p->clase_servicio }}</textarea>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="observaciones" class="form-label">
                                                                            Observaciones de la transacción:
                                                                        </label>
                                                                        <textarea class="form-control" name="observaciones" id="observaciones" rows="3"
                                                                            placeholder="Escriba las observaciones sobre esta transacción">{{ $p->observaciones }}</textarea>
                                                                    </div>
                                                                    <div class="mb-2 fw-bold text-uppercase">
                                                                        Confirmar información
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="efectivo" class="form-label">
                                                                            Valor en efectivo
                                                                        </label>
                                                                        <input type="number" min="0"
                                                                            step="0.01" class="form-control"
                                                                            name="efectivo" id="efectivo"
                                                                            placeholder="Escriba el valor"
                                                                            value="{{ $p->efectivo }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="tarjeta" class="form-label">
                                                                            Valor en tarjetas de débito / crédito
                                                                        </label>
                                                                        <input type="number" min="0"
                                                                            step="0.01" class="form-control"
                                                                            name="tarjeta" id="tarjeta"
                                                                            placeholder="Escriba el valor"
                                                                            value="{{ $p->tarjeta }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="cheque" class="form-label">
                                                                            Valor en cheques
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            name="cheque" id="cheque"
                                                                            placeholder="Escriba el valor"
                                                                            value="{{ $p->cheque }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="procedencia" class="form-label">
                                                                            Procedencia del efectivo (Requerido solo para
                                                                            efectivo)
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            name="procedencia" id="procedencia"
                                                                            placeholder="Escriba el valor"
                                                                            value="{{ $p->comprobante->procedencia }}"
                                                                            :disabled="!eprocedencia">

                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1"
                                                                                id="eprocedencia" name="eprocedencia"
                                                                                v-model="eprocedencia">
                                                                            <label class="form-check-label"
                                                                                for="eprocedencia">
                                                                                Editar procedencia
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-4">
                                                                        <label for="cargo" class="form-label">
                                                                            Cargo del empleado (Agregue el cargo de la
                                                                            persona
                                                                            que
                                                                            realizo el comprobante y recibió las divisas)
                                                                        </label>
                                                                        <input type="text" class="form-control"
                                                                            name="cargo" id="cargo"
                                                                            placeholder="Escriba el valor"
                                                                            value="RECEPCIONISTA/CAJERO">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox" value="1"
                                                                                name="confirmp2" v-model="confirmp2"
                                                                                id="confirmP2">
                                                                            <label class="form-check-label"
                                                                                for="confirmP2">
                                                                                Confirmo que he revisado toda la información
                                                                                y
                                                                                esta
                                                                                de acuerdo a la transacción
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <button class="btn btn-primary" type="submit"
                                                                            :disabled="!confirmp2">
                                                                            Guardar cambios
                                                                        </button>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    <!--Información de parte II-->
                                                    <div class="row" v-if="!editarII">
                                                        <div class="row">
                                                            <div class="col-2">
                                                                Punto de servicio:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                Punto de venta:{{ $p->caja->caja }} ·
                                                                Sucursal: {{ $p->caja->sucursales->sucursal }}
                                                            </div>
                                                            <div class="col-2">
                                                                Municipio:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->caja->sucursales->municipios->municipio }}
                                                            </div>
                                                            <div class="col-2">
                                                                Departamento:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->caja->sucursales->municipios->departamentos->departamento }}
                                                            </div>
                                                            <div class="col-2">
                                                                Nº comprobante:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                Correlativo Interno: {{ $p->comprobante->correlativo }} ·
                                                                Codigo de generacion:
                                                                {{ $p->comprobante?->dteOne?->codigo_generacion ?? 'SIN DTE' }}
                                                            </div>
                                                            <div class="col-2">
                                                                Clase de servicio:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->clase_servicio }}
                                                            </div>

                                                            <div class="col-2">
                                                                Observación de transacción:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->observaciones }}
                                                            </div>
                                                            <div class="col-2">
                                                                Monto de la transacción:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                ${{ number_format($p->comprobante->total, 2) }}
                                                            </div>
                                                            <div class="col-2">
                                                                Valor en efectivo:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                ${{ number_format($p->efectivo, 2) }}
                                                            </div>
                                                            <div class="col-2">
                                                                Valor en cheque:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                ${{ number_format($p->cheque, 2) }}
                                                            </div>
                                                            <div class="col-2">
                                                                Valor en tarjeta:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                ${{ number_format($p->tarjeta, 2) }}
                                                            </div>
                                                            <div class="col-2">
                                                                Procedencia del efectivo:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->comprobante?->procedencia ?? ($p->comprobante?->banco ?? 'Sin información agregada') }}
                                                            </div>
                                                            <div class="col-2">
                                                                Fecha de transacción:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->comprobante?->fecha }}
                                                            </div>
                                                            <div class="col-2">
                                                                Cargo del empleado:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->cargo }}
                                                            </div>
                                                            <div class="col-2">
                                                                Nombre del empleado:
                                                            </div>
                                                            <div class="col-10 text-uppercase">
                                                                {{ $p->comprobante->users->name }}
                                                            </div>
                                                            <div class="col-12 mt-4">
                                                                <button class="btn btn-warning" type="button"
                                                                    @click="editarII = 1">
                                                                    Habilitar edición
                                                                </button>
                                                                <a class="btn btn-primary ms-1"
                                                                    href="{{ route('operaciones_reguladas.completar', ['id' => $p->cid]) }}"
                                                                    @click="editarII = 1">
                                                                    Completar
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            @endif
                        @else
                            <div class="alert alert-success" role="alert">
                                <strong>Formulario completado</strong>
                            </div>
                        @endif
                    </div>
                    </p>
            </div>
        </div>
    </div>
    <div class="modal fade" id="personaNaturalModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
        role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-uppercase" id="modalTitleId">
                        Agregar persona natural
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <x-personas_naturales-form :cliente="$p?->comprobante?->clientes_id"></x-personas_naturales-form>
                </div>

            </div>
        </div>
    </div>

    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    distinto: 0,
                    apersonan: null,
                    tipo_persona: '{{ $p->comprobante->clientes->tipo_cliente ? 1 : 0 }}',
                    bpersona: null,
                    bjuridico: null,
                    eprocedencia: false,
                    confirmp2: false,
                    editarA: false,
                    editarB: false,
                    editarII: {{ $p->distinto !== null && $p->tipo_persona !== null && $p->clase_servicio == null ? 1 : 0 }},
                }
            },
        });

        app.mount("#operacionesReguladas");
    </script>
@endsection
