@extends('layouts.hab')
@section('content-hab')
    <style>
        .sidebarFilter {
            position: fixed;
            right: 1%;
            width: 240px;
            height: 90vh;
            overflow-x: auto;
            z-index: 1009;
            background: #37474F;
            color: #fefefe;
        }

        .text-ocupada {
            color: #FF5722;
        }

        .panel-body {
            min-height: 550px;
        }

        .text,
        h5 {
            font-size: 1.2rem;
            font-family: Arial, sans-serif;
        }

        h6 {
            font-size: 1rem;
        }

        .card-ocupado {
            background-color: #607D8B;
            color: #fff;
        }

        .card-ocupado .badge {
            background: #fff;
            color: #607D8B;
        }

        .tag-reserva-hoy {
            color: #C8E6C9;
        }

        .tag-reserva {
            color: #E1F5FE;
        }

        .card-ocupado .btn-group a,
        .card-ocupado .btn-group a .mdi {
            border: none;
            color: #fff;
        }

        .card-disponible {
            background-color: #009688;
            color: #fff;
        }

        .card-disponible .badge {
            background: #fff;
            color: #607D8B;
        }

        .card-disponible .btn-group a,
        .card-disponible .btn-group a .mdi {
            border: none;
            color: #fff;
        }

        .card-mantenimiento {
            background: #FF7043;
            color: #fff;
        }

        .card-mantenimiento .badge {
            background: #fff;
            color: #607D8B;
        }

        .card-mantenimiento .btn-group a,
        .card-mantenimiento .btn-group a .mdi {
            border: none;
            color: #fff;
        }

        body {
            background: #E0F2F1;
        }

        [v-cloak] {
            display: none;
        }
    </style>
    <div id="appHabitaciones" v-cloak>
        <!-- Panel de filtros-->
        <div class="p-3 shadow sidebarFilter" v-show="filterShow">
            <p class="col-12 fs-4">
                <a href="#" class="link-light link-underline link-underline-opacity-0" @click="setFilter(0)"><span
                        class="mdi mdi-minus"></span>
                    Filtros
                </a>
            </p>
            <p class="col-12 fs-5 text-uppercase">
                Sucursal
            </p>

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
            <p class="col-12 fs-5 text-uppercase">
                HABITACIONES
            </p>
            <ul class="nav nav-pills flex-column mb-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action">
                        <input v-model="ocupacionFilter" class="form-check-input me-1" type="radio" name="ocupacionFilter"
                            value="0" id="rbtnTodas" checked>

                        <label class="form-check-label" for="rbtnTodas">Todas</label>
                    </li>

                    <li class="list-group-item list-group-item-action">
                        <input class="form-check-input me-1" type="radio" name="ocupacionFilter" v-model="ocupacionFilter"
                            value="1" id="rbtnHabDisponibles">
                        <label class="form-check-label" for="rbtnHabDisponibles">Disponibles</label>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <input class="form-check-input me-1" type="radio" name="ocupacionFilter" v-model="ocupacionFilter"
                            value="2" id="rbtnHabOcupadas">
                        <label class="form-check-label" for="rbtnHabOcupadas">Ocupadas</label>
                    </li>
                </ul>
            </ul>

            <p class="col-12 fs-5 text-uppercase">TIPO DE HABITACION</p>
            <ul class="nav nav-pills flex-column mb-3">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action" @click="tipoFilter = []"
                        :class="{ 'active': tipoFilter.length == 0 }">
                        <label class="form-check-label" for="rbtnAllTipoHab">Todos</label>
                    </li>
                    <label class="list-group-item list-group-item-action" v-for="t in tipos " :for="'tp' + t.id">
                        <input class="form-check-input me-1" type="checkbox" name="tipoFilter" :value="t.id"
                            :id="'tp' + t.id" v-model="tipoFilter">
                        @{{ t.tipo_habitacion }}
                    </label>
                </ul>
            </ul>
        </div>

        <div class="container shadow panel-body p-4 bg-light" style="z-index: 100;">
            <div class="row">
                <x-message></x-message>
            </div>

            <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>

            <div class="row">
                <div class="col-12 text-uppercase fw-bold h5 mb-3">
                    Estadias
                </div>
            </div>
            <!--Caja de busqueda.-->
            <div class="row mb-3">
                <div class="col-11">
                    <div class="mb-3 row">
                        <label for="txtBusqueda" class="col-sm-1 col-form-label">Buscar:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control" placeholder="Buscar habitacion..."
                                autocomplete="off" v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <button class="btn btn-light" @click="setFilter(filterShow ? 0 : 1)"><span
                            class="mdi mdi-filter-cog"></span> Filtros</button>

                </div>
            </div>

            <div class="row">
                <!--Bucle FOR de habitaciones.-->
                <div v-for="(h, index) in funcBuscarHabitaciones" class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-3 mb-4">
                    <div class="card border-secondary border-1 overflow-hidden"
                        :class="h.recepcion.length > 0 ? 'card-ocupado' : (isValidStatus(h) ? 'card-disponible' :
                            'card-mantenimiento')">
                        <div class="card-body row">
                            <div style="position:absolute; top: 0px; left: 0px;" v-if="h.reservas.length > 0"
                                :class="{
                                    'tag-reserva-hoy': getReservaHoy(h.reservas),
                                    'tag-reserva': !getReservaHoy(h.reservas),

                                }"
                                :title="`${h.reservas.length} ${h.reservas.length > 1?'reservas activas':'reserva activa'}`">
                                <span class="mdi mdi-tag fs-1"></span>
                            </div>
                            <div class="col-12">
                                <h5 class="card-title placeholder-glow text-center fs-1">
                                    @{{ h.numero_habitacion }}
                                </h5>
                            </div>
                            <div class="col-12 text-uppercase">
                                <p class="card-text text-center">
                                    @{{ h.relacion_forma_habitaciones.forma_habitacion }} | @{{ h.relacion_tipo_habitaciones.codigo }}
                                </p>
                            </div>

                            <div class="col-12 text-center fs-5">
                                <span class="badge rounded-pill me-2">
                                    <span class="mdi mdi-bed"></span> @{{ h.relacion_forma_habitaciones.max_personas }}
                                </span>
                                <span class="badge rounded-pill">
                                    <span class="mdi mdi-account me-2"></span>
                                    @{{ h.relacion_forma_habitaciones.max_personas }}
                                </span>
                                <span class="btn badge rounded-pill me-2" data-bs-toggle="modal" href="#habitacion_detalle"
                                    role="button" @click="sHab = h">
                                    <span class="mdi mdi-information-outline"></span>
                                    Info
                                </span>

                            </div>
                        </div>

                        <div class="card-footer bg-transparent border-0 p-0">
                            <div class="d-grid">
                                <div class="btn-group border-0" role="group" aria-label="Basic outlined example">
                                    <a :href="isValidStatus(h) ? `/recepciones/crear/${ h.cid }` : '#'"
                                        class="btn rounded-0" :class="{ 'disabled': !isValidStatus(h) }"
                                        v-if="h.recepcion.length == 0">
                                        Entrada
                                    </a>
                                    <a :href="getSalida(h.recepcion[0]) ? '#' : `/recepciones/salida/${ h.recepcion[0].cid }`"
                                        class="btn rounded-0" v-if="h.recepcion.length > 0"
                                        :class="{ 'disabled': getSalida(h.recepcion[0]) }"
                                        :title="h.recepcion.length > 0 ? `Salida para el dia: ${h.recepcion[0].fecha_salida}` :
                                            ''">
                                        Salida
                                        <div class="spinner-grow text-white" role="status"
                                            v-show='!getSalida(h.recepcion[0])' style="height: 12px; width: 12px;">
                                            <span class="visually-hidden"></span>
                                        </div>

                                    </a>
                                    <a href="#" class="btn rounded-0" data-bs-toggle="modal"
                                        data-bs-target="#reservasDetalle" @click="habReservasSelected = h"
                                        :class="{ 'disabled': h.reservas.length == 0 }">
                                        Reservas
                                    </a>
                                    <a type="button" class="btn rounded-0"
                                        :title="h.recepcion.length > 0 ? `Salida para el dia: ${h.recepcion[0].fecha_salida}` :
                                            ''"
                                        :class="{ 'disabled': h.recepcion.length == 0 }"
                                        :href="h.recepcion.length > 0 ? `/recepciones/show/${h.recepcion[0].cid}` : '#'">
                                        <span class="mdi mdi-clipboard-check"></span>
                                        Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal ver reservaciones de la habitacion  -->
        <div class="modal fade" id="reservasDetalle" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true" v-show="habReservasSelected.length > 0">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalle de reservas de Habitacion
                            @{{ habReservasSelected.numero_habitacion }}</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="card mb-3" v-for="r in habReservasSelected.reservas">
                            <div class="card-body">
                                <a :href="`/recepciones/crear/${ habReservasSelected.cid }`" v-show="getIsToday(r)"
                                    class="btn btn-primary float-end">Entrada</a>
                                <h5 class="card-title">Reservacion No. @{{ r.reservaciones_id }}</h5>
                                <p class="card-text">$@{{ r.relacion_tarifas.precio }} · @{{ r.relacion_tarifas.tarifa }}</p>
                                <p class="card-text">@{{ r.fecha_ingreso }} al @{{ r.fecha_salida }} ·
                                    @{{ getDias(r) }}</p>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>



        <!-- Ver informacion de habitaciones -->
        <div id="habitacion_detalle" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body" v-if="sHab != null">
                        <div class="row">
                            <div class="col-12 h3">
                                Información
                            </div>
                            <div class="col-4  mb-1">
                                Habitación
                            </div>
                            <div class="col-8 mb-1">
                                @{{ sHab.numero_habitacion }}
                            </div>
                            <div class="col-4  mb-1">
                                Teléfono
                            </div>
                            <div class="col-8  mb-1">
                                @{{ sHab.telefono }}
                            </div>
                            <div class="col-4  mb-1">
                                Extensión
                            </div>
                            <div class="col-8  mb-1">
                                @{{ sHab.extension }}
                            </div>
                            <div class="col-4 mb-1">
                                Tipo habitación
                            </div>
                            <div class="col-8 mb-1">
                                @{{ sHab.relacion_tipo_habitaciones.tipo_habitacion }} (@{{ sHab.relacion_tipo_habitaciones.codigo }})
                            </div>
                            <div class="col-4 mb-1">
                                Forma habitación
                            </div>
                            <div class="col-8 mb-1">
                                @{{ sHab.relacion_forma_habitaciones.forma_habitacion }}
                            </div>
                            <div class="col-4 mb-1">
                                Maximo de personas
                            </div>
                            <div class="col-8 mb-1">
                                @{{ sHab.relacion_forma_habitaciones.max_personas }}
                            </div>
                            <div class="col-4 mb-1">
                                Estado de habitación
                            </div>
                            <div class="col-8 mb-1">
                                @{{ sHab.relacion_estado_habitaciones.estado_habitacion }}
                            </div>
                            <div class="col-4 mb-1">
                                Ubicación
                            </div>
                            <div class="col-8 text-justify">
                                @{{ sHab.relacion_ubicacion_habitaciones.ubicacion_habitacion }}
                            </div>
                            <div class="col-4  mb-1">
                                Descripción
                            </div>
                            <div class="col-8  mb-1 text-justify">
                                @{{ sHab.descripcion }}
                            </div>

                            <div class="col-12 mt-2" v-if="sHab.mantenimientos != null">
                                <h5>Mantenimientos activos</h5>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item" v-for="m in sHab.mantenimientos">
                                        <div class="row">
                                            <div class="col-12 fw-bold">
                                                @{{ m.tipo_mantenimientos.mantenimiento }}
                                                <span
                                                    class="float-end badge badge-pill text-bg-dark">@{{ m.tipo_mantenimientos.duracion_promedio }}
                                                    hora(s)</span>
                                            </div>
                                            <div class="col-12">
                                                Observaciones @{{ m.observacion ?? '--' }}
                                            </div>
                                            <div class="col-6" v-if="m.asignado != null">
                                                Asignado: <span class="text-muted">@{{ m.asignado.name }}</span>
                                            </div>
                                            <div class="col-6" v-if="m.asignado == null">
                                                Asignado: <span class="text-muted">Aun sin asignar</span>
                                            </div>
                                            <div class="col-6">
                                                Inicio de mant.: <span class="text-muted">@{{ m.inicio ?? 'Aun sin iniciar' }}</span>
                                            </div>
                                        </div>

                                    </li>

                                </ul>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                @click="sHab = null">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var compras = new Vue({
            el: '#appHabitaciones',
            data: {
                arrayHabitaciones: @json($p),
                formas: @json($formas),
                tipos: @json($tipos),
                message: {},
                tipoFiltrado: 0,
                txtBusqueda: '',
                tipoFilter: [],
                ocupacionFilter: 0,
                habReservasSelected: [],
                filterShow: 1,
                sHab: null,
            },
            methods: {
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 3 * 1000)
                },
                getDias: function(r) {
                    let ingreso = new Date(r.fecha_ingreso);
                    let salida = new Date(r.fecha_salida);
                    let diff = salida - ingreso;
                    let dias = diff / (1000 * 60 * 60 * 24);
                    return dias + (dias > 1 ? ' dias' : ' dia');

                },
                setFilter: function(status) {
                    localStorage.setItem('filterShow', status);
                    this.filterShow = status;
                },
                getReservaHoy: function(r) {

                    return r.find(h => this.getIsToday(h)) != null;

                },
                getIsToday: function(r) {
                    const fecha = new Date();
                    const fechaFormat =
                        `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;
                    return r.fecha_ingreso == fechaFormat;
                },
                getSalida: function(r) {
                    const fecha = new Date();
                    const fechaFormat =
                        `${fecha.getFullYear()}-${String(fecha.getMonth() + 1).padStart(2, '0')}-${String(fecha.getDate()).padStart(2, '0')}`;
                    return !(fechaFormat >= r.fecha_salida);
                },
                isValidStatus: function(h) {
                    return parseInt(h.relacion_estado_habitaciones.token) == 1401;
                }
            },
            computed: {
                funcBuscarHabitaciones() {
                    const regex = new RegExp(this.txtBusqueda, 'i');
                    return this.arrayHabitaciones.filter((h) => {

                        return regex.test(h.numero_habitacion) &&
                            (
                                this.tipoFilter.length == 0 ||
                                this.tipoFilter.find(f => f == h.tipo_habitaciones_id)
                            ) &&
                            (
                                (this.ocupacionFilter == 0) ||
                                (
                                    this.ocupacionFilter == 2 &&
                                    h.recepcion.length > 0
                                ) ||
                                (
                                    this.ocupacionFilter == 1 &&
                                    (h.recepcion.length == 0 || h.recepcion == null)
                                )
                            )

                    });

                }
            },
            created() {
                this.filterShow = parseInt(localStorage.getItem('filterShow')) == 1;
            }
        })
    </script>
@endsection
