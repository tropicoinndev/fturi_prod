@extends('layouts.app')

@section('content')
    <style>
        .message {
            position: fixed;
            top: 20%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }
        input:checked + label {
            background: red;
            color: white;
        }
        .panel{
            position: fixed;
            top: 8.5%;
            left: 0%;
            width: 280px;
            height: auto;
            z-index: 100;
        }
    </style>

    <div id="appReservaciones" class="container">
        <div class="row justify-content-center">

            <div class="alert show message" :class="'alert-'+ message.type" v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-center">Crear reservacion:</h5>
                    <h6 class="card-subtitle mb-4 text-muted text-center">Seleccione la fecha de ingreso y salida, posteriormente seleccione la reservacion de las habitaciones disponibles</h6>

                    <div class="card w-50 mb-3" style="margin: 0 auto;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="mb-3">
                                        <label for="fecha_ingreso" class="form-label">Fecha de ingreso:</label>
                                        <input type="date" class="form-control" id="fecha_ingreso" v-model="arrayForm.fecha_ingreso">
                                    </div>
                                </div>

                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <div class="mb-3">
                                        <label for="fecha_salida" class="form-label">Fecha de salida:</label>
                                        <input type="date" class="form-control" id="fecha_salida" v-model="arrayForm.fecha_salida" @change="apiReservasDisponibles">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="chckMismaFecha" v-model="chckMismaFecha" :checked="chckMismaFecha">
                                        <label class="form-check-label" for="chckMismaFecha">
                                            Usar misma fecha para todas las reservas
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div v-if="estadoReserva" class="row">
                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="mb-3">
                                        <label for="cantidad_personas" class="form-label">Nº de personas:</label>
                                        <input type="number" class="form-control" min="1" max="5" id="cantidad_personas" v-model="arrayForm.cantidad_personas">
                                    </div>

                                    {{-- <div class="input-group mb-3">
                                        <button class="btn btn-outline-secondary" type="button" id="cantidad_personas"><span class="mdi mdi-minus"></button>
                                        <input type="text" class="form-control text-center" placeholder="0" aria-label="Example text with button addon" aria-describedby="cantidad_personas">
                                        <button class="btn btn-outline-secondary" type="button" id="cantidad_personas"><span class="mdi mdi-plus"></button>
                                    </div> --}}
                                </div>

                                <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
                                    <div class="mb-3">
                                        <label for="habitacion" class="form-label">Nº de habitaciones:</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="rbtnHabSeparadas" checked>
                                            <label class="form-check-label" for="rbtnHabSeparadas">Habitaciones separadas</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefault" id="rbtnMismaHab">
                                            <label class="form-check-label" for="rbtnMismaHab">Misma habitacion</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="estadoReserva" class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Habitaciones disponibles</h5>
                    <h6 class="card-subtitle mb-4 text-muted">Seleccione las habitaciones necesarias para la reservacion</h6>

                    <!--Tipos de filtrados.-->
                    <div class="row">
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
                            <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">SENCILLA
                                <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                    @{{ funcBuscarHabitaciones.length }}
                                </span>
                            </button>

                            <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 2">DOBLE
                                <span v-if="tipoFiltrado == 2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                    @{{ funcBuscarHabitaciones.length }}
                                </span>
                            </button>

                            <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 3">TRIPLE
                                <span v-if="tipoFiltrado == 3" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                    @{{ funcBuscarHabitaciones.length }}
                                </span>
                            </button>

                            <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 4">CUADRUPLE
                                <span v-if="tipoFiltrado == 4" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                                    @{{ funcBuscarHabitaciones.length }}
                                </span>
                            </button>

                            <button type="button" class="btn btn-outline-primary position-relative btn-sm" @click="tipoFiltrado = 0">DISPONIBLES
                                <span v-if="tipoFiltrado == 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                                    @{{ funcBuscarHabitaciones.length }}
                                </span>
                            </button>
                        </div>

                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-3">
                            <input type="text" class="form-control" placeholder="Ingrese el nº de habitacion a buscar..." v-model="txtBusqueda">
                        </div>
                    </div>

                    <!--PANEL IZQUIERDO-->
                    <div class="panel" v-if="arrayHabReservadas.length > 0">
                        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow" style="height: 90vh; overflow-y: auto;">
                            <p class="col-12 fs-5 text-uppercase">DETALLE DE RESERVACION</p>
                            <hr>
                            <p>Habitaciones reservadas: <span class="badge rounded-pill text-bg-secondary fw-bold"> @{{ arrayHabReservadas.length }}</span></p>
                            <div style="display:block; height: 100%; overflow-y: auto;">
                                <div class="card mb-2" style="width: 100%;" v-for="h in arrayHabReservadas">
                                    <reserva 
                                        :pfechaentrada="h.fecha_ingreso" 
                                        @fecha_ingreso="(f) => h.fecha_ingreso = f" 
                                        :pfechasalida="h.fecha_salida" 
                                        :pfechamod="chckMismaFecha" 
                                        :habitacion="h.habitacion" 
                                        @eliminar="(h) => arrayHabReservadas = arrayHabReservadas.filter((item) => item.id != h.id)"
                                    />
                                </div>
                            </div>
                            <button v-if="estadoReserva" class="btn btn-outline-primary" type="button">Guardar reserva</button>
                        </div>
                    </div>

                    <!--FOR HABITACIONES -->
                    <div class="row">
                        <div v-for="(e, index) in funcBuscarHabitaciones" class="col-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-3">
                            <input class="form-check-input d-none"
                                    type="checkbox"
                                    :id=`checkBoxHab-${e.id}`
                                    :value="{habitacion:e}"
                                    v-model="arrayHabReservadas"
                                    >
                            <label class="card shadow" :for=`checkBoxHab-${e.id}` style="cursor: pointer;">
                                <div class="card-body">
                                    <h5 class="card-title placeholder-glow text-center fs-4"><b>@{{ e.numero_habitacion }}</b></h5>

                                    <div class="row">
                                        <p class="card-text text-center">@{{ e.relacion_tipo_habitaciones.tipo_habitacion }} | @{{ e.relacion_tipo_habitaciones.codigo }}</p>
                                    </div>

                                    <div class="row text-center">
                                        <b>
                                            <span v-for="max_person in e.relacion_forma_habitaciones.max_personas" class="text-secondary me-1 mdi mdi-bed"></span>
                                        </b>
                                    </div>

                                    <div class="row text-center">
                                        <b><span class="badge rounded-pill text-bg-secondary mdi mdi-account"> @{{ e.relacion_forma_habitaciones.max_personas }}</span></b>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        Vue.component('reserva', {
            props:['pfechaentrada', 'pfechasalida', 'pfechamod', 'habitacion'],

            data(){
                return{
                    fecha_entrada: this.pfechaentrada,
                    fecha_salida : this.pfechasalida,
                    min: this.pfechaentrada,
                    max: this.pfechasalida,

                    localMessage: '',

                    arrayHabFechasDiferentes: [{
                        idHabitacion : 0,
                        fecha_ingreso: '',
                        fecha_salida : '',
                    }],
                }
            },
            methods: {
                setFechaIngreso(){
                    if(this.fecha_entrada >= this.fechaActual()){
                        console.log('Fecha entrada: ',this.fecha_entrada);
                        console.log('Fecha salida: ',this.fecha_salida);
                        console.log('Nº Hab: ',this.habitacion.numero_habitacion);
                    }
                    else{
                        this.setMsj('La fecha de entrada no puede ser menor a la fecha de este dia.', 'danger');
                    }
                },
                setFechaSalida(){
                    if((this.fecha_entrada != '') && (this.fecha_salida > this.fecha_entrada)){
                        console.log('Fecha entrada: ',this.fecha_entrada);
                        console.log('Fecha salida: ',this.fecha_salida);
                        console.log('Nº Hab: ',this.habitacion.numero_habitacion);
                    }
                    else{
                        this.setMsj('Por favor ingrese una fecha de salida valida.', 'danger');
                    }
                },
                setMsj(m, t){
                    this.localMessage = {
                        message: m,
                        type: t
                    };
                    setTimeout(() => {
                        this.localMessage = {};
                    }, 6 * 1000);
                },
                fechaActual(){
                    const d = new Date();

                    const anio = d.getFullYear();
                    const mes  = ('0'+(d.getMonth()+1)).slice(-2);
                    const dia  = ('0'+d.getDate()).slice(-2);

                    const hoy = (anio)+'-'+(mes)+'-'+(dia);
                    return hoy;
                },
            },
            template: `
                <div class="card-body">
                    <h6 class="card-title">@{{ habitacion.numero_habitacion }} - @{{ habitacion.relacion_tipo_habitaciones.tipo_habitacion }}
                        <span class="mdi mdi-close-thick float-end mb-1" style="cursor: pointer;" @click="$emit('eliminar',habitacion.id)"></span>
                    </h6>
                    <div>
                        <div class="form-floating mb-2">
                            <input type="date"
                                   :id="'fi'+habitacion.id"
                                   class="form-control form-control-sm"
                                   :disabled="pfechamod"
                                   @change="setFechaIngreso()"
                                   v-model="fecha_entrada"
                                   :min="min"
                                   :max="max"
                            >
                            <label :for="'fi'+habitacion.id">Entrada</label>
                        </div>

                        <div class="form-floating mb-2">
                            <!--Crea la emicion de la fecha de salida para este input, y realiza el metodo de validacion de las fechas-->
                            <input type="date" 
                                   :id="'fs'+habitacion.id"
                                   class="form-control form-control-sm" 
                                   :disabled="pfechamod" 
                                   @change="setFechaSalida()"
                                   v-model="fecha_salida"
                                   :min="min"
                                   :max="max">
                            <label :for="'fs'+habitacion.id">Salida</label>
                        </div>
                    </div>

                    <div v-if="localMessage.message && localMessage.type" class="alert show message error-message" :class="'alert-' + localMessage.type">
                        <strong>@{{ localMessage.message }}</strong>
                    </div>
                </div>
                `
        })
        var app = new Vue({
            el: '#appReservaciones',
            data: {
                arrayForm: {
                    fecha_ingreso    : '',
                    fecha_salida     : '',
                    cantidad_personas: 1,
                },

                arrayHabitaciones: @json($data['habitaciones']),

                message      : {},
                estadoReserva: null,
                txtBusqueda  : '',
                tipoFiltrado : 0,

                arrayHabReservadas: [],

                checkBoxHab   : false,
                chckMismaFecha: true,
            },
            mounted(){

            },
            methods: {
                apiReservasDisponibles(){
                    if((this.arrayForm.fecha_ingreso != '') && (this.arrayForm.fecha_salida > this.arrayForm.fecha_ingreso)){
                        axios.post("{{ route('reservaciones.reservas_disponibles') }}",this.arrayForm)
                            .then((rs) => {
                                this.estadoReserva = (rs.data.type === 'success') ? true : false;

                                this.setMessage(rs.data.msj, rs.data.type);
                            })
                            .catch(error => {
                                console.log('Error JS: ',error);
                            })
                    }
                    else{
                        this.estadoReserva = false;
                        this.setMessage('Por favor ingrese una fecha valida.', 'danger');
                    }
                },
                limpiar(){
                    this.arrayForm     = {};
                    this.estadoReserva = null;
                },
                setMessage(m, t){
                    this.message = {
                        'message': m,
                        'type'   : t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 4000)
                },
                /*getIdHab(id){
                    //Encontrar posicion del ID de la habitacion seleccionada
                    //const index = this.arrayHabFechasDiferentes.indexOf(id);

                    //Si el metodo indexOf() retorna 0, es porque encontro un id duplicado, entonces procede a removerlo del array,
                    //De lo contrario, si retorna -1, no hay ningun id duplicado, asi que procede a guardarlo en el array.
                    if(index > -1) {
                        this.arrayHabFechasDiferentes.splice(index, 1);
                    }
                    else{
                        this.arrayHabFechasDiferentes.push(id);
                    }

                    console.log('Nº Id Hab: ',this.arrayHabFechasDiferentes);
                },*/
                
            },
            computed: {
                funcBuscarHabitaciones(){
                    switch(this.tipoFiltrado){
                        case 1://Sencilla
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion == 'SENCILLA'));
                        break;
                        case 2://Doble
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion == 'DOBLE'));
                        break;
                        case 3://Triple
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion == 'TRIPLE'));
                        break;
                        case 4://Cuadruple
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion == 'CUADRUPLE'));
                            //return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_tipo_habitaciones.codigo == 'JN'));
                        break;
                        default://Hab. disponibles
                            //return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_estado_habitaciones.token == 10002));
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => ((arrayHabitaciones.relacion_estado_habitaciones.token == 10002) || (arrayHabitaciones.numero_habitacion == parseInt(this.txtBusqueda))));
                        break;
                    }
                }
            },
        });
    </script>

@endsection
