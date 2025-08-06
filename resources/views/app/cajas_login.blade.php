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
            background: #3e988e;
            color: #FAFAFA;
            transition: height 0.5s ease-in-out;
        }
        .menuSinScroll {
            height: 148px !important;
            z-index: 10;
        }
        .menuConScroll {
            /*height: 65px !important;*//*auto, Debe ser un numero no auto para que funcione la animacion*/
            z-index: 135;
        }

        .title {/*Titulo del header*/
            font-weight: 500;
            font-size: 9.5pt;
        }
        .subtitle {/*Subtitulo del header*/
            font-weight: 400;
            font-size: 7pt;
        }
        .mdi-arrow-left, .mdi-magnify, .mdi-close {/*Iconos del header*/
            font-size: 20px;
            color: #FAFAFA;
        }

        #panel-main {
            position: relative;
            border-radius: 20px;
            background: #fff;
            padding: 12px;
            width: 90%;
            min-height: 89vh;
            margin-top: 45px !important;
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

        .panel-card {/*Estas son las card de color blancas donde se muestra la información*/
            background: #fff;
            color: #455A64;
            border: solid 1px #A7A4A4;
            border-radius: 8px;
            height: 120px;
        }
        .panel-card:hover {/*El color de fondo de la caja cambiara la hacer hover*/
            background: #4db6acd3;
            color: #fff;
        }
        #txtPinBodega {/*Por defecto no se mostrara el input para ingresar el pin, solo se mostrara al hacer hover*/
            display: none;
        }
        .panel-card:hover #txtPinBodega {/*Aqui ya se hizo hover sobre la caja, por lo tanto, ya se mostrará el input del pin*/
            display: block;
        }
        #titulosCaja {
            color: #546E7A;
            font-weight: 600;
            font-size: 12.5pt;
        }
        .panel-card:hover #titulosCaja {/*Al hacer hover sobre la caja, los titulos reduciran su margen superior para dar un poco mas de espacio al input del pin*/
            margin-top: -12px;
            color: #fff;
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

        /*Resolucion tablet*/
        @media(min-width: 768px){

        }
    </style>
@endsection

@section('content')
    <div id="appCajasLoginMovil" class="container-fluid">
        <div class="row justify-content-center">

            <!-- panel-header -->
            <nav id="panel-header" :class="{'menuSinScroll': !scrollInit, 'menuConScroll': scrollInit}" :style="{ height: scrollInit && !busquedaShow ? '63px' : '115px' }">
                <div style="margin-top: 6px;" class="row"><!--Margen superior positivo para centrar la fila con el panel-header-->
                    <div class="col-2">
                        <button style="margin-top: -9px;" type="button" class="btn">
                            <i class="mdi mdi-arrow-left"></i>
                        </button>
                    </div>

                    <div class="col-8">
                        <h1 class="title text-uppercase">Listado de Cajas</h1>
                        <h2 style="margin-top: -7px;" class="subtitle text-uppercase">Hola · {{ auth()->user()->name }}</h2>

                        {{-- <div id="information" v-show="!scrollInit && !busquedaShow" class="mt-3"><!--Esta informacion se ocultara al hacer scroll-->
                            <h1 class="title text-uppercase ms-4">aaa</h1>
                            <h2 style="margin-top: -7px;" class="subtitle text-uppercase ms-4">Apertura...</h2>
                        </div> --}}
                    </div>
                    
                    {{-- <div class="col-2 text-end">
                        <button style="margin-top: -7px;" type="button" class="btn">
                            <i v-show="!busquedaShow" class="mdi mdi-magnify" @click="busquedaShow = true"></i>
                            <i v-show="busquedaShow" class="mdi mdi-close" @click="busquedaShow = false"></i>
                        </button>
                    </div> --}}
                </div>

                <!--Caja de busqueda-->
                <div class="row mt-1 mb-2" :class="{ active: !busquedaShow }" v-show="busquedaShow">
                    <div class="col-11 m-auto">
                        <input v-model="txtSearch" ref="searchInput" style="border: 1px solid #26C6DA;" type="text" class="form-control rounded-pill" aria-label="First name" placeholder="Buscar por nombre de caja"/>
                    </div>
                </div>
            </nav>

            <!-- panel-main -->
            <section id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <p style="color: #546E7A; font-weight: 500; font-size: 10.5pt;" class="text-uppercase mt-2">Iniciar Sesión En Una Caja</p>

                <!--Alerta-->
                <div class="row">
                    <div class="col-12">
                        <x-message></x-message>
                    </div>
                </div>
                
                {{-- @{{ getCajas }} --}}
                <div v-show="getCajas.length > 0" id="panel-main-opacity"><!--Se usa para la opacidad-->
                    <div class="row">
                        <!-- v-for -->
                        <div v-for="c in getCajas" class="col-12 col-sm-6 col-md-4 mb-2"><!--Margen inferior entre cada card-->
                            <div class="panel-card p-4">
                                <div class="text-center">
                                    <div id="titulosCaja">
                                        <p style="margin-bottom: 1px;" class="text-uppercase">{{--@{{ c.cajas.cid }} --}} @{{ c.cajas.caja }}</p>
                                        <p style="font-size: 11px;">CAJA</p>
                                    </div>

                                    <div id="txtPinBodega">
                                        <form action="{{ route('cajas.auth.app') }}" method="post">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="caja" :value="c.cajas.cid">

                                                <input type="password" name="pin" class="form-control" placeholder="PIN" aria-label="PIN" autocomplete="off" aria-describedby="basic-addon2">
                                                <button style="background: #f1f1f1f5; color: #53585C;" class="btn" type="submit" id="button-addon2">Acceder</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-show="getCajas.length <= 0" style="color: #546E7A;">
                    <hr>
                    <p style="margin-bottom: 1px;">¡No se encontraron registros disponibles o no coincidieron los resultados con tu búsqueda!</p>
                    <p><b><small>Intenta agregar registros o ajusta tus criterios de búsqueda.</small></b></p>
                </div>
            </section>

            <!-- panel-bottom -->
            <div id="panel-bottom">
                <!--Boton scroll up-->
                <div v-show="getCajas && getCajas.length >= 10" id="btnScrollUp">
                    <button type="button" title="Ir arriba" class="btn btn-outline-secondary btn-sm rounded-5">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        let app = window.appVue({
            data(){
                return {
                    //---Scroll---
                    scrollInit:   false,//Se inicia sin scroll
                    busquedaShow: true,//Se usa para mostrar u ocultar la caja de busqueda y los iconos de equis y lupa
                    //-----

                    //---Data---
                    cajas: @json($cajas),
                    //-----

                    //---Cajas de texto---
                    txtSearch: '',
                    //-----
                }
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
            },
            computed: {
                getCajas(){
                    let regx = new RegExp((this.txtSearch).toLowerCase());
                    return this.cajas.filter(c => regx.test(c.cajas.caja.toLowerCase()));
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
        app.mount('#appCajasLoginMovil');
    </script>
@endsection
