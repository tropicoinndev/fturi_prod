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


    <div id="appReservaciones" class="container">


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
                        <h5 class="card-title fw-bold text-center">DISPONIBILIDAD DE HABITACIONES</h5>
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
                                                v-model="chckMismaFecha" :checked="chckMismaFecha" :value="true">
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
                                    <input type="checkbox" class="btn-check" :id="`btnTipo-${ft.id}`" name="filterTipo"
                                        :value="ft.id" autocomplete="off" v-model='filterTipo' multiple>
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

                                <label class="card" :for=`checkBoxHab-${e.id}` style="cursor: pointer;"
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
                                                    <span v-for="cam in cama.cantidad" class="me-1 mdi mdi-bed"></span>
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
    </div>







    <script>
        var app = new Vue({
            el: '#appReservaciones',
            data: {
                arrayForm: {
                    fecha_ingreso: '',
                    fecha_salida: '',
                    cantidad_personas: 1
                },
                arrayHabitaciones: [],

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
