@extends('layouts.app')

<style>
    .sidebarPanel {
        position: fixed;
        width: 280px;
        min-height: 90vh;
    }

    .panel-body {
        min-height: 550px;
    }
    .text,h5{
        font-size: 1.2rem;
        font-family: Arial, sans-serif;
    }
    h6{
        font-size: 1rem;
    }
    .card1,
    .card2,
    .card3,
    .cardpin,
    .cardinactiva {
        height: 14rem;
        text-align: center;
        margin-top: 10px;
        margin-right: 10px;
        padding: 3rem;
        border-radius: 10px;
        border: 2px solid grey;
    }

    .card1 {
        border-color: green;
    }

    .cardinactiva {
        width: 30rem;
    }

    .message {
        position: fixed;
        top: 20%;
        right: 1%;
        width: 15%;
        z-index: 100;
    }
</style>

@section('content')
    <div id="appHabitaciones">
        <div class="d-flex flex-column flex-shrink-0 p-3 bg-light shadow sidebarPanel">
            <p class="col-12 fs-4 text-uppercase">HABITACIONES</p>
            <hr>
            <ul class="nav nav-pills flex-column mb-5">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 0" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnHabDisponibles" checked>
                        <label class="form-check-label" for="rbtnHabDisponibles">Hab. disponibles <span v-if="tipoFiltrado == 0" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 1" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnHabOcupadas">
                        <label class="form-check-label" for="rbtnHabOcupadas">Hab. ocupadas <span v-if="tipoFiltrado == 1" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                </ul>
            </ul>

            <p class="col-12 fs-4 text-uppercase">CATEGORIAS</p>
            <hr>
            <ul class="nav nav-pills flex-column mb-5">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 2" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnAllCategorias">
                        <label class="form-check-label" for="rbtnAllCategorias">Todas las cateogrias <span v-if="tipoFiltrado == 2" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 3" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnSencilla">
                        <label class="form-check-label" for="rbtnSencilla">Sencilla <span v-if="tipoFiltrado == 3" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                </ul>
            </ul>

            <p class="col-12 fs-4 text-uppercase">TIPO DE HABITACION</p>
            <hr>
            <ul class="nav nav-pills flex-column mb-5">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 4" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnAllTipoHab">
                        <label class="form-check-label" for="rbtnAllTipoHab">Todos los tipos de hab. <span v-if="tipoFiltrado == 4" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                    <li class="list-group-item list-group-item-action">
                        <input @click="tipoFiltrado = 5" class="form-check-input me-1" type="radio" name="listGroupRadio" value="" id="rbtnSuite">
                        <label class="form-check-label" for="rbtnSuite">Suite <span v-if="tipoFiltrado == 5" class="badge text-bg-secondary">@{{ funcBuscarHabitaciones.length }}</span></label>
                    </li>
                </ul>
            </ul>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" id="dropdownUser2" data-bs-toggle="dropdown" aria-expanded="false">
                    <strong>Caja</strong>
                </a>
                <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser2">
                    <li><a class="dropdown-item" href="#">Agregar bloque</a></li>
                    <li><button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#cambiarPin">Cambiar PIN</button></li>
                    <li><a class="dropdown-item" href="#">Cerrar turno</a></li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="{{ route('cajas.logout') }}">Salir</a></li>
                </ul>
            </div>
        </div>

        <div class="container shadow panel-body p-4">
            <div class="row">
                <x-message></x-message>
            </div>
            
            <div class="alert show message" :class="'alert-'+ message.type" usuario="alert"
                v-show="message.message && message.type">
                <strong>@{{ message.message }}</strong>
            </div>

            <div class="container">
                <!--Caja de busqueda.-->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="mb-3 row">
                            <label for="txtBusqueda" class="col-sm-2 col-form-label">Buscar:</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control form-control-lg" placeholder="Buscar habitacion..." autocomplete="off" v-model="txtBusqueda">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!--Bucle FOR de habitaciones.-->
                    <div style="position: relative;" v-for="({id,numero_habitacion,relacion_tipo_habitaciones,relacion_forma_habitaciones,relacion_estado_habitaciones}, index) in funcBuscarHabitaciones" class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                        
                        <span style="position: absolute; top: 0%; left: 5%; z-index: 1;" class="mdi mdi-tag fs-1 text-muted"></span>

                        <div class="card border-success shadow">
                            <div class="card-body">
                                <div class="row mb-4">
                                    <h5 class="card-title placeholder-glow text-center fs-1"><b>@{{ numero_habitacion }}</b></h5>
                                </div>

                                <div class="row">
                                    <p class="card-text text-center fs-5">@{{ relacion_tipo_habitaciones.tipo_habitacion }} | @{{ relacion_tipo_habitaciones.codigo }}</p>
                                </div>

                                <div class="row text-center fs-4">
                                    <b>
                                        <span v-for="max_person in relacion_forma_habitaciones.max_personas" class="text-secondary me-1 mdi mdi-bed"></span>
                                    </b>
                                </div>

                                <div class="row text-center fs-5">
                                    <b><span class="badge rounded-pill text-bg-secondary mdi mdi-account"> @{{ relacion_forma_habitaciones.max_personas }}</span></b>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent border-0 p-3">
                                <div class="d-grid">
                                    <div class="btn-group" role="group" aria-label="Basic outlined example">
                                        <button v-if="relacion_estado_habitaciones.token == 10005 || relacion_estado_habitaciones.token == 10006" type="button" class="btn btn-block btn-outline-danger">SALIDA</button>
                                        <button v-else type="button" class="btn btn-block btn-outline-primary">ENTRADA</button>
                                        <button type="button" class="btn btn-outline-primary">RESERVA</button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <span class="mdi mdi-cog"></span>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary">
                                            <span class="mdi mdi-clipboard-check"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!--End row.-->
            </div><!--End container.-->
        </div>
    </div>

    <script>
        var compras = new Vue({
            el: '#appHabitaciones',
            data: {
                arrayHabitaciones: @json($p),

                message     : {},
                tipoFiltrado: 0,
                txtBusqueda : '',
            },
            methods: {
                setMessage(m, t){
                    this.message = {
                        'message': m,
                        'type'   : t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 3 * 1000)
                },
            },
            mounted(){

            },
            computed: {
                funcBuscarHabitaciones(){
                    switch(this.tipoFiltrado){
                        case 1://Ocupada limpia
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_estado_habitaciones.token == 10005 || arrayHabitaciones.relacion_estado_habitaciones.token == 10006));
                        break;
                        case 2://Todas las categorias
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion));
                        break;
                        case 3://Sencilla
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_forma_habitaciones.forma_habitacion == 'SENCILLA'));
                        break;
                        case 4://Todos los tipos de habitacion
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_tipo_habitaciones.codigo));
                        break;
                        case 5://Suite
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_tipo_habitaciones.codigo == 'JN'));
                        break;
                        default://Habitaciones disponibles
                            return this.arrayHabitaciones.filter((arrayHabitaciones) => (arrayHabitaciones.relacion_estado_habitaciones.token == 10006 || arrayHabitaciones.numero_habitacion == this.txtBusqueda));
                        break;
                    }
                }
            }
        })
    </script>
@endsection
