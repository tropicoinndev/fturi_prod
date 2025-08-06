@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior que trae por defecto Laravel cuando esta en tamaño movil*/
            display: none;
        }
        body {
            background: #E0F2F1;
            font-family: sans-serif;
            letter-spacing: 0.4px;
        }

        .color-naranja {
            color: #FF7043;
        }
        .color-gris {
            color: #546E7A;
        }
        .border-ocean {
            border-color: #26C6DA;
        }

        .menuSinScroll, .menuConScroll {
            position: fixed;
            top: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            padding: 10px;
            width: 100%;
            background: {{ session('caja')->color_fondo }};/*#37474F*/
            color: #FAFAFA;
            transition: height 0.5s ease-in-out;
        }
        .menuSinScroll {
            height: 141px !important;
            z-index: 10;
        }
        .menuConScroll {
            /*height: 65px !important;*//*auto, Debe ser un numero no auto para que funcione la animacion*/
            z-index: 135;
        }

        .title {/*Titulo del header*/
            font-weight: 700;
            font-size: 9.5pt;
        }
        .subtitle {/*Subtitulo del header*/
            font-weight: 400;
            font-size: 8pt;
        }
        .mdi-magnify, .mdi-close {/*Iconos del header*/
            font-size: 20px;
            color: #FAFAFA;
        }

        #panel-main {
            position: relative;
            border-radius: 20px;
            background: #fff;
            padding: 15px;
            width: 90%;
            min-height: 89vh;
            margin-top: 30px;
            z-index: 125;
        }
        /*--Aninacion de entrada de opacidad para los registros--*/
        #panel-main-opacity {
            opacity: 0;/*Inicialmente, el panel es transparente*/
            transition: opacity 0.3s ease-in;/*Transicion de entrada suave de la opacidad*/
        }
        #panel-main-opacity.loaded {
            opacity: 1;/*Cuando la clase 'loaded' se agrega, el panel se vuelve visible*/
        }
        /*---*/

        .panel-card, .panel-card-modal {/*Estas son las card de color celeste donde se muestra la informacion*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 5px #BBDEFB;
            padding: 10px;
            /*transition: transform 0.4s ease;*//*Transicion para el cambio de tamaño*/
        }
        /*.panel-card:hover {/*Aninacion de las card*/
            /*cursor: pointer;
            transform: scale(0.97);/*Hacer el elemento un poco mas pequeño en hover*/
            /*box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);/*Aplicar sombra abajo y a la derecha*/
        /*}*/
        .panel-card:active {
            background-color: #90CAF9;
            border-bottom: solid 5px #90CAF9;
        }

        #panel-bottom {/*Botones de la parte inferior de la pantalla*/
            position: fixed;
            bottom: 0;/*Inicia visible*/
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 10px;
            left: 0;
            width: 100%;
            /*background: #37474F;*/
            color: #fff;
            transition: bottom 0.5s ease-in-out;
            z-index: 125;
        }
        #panel-bottom.active {
            bottom: 0;/*Mostrar cuando se activa*/
        }
        /*---*/

        /*Boton para desplazarse desde abajo de la ventana hacia arriba*/
        #btnScrollUp {
            position: fixed;
            bottom: 23px;/*Alineacion central con el panel-bottom*/
            right: 12px;
            /*right: 5%;*/
        }

        /*Configuraciones de la modal*/
        .modal-content {
            background: #37474F;
            position: fixed;/*Indica la posicion inferior a la que se colocara la modal*/
            bottom: 0;
            left: 0;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        /*Input Google*/
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-google {
            width: 100%;
            padding: 0.5rem 1rem;
            border: 1px solid #ced4da;
            border-radius: 0.25rem;
            transition: all 0.3s;
        }
        .input-google:focus {
            outline: none;
            /*border-color: #26C6DA;*/
        }
        .form-label-google {
            position: absolute;
            top: 10px;
            left: 1rem;
            color: #6c757d;
            font-size: 0.85rem;
            pointer-events: none;
            transition: all 0.3s;
        }
        .input-google:focus + .form-label-google,
        .input-google:not(:placeholder-shown) + .form-label-google {
            top: -25px;
            font-size: 14px;
            color: #263238;
        }
        /*Input Google*/

        .msjAlert {
            position: fixed;
            /*bottom: 10%;*/
            bottom: -80px;/*Posicion inicial fuera de la pantalla*/
            width: 300px;
            right: 5%;
            border-radius: 8px;
            /*border-left: solid 5px #b92e2e97;*/
            z-index: 150;

            transition: bottom 0.7s ease-in-out;/*Transicion suave de la propiedad de bottom*/
        }
        .msjAlert.loaded {
            bottom: 8%;/*Cuando la clase 'loaded' se agrega, la alerta se desplaza hacia arriba*/
        }

        /*Resolucion tablet*/
        @media(min-width: 768px){
            .modal-content {
                right: 0;      /*Nos aseguramos que la modal no tenga margen a la derecha*/
                margin: 0 auto;/*Centramos la modal en dispositivos tablet*/
                width: 500px;  /*En resolucion movil tendra un ancho especifico*/
            }
        }
    </style>
@endsection

@section('content')
    <div id="appComandasMovil" class="container-fluid">
        <div class="row justify-content-center">

            <!--Alerta-->
            <div :style="'border-left: solid 5px '+bordeIzquierdoAlerta+' !important'" class="alert alert-dismissible fade show msjAlert" :class="'alert-' + message.type" role="alert" v-show="message.message && message.type">
                <b><small>@{{ message.message }}</small></b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- panel-header -->
            <nav id="panel-header" :class="{'menuSinScroll': !scrollInit, 'menuConScroll': scrollInit}" :style="{ height: scrollInit && !busquedaShow ? '63px' : '108px' }">
                <div style="margin-top: 6px;" class="row"><!--Margen superior positivo para centrar la fila con el panel-header-->
                    @if(session('turno'))
                        @php
                            $fApertura = Carbon::parse(session('turno')->fecha.' '.session('turno')->opcion->apertura);
                            $cierre    = Carbon::parse(session('turno')->fecha.' '.session('turno')->opcion->cierre);

                            if(session('turno')->opcion->cierre < session('turno')->opcion->apertura){
                                $cierre = $cierre->addDay();
                            }
                            $horas   = $fApertura->diffInHours($cierre);
                            $fCierre = $fApertura->addHours($horas);
                        @endphp

                        <div class="col-10 text-uppercase">
                            <h1 class="title ms-4">{{ session('caja')->caja }}</h1>
                            <h2 style="margin-top: -7px;" class="subtitle ms-4">Hola · {{ auth()->user()->name }}</h2>

                            <div id="information" v-show="!scrollInit && !busquedaShow" class="mt-3"><!--Esta informacion se ocultara al hacer scroll-->
                                <h1 class="title ms-4">{{ session('turno')->opcion->turno }}</h1>
                                <h2 style="margin-top: -7px;" class="subtitle text-truncate ms-4">Apertura {{ Carbon::parse(session('turno')->apertura)->diffForHumans() }} · cierre {{ $fCierre->diffForHumans() }}</h2>
                            </div>
                        </div>

                        <div class="col-2 text-end">
                            <button style="margin-top: -7px;" type="button" class="btn">
                                <i v-show="!busquedaShow" class="mdi mdi-magnify" @click="busquedaShow = true"></i>
                                <i v-show="busquedaShow" class="mdi mdi-close" @click="busquedaShow = false"></i>
                            </button>
                        </div>
                    @endif
                </div>

                <!--Caja de busqueda-->
                <div class="row mt-1 mb-2" :class="{ active: !busquedaShow }" v-show="busquedaShow">
                    <div class="col-10 m-auto">
                        <input v-model="txtSearch" ref="searchInput" style="border: 1px solid #26C6DA;" type="text" class="form-control form-control-sm rounded-pill" aria-label="First name" placeholder="Buscar por número de mesa"/>
                    </div>
                </div>
            </nav>

            <!-- panel-main -->
            <section id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <div style="margin-top: 10px;" class="row">
                    <div class="col-8">
                        <p style="font-weight: 500; font-size: 10.5pt;" class="color-gris text-uppercase">Mesa agregadas <small style="color: #8c8e8f;">(@{{ getComanda.length }})</small></p>
                    </div>
                    <div class="col-4 text-end">
                        {{-- <button @click="lockScreen" type="button" class="btn btn-secondary btn-sm rounded-pill me-2">
                            <i class="bi bi-lock-fill"></i>
                        </button> --}}
                        <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalInfo">
                            <span class="mdi mdi-information-outline"></span>
                        </button>
                    </div>
                </div>

                <!--Componente de Vue-->
                {{-- <comandas_movil></comandas_movil> --}}

                {{-- @{{ getComanda }} --}}
                <div v-show="getComanda.length > 0" id="panel-main-opacity"><!--Se usa para la opacidad-->
                    <div class="row">
                        <!-- v-for -->
                        <div v-for="c in getComanda" class="col-6 col-sm-4 col-md-4 col-lg-3 mb-2"><!--Margen inferior entre cada card-->
                            <a :href="'/app/comandas/productos/'+c.cid" class="text-decoration-none">
                                <div class="panel-card color-gris">
                                    <p style="font-weight: 600; font-size: 10.5pt; margin-bottom: 3px;" class="text-center">#@{{ c.mesa }}</p>
                                    <p style="font-weight: 500; font-size: 10.5pt; margin-bottom: 1px;" class="text-uppercase text-center text-truncate">@{{ c.titular != null ? c.titular : (c.clientes_id != null ? c.clientes.nombre : 'CLIENTE / TITULAR ASIGNADO') }}</p>
                                    <p style="font-weight: 500; font-size: 8.5pt; margin-bottom: -3px;" class="text-center text-truncate">Creada @{{ c.creacion }} · <span class="text-capitalize">@{{ c.usuarios.user }}</span></p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-show="getComanda.length <= 0" class="color-gris">
                    <hr>
                    <p style="margin-bottom: 1px;">¡No se encontraron registros disponibles o no coincidieron los resultados con tu búsqueda!</p>
                    <p><b><small>Intenta agregar registros o ajusta tus criterios de búsqueda.</small></b></p>
                </div>
            </section>

            <!-- panel-bottom -->
            <div id="panel-bottom">
                <div class="row text-center">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle" data-bs-toggle="modal" data-bs-target="#modalCambiarPin">
                                <span class="mdi mdi-lock-outline"></span>
                            </button>

                            <span style="background: #0d6efd; padding: 3px;" data-bs-toggle="modal" data-bs-target="#modalCrearNuevaMesa">
                                <button style="height: 50px; background: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <span style="color: #0d6efd;" class="mdi mdi-plus"></span>
                                </button>
                            </span>

                            <button type="button" class="btn btn-primary btn-lg rounded-end-circle" title="Cerrar Sesión">
                                <a href="{{ route('cajas.logout.app') }}" class="text-decoration-none">
                                    <span class="mdi mdi-logout text-white"></span>
                                </a>
                            </button>

                        </div>
                    </div>
                </div>

                <!--Boton scroll up-->
                <div v-show="getComanda && getComanda.length >= 10" id="btnScrollUp">
                    <button type="button" title="Ir arriba" class="btn btn-outline-secondary btn-sm rounded-5">
                        <span class="mdi mdi-chevron-up"></span>
                    </button>
                </div>
            </div>



            <!--Modal crear mesa-->
            <div class="modal fade" id="modalCrearNuevaMesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">Crear Nueva Mesa</h1>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <span class="mdi mdi-close"></span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div style="margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">
                                <!--Forma 1-->
                                {{-- <p style="color: #263238; margin-bottom: 2px;">Número de mesa</p>
                                <input v-model="txtNumMesa" @change="validarSiComandaExiste" style="border-color: #26C6DA;" type="number" min="1" step="1" class="form-control rounded-pill mt-2 mb-3" required placeholder="Ingrese el número de mesa"> --}}

                                <!--Forma 2-->
                                <div class="form-group mt-3">
                                    <input v-model="txtNumMesa" @keyup="validarSiComandaExiste" style="border-color: #26C6DA;" type="number" min="1" step="1" id="numeroMesa" class="input-google rounded-pill" aria-label="Número de mesa" required placeholder="">
                                    <label for="numeroMesa" class="form-label form-label-google">Ingrese el número de mesa</label>
                                </div>

                                <div class="row">
                                    <div class="col-7">
                                        <p v-show="messageLblModal" style="margin-top: 5px;" :style="{ color: colorLblModal }"><i :class="iconLblModal"></i> @{{ messageLblModal }}</p>
                                    </div>
                                    <div class="col-5 text-end">
                                        <button @click="crearComanda" :disabled="txtNumMesa <= 0 || statusError" style="background: #DAE0E5; color: #546E7A;" class="btn"><span class="mdi mdi-plus"></span> Agregar</button>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <!--Modal cambiar pin-->
            <div class="modal fade" id="modalCambiarPin" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">Cambiar PIN · <span class="fs-6">{{ session('caja')->caja }}</span></h1>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <span class="mdi mdi-close"></span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div style="margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">

                                <!--Alerta-->
                                <div v-if="typeCambiarPin && msjCambiarPin" :style="'border-radius: 12px; border-left: solid 5px '+colorBordeIzquierdo" :class="'alert alert-'+typeCambiarPin" role="alert">
                                    @{{ msjCambiarPin }}
                                </div>

                                <!--Indicaciones-->
                                <small class="color-naranja">
                                    <p style="margin-bottom: -1px;">- Todos los campos son requeridos *</p>
                                    <p>- Solo se permiten cuatro números como máximo</p>
                                </small>

                                <!--Inputs-->
                                <div class="mb-3">
                                    <label for="pin" class="form-label color-gris">PIN Actual: <span class="color-naranja">*</span></label>
                                    <input v-model="txtPinActual" @input="validateInput('txtPinActual', $event)" style="font-size: 15px;" type="password" class="form-control border-ocean rounded-pill" id="pin" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="Digite el PIN Actual">
                                </div>

                                <div class="mb-3">
                                    <label for="npin" class="form-label color-gris">PIN Nuevo: <span class="color-naranja">*</span></label>
                                    <input v-model="txtPinNuevo" @input="validateInput('txtPinNuevo', $event)" style="font-size: 15px;" type="password" class="form-control border-ocean rounded-pill" id="npin" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="Digite el Nuevo PIN">
                                </div>

                                <div class="mb-3">
                                    <label for="cpin" class="form-label color-gris">Confirmar Nuevo PIN: <span class="color-naranja">*</span></label>
                                    <input v-model="txtConfirmarPin" @input="validateInput('txtConfirmarPin', $event)" style="font-size: 15px;" type="password" class="form-control border-ocean rounded-pill mb-2" id="cpin" required maxlength="4" pattern="[0-9]{4}" inputmode="numeric" placeholder="Confirme el Nuevo PIN" aria-describedby="pinHelp">
                                    <div id="pinHelp" class="form-text color-gris"><small>Al cambiar el PIN tendrá que volver a iniciar sesión en esta caja. (El PIN solo cambiará en esta caja).</small></div>
                                </div>

                                <!--Boton-->
                                <hr>
                                <div class="mb-2 text-end">
                                    <button @click="cambiarPin" :disabled="txtPinActual.length <= 3 || txtPinNuevo.length <= 3 || txtConfirmarPin.length <= 3" type="submit" class="btn btn-primary rounded-pill"><span class="mdi mdi-lock-open-outline"></span> Cambiar PIN</button>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>



            <!-- Modal info-->
            <div class="modal fade" id="modalInfo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">Información de ayuda</h1>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <span class="mdi mdi-close"></span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div style="margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">

                                <!--Indicador de avance-->
                                <div class="position-relative m-3 mb-4">
                                    <div class="progress" role="progressbar" aria-label="Progress" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="height: 1px;">
                                        <div class="progress-bar" :style="{width: porcentaje}"></div>
                                    </div>
                                    <button type="button" class="position-absolute top-0 start-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn1" style="width: 2rem; height:2rem;">1</button>
                                    <button type="button" class="position-absolute top-0 start-50 translate-middle btn btn-sm rounded-pill" :class="colorBtn2" style="width: 2rem; height:2rem;">2</button>
                                    <button type="button" class="position-absolute top-0 start-100 translate-middle btn btn-sm rounded-pill" :class="colorBtn3" style="width: 2rem; height:2rem;">3</button>
                                </div>
                                <hr style="margin-top: -1px;">

                                <!--Descripciones-->
                                <div class="row text-center">
                                    <div class="col-12">
                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle">
                                                <span class="mdi mdi-lock-outline"></span>
                                            </button>

                                            <span style="background: #0d6efd; padding: 3px;">
                                                <button style="height: 50px; background: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                                    <span style="color: #0d6efd;" class="mdi mdi-plus"></span>
                                                </button>
                                            </span>

                                            <button type="button" class="btn btn-primary btn-lg rounded-end-circle" title="Cerrar Sesión">
                                                <a href="javascript:void(0);" class="text-decoration-none">
                                                    <span class="mdi mdi-logout text-white"></span>
                                                </a>
                                            </button>

                                        </div>
                                    </div>

                                    <!--Escreen 1-->
                                    <div v-if="screen === 1" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-left: -100px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El primer ícono que tiene la figura de una persona y un engranaje, tiene como funcionalidad principal, cambiar el PIN de la caja donde se ha logueado el usuario actual;
                                            En ese formulario deberá ingresar el PIN actual, el nuevo PIN y la confirmación del nuevo PIN, luego se tendrá que iniciar sesión nuevamente para tomar los nuevos cambios.</p>
                                    </div>

                                    <!--Escreen 2-->
                                    <div v-if="screen === 2" style="height: 200px;">
                                        <p style="font-size: 15pt;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El segundo ícono que representa el signo más, abrirá una ventana emergente con un campo de entrada, el cual le permitirá introducir el número de mesa a ser creada;
                                            Ademas, le notificará si la mesa está ocupada o disponible.</p>
                                    </div>

                                    <!--Escreen 3-->
                                    <div v-if="screen === 3" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-right: -100px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El tercer ícono que está representado con una flecha hacia la derecha, le permitirá automaticamente salir o cerrar sesión de la caja actual;
                                            Para que así, un nuevo usuario pueda loguerse, o simplemente dar por finalizado el turno.</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button v-show="screen !== 1" @click="decrementScreen" type="button" class="btn btn-outline-secondary rounded-pill me-3"><span class="mdi mdi-arrow-left"></span> Anterior</button>
                                        <button @click="incrementScreen" type="button" class="btn btn-outline-primary rounded-pill">Siguiente <span class="mdi mdi-arrow-right"></span></button>
                                    </div>
                                </div>

                                <!--Boton-->
                                <hr>
                                <div class="mb-2 text-end">
                                    <button type="submit" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal"><i class="bi bi-check-lg"></i> Ok, lo tengo</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        /*CREACION DE UNA NUEVA INSTANCIA DE VUE DENTRO DE UN ARCHIVO BLADE.
            1.- Crear el componente de Vue en la ruta: \resource\js\components\comandas_movil.vue
            2.- Ir a la ruta: \resource\js\app.js y hacer lo siguiente:
                Importar componente: import comandas_movil from "./components/comandas_movil.vue";
                Agregarlo al objeto de componentes: window.component = {comandas_movil: comandas_movil},
            3.- En este mismo archivo de Blade, referenciar el compomente y montarlo en la aplicacion:
                <comandas_movil></comandas_movil>
                app.component('comandas_movil',component.comandas_movil);
                app.mount("#appComandasMovil");
            4.- Compilar archivos: npm run build
        */
        let app = window.appVue({
            //Emitir algo...
            data(){
                return {
                    //---Modal info---
                    screen: 1,

                    porcentaje: null,
                    colorBtn1: null,
                    colorBtn2: null,
                    colorBtn3: null,
                    //----------------

                    //---Scroll---
                    scrollInit:   false,//Se inicia sin scroll
                    busquedaShow: false,//Se usa para mostrar u ocultar la caja de busqueda y los iconos de equis y lupa
                    //-----

                    //---Data---
                    comandas: @json($comandas),
                    //-----

                    //---Cajas de texto---
                    txtSearch:  '',
                    txtNumMesa: null,
                    //-----

                    //---Elementos de la modal---
                    iconLblModal:    null,//Icono dinamico
                    colorLblModal:   null,//Color dinamico
                    messageLblModal: null,//Mensaje dinamico
                    statusError:     null,//Permite saber si ya existe una comanda para deshabilitar el boton de crear
                    //-----

                    //---Cambiar PIN---
                    txtPinActual:    '',
                    txtPinNuevo:     '',
                    txtConfirmarPin: '',

                    typeCambiarPin: null,
                    msjCambiarPin: null,
                    colorBordeIzquierdo: null,
                    //---

                    //---Alerta flotante---
                    message: {},
                    bordeIzquierdoAlerta: null,
                    //-----
                }
            },
            created(){
                window.Echo.private('pedidos.response.'+{{ session('caja')->id }})
                    .listen('ResponsePedidos',(d) => {
                        console.log('Evento desde produccion: ',d.comanda_detalles.comandas.detalles_comanda);

                        this.sound();
                        this.setMessage(d.mensaje, 'success');
                    });

                this.porcentScreen();
            },
            mounted(){
                //1.- Se usa para agregar opacidad al panel-main donde se muestran los registros
                this.funcPanelMainOpacity();

                //2.- Se usa para la funcionalidad general del scroll up y down
                this.funcScrollUpDown();

                //3.- Se usa para mostrar u ocultar el panel-bottom, ya sea si se ha hecho scroll up o down
                this.funcPanelBottomUpDown();

                //4.- Lanzar alerta de prueba
                //this.setMessage('Mensaje de prueba','success');
            },
            beforeUnmounted(){
                window.removeEventListener('scroll',this.funcScrollUpDown);
            },
            methods: {
                incrementScreen(){
                    this.screen++;
                    this.porcentScreen();
                },
                decrementScreen(){
                    this.screen--;
                    this.porcentScreen();
                },
                porcentScreen(){
                    switch(this.screen){
                        case 1:
                        default:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-secondary';
                            this.colorBtn3 = 'btn-secondary';
                            this.porcentaje = '0%';
                            this.screen = 1;
                        break;
                        case 2:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-secondary';
                            this.porcentaje = '50%';
                        break;
                        case 3:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-primary';
                            this.porcentaje = '100%';
                        break;
                    }
                },
                lockScreen(){
                    window.location.href = "{{ route('comandas.lock') }}";
                },

                funcPanelMainOpacity(){
                    //Primero debe cargar todo el DOM y luego agregar la clase
                    document.addEventListener('DOMContentLoaded',() => {
                        document.getElementById('panel-main-opacity').classList.add('loaded');
                    });
                },
                funcScrollUpDown(){
                    //Se usa para que el boton scroll up nos lleve a la parte superior de la ventana
                    let btnScrollUp = document.getElementById('btnScrollUp');
                    btnScrollUp.style.visibility = 'hidden';//El boton scroll up comienza oculto ya que aun no se ha hecho scroll up

                    btnScrollUp.addEventListener('click',() => {//Escucha del evento click del boton scroll up
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                    //---

                    //---
                    window.addEventListener('scroll',() => {
                        //---Muestra u oculta el boton scroll up, dependiendo si esta en la parte superior de la ventana o si se ha hecho scroll
                        let position = window.scrollY;//Calcula la posicion actual del scroll
                        /*if(position > 0)
                            btnScrollUp.style.visibility = 'visible';//Como ya se hizo scroll hacia abajo aunque sea un poco, se muestra el boton scroll up
                        else
                            btnScrollUp.style.visibility = 'hidden';//Si aun no se ha hecho scroll, el boton scroll up se mantiene oculto*/
                        btnScrollUp.style.visibility = (position > 0) ? 'visible' : 'hidden';



                        //---Scroll general
                        this.scrollInit = position >= 12;
                    });
                },
                funcPanelBottomUpDown(){
                    let lastScrollTop = 0;
                    let sensibility   = 5;//5 - 100 Entre mas bajo el numero, mayor sensibilidad al desplazamiento

                    let navbarHeightBottom = document.getElementById('panel-bottom').offsetHeight;

                    window.addEventListener('scroll',() => {
                        let panelBottom = document.getElementById('panel-bottom');

                        let currentScroll = parseInt(window.pageYOffset || document.documentElement.scrollTop);

                        //Se usa para calcular la precision de la sensibilidad del desplazamiento
                        if(Math.abs(lastScrollTop - currentScroll) <= sensibility)
                            return;

                        //True = scroll up, False = scroll down, Contiene una variable negativa
                        panelBottom.style.bottom = (currentScroll > lastScrollTop) ? -navbarHeightBottom + 'px' : '0';

                        lastScrollTop = currentScroll;
                    });
                },
                setMessage(m, t){
                    this.bordeIzquierdoAlerta = (t === 'danger') ? '#b92e2e97' : '#2eb96a97';

                    this.message = {
                        message: m,
                        type: t,
                    };

                    //Se coloco dentro de un timer 0, ya que funcionaba pero al segundo intento, este lo lanza inmediatamente
                    setTimeout(() => {
                        document.querySelector('.msjAlert').classList.add('loaded');
                    }, 0);

                    setTimeout(() => {
                        this.message = {};
                    }, 8 * 1000);
                },
                sound: function() {
                    if (Notification.permission !== "granted") {
                        Notification.requestPermission();
                    } else {
                        let notification = new Notification("¡Nuevo pedido!", {
                            body: "Hay una nueva solicitud de pedidos"
                        });
                    }
                    const sound = "{{ asset('sonidos/notify.wav') }}";
                    new Audio(sound).play();
                    //console.log('sonando');
                },
                validarSiComandaExiste(){
                    axios.post("{{ route('comandas.store.app.validate') }}",{
                        mesa: parseInt(this.txtNumMesa),
                    }).then((r) => {
                        //console.log(`r validacion: ${r.data.message}`);

                        if(r.data.status){
                            this.iconLblModal    = 'bi bi-check-lg';
                            this.colorLblModal   = '#0097A7';
                            this.messageLblModal = r.data.message;
                            this.statusError     = false;
                        }
                        else{
                            this.iconLblModal    = 'bi bi-exclamation-circle';
                            this.colorLblModal   = '#FF7043';
                            this.messageLblModal = r.data.message;
                            this.statusError     = true;
                        }
                    }).catch((err) => {
                        console.log(`Error JS: ${err.response.data}`);
                    });
                },
                crearComanda(){
                    axios.post("{{ route('comandas.store.app') }}",{
                        mesa: parseInt(this.txtNumMesa),
                    }).then((r) => {
                        //console.log(`result: ${r.data.comandaId}`);

                        if(r.data.status){
                            //this.messageLblModal = r.data.message + ', espere...';

                            //setTimeout(() => {
                                window.location.href = `/app/comandas/productos/${r.data.comandaId}`;
                            //}, 1.5 * 1000);
                        }
                        else{
                            this.iconLblModal    = 'bi bi-exclamation-circle';
                            this.colorLblModal   = '#FF7043';
                            this.messageLblModal = r.data.message;
                        }

                    }).catch((err) => {
                        console.log(`Error JS: ${err}`);
                    });
                },
                cambiarPin(){
                    if(this.txtPinActual.length == 4 && this.txtPinNuevo.length == 4 && this.txtConfirmarPin.length == 4){
                        axios.post("{{ route('cajas_users.appPin') }}",{
                            txtPinActual:    parseInt(this.txtPinActual),
                            txtPinNuevo:     parseInt(this.txtPinNuevo),
                            txtConfirmarPin: parseInt(this.txtConfirmarPin),
                        }).then((r) => {
                            //console.log('r: ',r);

                            this.msjCambiarPin = r.data.message;

                            if(r.data.status){
                                this.typeCambiarPin = 'success';
                                this.colorBordeIzquierdo = '#2eb96a97';
                                window.location.href = "{{ route('cajas.login.app') }}";
                            }
                            else{
                                this.typeCambiarPin = 'danger';
                                this.colorBordeIzquierdo = '#b92e2e97';
                            }
                        }).catch((err) => {
                            console.log('Error JS: ',err);
                        });
                    }
                },
                validateInput(inputName, event){//Recibe como parametro el v-model de cada input y el event
                    let input = event.target.value;

                    input = input.replace(/\s/g, '');//Eliminar espacios en blanco
                    input = input.replace(/\D/g, '');//Eliminar caracteres que no sean números

                    this[inputName] = input;//Actualizar el valor del input correspondiente
                },
            },
            computed: {
                getComanda(){
                    let regx = new RegExp((this.txtSearch).toLowerCase());
                    return this.comandas.filter(c => regx.test(c.mesa));
                },
            },
            watch: {//Estar al pendiente de la variable 'busquedaShow' para cuando cambie de estado, establecer el foco en la caja de busqueda
                busquedaShow: function(newValue){
                    if(newValue){
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    }
                }
            },
        });
        app.mount('#appComandasMovil');
    </script>
@endsection
