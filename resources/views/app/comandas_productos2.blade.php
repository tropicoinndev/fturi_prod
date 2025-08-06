@extends('layouts.app')

@section('style')
    <style>
        body {
            background-color: #E0F2F1;
            font-family: sans-serif;
        }
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
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
        .panel-card, .panel-card-modal {/*Estas son las card de color celeste donde se muestra la información*/
            background: #E3F2FD;
            border-radius: 8px;
            border-bottom: solid 4px #BBDEFB;
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
        .ocultarElementosModal {
            display: none;
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
        @media (min-width: 992px){

        }
    </style>
@endsection

@section('content')
    <div style="margin-top: -8px;" id="appProductosComandas" class="container-fluid panel-main-opacity">
        <div class="row justify-content-center">
            
            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-1">
                            <a href="{{ route('app.comandas') }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">COMANDA #{{ $comanda->mesa }}</h1>
                            <div id="titles">
                                <h2 style="margin-top: -3px;" class="subtitle text-uppercase ms-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">{{ auth()->user()->user }}<span style="cursor: pointer;">{{-- @{{ bodegaSelectedName }} --}}</span></h2>
                                <h1 class="title text-uppercase ms-2">{{ ($comanda->titular == null && $comanda->clientes_id == null) ? 'Agregue un cliente / titular' : $comanda->titular }}</h1>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i v-show="getComanda.length > 0" id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" autocomplete="off" placeholder="Buscar por nombre de producto">
                        </div>
                    </div>
                </div>
            </div><!-- end panel header-->



            <!-- panel main -->
            <div class="panel-main">
                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">PRODUCTOS AGREGADOS</p>

                {{-- @{{ bodegas }} --}}
                {{-- @{{ getComanda }} --}}
                <div v-if="getComanda.length > 0" class="row mb-5">
                    <!-- v-for -->
                    <div v-for="co in getComanda" class="col-12 mb-2">
                        <div class="panel-card">
                            <div class="row">
                                <div style="margin-top: 5px;" class="col-1 text-center">
                                    <b style="color: #37474F; font-size: 11px; margin-left: 5px;">@{{ co.cantidad }}</b>
                                </div>
                                <div style="margin-top: -4px;" class="col-8">
                                    <b style="font-size: 12px; color: #37474F;" class="text-truncate">@{{ co.precios.detalle }}</b>
                                    <!--@{{ co.lotes[0]['existencias'].bodegas.bodega }}-->
                                    <p style="color: #546E7A; font-size: 10px; margin-top: -1px; margin-bottom: -2px;">aaa</p>
                                </div>
                                <div style="margin-top: -4px;" class="col-3 text-end">
                                    <b style="color: #37474F; font-size: 13px;" class="me-2">$ @{{ co.total.toFixed(2) }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p style="color: #546E7A;">Aun no hay productos agregados a esta comanda.</p>
                </div>
            </div><!-- end panel main -->



            <!-- panel bottom -->
            <div class="panel-fixed">
                <div class="row">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle">
                                <a href="{{ route('app.categorias.precios') }}" style="text-decoration: none;">
                                    <i style="color: white;" class="bi bi-list-ul"></i>
                                </a>
                            </button>

                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-cart"></i>
                            </button>

                            <span style="background-color: #0d6efd; padding: 3px;">
                                <button style="height: 50px; background-color: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <i class="bi bi-printer" style="color: #0d6efd;"></i>
                                </button>
                            </span>

                            <button type="button" class="btn btn-primary btn-lg">
                                <i class="bi bi-file-earmark-text"></i>
                            </button>

                            <button @click="getProducto" id="btnSearch" type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <i class="bi bi-search"></i>
                            </button>

                        </div>
                    </div>
                </div>
            </div><!-- end panel bottom -->



            <!-- Modal cambiar bodega -->
            <div class="modal fade" id="modalCambiarBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">CAMBIAR BODEGA</h1>
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
                                        <div style="background: #fff; margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3">

                                            <div class="row mt-1 mb-2">
                                                <div v-for="bo in bodegas" class="col-12 mb-2">
                                                    <div @click="setBodega(bo.bodegas)" data-bs-dismiss="modal"><!--Se a grego el atributo para cerrar la modal-->
                                                        <div class="panel-card">
                                                            <div class="row">
                                                                <div class="col-10">
                                                                    <b style="font-size: 12px; color: #37474F;" class="text-truncate ms-3">@{{ bo.bodegas.id }} - @{{ bo.bodegas.bodega }}</b>
                                                                </div>
                                                                <div class="col-2 text-end">
                                                                    <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
        
                                            {{-- <div class="row">
                                                <div class="col-12 text-end">
                                                    <button style="background: #DAE0E5; color: #546E7A;" type="button" class="btn" disabled><i class="bi bi-floppy"></i> Guardar</button>
                                                </div>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!--End modal body-->
                    </div>
                </div>
            </div><!--End modal cambiar bodega-->

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
            
            //Escucha de botones del panel main
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

        //Vue
        var app = new Vue({
            el: '#appProductosComandas',
            data: {
                comanda: @json($comanda->detalles_comanda),
                bodegas: @json($bodegas),//el campo 'cid' es el campo 'bodegas_id' pero encriptado

                bodegaSelectedId:   null,
                bodegaSelectedName: null,

                txtBusqueda: '',
            },
            mounted() {
                //console.log('Component mounted. Comandas Productos Blade');

                //console.log('Protocolo:',window.location.protocol);//http
                //console.log('Host:',window.location.host);         //Con puerto: 192.168.3.214:8005
                //console.log('HostName:',window.location.hostname); //Sin puerto: 192.168.3.214
                //console.log('Pathname:',window.location.pathname); //app/comandas/productos/eyJpdiI6ImxocHlLRlBK
                
                let urlCompleta = window.location.pathname;       //Obtener la URL actual
                let partesUrl   = urlCompleta.split('/');         //Separar la URL por el caracter '/'
                let comandaId   = partesUrl[partesUrl.length - 1];//El ultimo elemento del array sera el ID encriptado de la comanda

                //Almacenar la URL de la comanda actual y el id encriptado en el localStorage
                localStorage.setItem('urlComandaActual',window.location.href);
                localStorage.setItem('comandaId',comandaId);
                localStorage.setItem('cajaId',{{ session('caja')->id }});

                //Almacenar bodegas en localStorage
                if(this.bodegaSelectedId == null){
                    if(localStorage.getItem('bodegaSelectedId') != null){
                        if(this.bodegas.find(i => i.bodegas_id == localStorage.getItem('bodegaSelectedId'))){
                            this.bodegaSelectedId = localStorage.getItem('bodegaSelectedId');
                        }
                    }
                    else{
                        this.bodegaSelectedId = this.bodegas[0].bodegas_id;
                        localStorage.setItem('bodegaSelectedId',this.bodegaSelectedId);

                        this.bodegaSelectedName = this.bodegas[0]['bodegas'].bodega;
                    }
                }
            },
            methods: {
                setBodega: function(bodega){
                    localStorage.setItem('bodegaSelectedId',bodega.id);

                    this.bodegaSelectedId   = localStorage.getItem('bodegaSelectedId');
                    this.bodegaSelectedName = bodega.bodega;
                },
                getProducto: function(){
                    axios.post("{{ route('precios.apiGetProductos') }}", {
                        busqueda: 1,//productos_id
                        bodega:   this.bodegaSelectedId,
                    }).then((r) => {
                        console.log('r: ', ...r.data.list_1);
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
            },
            computed: {
                getComanda: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.comanda.filter(c => regx.test(c.precios.detalle.toLowerCase()));
                },
            }
        });
    </script>
@endsection
