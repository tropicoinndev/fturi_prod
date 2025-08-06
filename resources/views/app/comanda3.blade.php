@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
            }
        body {
            background-color: #E0F2F1;
            font-family: sans-serif;
        }
        .menuSinScroll {/*Por defecto, su posicion es fijo en la parte superior*/
            background: {{ session('caja')->color_fondo }};
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            height: 140px;
        }
        .menuConScroll {
            position: fixed;
            top: 0;
            background: {{ session('caja')->color_fondo }};
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            height: 65px;
        }
        .ocultarAlHacerScroll {
            visibility: hidden;
        }
        .title {/*Titulo del header de cada pantalla*/
            color: #FAFAFA;
            font-size: 13px;
            font-weight: bold;
            font-family: sans-serif;
            letter-spacing: 0.5px;
        }
        .subtitle {/*Subtitulo del header de cada pantalla*/
            color: #FAFAFA;
            font-size: 10px;
            font-family: sans-serif;
            letter-spacing: 0.5px;
        }
        .bi-x-lg, .bi-search {/*Iconos del header de flecha izquierda y equis*/
            color: #FAFAFA;
            font-size: 20px;
        }
        .panel-main {/*Este es el panel de color blanco, sobre él se colocaran las card celestes*/
            border-radius: 20px;
            background: #FAFAFA;
            padding: 12px;
            width: 90% !important;
            height: auto;
            margin-top: -30px !important;
        }
        /*--Aninacion de entrada de opacidad para los registros--*/
        .panel-main-opacity {
            opacity: 0;/*Inicialmente, el panel es transparente*/
            transition: opacity 0.3s ease-in;/*Transicion de entrada suave de la opacidad*/
        }
        .panel-main-opacity.loaded {
            opacity: 1;/*Cuando la clase 'loaded' se agrega, el panel se vuelve visible*/
        }
        /*---*/
        .panel-card, .panel-card-modal {/*Estas son las card de color celeste donde se muestra la información*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 5px #BBDEFB;
            padding: 10px;
            transition: transform 0.4s ease;/*Transición para el cambio de tamaño*/
        }
        .panel-card:hover {
            cursor: pointer;
            transform: scale(0.97);/*Hacer el elemento un poco más pequeño en hover*/
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);/*Aplicar sombra abajo y a la derecha*/
        }
        /*---*/
        .panel-fixed {/*Este panel se usa para posicionar el botón azul en la parte inferior de la pantalla*/
            position: fixed;
            text-align: center;
            bottom: 9px;
        }
        /*Configuraciones de la modal*/
        .modal-content {
            background: #37474F;
            position: fixed;/*Indica la posicion inferior a la que se colocara la modal*/
            bottom: 0;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 0px;
            border-bottom-right-radius: 0px;
        }
        @media(max-width: 767px){/*Se cambiara el tamaño de la modal cuando la resolucion sea un telefono*/
            .modal-content {
                left: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
                width: 100%;/*Establecemos el ancho al 100% para dispositivos móviles*/
            }
        }
        @media(min-width: 768px) and (max-width: 991px){/*Se cambiara el tamaño de la modal cuando la resolucion sea una tablet*/
            .modal-content {
                margin: 0 auto;/*Centramos la modal en dispositivos de tablet*/
                width: 500px;
            }
        }
    </style>
@endsection

@section('content')
    <div style="margin-top: -8px;" id="appComandasMovil" class="container-fluid panel-main-opacity">
        <div class="row justify-content-center">
            
            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-10">
                            <h1 style="margin-top: 8px;" class="title ms-2">{{ session('caja')->caja }}</h1>
                            <div id="titles">
                                <h2 style="margin-top: -7px;" class="subtitle text-capitalize ms-2 mb-3">Hola, {{ auth()->user()->name }}.</h2>
                                <h1 class="title ms-2">TURNO 2</h1>
                                <h2 style="margin-top: -7px;" class="subtitle ms-2 mb-3">Apertura hace 1 sec · cierre en 5 horas</h2>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por numero de mesa">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->



            <!-- panel main -->
            <div class="panel-main">
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">MESAS AGREGADAS</p>

                <!--Componente de Vue-->
                {{-- <comandas_movil></comandas_movil> --}}

                <!--Alerta-->
                @if(session()->has('message') && session()->has('type'))
                    <div class="alert alert-{{ session('type') }} alert-dismissible fade show" role="alert">
                        <strong>{{ session('message') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- @{{ getComanda }} --}}
                <div v-if="getComanda.length > 0" class="row mb-5">
                    <!-- v-for -->
                    <div v-for="c in getComanda" class="col-12 col-sm-6 col-md-4 col-lg-3 mb-2">
                        <a :href="'/app/comandas/productos/'+c.cid" style="text-decoration: none;">
                            <div class="panel-card">
                                <div class="row">
                                    <p style="text-align: center; color: #263238; margin-bottom: 3px;">#@{{ c.mesa }}</p>
                                    <p style="font-size: 12px; color: #546E7A; margin-bottom: 1px;" class="text-truncate">@{{ c.clientes ?? 'CLIENTE/TITULAR ASIGNADO' }}</p>
                                    <p style="font-size: 10px; color: #37474F; margin-bottom: -3px;">Creada @{{ c.creacion }} · <span class="text-capitalize">@{{ c.usuarios.user }}</span></p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div v-else style="color: #546E7A;">
                    <hr>
                    <p style="margin-bottom: 1px;">¡Aún no hay registros disponibles o no se encontraron resultados coincidentes con tu búsqueda!</p>
                    <p><b><small>Intenta agregar registros o ajusta tus criterios de búsqueda.</small></b></p>
                </div>
            </div><!-- end panel main -->



            <!-- panel bottom -->
            <div class="panel-fixed">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle" data-bs-toggle="modal" data-bs-target="#modalMesaOcupada">
                                <i class="bi bi-person-gear"></i>
                            </button>

                            <span style="background-color: #0d6efd; padding: 3px;" data-bs-toggle="modal" data-bs-target="#modalCrearNuevaMesa">
                                <button style="height: 50px; background-color: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <i class="bi bi-plus-lg" style="color: #0d6efd;"></i>
                                </button>
                            </span>

                            <button id="btnSearch" type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <a href="{{ route('cajas.logout.app') }}" style="text-decoration: none;">
                                    <i style="color: white;" class="bi bi-box-arrow-right"></i>
                                </a>
                            </button>

                        </div>
                    </div>
                </div>
            </div><!-- end panel bottom -->



            <!-- Modal crear nueva mesa -->
            <div class="modal fade" id="modalCrearNuevaMesa" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        {{-- <form action="{{ route('comandas.store') }}" method="POST">
                            @csrf --}}
                            <div class="modal-body">
                                <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                    <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                        <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
                                    </div>
                    
                                    <!--Icono derecho-->
                                    <div class="position-absolute top-0 end-0" style="margin-top: 4px;">
                                        <span data-bs-dismiss="modal" aria-label="Close">
                                            <i style="color: #FAFAFA; font-size: 20px;" class="bi bi-x-lg me-3"></i>
                                        </span>
                                    </div>
                                </div>
    
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col px-0 py-3">
                                            <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3">
                                                <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                                <input v-model="mesa" @change="validarSiComandaExiste" style="border-color: #26C6DA;" type="number" min="1" step="1" class="form-control mb-2" required placeholder="Ingrese el numero de mesa">
                                                
            
                                                <div class="row align-items-center">
                                                    <div class="col-7 mt-3">
                                                        {{-- <p v-show="lblMensajeModal !== ''" style="color: #0097A7; font-size: 12px;"><i class="bi bi-check-lg"></i> @{{ lblMensajeModal }}</p> --}}
                                                        <p v-show="lblMensajeModal !== ''" :style="{ color: colorLblModal }"><i :class="iconModal"></i> @{{ lblMensajeModal }}</p>
                                                    </div>
                                                    <div class="col-5 text-end">
                                                        <button @click="crearComanda" :disabled="mesa <= 0 || statusError" style="background: #DAE0E5; color: #546E7A;" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {{-- </form> --}}
                        
                    </div>
                </div>
            </div><!--End modal crear nueva mesa-->

            <!-- Modal mesa ocupada -->
            <div class="modal fade" id="modalMesaOcupada" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        
                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
                                </div>
                
                                <!--Icono derecho-->
                                <div class="position-absolute top-0 end-0" style="margin-top: 4px;">
                                    <span data-bs-dismiss="modal" aria-label="Close">
                                        <i style="color: #FAFAFA; font-size: 20px;" class="bi bi-x-lg me-3"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col px-0 py-3">
                                        <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3">
                                            <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                            <input style="border-color: #26C6DA;" type="number" class="form-control mb-2" placeholder="1">
        
                                            <div class="row align-items-center">
                                                <div class="col-6 mt-3">
                                                    <p style="color: #FF7043;"><i class="bi bi-exclamation-circle"></i> Ocupada</p>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal mesa ocupada-->

            <!-- Modal mesa sin asignar -->
            <div class="modal fade" id="modalMesaSinAsignar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CREAR NUEVA MESA</h1>
                                </div>
                
                                <!--Icono derecho-->
                                <div class="position-absolute top-0 end-0" style="margin-top: 4px;">
                                    <span data-bs-dismiss="modal" aria-label="Close">
                                        <i style="color: #FAFAFA; font-size: 20px;" class="bi bi-x-lg me-3"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col px-0 py-3">
                                        <div style="margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3">
                                            <p style="color: #263238; margin-bottom: 2px;">Numero de mesa</p>
                                            <input style="border-color: #888888;" type="number" class="form-control mb-2" placeholder="Nº de mesa">
        
                                            <div class="row">
                                                <div class="col-12 text-end mt-3">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal mesa sin asignar-->

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        document.addEventListener('DOMContentLoaded',function(){
            document.querySelector('.panel-main-opacity').classList.add('loaded');
            
            //Declaracion de variables
            let menu              = document.getElementById("menu");
            let iconSearch        = document.getElementById('iconSearch');
            let iconClose         = document.getElementById('iconClose');
            let titles            = document.getElementById('titles');
            let txtSearch         = document.getElementById('txtSearch');

            let statusBtnSearch = false;
            let statusBtnClose  = false;
            let firstLoad       = true;

            let menuHeightWithoutScroll = `${140}px`;//Medidas en px
            let menuHeightWithScroll    = `${65}px`; //Medidas en px
            let maxHeightToScroll       = 65;        //Altura a la que se comanzara a hacer scroll

            //Iniciar por defecto con algunos elementos ocultos
            iconClose.style.display = 'none';
            txtSearch.style.display = 'none';
            
            //Escucha de botones
            iconSearch.addEventListener('click',function(){
                statusBtnSearch = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnSearchWithScroll();
                }
                else{
                    clickBtnSearchWithoutScroll();
                }
            });

            iconClose.addEventListener('click',function(){
                statusBtnClose = true;

                if(window.scrollY > maxHeightToScroll){
                    clickBtnCloseWithScroll();
                }
                else{
                    clickBtnCloseWithoutScroll();
                }
            });

            //Funciones que se usan para mostrar y ocultrar elementos cuando no se ha hecho scroll
            function clickBtnSearchWithoutScroll(){
                menu.style.height        = menuHeightWithoutScroll;//Aumentamos la altura del menu
                titles.style.display     = 'none';
                iconSearch.style.display = 'none';
                iconClose.style.display  = 'block';
                txtSearch.style.display  = 'block';
                txtSearch.focus();
            }
            function clickBtnCloseWithoutScroll(){
                menu.style.height        = menuHeightWithoutScroll;//Aumentamos la altura del menu
                titles.style.display     = 'block';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
            }

            //funciones que se usan para mostrar y ocultar elementos cuando ya se hizo scroll
            function clickBtnSearchWithScroll(){
                clickBtnSearchWithoutScroll();
            }
            function clickBtnCloseWithScroll(){
                menu.style.height        = menuHeightWithScroll;//Disminuimos la altura del menu
                titles.style.display     = 'none';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
            }

            function scroll(){
                window.addEventListener("scroll",function(){
                    //console.log("Scroll", window.scrollY);
                    
                    if(window.scrollY > maxHeightToScroll){//Con scroll
                        menu.classList.add("menuConScroll");
                        menu.classList.remove("menuSinScroll");

                        if(firstLoad){
                            titles.style.display = 'none';

                        }

                        if(statusBtnSearch){
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
                            titles.style.display = 'none';
                            //statusBtnSearch = false;
                            statusBtnClose  = false;
                        }

                        if(statusBtnClose){
                            menu.style.height    = menuHeightWithScroll;//Disminuimos la altura del menu
                            titles.style.display = 'none';
                        }
                        //titles.style.display = 'none';
                    }
                    else{//Sin scroll
                        menu.classList.add("menuSinScroll");
                        menu.classList.remove("menuConScroll");

                        if(firstLoad){
                            titles.style.display = 'block';
                        }
                        
                        if(statusBtnSearch){
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
                            titles.style.display = 'none';
                            statusBtnSearch = false;
                            statusBtnClose  = false;
                            firstLoad       = false;
                        }

                        if(statusBtnClose){
                            menu.style.height    = menuHeightWithoutScroll;//Aumentamos la altura del menu
                            titles.style.display = 'block';
                        }
                        //titles.style.display = 'block';
                    }
                });
            }
            scroll();
        });



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
        var app = window.appVue({
            //Emitir algo...
            data(){
                return {
                    comandas: @json($comandas),
                    txtBusqueda: '',

                    mesa: null,
                    lblMensajeModal: '',
                    colorLblModal: '',
                    iconModal: '',
                    statusError: null,
                }
            },
            mounted() {
                //console.log('Component mounted. Comandas Blade');
            },
            methods: {
                validarSiComandaExiste: function(){
                    axios.post("{{ route('comandas.store.app.validate') }}",{
                        mesa: parseInt(this.mesa),
                    }).then((r) => {
                        //console.log(`R validacion: ${r.data.message}`);

                        if(r.data.status){
                            this.lblMensajeModal = r.data.message;
                            this.colorLblModal = '#0097A7';
                            this.iconModal = 'bi bi-check-lg';
                            this.statusError = false;
                        }
                        else{
                            this.lblMensajeModal = r.data.message;
                            this.colorLblModal = '#FF7043';
                            this.iconModal = 'bi bi-exclamation-circle';
                            this.statusError = true;
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err.response.data);
                    });
                },

                crearComanda: function(){
                    axios.post("{{ route('comandas.store.app') }}",{
                        mesa: parseInt(this.mesa),
                    }).then((r) => {
                        console.log('Res: ',r.data);

                        if(r.data.status){
                            this.lblMensajeModal = r.data.message;

                            setTimeout(() => {
                                window.location.href = '/app/comandas';
                            }, 1.5 * 1000);
                        }
                        else{
                            this.lblMensajeModal = r.data.message;
                            this.colorLblModal = '#FF7043';
                            this.iconModal = 'bi bi-exclamation-circle';
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
            },
            computed: {
                getComanda: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.comandas.filter(c => regx.test(c.mesa));
                },
            }
        });
        app.component('comandas_movil',component.comandas_movil);
        app.mount("#appComandasMovil");
    </script>
@endsection
