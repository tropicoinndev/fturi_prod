@extends('layouts.hab')

@section('content-hab')
    <style>
        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }

        input:checked+label {
            background: #4DB6AC;
            color: #FFFFFF !important;
        }

        .panel {
            position: fixed;
            top: 7.5%;
            right: 4px;
            width: 260px;
            height: auto;
            z-index: 100;
            background: #B2DFDB;
            bottom: 16px;
            overflow-y: auto;
        }

        .btnPanel {
            position: fixed;
            bottom: 1%;
            right: 2%;
            width: 62px;
            height: 62px;
            border-radius: 50%;
            z-index: 100;
        }

        .habitacionCard {
            background: #F5F5F5;
        }

        .slide-fade-enter-active {
            transition: all 0.5s ease-in-out;

        }

        .slide-fade-leave-active {
            transition: all 0.3s ease-in-out;
        }

        .slide-fade-enter-from,
        .slide-fade-leave-to {
            transform: translateX(20px);
            opacity: 0;
        }

        body {
            background: #E0F2F1;
        }

        body::-webkit-scrollbar {
            width: 8px;

        }

        body::-webkit-scrollbar-thumb {
            background-color: #ababab;
            border-radius: 6px;
        }

        body::-webkit-scrollbar-thumb:hover {
            background-color: #9f9f9f;

        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #ababab #f5f5f5;
        }

        *:hover {
            scrollbar-color: #9f9f9f #f5f5f5;
        }

        .panel::-webkit-scrollbar {
            width: 8px;

        }

        .panel::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .panel::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }
    </style>
    <!-- cSpell:ignore chck, endcan, endphp, relacion, csrf  -->
    <!-- cSpell:ignoreRegExp /\{\{\s*([\s\S]*?)\s*\}\}/g -->
    <!-- cSpell:ignoreRegExp /\s*=\s*["'`](?:(?:(?!['"]).)*)["'`]/g -->

    @if (!$p->completa)
        <div id="appReservaciones" class="container">
            <!--Panel izquierdo-->
            <Transition name="slide-fade">
                <div class="panel rounded-3 p-3" v-show="showDetails">

                    <div class="col-12 fs-4  text-uppercase mb-3">
                        <button class="btn" @click="setShowDetailsBtn(0)">
                            <span class="mdi mdi-chevron-left"></span>
                        </button>
                        Reservación
                    </div>
                    <div class="col-12 h5">
                        Sucursal
                    </div>
                    <ul class="nav nav-pills flex-column mb-3">
                        <ul class="list-group">
                            <a class="list-group-item list-group-item-action {{ !session('sucursal') ? 'active' : '' }}"
                                href="{{ route('recepciones.sucursal_clear') }}">
                                Todas
                            </a>
                            @foreach ($sucursales as $s)
                                <a class="list-group-item list-group-item-action {{ session('sucursal') && session('sucursal')->id == $s->id ? 'active' : '' }}"
                                    href="{{ route('recepciones.sucursal', ['id' => Crypt::encryptString($s->id)]) }}">
                                    {{ $s->sucursal }}
                                </a>
                            @endforeach
                        </ul>
                    </ul>
                    <div class="col-12 mb-3">
                        <b class="text-muted">CLIENTE / TITULAR:</b>
                        <div class="text-uppercase">
                            {{ $p->clientes_id != null ? $p->relacionClientes->nombre : $p->titular }}
                        </div>
                    </div>
                    @if (count($detalleReservas) > 0)
                        <div class="col-12 mb-3">
                            <a href="{{ route('reservaciones.completa', ['id' => \Crypt::encryptString($p->id)]) }}"
                                class="btn btn-light">
                                <span class="mdi mdi-check "></span>
                                Completar reservación
                            </a>
                        </div>
                    @endif

                    <div class="col-12 fw-bolder">Opciones de anticipos</div>
                    @if ($p->clientes_id == null)
                        <div class="col-12 mb-2">
                            No se pueden agregar anticipos, porque no esta registrado como cliente.
                        </div>
                    @endif
                    @can('anticipos.create')
                        @if ($p->clientes_id > 0)
                            <div class="col-12 mb-2">
                                @php
                                    $totalAnticipo = 0;
                                    if ($p->anticipos != null) {
                                        foreach ($p->anticipos as $a) {
                                            $totalAnticipo += $a->anticipos->monto;
                                        }
                                    }
                                @endphp

                                <b class="text-muted">Anticipos:</b> ${{ number_format($totalAnticipo, 2) }}
                            </div>

                            <div class="col-12 mb-4">
                                @if ($p->anticipos != null && count($p->anticipos) > 0)
                                    <button class="btn btn-sm btn-light"
                                        @click='detalleAnticipos = @json($p->anticipos)' type="button"
                                        data-bs-toggle="modal" data-bs-target="#detalleAnticipos">
                                        <span class="mdi mdi-cash-clock"></span> {{ count($p->anticipos) }}
                                        {{ count($p->anticipos) > 1 ? 'anticipos' : 'anticipo' }}
                                    </button>
                                @endif
                                <button class="btn btn-sm btn-light" type="button" data-bs-toggle="modal"
                                    data-bs-target="#modalAnticipos"><span class="mdi mdi-plus"></span> Agregar
                                    anticipo</button>


                            </div>
                        @endif
                    @endcan
                    <div class="col-12">
                        <p class="fw-bold text-uppercase">
                            Habitaciones:
                            <span class="badge rounded-pill text-bg-light float-end">
                                @{{ arrayDetalleReservas.length }}
                            </span>
                        </p>
                    </div>

                    <div class="col-12" style="display: block; overflow-y: auto;">
                        <div class="alert alert-light" role="alert" v-if="arrayDetalleReservas.length == 0">
                            Aquí aparecerá el detalle de las reservaciones.
                        </div>
                        <div v-for="(dr, index) in arrayDetalleReservas" class="card mb-3 col-12" style="width: 100%;">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    Habitación @{{ dr.relacion_habitaciones.numero_habitacion }}
                                </h6>
                                <div class="card-text">
                                    <div class="mb-1 d-flex justify-content-between">
                                        <small>Se esperan @{{ dr.cantidad_personas }} @{{ dr.cantidad_personas > 1 ? 'personas' : 'persona' }} </small>
                                    </div>
                                    <div class="mb-1 d-flex justify-content-between">
                                        <span class="font-monospace">Del</span>
                                        <small class="font-monospace">@{{ dr.fecha_ingreso }}</small>

                                        <span class="font-monospace">al</span>
                                        <small class="font-monospace">@{{ dr.fecha_salida }}</small>

                                    </div>
                                </div>

                                <div class="mb-1">
                                    <small class="">
                                        Tarifa: $@{{ parseFloat(dr.relacion_tarifas.precio).toFixed(2) }} · @{{ dr.relacion_tarifas.tarifa }}
                                    </small>
                                </div>

                                {{--Botones de acciones--}}
                                <div class="row">
                                    <div class="col-12 d-flex justify-content-between">
                                        <span style="cursor: pointer;" class="text-info" data-bs-toggle="modal" data-bs-target="#showDetails" @click="detalleReservasSelect = dr">
                                            Detalles
                                        </span>
                                        <span>
                                            <div style="margin-top: -3px;" class="dropdown">
                                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="mdi mdi-pencil"></span> Editar
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" :href="'/reservaciones/edicion/tarifas/' + dr.cid">Editar tarifa</a></li>
                                                    <li><a class="dropdown-item" :href="'/reservaciones/edicion/descripcion/' + dr.cid">Editar observación</a></li>
                                                </ul>
                                            </div>
                                        </span>
                                        <span style="cursor: pointer;" class="text-danger" data-bs-toggle="modal" data-bs-target="#modalEliminar" @click="eliminarHabReservada(dr.id, dr.relacion_habitaciones.numero_habitacion)">
                                            Eliminar
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </Transition>

            <!--Panel botón izquierdo-->
            <Transition name="slide-fade">
                <button class="btn btn-primary btnPanel shadow" @click="setShowDetailsBtn(1)" v-show="!showDetails">
                    <span class="mdi mdi-bookmark-check-outline h1"></span>
                </button>
            </Transition>
            <div class="row justify-content-center">

                <!--Controles de búsqueda de disponibilidad-->
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-center">Crear reservación</h5>
                            <h6 class="card-subtitle mb-4 text-muted text-center">Seleccione la fecha de ingreso y salida,
                                posteriormente seleccione las habitaciones disponibles</h6>
                            <div class="col-12">
                                <!--Mensaje de alerta fixed-->
                                <div class="alert show" :class="'alert-' + message.type"
                                    v-show="message.message && message.type">
                                    <strong>@{{ message.message }}</strong>
                                </div>
                                <x-message></x-message>
                            </div>
                            <div class="col-10 m-auto">
                                <Transition name="slide-fade">
                                    <div class="row" v-show="controls">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="fecha_ingreso" class="form-label" data-bs-toggle="tooltip"
                                                    data-bs-title="Default tooltip">Fecha de ingreso:</label>
                                                <input type="date" class="form-control" id="fecha_ingreso"
                                                    v-model="arrayForm.fecha_ingreso" :min="getMinDate()"
                                                    @change="apiReservasDisponibles">

                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label for="fecha_salida" class="form-label">Fecha de salida:</label>
                                                <input type="date" class="form-control" id="fecha_salida"
                                                    v-model="arrayForm.fecha_salida" :min="getMinDateOut()"
                                                    @change="apiReservasDisponibles">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label" for="personas">Numero de personas</label>
                                            <input type="number" class="form-control" id="personas"
                                                v-model="arrayForm.cantidad_personas">
                                        </div>
                                        <div class="col-6 d-flex align-content-end flex-wrap">
                                            <div class="btn-group" role="group">
                                                <input type="checkbox" class="btn-check" id="chckMismaFecha"
                                                    v-model="chckMismaFecha" :checked="chckMismaFecha"
                                                    :value="true">
                                                <label class="btn btn-outline-primary" for="chckMismaFecha">
                                                    Usar misma fecha para las reservas
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-12 text-center mt-3">
                                            <button class="btn btn-light btn-sm" type="button" role="button"
                                                @click="setControlBtn(0)">
                                                Ocultar
                                                <span class="mdi mdi-chevron-up"></span>
                                            </button>
                                        </div>
                                        @{{ getShowControls }}

                                    </div>


                                </Transition>
                                <Transition name="slide-fade">
                                    <div class="row" v-show="!controls">
                                        <div class="col-12 text-center">
                                            <button class="btn btn-light" type="button" role="button"
                                                @click="setControlBtn(1)">
                                                Mostrar
                                                <span class="mdi mdi-chevron-down"></span>
                                            </button>
                                        </div>
                                    </div>
                                </Transition>
                            </div>
                        </div>
                    </div>
                </div>


                <!--Card inferior-->
                <div class="col-12">
                    <div class="card mb-3" style="min-height: 65vh">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">Habitaciones Disponibles</h5>
                            <!--Tipos de filtrados-->
                            <div class="row">
                                <!--Caja de búsqueda-->
                                <div class="col-12 mb-3">
                                    <input type="text" class="form-control form-control-sm"
                                        placeholder="Ingrese el nº de habitación a buscar..." id='BuscarHabitacionNum'
                                        v-model="txtBusqueda">
                                </div>
                                <div class="col-12 mb-3">
                                    <button class="btn btn-sm btn-outline-secondary"
                                        @click="filterForma = filterTipo = []">Todos</button>
                                    <span class="ms-1" v-for="ft in tipo_habitaciones" :key="'tipo' + ft.id">
                                        <input type="checkbox" class="btn-check" :id="`btnTipo-${ft.id}`"
                                            name="filterTipo" :value="ft.id" autocomplete="off"
                                            v-model='filterTipo' multiple>
                                        <label class="btn btn-sm btn-outline-secondary" :for="`btnTipo-${ft.id}`">
                                            @{{ ft.tipo_habitacion }} <span
                                                class="badge rounded-pill text-bg-light">@{{ getTipoLength(ft.id) }}</span>

                                        </label>
                                    </span>
                                </div>
                            </div>

                            <!--For habitaciones disponibles-->
                            <div class="row">
                                <div class="col-12"
                                    v-if="(this.arrayForm.fecha_ingreso != '' && this.arrayForm.fecha_salida != '')
                        && (this.arrayForm.fecha_salida > this.arrayForm.fecha_ingreso)
                        && arrayHabitaciones.length == 0">
                                    <div class="alert alert-light shadow" role="alert"
                                        style=" display: flex; align-items: center;">
                                        <span class="mdi mdi-calendar-search fs-2 text-danger me-3"></span>
                                        No hay resultados con las fechas seleccionadas, puede cambiar los rangos de fechas y
                                        buscar por día.
                                    </div>
                                </div>
                                <div v-for="(e, index) in funcBuscarHabitaciones"
                                    class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3">
                                    <input class="form-check-input d-none" type="radio" :id=`checkBoxHab-${e.id}`
                                        :value="e" v-model="habitacionSelected" @click="validarFechas">
                                    <label class="card habitacionCard" :for=`checkBoxHab-${e.id}` style="cursor: pointer;"
                                        data-bs-toggle="modal" data-bs-target="#modalTarifas" @click="setFechas()">
                                        <div class="card-body">
                                            <h5 class="card-title placeholder-glow text-center fs-4">
                                                <b>@{{ e.numero_habitacion }}</b>
                                            </h5>

                                            <div class="row">
                                                <p class="card-text text-center">
                                                    @{{ e.relacion_tipo_habitaciones.tipo_habitacion }} | @{{ e.relacion_tipo_habitaciones.codigo }}
                                                </p>
                                            </div>

                                            <div class="row text-center">
                                                <b>
                                                    {{-- <span v-for="max_person in e.relacion_forma_habitaciones.max_personas" class="me-1 mdi mdi-bed"></span> --}}
                                                    <span v-for="cama in e.habitacion_camas"
                                                        class="me-2">@{{ cama.tipo_camas.tipo_cama }}
                                                        <span v-for="cam in cama.cantidad"
                                                            class="me-1 mdi mdi-bed"></span>
                                                    </span>
                                                </b>
                                            </div>

                                            <div class="col-12 text-center">
                                                <b>
                                                    <span class="badge rounded-pill text-bg-secondary">
                                                        <span class="mdi mdi-account"></span>
                                                        @{{ e.relacion_forma_habitaciones.max_personas }}
                                                    </span>
                                                </b>
                                                <b>
                                                    <span class="badge rounded-pill text-bg-secondary"
                                                        v-if="e.get_tarifas != null">
                                                        @{{ e.get_tarifas.length }} @{{ e.get_tarifas.length > 1 ? 'tarifas' : 'tarifa' }}
                                                    </span>
                                                </b>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--Modal Tarifas-->
            <div class="modal fade" id="modalTarifas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <form action="{{ route('detalle_reservas.store') }}" method="POST">
                            @csrf
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel"><span class="mdi mdi-plus"></span>
                                    Crear Reservación</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    @click="limpiar"></button>
                            </div>
                            <div class="modal-body" style="max-height: 75vh"
                                v-if="habitacionSelected != null && habitacionSelected.id > 0">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="card-title text-uppercase">
                                                    Datos de reservación: Habitación @{{ habitacionSelected.numero_habitacion }}
                                                </h4>
                                                <!--Inputs ocultos a enviar-->
                                                <input type="hidden" class="form-control" name="reservaciones_id"
                                                    :value="reservacionesId">
                                                <input type="hidden" class="form-control" name="habitaciones_id"
                                                    :value="habitacionSelected.id">
                                                <input type="hidden" class="form-control" name="tarifas_id"
                                                    v-model="tarifaSeleccionadaId">
                                                <input type="hidden" name="fecha2Ingreso"
                                                    :value="arrayForm.fecha_ingreso">
                                                <input type="hidden" name="fecha2Salida"
                                                    :value="arrayForm.fecha_salida">

                                                <div class="mb-3">
                                                    <label for="fecha_ingreso" class="form-label">Entrada:</label>
                                                    <input type="date" class="form-control" id="fecha_ingreso"
                                                        name="fecha_ingreso" v-model="fecha_ingreso"
                                                        :min="arrayForm.fecha_ingreso" :max="arrayForm.fecha_salida"
                                                        :readonly="this.chckMismaFecha">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="fecha_salida" class="form-label">Salida:</label>
                                                    <input type="date" class="form-control" id="fecha_salida"
                                                        name="fecha_salida" v-model="fecha_salida"
                                                        :min="arrayForm.fecha_ingreso" :max="arrayForm.fecha_salida"
                                                        :readonly="this.chckMismaFecha">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="cantidad_personas" class="form-label">Cantidad de
                                                        personas:</label>
                                                    <input type="number" class="form-control" id="cantidad_personas"
                                                        name="cantidad_personas" min="1" v-model="cantidadPersona"
                                                        placeholder="Agregue la cantidad de personas para esta habitacion"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="descripcion" class="form-label">Descripción:</label>
                                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" v-model="descripcion"
                                                        placeholder="Ingrese una breve descripcion de la reserva..."></textarea>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="tarifa" class="form-label">Seleccione una
                                                        tarifa:</label>
                                                    <!--For tarifas-->
                                                    <div class="row">
                                                        <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-4 mb-3"
                                                            v-for="t in getTarifas">
                                                            <input class="form-check-input d-none" type="radio"
                                                                name="tarifas_id" :id="'tarifas-' + t.tarifas_id"
                                                                :value="t.tarifas_id" v-model='tarifa'
                                                                @click="tarifaSeleccionadaId = t.tarifas_id" required>
                                                            <label :for="'tarifas-' + t.tarifas_id" class="card"
                                                                style="cursor: pointer">
                                                                <div class="card-header">
                                                                    <span class="fs-5">$ @{{ t.precio }} /
                                                                        @{{ t.numero_dias }} DIA(S)</span>
                                                                </div>
                                                                <div class="card-body">
                                                                    <h6 class="card-title mb-3 fw-bold">
                                                                        @{{ t.tarifa }}
                                                                    </h6>
                                                                    <small class="card-text">@{{ t.fecha_inicio }} AL
                                                                        @{{ t.fecha_finalizacion }}</small>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-4">
                                        <div class="card" style="overflow-y: auto; max-height: 100%; min-height: 100%;">
                                            <div class="card-body">
                                                <h4 class="card-title text-uppercase">Registro de huéspedes</h4>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="mb-3">
                                                            <label for="buscarhuesped" class="form-label">Buscar huésped
                                                                registrado</label>
                                                            <input type="text" class="form-control" id="buscarhuesped"
                                                                placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                                                v-model="buscarHuesped" @keyup="getHuespedSearch()">

                                                            <div class="lista shadow-lg w-25">
                                                                <ul class="list-group list-group"
                                                                    v-show="listHuesped.length > 0">
                                                                    <li v-for="v in listHuesped" :key="v.id"
                                                                        class="list-group-item list-group-item-action text-uppercase"
                                                                        @click="setHuesped(v)">
                                                                        @{{ v.nombre }} · @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identifiación' }}
                                                                        @{{ v.identificacion }} · @{{ v.telefono ?? 'Sin teléfono' }}
                                                                    </li>
                                                                </ul>
                                                                <ul class="list-group list-group"
                                                                    v-show="listHuesped.length == 0 && buscarHuesped.length > 4">
                                                                    <li
                                                                        class="list-group-item list-group-item-action text-uppercase">
                                                                        No se han encontrado registros con los parámetros de
                                                                        búsqueda, intente cambiarlos o buscar otros nombres.
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" v-show="huespedSelect.length > 0">

                                                    <div class="col-12">
                                                        <h5>HUÉSPEDES AGREGADOS A LA HABITACIÓN</h5>
                                                        <div class="card border border-info mb-3"
                                                            v-for="v in huespedSelect" :key="v.id">
                                                            <input class="d-none" type="checkbox" :value="v.id"
                                                                name="huespedes[]" checked>
                                                            <div class="card-body text-uppercase">
                                                                <h5 class="card-title">
                                                                    <span
                                                                        class="mdi mdi-close float-end pointer text-danger"
                                                                        @click="deletHuesped(v.id)"></span>
                                                                    @{{ v.nombre }}
                                                                </h5>
                                                                <p class="card-text">
                                                                    @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identifiación' }} @{{ v.identificacion }} ·
                                                                    @{{ v.teléfono ?? 'Sin teléfono' }}
                                                                    <br>
                                                                    @{{ v.municipios_id > 0 ? v.municipios.municipio : 'Sin ciudad' }}
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
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                                    @click="limpiar">
                                    <span class="mdi mdi-close"></span> Cerrar
                                </button>
                                <button type="submit" class="btn btn-primary" :disabled="validarFechas()"><span
                                        class="mdi mdi-check"></span> Reservar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Modal eliminar-->
            <div class="modal fade" id="modalEliminar" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('detalle_reservas.delete') }}" method="POST">
                            @csrf

                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Esta seguro de eliminar esta
                                    reserva?
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="idEliminarDetReserva" :value="idEliminarDetReserva">
                                <input type="hidden" name="num_hab" :value="n_hab">

                                Numero de habitación: <span class="fw-bold">@{{ n_hab }}</span>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-danger">Si, Eliminar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!--Modal detalle de reservación -->
            <div id="showDetails" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                Información de reservación
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                @click="limpiar"></button>
                        </div>
                        <div class="modal-body" v-if="detalleReservasSelect.id != null">
                            <div class="row">
                                <div class="col-12 h3">
                                    Habitación @{{ detalleReservasSelect.relacion_habitaciones.numero_habitacion }}
                                </div>
                                <div class="col-12">
                                    Se esperan @{{ detalleReservasSelect.cantidad_personas }} personas / Huéspedes registrados
                                    @{{ detalleReservasSelect.huespedes.length }}
                                </div>
                                <div class="col-12">
                                    Del @{{ detalleReservasSelect.fecha_ingreso }} al @{{ detalleReservasSelect.fecha_salida }} / @{{ getDias(detalleReservasSelect.fecha_ingreso, detalleReservasSelect.fecha_salida) }}
                                </div>
                                <div class="col-12">
                                    Tarifa:
                                    <b>
                                        @{{ detalleReservasSelect.relacion_tarifas.tarifa }}
                                        $@{{ parseFloat(detalleReservasSelect.relacion_tarifas.precio).toFixed(2) }} / @{{ detalleReservasSelect.relacion_tarifas.numero_dias }} dia(s)
                                    </b>
                                </div>
                                <div class="col-12 mt-4 h5">
                                    Huéspedes registrados
                                </div>
                                <div class="col-12 mb-2">
                                    <form action="{{ route('huesped_reservas.store') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="detalle_reservas_id"
                                            :value="detalleReservasSelect.cid">
                                        <div class="mb-3">
                                            <label for="buscarhuesped" class="form-label">Buscar huésped
                                                registrado</label>
                                            <input type="text" class="form-control" id="buscarhuesped"
                                                placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                                v-model="buscarHuesped" @keyup="getHuespedSearch()">

                                            <div class="lista shadow-lg w-25">
                                                <ul class="list-group list-group" v-show="listHuesped.length > 0">
                                                    <button type="submit" v-for="v in listHuesped"
                                                        :value="v.id" :key="v.id" name="huespedes_id"
                                                        class="list-group-item list-group-item-action text-uppercase">
                                                        @{{ v.nombre }} · @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identifiacion' }}
                                                        @{{ v.identificacion }} · @{{ v.telefono ?? 'Sin telefono' }}
                                                    </button>
                                                </ul>
                                                <ul class="list-group list-group"
                                                    v-show="listHuesped.length == 0 && buscarHuesped.length > 4">
                                                    <li class="list-group-item list-group-item-action text-uppercase">No se
                                                        han
                                                        encontrado registros con los parámetros de búsqueda, intente cambiar
                                                        los
                                                        parámetros de búsqueda.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-12 mb-2" v-for="d in detalleReservasSelect.huespedes"
                                    :key="d.id" v-if="detalleReservasSelect.huespedes.length > 0">
                                    <div class="card"
                                        style="background-color:rgb(166, 233, 235); border-color:rgb(161, 245, 246);">
                                        <div class="card-body">
                                            <div class="card-title text-uppercase">
                                                <form action="{{ route('huesped_reservas.delete') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="id" :value="d.id">
                                                    <button type="submit" class="btn text-danger float-end">
                                                        <span class="mdi mdi-delete h4"></span>
                                                    </button>
                                                </form>
                                                @{{ d.huesped.nombre }}
                                            </div>
                                            <p class="card-text">
                                                Numero de identificación: @{{ d.huesped.identificacion ?? '---' }}
                                                <br>
                                                Teléfono: @{{ d.huesped.telefono ?? '---' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            @can('anticipos.create')
                @if ($p->clientes_id > 0)
                    <!--Modal anticipos detalles-->
                    <div class="modal fade" id="detalleAnticipos" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content" v-if="detalleAnticipos.length > 0">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                        <span class="mdi mdi-plus"></span> Detalle de anticipos reservados a este proceso
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-12 mb-2" v-for="a in detalleAnticipos">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        <span class="float-end text-danger">No. @{{ a.anticipos_id }}</span>
                                                        @{{ a.anticipos.clientes.nombre }}
                                                    </h5>
                                                    <p class="card-text">
                                                        @{{ a.anticipos.concepto }}
                                                    </p>
                                                    <p class="card-text">
                                                        Monto: $@{{ parseFloat(a.anticipos.monto).toFixed(2) }} · Fecha: @{{ a.anticipos.fecha }}
                                                    </p>
                                                    <p class="card-text">
                                                        <a class="btn btn-outline-secondary"
                                                            :href="'/anticipos/impresion/' + a.anticipos.cid" role="button"
                                                            target="_blank"> <span class="mdi mdi-printer"></span>
                                                            Imprimir</a>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endcan

        </div>
    @else
        <div class="alert alert-danger" role="alert">
            No se pueden hacer modificaciones a esta reservación ya esta completada.
        </div>
    @endif
    <!--Modal anticipos-->
    @can('anticipos.create')
        @if ($p->clientes_id > 0)
            <div class="modal fade" id="modalAnticipos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                <span class="mdi mdi-plus"></span> Agregar anticipos
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">

                                </h5>
                                <p class="card-text">
                                    @php
                                        $nh = '';
                                        $ultimo = count($detalleReservas);
                                        $contador = 0;
                                        foreach ($detalleReservas as $dr) {
                                            $contador++;
                                            if ($ultimo > 1 && $contador == $ultimo) {
                                                $nh = $nh . 'y ';
                                            } elseif ($ultimo > 1 && $contador < $ultimo && $contador > 1) {
                                                $nh = $nh . ', ';
                                            }
                                            $nh = $nh . $dr->relacionHabitaciones->numero_habitacion . ' ';
                                        }
                                    @endphp
                                    <x-anticipos-form table="anticipos" :forma="$forma_pagos" :concepto="'Pago de anticipo por reservación de ' .
                                        (count($detalleReservas) == 1 ? 'habitacion' : 'habitaciones') .
                                        ' ' .
                                        $nh" :cliente="$p->clientes_id"
                                        :tipo_reservacion="1" :reservacion_id="$p->id" />
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan






    <script>
        var app = new Vue({
            el: '#appReservaciones',
            data: {
                arrayForm: {
                    fecha_ingreso: '{{ $fechaIngreso ?? '' }}',
                    fecha_salida: '{{ $fechaSalida ?? '' }}',
                    cantidad_personas: 1
                },
                fecha2Ingreso: '',
                fecha2Salida: '',
                arrayHabitaciones: @json($habitaciones),
                reservacionesId: '{{ $p->id }}',
                message: {},
                estadoReserva: null,
                txtBusqueda: '',
                tipoFiltrado: 0,
                arrayHabReservadas: [],
                arrayTarifas: [],
                checkBoxHab: false,
                chckMismaFecha: true,
                habitacionSelected: [],
                tarifaSeleccionadaId: 0,
                arrayDetalleReservas: @json($detalleReservas),
                idEliminarDetReserva: null,
                n_hab: null,
                descripcion: '',
                fecha_ingreso: '',
                fecha_salida: '',
                tarifa: null,
                buscarHuesped: '',
                listHuesped: [],
                huespedSelect: [],
                cantidadPersona: 1,
                detalleReservasSelect: [],
                detalleAnticipos: [],
                tipo_habitaciones: @json($tipo_habitaciones),
                forma_habitaciones: @json($forma_habitaciones),
                filterForma: [],
                filterTipo: [],
                controls: null,
                showDetails: null
            },
            methods: {
                apiReservasDisponibles: function() {
                    if (this.arrayForm.fecha_ingreso != '' && this.arrayForm.fecha_salida != '')
                        if (this.arrayForm.fecha_salida > this.arrayForm.fecha_ingreso) {
                            //Traer las habitaciones disponibles.
                            axios.post("{{ route('habitaciones.api_get_habitaciones_disponibles') }}", {
                                    fecha_entrada: this.arrayForm.fecha_ingreso,
                                    fecha_salida: this.arrayForm.fecha_salida,
                                })
                                .then((rs2) => {
                                    this.arrayHabitaciones = rs2.data.habitaciones;
                                })
                                .catch(error2 => {
                                    console.log('Error2 JS: ', error2);
                                })
                        } else {
                            this.estadoReserva = false;
                            this.setMessage('Por favor ingrese una fecha valida.', 'danger');
                        }
                },
                getMinDate: function() {
                    let fecha = new Date();
                    if (fecha.getHours() >= 2)
                        return this.getDateFormat(fecha);
                    fecha.setDate(fecha.getDate() + 1)
                    return this.getDateFormat(fecha);

                },
                getMinDateOut: function() {
                    let fecha;
                    if (this.arrayForm.fecha_ingreso && this.arrayForm.fecha_ingreso.length > 2)
                        fecha = new Date(this.arrayForm.fecha_ingreso);
                    else
                        fecha = new Date(this.getMinDate());
                    fecha.setDate(fecha.getDate() + 2);
                    return this.getDateFormat(fecha)
                },
                getDateFormat: function(fecha) {
                    var mes = fecha.getMonth() + 1;
                    mes = mes < 10 ? '0' + mes : mes;
                    var dia = fecha.getDate() < 10 ? '0' + fecha.getDate() : fecha.getDate();
                    return `${fecha.getFullYear()}-${mes}-${dia}`;
                },
                setFechas: function() {
                    if (this.chckMismaFecha) {
                        this.fecha_ingreso = this.arrayForm.fecha_ingreso;
                        this.fecha_salida = this.arrayForm.fecha_salida;
                    }
                    this.cantidadPersona = this.arrayForm.cantidad_personas > 0 ? this.arrayForm
                        .cantidad_personas : 1;

                },
                validarFechas: function() {
                    let fi = this.getDate(this.fecha_ingreso);
                    let ff = this.getDate(this.fecha_salida);
                    return !(fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime());
                },
                getDate: function(fecha) {
                    const date = new Date(fecha);
                    return [!isNaN(date.getTime()) && date.toISOString().slice(0, 10) === fecha, date];
                },
                limpiar: function() {
                    this.fecha_ingreso = '';
                    this.fecha_salida = '';
                    this.descripcion = '';
                    this.tarifa = null;
                    this.habitacionSelected = [];
                },
                eliminarHabReservada: function(id, n_hab) {
                    this.idEliminarDetReserva = id;
                    this.n_hab = n_hab;
                },
                setMessage: function(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 4000)
                },
                getHuespedSearch: function() {
                    if (this.buscarHuesped.length > 3) {
                        axios.post('{{ route('huespedes.api_buscar') }}', {
                            'buscar': this.buscarHuesped.toUpperCase(),
                        }).then(r => {
                            if (r.data.list.length)
                                this.listHuesped = r.data.list;
                            else this.listHuesped = [];
                        })
                    } else this.listHuesped = [];

                },
                setHuesped: function(huesped) {
                    if (this.huespedSelect.length < this.cantidadPersona) {
                        this.huespedSelect.push(huesped);

                    } else alert(
                        'Ya se agrego la cantidad de personas requerida para esta habitacion, si necesita agregar mas personas, modifique el campo cantidad de personas.'
                    )

                    this.listHuesped = [];
                    this.buscarHuesped = '';

                },
                deletHuesped: function(id) {
                    this.huespedSelect = this.huespedSelect.filter(h => h.id != id);
                },
                getDias: function(inicio, fin) {
                    let i = new Date(inicio),
                        f = new Date(fin);
                    if (f.getTime() >= i.getTime())
                        return ((f.getTime() - i.getTime()) / 1000 / 60 / 60 / 24) + " dia(s)";
                    else
                        return "No se pueden calcular los dias."
                },

                getTipoLength: function(id) {
                    return this.arrayHabitaciones.filter(h => h.tipo_habitaciones_id == id).length;
                },
                setControlBtn: function(state) {
                    this.controls = state;
                    localStorage.setItem('controls', state);

                },
                setShowDetailsBtn: function(state) {
                    this.showDetails = state;
                    localStorage.setItem('showDetails', state);


                },


            },
            computed: {
                funcBuscarHabitaciones() {
                    const regex = new RegExp(this.txtBusqueda, 'i');

                    return this.arrayHabitaciones.filter((hab) => {
                        return (regex.test(hab.numero_habitacion) &&
                                this.arrayForm.cantidad_personas <= hab.relacion_forma_habitaciones
                                .max_personas) &&
                            (
                                (
                                    this.filterTipo.length == 0 || this.filterTipo.find(f => f == hab
                                        .tipo_habitaciones_id)
                                )
                            )
                    });
                },
                getShowControls() {
                    return
                },
                getTarifas: function() {
                    if (this.habitacionSelected &&
                        this.habitacionSelected.id > 0 &&
                        this.habitacionSelected.get_tarifas.length > 0)
                        return this.habitacionSelected.get_tarifas.slice().sort((a, b) => a.tarifa
                            .localeCompare(b.tarifa));
                    return [];
                }
            },
            created() {
                this.showDetails = parseInt(localStorage.getItem('showDetails')) == 1;
                this.controls = parseInt(localStorage.getItem('controls')) == 1;
            }

            ,
        });
    </script>
@endsection
