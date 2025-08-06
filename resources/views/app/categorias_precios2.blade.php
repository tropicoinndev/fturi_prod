@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
            display: none;
        }
        .container {
            display: none;/*Se oculta el container que esta dentro de las seccion list*/
        }
        #abecedarioDiv {
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
            height: 110px;
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
        .bi-arrow-left, .bi-x-lg, .bi-search {/*Iconos del header de flecha izquierda y equis*/
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
        .panel-card {/*Estas son las card de color celeste donde se muestra la información*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 4px #BBDEFB;
            padding: 10px;
            transition: transform 0.3s ease;/*Transición para el cambio de tamaño*/
        }
        .panel-card:hover {
            cursor: pointer;
            transform: scale(0.97);/*Hacer el elemento un poco más pequeño en hover*/
            box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);/*Aplicar sombra abajo y a la derecha*/
        }
        /*---*/
        .abecedario {
            background: #DAE0E5;
            color: #546E7A;
            border-radius: 100%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            padding: 1px;
            margin-right: 3px;
        }
        .letters {/*Se usa unicamente para colocar/centrar las letras dentro del boton*/
            margin-left: -1px;
        }
        /*Resolucion movil o tablet*/
        @media(max-width: 767px) or (max-width: 991px){/*Se ocultara el div que contiene el abecesario cuando el tamaño sea un telefono*/
            
        }
        /*Resolucion tablet*/
        @media (min-width: 768px){/*Se mostrara el div que contiene el abecesario cuando el tamaño sea una tablet*/
            #abecedarioDiv {
                display: block;
            }
        }
        /*Resolucion desktop*/
        @media (min-width: 992px){/*Se mostrará el div que contiene el abecedario cuando el tamaño sea un escritorio*/
            
        }
    </style>
@endsection

@section('content')
    <div style="margin-top: -20px;" id="appListarCategoriasPrecios" class="container-fluid panel-main-opacity">
        <div class="row justify-content-center">

            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-1">
                            <a :href="urlComandaActual" style="text-decoration: none;"><!--Redirigir a la URL de la comanda seleccionada-->
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">CATEGORIAS - PRECIOS</h1>
                            <div id="titles">
                                <h2 style="margin-top: -3px;" class="subtitle text-uppercase ms-2 mb-3">{{-- Hola, {{ auth()->user()->name }} --}} JORGE CASTILLO</h2>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i v-show="getCategoriasPrecios.length > 0" id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por categoria">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->



            <!-- panel main -->
            <div class="panel-main">
                <div class="row">
                    <div class="col-12">
                        <div id="abecedarioDiv" class="mb-2"><!--Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                            <p style="color: #37474F; font-size: 10px; margin-top: 12px; margin-bottom: 10px;" class="ms-2">FILTRAR POR</p>
                            <div style="margin-top: -5px;" class="text-center">
                                
                                <!--Nueva manera de declarar e inicializar variables en Blade-->
                                @php($letras = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','Ñ','O','P','Q','R','S','T','U','V','W','X','Y','Z'])
    
                                @foreach($letras as $letra)
                                    <button @click="txtBusqueda = '{{ $letra }}'" class="btn abecedario"><span class="letters">{{ $letra }}</span></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
    
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">SELECCIONE UNA CATEGORIA</p>
    
                {{-- @{{ bodegas }} --}}
                <div v-if="getCategoriasPrecios.length > 0" class="row mt-1">
                    <!-- v-for -->
                    <div v-for="cat in getCategoriasPrecios" class="col-12 mb-2">
                        <a :href="'/app/precios/'+cat.id" style="text-decoration: none;">
                            <div class="panel-card">
                                <div class="row">
                                    <div class="col-10">
                                        <b style="font-size: 12px; color: #37474F;" class="text-truncate text-uppercase ms-3">{{-- @{{ cat.token }} --}} @{{ cat.categoria }}</b>
                                    </div>
                                    <div class="col-2 text-end">
                                        <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div v-else>
                    <p style="color: #546E7A;">Aun no hay datos para mostrar.</p>
                </div>
            </div><!-- end panel main -->

        </div>
    </div>
@endsection



@section('script')
    <script>
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
                menu.style.height        = '110px';//Disminuimos la altura del menu
                titles.style.display     = 'block';
                iconSearch.style.display = 'block';
                iconClose.style.display  = 'none';
                txtSearch.style.display  = 'none';
                txtSearch.focus();
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
                            menu.style.height    = '110px';//Aumentamos la altura del menu
                            titles.style.display = 'block';
                        }
                        //titles.style.display = 'block';
                    }
                });
            }
            scroll();
        });

        //Vue
        var app = new Vue({
            el: '#appListarCategoriasPrecios',
            data: {
                categoriasPrecios: @json($p),
                bodegas:           @json($bodegas),//el campo 'cid' es el campo 'bodegas_id' pero encriptado
                txtBusqueda:       '',

                //Obtener la URL de la comanda actual desde el localStorage
                urlComandaActual: localStorage.getItem('urlComandaActual'),
            },
            mounted() {
                //console.log('Component mounted. Categorias Precios Blade');
            },
            methods: {

            },
            computed: {
                getCategoriasPrecios: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    //Se usa la propiedad 'data' en el filter, ya que la variable $p que viene desde el controlador no es un array, sino un objeto de paginador de Laravel.
                    //Usando esa propiedad, ya se podra recorrer el array en el v-for.
                    return this.categoriasPrecios.data.filter(c => regx.test(c.categoria.toLowerCase()));
                },
            }
        });
    </script>
@endsection
