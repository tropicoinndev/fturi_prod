@extends('layouts.app')

@section('style')
    <style>
        .navbar-expand-md {/*Ocultamos el menu superior cuando esta en tamaño movil*/
                display: none;
        }
        .container {
            display: none;/*Se oculta el container que esta dentro de las seccion list*/
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
            width: 90%;
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
        .abecedario {
            background: #DAE0E5;
            color: #546E7A;
            border-radius: 100%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            padding: 1px;
            margin-right: 3px;
            /*text-align: center;*/
        }
        .letters {/*Se usa unicamente para colocar/centrar las letras dentro del boton*/
            margin-left: -1px;
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
        #abecedarioDiv {
            display: none;
        }
        .modal-content {
            left: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
            width: 100%;/*Establecemos el ancho al 100% para dispositivos móviles*/
        }
        .msjAlert {
            position: fixed;
            top: 7.5%;
            right: 1%;
            z-index: 100;
        }
        /*Resolucion movil o tablet*/
        @media(max-width: 767px) or (max-width: 991px){/*Se ocultara el div que contiene el abecesario cuando el tamaño sea un telefono*/
            
        }
        /*Resolucion tablet*/
        @media (min-width: 768px){/*Se mostrara el div que contiene el abecesario cuando el tamaño sea una tablet*/
            #abecedarioDiv {
                display: block;
            }
            .modal-content {
                right: 0;    /*Agregamos esta línea para asegurar que la modal ocupe todo el ancho de la pantalla*/
                margin: 0 auto;/*Centramos la modal en dispositivos tablet*/
                width: 500px;
            }
        }
        /*Resolucion desktop*/
        @media (min-width: 992px){/*Se mostrará el div que contiene el abecedario cuando el tamaño sea un escritorio*/

        }
    </style>
@endsection

@section('content')
    <div style="margin-top: -8px;" id="appListarPrecios" class="container-fluid panel-main-opacity">
        <!--Alerta-->
        <div class="alert show msjAlert" :class="'alert-' + message.type" usuario="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>

        <div class="row justify-content-center">
            
            <!-- panel header -->
            <div class="menuSinScroll" id="menu">
                <div class="container-fluid mt-3">
                    <div class="row">
                        <div class="col-1">
                            <a href="{{ route('app.categorias.precios') }}" style="text-decoration: none;">
                                <i class="bi bi-arrow-left"></i>
                            </a>
                        </div>
                        <div class="col-9">
                            <h1 style="margin-top: 8px;" class="title ms-2">@{{ categoriaPrecio.categoria }} - @{{ categoriaPrecio.token }}</h1>
                            <div id="titles">
                                {{-- <h2 style="margin-top: -3px;" class="subtitle text-uppercase ms-2 mb-3">Hola, {{ auth()->user()->name }}</h2> --}}
                                <h2 style="margin-top: -3px;" class="subtitle text-uppercase ms-2 mb-3" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">{{ auth()->user()->user }} · <span style="cursor: pointer;">@{{ bodegaSelectedName }}</span></h2>
                            </div>
                        </div>
                        <div class="col-2 text-end">
                            <i id="iconClose" class="bi bi-x-lg me-1"></i>
                            <i v-show="getPrecios.length > 0" id="iconSearch" class="bi bi-search me-1"></i>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-12">
                            <input v-model="txtBusqueda" id="txtSearch" type="text" class="form-control rounded-pill" placeholder="Buscar por nombre de producto">
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

                <p style="color: #37474F; font-size: 12px; font-weight: bold; margin-top: 10px; margin-bottom: 10px;">SELECCIONE UN PRODUCTO</p>
                {{-- {{ session('caja')->id }} --}}
                {{-- @{{ bodegas[0].bodegas.bodega }} --}}
                {{-- @{{ precios }} --}}
                <div v-if="getPrecios.length > 0" class="row mt-1">
                    <!-- v-for -->
                    <div v-for="pre in getPrecios" class="col-12 mb-2" style="cursor: pointer;">
                        <div class="panel-card" @click="funcSeleccionarPrecio(pre)" data-bs-toggle="modal" data-bs-target="#modalAgregarCantidad">
                            <div class="row">
                                <div style="margin-top: -4px;" class="col-9">
                                    <b style="font-size: 12px; color: #37474F;" class="text-truncate text-uppercase ms-3">@{{ pre.detalle }}</b>
                                    <p style="color: #546E7A; font-size: 10px; margin-top: -1px; margin-bottom: -2px;" class="ms-3 text-uppercase">@{{ bodegas[0].bodegas.bodega }}</p>
                                </div>
                                <div style="margin-top: -4px;" class="col-3 text-end">
                                    <b style="color: #37474F; font-size: 13px;" class="me-2">$ @{{ pre.precio }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <p style="color: #546E7A;">No se encontraron registros coincidentes.</p>
                </div>
            </div><!-- end panel main -->



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
                                                    <!--Verificar si hay coincidenacias con el id de la bodega actual y las restantes para no mostrarla-->
                                                    <div @click="setBodegaa(bo.bodegas_id)" data-bs-dismiss="modal"><!--Se a grego el atributo para cerrar la modal-->
                                                        <div class="panel-card">
                                                            <div class="row">
                                                                <div class="col-10">
                                                                    <b style="font-size: 12px; color: #37474F;" class="text-truncate ms-3">@{{ bo.bodegas_id }} - @{{ bo.bodegas.bodega }}</b>
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



            <!-- Modal agregar cantidad -->
            <div class="modal fade" id="modalAgregarCantidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div class="modal-body">
                            <div class="position-relative mb-4"><!--Div relativo para posicionar los iconos a la izquierda y derecha-->
                                <div class="position-absolute top-0 start-0" style="margin-top: 4px;">
                                    <h1 style="color: #FAFAFA;" class="modal-title fs-6 ms-3" id="staticBackdropLabel">@{{ precioSeleccionado.nombre }}</h1>
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
                                        <div style="height: 210px; margin-top: 10px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3">
                                            <div v-if="showFormModal">
                                                <p style="color: #546E7A; font-size: 11px; margin-bottom: 2px;">SALIDA DE @{{ bodegaSelectedName }}</p>
                                                <p style="color: #37474F; font-size: 11px; margin-bottom: 5px; font-weight: bold;">$ @{{ precioSeleccionado.precio }}</p>

                                                <div v-show="!ocultarCantidad">
                                                    <p style="color: #546E7A; margin-bottom: 2px;">Cantidad: @{{ precioSeleccionado.existencias }} · LOTE #@{{ precioSeleccionado.lote }} · Ven. @{{ precioSeleccionado.vencimiento }}</p>
                                                    <!--style="border-color: #26C6DA;"-->
                                                    <input v-model="txtCantidad" @keyup="funcValidarCantidad(precioSeleccionado.existencias)" aria-describedby="cantidadHelp" type="number" min="1" step="1" :max="precioSeleccionado.existencias" :class="'form-control mb-2 ' + borderColor" placeholder="0">
                                                    <div v-if="lblError" id="cantidadHelp" class="form-text text-danger">@{{ lblError }}</div>
                                                </div>
                                                <div v-show="ocultarCantidad">
                                                    <p style="color: #546E7A; margin-bottom: 2px;">Observaciones</p>
                                                    <textarea v-model="txtObservaciones" style="border-radius: 6px; border-color: #787878;" class="form-control form-control-sm mb-2" cols="30" rows="2" placeholder="Agregue las observaciones necesarias para este producto en este campo. (Max. 200 caracteres)"></textarea>
                                                </div>
            
                                                <div class="row align-items-center">
                                                    <div style="color: #546E7A; font-size: 11px; cursor: pointer;" class="col-6 mt-3">
                                                        <p v-show="!ocultarCantidad" @click="funcLblAddObservacion"><i class="bi bi-check2-all"></i> Agregar observacion</p>
                                                        <p v-show="ocultarCantidad" @click="funcLblAddObs" style="margin-top: -12px;">Agregar</p>
                                                    </div>
                                                    <div class="col-6 text-end">
                                                        <button @click="funcAddProducto" :disabled="txtCantidad <= 0 || lblError" style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else class="text-uppercase text-center">
                                                <h5 style="color: #37474F; margin-top: 15px;">No se pudieron encontrar existencias para este precio!!!</h5>
                                                <hr>
                                                <p>Verifique la existencia de <a href="{{ route('productos.index') }}">productos.</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div><!--End modal agregar cantidad-->

        </div>
    </div>
@endsection



@section('script')
    <script>
        document.addEventListener('DOMContentLoaded',function(){
            document.querySelector('.panel-main-opacity').classList.add('loaded');

            //Declaracion de variables
            let menu       = document.getElementById("menu");
            let iconSearch = document.getElementById('iconSearch');
            let iconClose  = document.getElementById('iconClose');
            let titles     = document.getElementById('titles');
            let txtSearch  = document.getElementById('txtSearch');

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
            el: '#appListarPrecios',
            data: {
                precios:         @json($p),                      //Se listan todos los precios/productos segun la categoria_precios que hemos seleccionado
                categoriaPrecio: @json($data['categoriaPrecio']),//Se usa para saber el nombre de la categoria seleccionada y el token para la verificacion de existencias
                bodegas:         @json($data['bodegas']),
                txtBusqueda:     '',

                bodegaSelectedName: '',
                bodegaSelectedId:   null,
                bodegaSelectedIdd:  null,

                precioSeleccionado: {//Para guardar las propiedades del precio seleccionado
                    id:     null,
                    nombre: '',
                    precio: 0.00,
                    existencias: 0,
                    lote: null,
                    vencimiento: null,
                },
                productos_id: null,

                //Para mostrar y ocultar elementos de la modal agregar-cantidad
                showFormModal: false,
                ocultarCantidad: false,
                txtCantidad: 1,
                txtObservaciones: null,
                borderColor: 'border border-info-subtle',
                lblError: null,

                //Alerta
                message: {},
            },
            mounted() {
                this.bodegaSelectedName = localStorage.getItem('bodegaSelectedName');
                this.bodegaSelectedId   = localStorage.getItem('bodegaSelectedId');
            },
            methods: {
                setBodega: function(bodega){
                    localStorage.setItem('bodegaSelectedId',bodega.id);
                    localStorage.setItem('bodegaSelectedName',bodega.bodega);

                    this.bodegaSelectedName = localStorage.getItem('bodegaSelectedName');
                    this.bodegaSelectedId   = localStorage.getItem('bodegaSelectedId');
                },
                setBodegaa: function(id){
                    console.log('bo id: ',id);
                },
                funcSeleccionarPrecio: function(pre){//Recibe como parametro todo el array del precio seleccionado
                    this.precioSeleccionado.id     = pre.id;
                    this.precioSeleccionado.nombre = pre.detalle;
                    this.precioSeleccionado.precio = pre.precio;

                    this.getProducto();
                },
                getProducto: function(){
                    axios.post("{{ route('precios.getProductosApp') }}",{
                        precio_id: this.precioSeleccionado.id,
                        bodega_id: parseInt(this.bodegaSelectedId),
                    }).then((r) => {
                        console.log('r: ', r.data);

                        if(r.data.list != null){
                            this.precioSeleccionado.existencias = r.data.list.existencia;
                            this.precioSeleccionado.lote        = r.data.list.id;
                            this.precioSeleccionado.vencimiento = r.data.list.ven;
                            this.productos_id = r.data.list.productos_id;//Id del producto que se usara para hacer el descargo de la tabla 'existencias'
                            
                            this.showFormModal = true;
                        }
                        else{
                            this.showFormModal = false;
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                //Funciones para mostrar y ocultar los campos de las obervaciones
                funcLblAddObservacion: function(){
                    this.ocultarCantidad = true;
                },
                funcLblAddObs: function(){
                    this.ocultarCantidad = false;
                },
                funcValidarCantidad: function(existencias){
                    if(this.txtCantidad <= 0 || this.txtCantidad > existencias){
                        this.borderColor = 'is-invalid';
                        this.lblError = (this.txtCantidad <= 0) ? 'Ingrese una cantidad válida.' : 'La cantidad excede a la de existencia.';
                    }
                    else{
                        this.borderColor = 'is-valid';
                        this.lblError = null;
                    }
                },
                setMessage(m, t){
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 3 * 1000)
                },
                funcAddProducto: function(){
                    axios.post("{{ route('precios.addProductoApp') }}",{
                        comandas_id:   localStorage.getItem('comandaId'),//Id encriptado
                        precios_id:    this.precioSeleccionado.id,//Id no enciptado
                        precio:        this.precioSeleccionado.precio,
                        cantidad:      this.txtCantidad,
                        observaciones: this.txtObservaciones,
                        productos_id:  this.productos_id,//Id del producto que se usara para hacer el descargo de la tabla 'existencias'
                        bodegas_id:    this.bodegaSelectedId,//Id no encriptado
                    }).then((r) => {
                        //console.log('r2: ',r);

                        this.setMessage(r.data.message, (r.data.status) ? 'success' : 'danger');

                        if(r.data.status){//Se usa solo para mostrar/actualizar la cantidad de producto descargado en la modal
                            this.precioSeleccionado.existencias = (this.precioSeleccionado.existencias - this.txtCantidad);
                            this.txtCantidad = 1;//Cuando ya se agrego un producto, colocamos de nuevo la cantidad en 1
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
            },
            computed: {
                getPrecios: function(){
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.precios.filter(c => regx.test(c.detalle.toLowerCase()));
                },
            }
        });
    </script>
@endsection
