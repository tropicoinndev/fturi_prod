@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior que trae por defecto Laravel cuando esta en tamaño movil*/
            display: none;
        }
        body {
            background: #E0F2F1;
            font-family: sans-serif;
        }

        /*Panel header*/
        #panel-header {
            position: fixed;
            top: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            letter-spacing: 0.5px;
            /*padding: 25px 25px;*//*Padding normal*/ /*Arriba-Abajo, Izquierda-Derecha*/
            padding: 15px 25px;
            left: 0;
            width: 100%;
            /*60 altura solo con titulos*/
            /*140 altura con caja de busqueda*/
            height: 140px;
            background: #37474F;
            color: #FAFAFA;
            transition: height 0.5s ease-in-out;
        }
        #panel-header.active-search {/*Se usa para mantener siempre la altura cuando la caja de busqueda esta activa*/
            height: 110px !important;
        }
        #panel-header.shrink {
            /*padding: 12px 20px;*//*Reduccion del padding al hacer scroll*/ /*Arriba-Abajo, Izquierda-Derecha*/
            height: 60px;
        }
        /*---*/

        .title {/*Titulo del header de cada pantalla*/
            font-size: 13px;
            font-weight: bold;
        }
        .subtitle {/*Subtitulo del header de cada pantalla*/
            font-size: 10px;
        }
        .bi-arrow-left, .bi-x-lg, .bi-search, .bi-three-dots-vertical {/*Iconos del header de flecha izquierda y equis*/
            color: #FAFAFA;
            font-size: 16px;
        }

        #panel-main {/*Este es el panel de color blanco, sobre él se colocaran las card celestes*/
            border-radius: 20px;
            background: #FAFAFA;
            padding: 12px;
            width: 90%;
            height: auto;
            margin-top: 102px;
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

        .btnAbecedario {
            background: #DAE0E5;
            color: #546E7A;
            border-radius: 100%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            padding: 1px;
            margin-right: 3px;
        }
        #abecedarioDiv {
            display: none;
        }

        .panel-card, .panel-card-modal {/*Estas son las card de color celeste donde se muestra la informacion*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 4px #BBDEFB;
            padding: 10px;
            transition: transform 0.4s ease;/*Transicion para el cambio de tamaño*/
        }
        .panel-card:hover {/*Aninacion de las card*/
            cursor: pointer;
            transform: scale(0.97);/*Hacer el elemento un poco mas pequeño en hover*/
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);/*Aplicar sombra abajo y a la derecha*/
        }

        /*Panel bottom*/
        #panel-bottom {
            position: fixed;
            /*bottom: -115px;*//*Inicia oculto*/
            bottom: 0;/*Inicia visible*/
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            padding: 10px;
            left: 0;
            width: 100%;
            /*background: #37474F;*/
            color: #fff;
            transition: bottom 0.5s ease-in-out;
            z-index: 2;
        }
        #panel-bottom.active {
            bottom: 0;/*Mostrar cuando se activa*/
        }
        /*---*/

        /*Boton para desplazarse desde abajo de la ventana hacia arriba*/
        #btnScrollUp {
            position: fixed;
            bottom: 23px;
            right: 12px;
            /*right: 5%;*/
        }

        .msjAlert {
            position: fixed;
            /*bottom: 10%;*/
            bottom: -80px;/*Posicion inicial fuera de la pantalla*/
            width: 300px;
            right: 5%;
            z-index: 100;

            transition: bottom 0.7s ease-in-out;/*Transicion suave de la propiedad de posicion*/
        }
        .msjAlert.loaded {
            bottom: 8%;/*Cuando la clase 'load' se agrega, la alerta se desplaza hacia abajo */
        }

        /*Resolucion tablet*/
        @media(min-width: 768px){/*Se mostrara el div que contiene el abecedario cuando la resolucion sea una tablet*/
            #abecedarioDiv {
                display: block;
            }
        }
    </style>
@endsection

@section('content')
    <div id="appDiseno1" class="container-fluid">
        <div class="row justify-content-center">

            <!--Alerta-->
            <div class="alert alert-dismissible fade show msjAlert" :class="'alert-' + message.type" role="alert" v-show="message.message && message.type">
                <b><small>@{{ message.message }}</small></b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!--Panel superior-->
            {{--<div id="panel-header" :class="{ 'active-search': showIconSearch }">--}}
            {{-- <div id="panel-header" :class="{ 'scrollActivo': scrollActivado, 'scrollInactivo': !scrollActivado }"> --}}
                <div id="panel-header">
                <div class="row align-items-start">

                    <div class="col-1">
                        <a style="margin-top: -6px; margin-left: -8px;" href="javascript:void(0);" type="button" class="btn btn-sm">
                            <i class="bi bi-arrow-left fs-4"></i>
                        </a>
                    </div>

                    <div class="col-8">
                        <h1 class="title text-uppercase ms-3">{{-- {{ session('caja')->caja }} --}} Black And White</h1>
                        <h2 style="margin-top: -7px;" class="subtitle ms-3 mb-3">Hola, Jorge</h2>
                    </div>

                    <div class="col-3 d-flex justify-content-end">
                        <button style="margin-top: -6px;" @click="showIconSearch = !showIconSearch" type="button" class="btn">
                            <i v-show="!showIconSearch" class="bi bi-search"></i>
                            <i v-show="showIconSearch" class="bi-x-lg"></i>
                        </button>

                        <div style="margin-top: -6px; margin-right: -18px;" class="dropdown"><!--Colocar el margin-right al ultimo elemento-->
                            <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!--Caja de titulos-->
                <div v-show="!showIconSearch" id="information" class="row justify-content-center">
                    <div class="col-10">
                        <div>
                            <h1 class="title text-uppercase ms-3">Turno 2</h1>
                            <h2 style="margin-top: -7px;" class="subtitle ms-3">Apertura hace 1 sec · cierre en 5 horas</h2>
                        </div>
                    </div>
                </div>

                <!--Caja de busqueda-->
                <div v-show="showIconSearch" class="row justify-content-center">
                    <div class="col-12 col-sm-11">
                        <input type="text" id="txtSearch" class="form-control form-control-sm rounded-pill" placeholder="Buscar por nombre de producto">
                    </div>
                </div>
            </div>

            <!--Panel main-->
            <div id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <!--Abecedario-->
                <div style="margin-top: 10px;" id="abecedarioDiv" class="row mb-4">
                    <div class="col-12">
                        <div class="mb-2"><!--Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                            <p style="color: #37474F; font-size: 10px;" class="text-uppercase">Filtrar Por</p>

                            <div style="margin-top: -10px;" class="text-center">
                                <!--Nueva manera de declarar e inicializar variables en Blade-->
                                @php($letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z'])

                                @foreach($letras as $letra)
                                    <button type="button" class="btn btnAbecedario"><span>{{ $letra }}</span></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <p style="color: #37474F; font-size: 12px; margin-top: 10px;" class="text-uppercase"><b>Productos agregados</b></p>

                <div v-show="getListado" id="panel-main-opacity"><!--Solo se usar para la opacidad-->
                    <!-- v-for -->
                    <div v-for="p in getListado" class="panel-card mb-2"><!--Margen inferior entre cada card-->
                        <div class="row">
                            <div style="margin-top: 6px;" class="col-1 text-center">
                                <b style="color: #37474F; font-size: 11px;" class="ms-1">@{{ p.cantidad }}</b>
                            </div>
    
                            <div style="margin-top: -4px;" class="col-8">
                                <b style="color: #37474F; font-size: 12px;" class="text-uppercase">@{{ p.detalle }}</b>
                                <p style="color: #546E7A; font-size: 10px; margin-bottom: -3px;">@{{ p.creacion }}</p>
                            </div>
    
                            <div style="margin-top: -4px;" class="col-3 text-end">
                                <b style="color: #37474F; font-size: 12px;" class="me-1">$ @{{ p.precio.toFixed(2) }}</b>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="!getListado">
                    <hr>
                    <p style="color: #546E7A; font-size: 12px;">Aun no hay productos agregados a esta comanda.</p>
                </div>
            </div>

            <!--Panel inferior-->
            <div id="panel-bottom">
                <div class="row text-center">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle">
                                <i class="bi bi-list-ul"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart"></i>
                            </button>

                            <span style="background: #0d6efd; padding: 3px;">
                                <button style="height: 50px; background: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <i style="color: #0d6efd;" class="bi bi-printer"></i>
                                </button>
                            </span>

                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-file-earmark-text"></i>
                            </button>
                            <button type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!--Boton scroll up-->
                <div v-show="getListado && getListado.length >= 10" id="btnScrollUp">
                    <button type="button" title="Ir arriba" class="btn btn-outline-secondary btn-sm rounded-5">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script>
        var app = new Vue({
            el: '#appDiseno1',
            data: {
                getListado: [
                    { cantidad: 1,  detalle: 'Cerveza Pilsener', precio: 1.25, creacion: 'Creada hace un dia.' },
                    { cantidad: 2,  detalle: 'Cerveza Golden',   precio: 1.68, creacion: 'Creada hace dos dias.' },
                    { cantidad: 3,  detalle: 'Cerveza Corona',   precio: 1.74, creacion: 'Creada hace tres dias.' },
                    { cantidad: 4,  detalle: 'Cerveza Heineken', precio: 3.26, creacion: 'Creada hace cuatro dias.' },
                    { cantidad: 5,  detalle: 'Coca cola uva',    precio: 0.75, creacion: 'Creada hace cinco dias.' },
                    { cantidad: 6,  detalle: 'Coca cola fresa',  precio: 1.80, creacion: 'Creada hace seis dias.' },

                    { cantidad: 7,  detalle: 'Cerveza Pilsener', precio: 1.25, creacion: 'Creada hace un dia.' },
                    { cantidad: 8,  detalle: 'Cerveza Golden',   precio: 1.68, creacion: 'Creada hace dos dias.' },
                    { cantidad: 9,  detalle: 'Cerveza Corona',   precio: 1.74, creacion: 'Creada hace tres dias.' },
                    { cantidad: 10, detalle: 'Cerveza Heineken', precio: 3.26, creacion: 'Creada hace cuatro dias.' },
                    { cantidad: 11, detalle: 'Coca cola uva',    precio: 0.75, creacion: 'Creada hace cinco dias.' },
                    { cantidad: 12, detalle: 'Coca cola fresa',  precio: 1.80, creacion: 'Creada hace seis dias.' },

                    { cantidad: 13, detalle: 'Cerveza Pilsener', precio: 1.25, creacion: 'Creada hace un dia.' },
                    { cantidad: 14, detalle: 'Cerveza Golden',   precio: 1.68, creacion: 'Creada hace dos dias.' },
                    { cantidad: 15, detalle: 'Cerveza Corona',   precio: 1.74, creacion: 'Creada hace tres dias.' },
                    { cantidad: 16, detalle: 'Cerveza Heineken', precio: 3.26, creacion: 'Creada hace cuatro dias.' },
                    { cantidad: 17, detalle: 'Coca cola uva',    precio: 0.75, creacion: 'Creada hace cinco dias.' },
                    { cantidad: 18, detalle: 'Coca cola fresa',  precio: 1.80, creacion: 'Creada hace seis dias.' },
                ],

                //Se usa para mostrar u ocultar la caja de busqueda en el panel-header
                showIconSearch: false,

                //Alerta
                message: {},
            },
            mounted(){
                //1.- Se usa para agregar opacidad, primero debe cargar todo el DOM y luego agregar la clase
                document.addEventListener('DOMContentLoaded',() => {
                    document.getElementById('panel-main-opacity').classList.add('loaded');
                });

                //2.- Lanzar mensaje de alerta
                this.setMessage('Mensaje de prueba','success');

                //3.- Se usa para el scroll up y down del panel-header
                this.funcScrollUpDown();//Llama al metodo para inicializar las variables

                window.addEventListener('scroll',() => {//A la escucha del evento scroll para ejecutar el metodo funcScrollUpDown()
                    this.funcScrollUpDown();
                });

                //4.- Se usa para el scroll up y down del panel-bottom
                this.funcPanelBottom();
            },
            methods: {
                setMessage(m, t){
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
                    }, 3 * 1000);
                },
                funcScrollUpDown(){
                    //Declaracion de variables
                    let panelHeader = document.getElementById('panel-header');
                    let information = document.getElementById('information');
                    let panelMain   = document.getElementById('panel-main');
                    let btnScrollUp = document.getElementById('btnScrollUp');
                    btnScrollUp.style.visibility = 'hidden';//El boton scroll up comienza oculto ya que aun no se ha hecho scroll up

                    //Escucha del evento click del boton scroll up
                    btnScrollUp.addEventListener('click',function(){
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });

                    const scrollPos = window.scrollY || window.pageYOffset;//Calcula la posicion del scroll

                    //Muestra u oculta el boton scroll up, dependiendo si esta en la parte superior de la ventana o si se ha hecho scroll
                    if(scrollPos > 0)
                        btnScrollUp.style.visibility = 'visible';//Como ya se hizo scroll hacia abajo aunque sea un poco, se muestra el boton de scroll up
                    else
                        btnScrollUp.style.visibility = 'hidden';//Si aun no se ha hecho scroll, el boton scroll up se mantiene oculto

                    //---
                    if(scrollPos >= 20){//Indica desde donde se comenzara a ocultarse el panel-main
                        panelHeader.style.height = (this.showIconSearch) ? '110px' : '60px';
                        information.style.visibility = 'hidden';
                        panelMain.style.zIndex = '-1';
                    }
                    else{
                        panelHeader.style.height = '140px';
                        information.style.visibility = 'visible';
                        panelMain.style.zIndex = '1';
                    }
                },
                funcPanelBottom(){
                    //Se usa para el panel-bottom de scroll up y down
                    let lastScrollTop = 0;
                    let sensibilidad  = 5;//5 - 100 Entre mas bajo el numero, mayor sensibilidad al desplazamiento

                    //Se usa para ocultar el boton scroll up segun desplazamiento arriba o abajo
                    let navbarHeightBottom = document.getElementById('panel-bottom').offsetHeight;

                    window.addEventListener('scroll',function(){
                        let panelBottom = document.getElementById('panel-bottom');

                        let currentScroll = parseInt(window.pageYOffset || document.documentElement.scrollTop);
                        
                        //Se usa para calcular la precision de la sensibilidad del desplazamiento
                        if(Math.abs(lastScrollTop - currentScroll) <= sensibilidad)
                            return;

                        //Se usa para el panel-bottom, contiene una variable negativa
                        //True = scroll up, False = scroll down
                        panelBottom.style.bottom = (currentScroll > lastScrollTop) ? -navbarHeightBottom + 'px' : '0';
                        
                        lastScrollTop = currentScroll;
                    });
                },
            },
            watch: {
                showIconSearchChanged(newValue, oldValue){//Observador para la propiedad computada showIconSearchChanged
                    this.funcScrollUpDown();//Llama a funcScrollUpDown() cuando showIconSearch cambia de estado
                }
            },
            computed: {
                //Esta propiedad computada se actualizará cada vez que showIconSearch cambie de estado
                //y llamará automáticamente al método funcScrollUpDown()
                showIconSearchChanged(){
                    return this.showIconSearch;
                }
            },
        });
    </script>
@endsection
