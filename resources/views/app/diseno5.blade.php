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

        .menuSinScroll, .menuConScroll {
            position: fixed;
            top: 0;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            letter-spacing: 0.5px;
            padding: 10px;
            width: 100%;
            background: #37474F;
            color: #ccc;
            transition: height 0.5s ease-in-out;
        }
        .menuSinScroll {
            height: 140px;
            z-index: 10;
        }
        .menuConScroll {
            height: auto;/*auto, Debe ser un numero no auto para que funcione la animacion*/
            z-index: 135;
        }

        .title {/*Titulo del header de cada pantalla*/
            font-size: 13px;
            font-weight: bold;
        }
        .subtitle {/*Subtitulo del header de cada pantalla*/
            font-size: 10px;
        }
        .mdi-arrow-left, .mdi-magnify, .mdi-close {
            font-size: 20px;
            color: #ccc;
        }

        #panel-main {
            position: relative;
            border-radius: 20px;
            background: #fff;
            padding: 12px;
            width: 90%;
            margin-top: 95px;
            /*margin: auto;*/
            /*box-shadow: 7px 7px rgba(0, 0, 0, 0.1);*/
            /*min-height: 100vh;*/
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

        /*Reduce la altura del panel-main al hacer scroll up*/
        .initBusqueda {
            margin-top: 50px !important;
            /*height: auto;*/
        }

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
            z-index: 125;
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

            <!-- panel-header -->
            <nav id="panel-header" :class="{'menuSinScroll': !scrollInit, 'menuConScroll': scrollInit}">
                {{-- <div class="container-fluid"> --}}
                    <div style="margin-top: 6px;" class="row"><!--Margen superior positivo para centrar la fila con el panel-header-->
                        <div class="col-1">
                            <a style="margin-top: -7px;" href="javascript:void(0);" type="button" class="btn">
                                <i class="mdi mdi-arrow-left"></i>
                            </a>
                        </div>

                        <div class="col-9">
                            <h1 class="title text-uppercase ms-3">Black And White</h1>
                            <h2 style="margin-top: -7px;" class="subtitle text-capitalize ms-3">Hola, Jorge</h2>

                            <div id="information" v-show="!scrollInit && !busquedaShow" class="mt-3">
                                <h1 class="title text-uppercase ms-3">Turno 2</h1>
                                <h2 style="margin-top: -7px;" class="subtitle text-capitalize ms-3">Apertura hace 1 sec * cierre en 5 horas</h2>
                            </div>
                        </div>
                        
                        <div class="col-2 text-end">
                            <button style="margin-top: -7px;" type="button" class="btn">
                                <i v-show="!busquedaShow" class="mdi mdi-magnify" @click="busquedaShow = true"></i>
                                <i v-show="busquedaShow" class="mdi mdi-close" @click="busquedaShow = false"></i>
                            </button>
                        </div>
                    </div>

                    <!--Caja de busqueda-->
                    <div class="row mt-1 mb-2" v-show="busquedaShow">
                        <div class="col-10 m-auto">
                            <input type="text" class="form-control form-control-sm rounded-pill" aria-label="First name" placeholder="Buscar por nombre de producto"/>
                        </div>
                    </div>
                {{-- </div> --}}
            </nav>

            <!-- panel-main -->
            <section id="panel-main" :class="{'initBusqueda': scrollInit && !busquedaShow}" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <!--Abecedario: Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                <div id="abecedarioDiv" class="row mt-3 mb-4">
                    <div class="col-12">
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

                <p style="color: #37474F; font-size: 12px;" class="text-uppercase mt-3"><b>Productos agregados</b></p>
                
                <div v-show="getListado" id="panel-main-opacity"><!--Se usa para la opacidad-->
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
            </section>

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
                scrollInit:   false,//Se inicia sin scroll
                busquedaShow: false,//Para mostrar u ocultar la caja de busqueda y los iconos de equis y lupa

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
            },
            mounted(){
                //1.- Se usa para agregar opacidad al panel-main donde se muestran los registros
                this.funcPanelMainOpacity();

                //2.- Se usa para la funcionalidad general del scroll up y down
                this.funcScrollUpDown();

                //3.- Se usa para mostrar u ocultar el panel-bottom, ya sea si se ha hecho scroll up o down
                this.funcPanelBottomUpDown();
            },
            beforeUnmounted(){
                window.removeEventListener('scroll',this.funcScrollUpDown);
            },
            methods: {
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

                    window.addEventListener('scroll',() => {
                        //Muestra u oculta el boton scroll up, dependiendo si esta en la parte superior de la ventana o si se ha hecho scroll
                        let position = window.scrollY;//Calcula la posicion actual del scroll
                        /*if(position > 0)
                            btnScrollUp.style.visibility = 'visible';//Como ya se hizo scroll hacia abajo aunque sea un poco, se muestra el boton scroll up
                        else
                            btnScrollUp.style.visibility = 'hidden';//Si aun no se ha hecho scroll, el boton scroll up se mantiene oculto*/
                        btnScrollUp.style.visibility = (position > 0) ? 'visible' : 'hidden';



                        //Scroll general
                        if(position > 12){
                            this.scrollInit = true;
                        }
                        else{
                            this.scrollInit = false;
                        }
                        //this.scrollInit = position > 12;
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
            },
            computed: {

            },
        });
    </script>
@endsection
