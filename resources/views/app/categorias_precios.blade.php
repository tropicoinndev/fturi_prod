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
            height: 148px !important;
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
        .mdi-arrow-left, .mdi-magnify, .mdi-close {/*Iconos del header*/
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
            margin-top: -10px !important;
            transition: margin-top 0.3s ease-in-out;
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

        /*Reduce / aumenta la altura del panel-main al hacer scroll up o click en caja de busqueda*/
        .initBusqueda {
            margin-top: 43px !important;
            transition: margin-top 0.3s ease-in-out;
        }

        .btnAbecedario {/*Unicamente se mostrara en resolucion tablet y se ocultara en resolucion movil*/
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
            border-bottom: solid 5px #BBDEFB;
            padding: 10px;
            transition: transform 0.4s ease;/*Transicion para el cambio de tamaño*/
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
            #abecedarioDiv {
                display: block;
            }
        }
    </style>
@endsection

@section('content')
    <div id="appCategoriasPreciosMovil" class="container-fluid">
        <div class="row justify-content-center">

            <!--Alerta-->
            <div :style="'border-left: solid 5px '+bordeIzquierdoAlerta+' !important'" class="alert alert-dismissible fade show msjAlert" :class="'alert-' + message.type" role="alert" v-show="message.message && message.type">
                <b><small>@{{ message.message }}</small></b>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- panel-header -->
            <nav id="panel-header" :class="{'menuSinScroll': !scrollInit, 'menuConScroll': scrollInit}" :style="{ height: scrollInit && !busquedaShow ? '63px' : '115px' }">
                <div style="margin-top: 6px;" class="row"><!--Margen superior positivo para centrar la fila con el panel-header-->
                    <div @click="goToBack" class="col-2">
                        <button style="margin-top: -9px;" type="button" class="btn">
                            <i class="mdi mdi-arrow-left"></i>
                        </button>
                    </div>

                    <div class="col-8">
                        <h1 class="title text-uppercase">Categorías - Precios</h1>
                        <h2 style="margin-top: -7px;" class="subtitle text-truncate text-uppercase">{{ auth()->user()->name }} · <span style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">@{{ bodegaSelected.id }} - @{{ bodegaSelected.name ?? 'Seleccione Una Bodega' }}</span></h2>
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
                        <input v-model="txtSearch" ref="searchInput" style="border: 1px solid #26C6DA;" type="text" class="form-control rounded-pill" aria-label="First name" placeholder="Buscar por categoría"/>
                    </div>
                </div>
            </nav>

            <!-- panel-main -->
            <section :class="{'initBusqueda': !scrollInit && busquedaShow}" id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <!--Abecedario: Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                <div id="abecedarioDiv" class="row mt-3 mb-4">
                    <div class="col-12">
                        <p style="color: #546E7A; font-weight: 500; font-size: 8pt;" class="text-uppercase">Filtrar Por</p>

                        <div style="margin-top: -10px;" class="text-start">
                            <!--Nueva manera de declarar e inicializar variables en Blade-->
                            {{-- @php($letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z'])

                            @foreach($letras as $letra)
                                <button @click="txtSearch = '{{ $letra }}'" type="button" class="btn btnAbecedario"><span>{{ $letra }}</span></button>
                            @endforeach --}}

                            <!---->
                            <button v-for="letra in getLetters" @click="filterByLetter(letra)" type="button" class="btn btnAbecedario">
                                <span>@{{ letra }}</span>
                            </button>
                        </div>
                    </div>
                </div>

                <p style="color: #546E7A; font-weight: 500; font-size: 10.5pt;" class="text-uppercase mt-2">Seleccione Una Categoría</p>
                
                {{-- @{{ getCategoriasPrecios }} --}}
                <div v-show="getCategoriasPrecios.length > 0" id="panel-main-opacity"><!--Se usa para la opacidad-->
                    <div class="row">
                        <!-- v-for -->
                        <div v-for="cat in getCategoriasPrecios" class="col-12 mb-2"><!--Margen inferior entre cada card-->
                            <a @click="setCategoriaPrecio(cat)" :href="'/app/precios/'+cat.cid" style="text-decoration: none;">
                                <div class="panel-card">
                                    <div style="margin-bottom: -13px;" class="row">
                                        <div style="margin-top: 5px;" class="col-10">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="text-truncate text-uppercase ms-3">{{--@{{ cat.id }} --}} @{{ cat.categoria }}</p>
                                        </div>
                                        <div class="col-2 text-end">
                                            <span style="color: #546E7A;" class="mdi mdi-chevron-right fs-4 me-3"></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div v-show="getCategoriasPrecios.length == 0" style="color: #546E7A;">
                    <hr>
                    <p><b><small>Esta categoría no contiene ningún producto activo, si esto no cambia en un tiempo solicite la desactivación de la categoría.</small></b></p>
                </div>
            </section>

            <!-- panel-bottom -->
            <div id="panel-bottom">

                <!--Boton scroll up-->
                <div v-show="getCategoriasPrecios && getCategoriasPrecios.length >= 10" id="btnScrollUp">
                    <button type="button" title="Ir arriba" class="btn btn-outline-secondary btn-sm rounded-5">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>

            </div>

            <!--Modal cambiar bodega-->
            <div class="modal fade" id="modalCambiarBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <!--Hacemos uso del componente modal para cambiar bodega-->
                <!-- bodega-seleccionada es el evento y esta ejecuta el metodo handleBodegaSeleccionada-->
                <modal_cambiar_bodega :bodegas="bodegas" @bodega-seleccionada="handleBodegaSeleccionada" />
            </div>


        </div>
    </div>
@endsection

@section('script')
    <script type="module">
        let app = window.appVue({
            //Emitir algo...
            data(){
                return {
                    //---Scroll---
                    scrollInit:   false,//Se inicia sin scroll
                    busquedaShow: true,//Se usa para mostrar u ocultar la caja de busqueda y los iconos de equis y lupa
                    //-----

                    //---Data---
                    categoriasPrecios: @json($p),
                    bodegas: @json($bodegas),//El campo 'cid' es el campo 'bodegas_id' pero encriptado
                    //-----

                    //---Variables---
                    txtSearch: '',
                    bodegaSelected: {
                        id: null,
                        name: null,
                    },
                    //Se usa para evitar repetir la misma bodega que esta en el listado de la modal...
                    //Si ya esta seleccionada la bodega, no se mostrara en el listado
                    bs: null,
                    //-----

                    //---Alerta flotante---
                    message: {},
                    bordeIzquierdoAlerta: null,
                    //-----
                }
            },
            created(){
                //6.- Almacenar bodega en el localStorage
                if(this.bodegaSelected.id == null){
                    if(localStorage.getItem('bodegaSelectedId') != null){//Si ya hay algo en el localStorage, tomamos los datos segun el id
                        let bodegaEncontrada = this.bodegas.find(i => i.bodegas_id == localStorage.getItem('bodegaSelectedId'));

                        if(bodegaEncontrada){
                            this.bs = bodegaEncontrada;

                            this.bodegaSelected.id   = localStorage.getItem('bodegaSelectedId');
                            this.bodegaSelected.name = localStorage.getItem('bodegaSelectedName');
                        }
                    }
                    else{//De lo contrario, sino hay datos... seleccionamos la primer bodega por defecto y la guardamos en el localStorage
                        this.bs = this.bodegas[0];
                    }
                }

                //A la escucha del evento
                window.Echo.private('pedidos.response.'+{{ session('caja')->id }})
                    .listen('ResponsePedidos',(d) => {
                        console.log('Evento desde produccion: ',d.comanda_detalles.comandas.detalles_comanda);

                        this.sound();
                        this.setMessage(d.mensaje, 'success');
                    });
            },
            mounted(){
                //1.- Se usa para agregar opacidad al panel-main donde se muestran los registros
                this.funcPanelMainOpacity();

                //2.- Se usa para la funcionalidad general del scroll up y down
                this.funcScrollUpDown();

                //3.- Se usa para mostrar u ocultar el panel-bottom, ya sea si se ha hecho scroll up o down
                this.funcPanelBottomUpDown();

                this.$refs.searchInput.focus();
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
                //Logica
                setCategoriaPrecio(cat){
                    //Almacenar en el localStorage el id y la categoria del precio solamente para mostrar o indicar en la siguiente pantalla
                    //de los 'precios', cual categoria se selecciono
                    localStorage.setItem('categoriaPreciosId',cat.cid);
                    localStorage.setItem('categoriaPreciosName',cat.categoria);
                },
                filterByLetter(letter){//La letra seleccionada se asignara de forma automatica a la caja de busqueda
                    this.txtSearch = letter;
                },
                goToBack(){
                    //Obtener la URL de la comanda actual desde el localStorage
                    window.location.href = localStorage.getItem('urlComandaActual');
                },
                handleBodegaSeleccionada(bodega){
                    this.bs = bodega;

                    this.bodegaSelected.id   = this.bs.id;
                    this.bodegaSelected.name = this.bs.name;

                    localStorage.setItem('bodegaSelectedId',this.bodegaSelected.id);
                    localStorage.setItem('bodegaSelectedName',this.bodegaSelected.name);
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
            },
            computed: {
                getCategoriasPrecios(){
                    let regx = new RegExp((this.txtSearch).toLowerCase());

                    //Se usa la propiedad 'data' en el filter, ya que la variable $p que viene desde el controlador no es un array, sino un objeto de paginador de Laravel.
                    //Usando esa propiedad, ya se podra recorrer el array en el v-for.
                    return this.categoriasPrecios.data.filter(c => {
                        //Si hay mas de dos caracteres en la caja de busqueda, hacemos el filtrado de los registros en todo el array, pero...
                        //Si solo hay un caracter en la caja de busqueda, se hace el filtrado del array, pero unicamente de los registros que comiencen por ese caracter nada mas
                        return regx.test((this.txtSearch.length >= 2) ? c.categoria.toLowerCase() : c.categoria.charAt(0).toLowerCase());
                    });

                    /*if(this.txtSearch.length >= 2)
                        return this.categoriasPrecios.data.filter(c => regx.test(c.categoria.toLowerCase()));
                    else
                        return this.categoriasPrecios.data.filter(c => regx.test(c.categoria.charAt(0).toLowerCase()));*/
                },
                getLetters(){//Extrae la primer letra de cada registro y devuelve un array de letras unicas ordenadas
                    const arrayLetters = this.categoriasPrecios.data.map(al => al.categoria.charAt(0).toUpperCase());
                    return [...new Set(arrayLetters)].sort();
                },
            },
            /*watch: {//Estar al pendiente de la variable 'busquedaShow' para cuando cambie de estado, establecer el foco en la caja de busqueda
                busquedaShow: function(newValue){
                    if(newValue){
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });
                    }
                }
            },*/
        });
        app.component('modal_cambiar_bodega',component.modal_cambiar_bodega);
        app.mount('#appCategoriasPreciosMovil');
    </script>
@endsection
