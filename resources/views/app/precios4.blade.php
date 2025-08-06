@extends('layouts.app')

@section('style')
    <style>
        /*Funcionamiento correto: falta agregar el componente del progress bar en los productos, y en precios realizar la busqueda de productos con y sin existencias*/
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
            margin-top: 40px !important;
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
            margin-top: 95px !important;
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
            /*transform: scale(0.97);/*Hacer el elemento un poco mas pequeño en hover*/
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
            .modal-content {
                right: 0;      /*Nos aseguramos que la modal no tenga margen a la derecha*/
                margin: 0 auto;/*Centramos la modal en dispositivos tablet*/
                width: 500px;  /*En resolucion movil tendra un ancho especifico*/
            }
        }
    </style>
@endsection

@section('content')
    <div id="appPreciosMovil" class="container-fluid">
        <div class="row justify-content-center">

            <!--Alerta-->
            <div :style="'border-left: solid 5px '+bordeIzquierdoAlerta+' !important'" class="alert alert-dismissible fade show msjAlert" :class="'alert-' + message.type" role="alert" v-show="message.message && message.type">
                <b><small>@{{ message.message }}</small></b>
                {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}}
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
                        <h1 class="title text-uppercase">@{{ (catPreciosId === null) ? 'Buscar un producto' : categoriaPreciosSelected.name }}</h1>
                        <h2 style="margin-top: -7px;" class="subtitle text-truncate text-uppercase">{{ auth()->user()->name }} · <span style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">@{{ bodegaSelected.name ?? 'Seleccione Una Bodega' }}</span></h2>
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
                        <input v-model="txtSearch" @keyup="getProducto" ref="searchInput" style="border: 1px solid #26C6DA;" type="text" class="form-control rounded-pill" aria-label="First name" placeholder="Buscar por nombre de producto"/>
                    </div>
                </div>
            </nav>

            <!-- panel-main -->
            <section :class="{'initBusqueda': !scrollInit && busquedaShow}" id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                <!--Abecedario: Este div se ocultara cuando el tamaño sea movil, y se mostrara cuando el tamaño sea tablet-->
                <div v-if="Object.keys(productosList).length > 0" id="abecedarioDiv" class="row mt-3 mb-4">
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

                <p v-if="Object.keys(productosList).length > 0" style="color: #546E7A; font-weight: 500; font-size: 10.5pt;" class="text-uppercase mt-2">Seleccione Un Producto</p>
                
                {{-- @{{ productosList }} --}}
                <div id="panel-main-opacity"><!--Se usa para la opacidad-->
                    <div class="row">
                        <!--Listado de productos-->
                        <div v-if="Object.keys(productosList).length > 0">
                            <div v-for="l in productosList" class="col-12 mb-2"><!--Margen inferior entre cada card-->
                                <div @click="setPrecio(l)" style="cursor: pointer;" class="panel-card" data-bs-toggle="modal" data-bs-target="#modalAgregarCantidad">
                                    <div style="margin-bottom: -15px;" class="row">
                                        <div class="col-9">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="text-uppercase text-truncate ms-3">@{{ l.detalle }}</p>
                                            <p style="color: #546E7A; font-weight: 500; font-size: 8.5pt; margin-top: -17px;" class="ms-3 text-uppercase">@{{ bodegaSelected.name }}</p>
                                        </div>
                                        <div class="col-3 text-end">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="me-2">$@{{ parseFloat(l.precio).toFixed(2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="txtSearch.length > 3 && Object.keys(productosList).length <= 0" style="color: #546E7A;">
                            <br><br>
                            <hr>
                            <p><b>
                                El producto que busca no tiene existencias o no está registrado con ese nombre. Consulte la existencia o el nombre del producto.
                            </b></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- panel-bottom -->
            <div id="panel-bottom">

                <!--Boton scroll up-->
                <div v-show="Object.keys(productosList).length >= 10" id="btnScrollUp">
                    <button type="button" title="Ir arriba" class="btn btn-outline-secondary btn-sm rounded-5">
                        <i class="bi bi-chevron-up"></i>
                    </button>
                </div>

            </div>



            <!--Modal agregar cantidad-->
            <div class="modal fade" id="modalAgregarCantidad" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">

                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">@{{ precioSelected.detalle }}</h1>
                            <button @click="lblOk = contadorProductos = null" type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div style="margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">
                                <div v-show="showModal">
                                    <p style="color: #546E7A; font-size: 11px; margin-bottom: 2px;">@{{ lblNombreBodega }}</p>
                                    <p style="color: #37474F; font-size: 11px; margin-bottom: 5px; font-weight: bold;">$ @{{ precioSelected.precio }}</p>

                                    <div v-show="!ocultarCantidad">
                                        <p style="color: #546E7A; margin-bottom: 2px;">Cantidad: @{{ precioSelected.existencias ?? '∞' }}<span v-show="precioSelected.existencias"> · LOTE #@{{ precioSelected.lote }} · Ven. @{{ precioSelected.vencimiento }}</span></p>
                                        <!--style="border-color: #26C6DA;"-->
                                        {{-- <input v-model="txtCantidad" @keyup="validarCantidad" aria-describedby="cantidadHelp" type="number" min="1" step="1" :max="precioSelected.existencias" :class="'form-control mb-2 ' + borderColorInputCantidad" placeholder="0"> --}}
                                        <input v-model="txtCantidad" @input="validateInput('txtCantidad', $event)" aria-describedby="cantidadHelp" type="text" :class="'form-control mb-2 ' + borderColorInputCantidad" placeholder="0" required maxlength="3" pattern="[0-9]{1,3}" inputmode="numeric" autocomplete="off">
                                        <div v-show="lblError" id="cantidadHelp" class="form-text text-danger mb-2">@{{ lblError }}</div>
                                        <div v-show="lblOk" id="cantidadHelp" class="form-text text-success mb-2">@{{ lblOk }}</div>
                                    </div>
    
                                    <div v-show="ocultarCantidad">
                                        <p style="color: #546E7A; margin-bottom: 2px;">Observaciones</p>
                                        <textarea v-model="txtObservaciones" style="border-radius: 6px; border-color: #787878;" class="form-control form-control-sm mb-2" cols="30" rows="2" placeholder="Agregue las observaciones necesarias para este producto en este campo. (Max. 200 caracteres)"></textarea>
                                    </div>
    
                                    <div class="row align-items-center">
                                        <div style="color: #546E7A; font-size: 11px; cursor: pointer;" class="col-6 mt-3">
                                            <p v-show="!ocultarCantidad" @click="ocultarCantidad = true"><i v-show="txtObservaciones.length > 0" class="bi bi-check2-all"></i> Agregar observación</p>
                                            <p v-show="ocultarCantidad" @click="ocultarCantidad = false" style="margin-top: -12px;">Agregar</p>
                                        </div>
                                        <div class="col-6 text-end">
                                            <button @click="funcAddProduct" :disabled="txtCantidad <= 0" style="background: #DAE0E5; color: #546E7A;" type="button" class="btn"><i class="bi bi-plus-lg"></i> Agregar</button>
                                        </div>
                                    </div>

                                    <p v-show="messageSuccess" style="margin-bottom: 2px;" class="text-success">@{{ messageSuccess }}</p>
                                </div>
                                <div v-show="!showModal" class="text-uppercase text-center">
                                    <h5 style="color: #37474F; margin-top: 15px;">@{{ lblError }}</h5>
                                </div>

                            </div>
                        </div>
                        
                    </div>
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
                    precios: @json($p),//Se listan todos los precios/productos segun la categoria_precios que hemos seleccionado
                    bodegas: @json($data['bodegas']),//El campo 'cid' es el campo 'bodegas_id' pero encriptado
                    catPreciosId: @json($catPreciosId),
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

                    //Tomar del localStorage el id y la categoria del precio que se selecciono en la pantalla anterior de 'categorias_precios' para mostralos en esta pantalla
                    categoriaPreciosSelected: {
                        id: null,
                        name: null,
                    },

                    //Para guardar las propiedades del precio seleccionado
                    precioSelected: {
                        id: null,
                        detalle: null,
                        precio: null,
                        token: null,
                        existencias: null,
                        lote: null,
                        vencimiento: null,
                    },
                    productos_id: null,
                    //-----

                    //---Elementos de la modal---
                    //showFormModal: false,
                    showModal: false,
                    lblNombreBodega: null,
                    ocultarCantidad: false,
                    txtCantidad: 1,
                    borderColorInputCantidad: 'border border-info-subtle',
                    txtObservaciones: '',
                    lblError: null,
                    lblOk: null,
                    messageSuccess: null,
                    contadorProductos: 0,
                    //---

                    //---Busqueda de productos---
                    productosList: {},
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

                            this.buscarProductosEnBodega(this.bodegaSelected.id);
                        }
                    }
                    else{//De lo contrario, sino hay datos... seleccionamos la primer bodega por defecto y la guardamos en el localStorage
                        this.bs = this.bodegas[0];

                        this.buscarProductosEnBodega(this.bs.id);
                    }
                }

                //A la escucha de eventos
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

                //4.- Tomar los datos del localStorage de 'categoria_precios' y mostrarlos en esta pantalla
                this.getCategoriaPreciosFromLocalStorage();

                //5.- Tomar los datos del localStorage de 'bodegas' y mostrarlos en esta pantalla
                this.getBodegasFromLocalStorage();

                this.$refs.searchInput.focus();

                //Precargar informacion antes de renderizarla
                if(localStorage.getItem('categoriaPreciosName') != null){
                    let nombrePrecio = localStorage.getItem('categoriaPreciosName');
                    nombrePrecio = nombrePrecio.slice(0, -1);//Elimina el ultimo caracter
                    //this.txtSearch = (nombrePrecio).toLowerCase();

                    //this.getProducto();
                }
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
                //New:
                getProductosApp2(){
                    axios.post("{{ route('precios.getProductosApp2') }}",{
                        categoriaPreciosId: localStorage.getItem('categoriaPreciosId'),
                        bodegasId: parseInt(localStorage.getItem('bodegaSelectedId')),
                    }).then((r) => {
                        console.log('rrr: ',r);

                        if(r.data.status)
                            this.productosList = r.data.list;

                        this.setMessage(r.data.message, r.data.type ? 'success' : 'danger');
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                getCategoriaPreciosFromLocalStorage(){
                    if(this.categoriaPreciosSelected.id == null){
                        if(localStorage.getItem('categoriaPreciosId') != null){
                            this.categoriaPreciosSelected.id   = localStorage.getItem('categoriaPreciosId');
                            this.categoriaPreciosSelected.name = localStorage.getItem('categoriaPreciosName');

                            this.getProductosApp2();
                        }
                    }
                },
                validateInput(inputName, event){//Recibe como parámetro el v-model de cada input y el event
                    let input = event.target.value;

                    input = input.replace(/\s/g, '');//Eliminar espacios en blanco
                    input = input.replace(/\D/g, '');//Eliminar caracteres que no sean números

                    this[inputName] = input;//Actualizar el valor del input correspondiente

                    //Validar existencias
                    if(this.txtCantidad <= 0){
                        this.borderColorInputCantidad = 'is-invalid';
                        this.lblError = 'Ingrese una cantidad válida.';
                    }
                    else if(this.txtCantidad > this.precioSelected.existencias && this.precioSelected.token === 1101){
                        this.borderColorInputCantidad = 'is-invalid';
                        this.lblError = 'La cantidad excede a la de existencias.';
                    }
                    else{
                        this.borderColorInputCantidad = 'is-valid';
                        this.lblError = null;
                    }
                },
                getBodegasFromLocalStorage(){
                    if(this.bodegaSelected.id == null){
                        if(localStorage.getItem('bodegaSelectedId') != null){
                            this.bodegaSelected.id   = localStorage.getItem('bodegaSelectedId');
                            this.bodegaSelected.name = localStorage.getItem('bodegaSelectedName');
                        }
                    }
                },
                getProducto(){
                    if(this.txtSearch.length > 2 && this.bodegaSelected && this.bodegaSelected != null){
                        axios.post("{{ route('precios.apiGetProductos') }}",{
                            busqueda: this.txtSearch.toLowerCase(),
                            bodega: parseInt(this.bodegaSelected.id),
                        }).then((r) => {
                            //console.log('r: ',r);

                            this.productosList = {
                                ...r.data.list_1,
                                ...r.data.list_5,
                            };

                            //console.log('Lista: ',this.productosList);
                        }).catch((err) => {
                            console.log('Error JS: ',err);
                        });
                    }
                    else{
                        localStorage.removeItem('categoriaPreciosId');
                        this.productosList = {};//Se limpia el objeto para no tener nada pre-cargado por defecto
                        //this.getProductosApp2();
                    }
                },
                setPrecio(pre){
                    //console.log('Precio: ',pre);

                    axios.post("{{ route('precios.getExistenciasProductosApp2') }}",{
                        categoriaPreciosId: localStorage.getItem('categoriaPreciosId'),
                        preciosId: parseInt(pre.id),
                        bodegasId: parseInt(localStorage.getItem('bodegaSelectedId')),
                    }).then((r) => {
                        console.log('Response precio seleccionado: ',r);

                        if(r.data.status){
                            this.showModal = true;
                            this.lblError = '';

                            let p = r.data.list;

                            this.precioSelected.id = p.id;

                            switch(p.token){
                                case 1101://Productos bajo inventario
                                    this.precioSelected.detalle     = p.detalle;
                                    this.precioSelected.precio      = p.precio;
                                    this.precioSelected.token       = p.token;
                                    this.precioSelected.existencias = p.existencia;
                                    this.precioSelected.lote        = p.lote;
                                    this.precioSelected.vencimiento = p.ven;
                                break;
                                case 1102://Bebidas preparadas
                                break;
                                case 1103://Platos
                                break;
                                case 1104://Combos-promocion
                                break;
                                case 1105://Productos sin existencias (desayunos, cenas, combos, cafes, bebidas preparadas)
                                    this.precioSelected.detalle     = p.detalle;
                                    this.precioSelected.precio      = p.precio;
                                    this.precioSelected.existencias = null;
                                break;
                                default:
                            }
                        }
                        else{
                            this.showModal = false;
                            this.precioSelected.detalle = '---';
                            this.lblError = r.data.message;
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                funcAddProduct(){
                    axios.post("{{ route('detalle_comandas.storeApp') }}",{
                        cantidad:    parseInt(this.txtCantidad),
                        comanda:     localStorage.getItem('comandaId'),//Id encriptado
                        observacion: this.txtObservaciones,
                        precio:      this.precioSelected.id,//Id encriptado
                        lote:        parseInt(this.precioSelected.lote),
                    }).then((r) => {
                        console.log('r: ',r);

                        if(r.data.status){
                            //window.location.href = window.location.pathname;//Recargamos esta misma pagina como la propiedad 'pathname'
                            this.contadorProductos += parseInt(this.txtCantidad);

                            if(this.precioSelected.existencias !== null){
                                this.precioSelected.existencias = this.precioSelected.existencias - parseInt(this.txtCantidad);
                            }
                            this.lblOk = `Se agregaron ${this.contadorProductos} producto(s) a esta comanda.`;
                            parseInt(this.txtCantidad = 0);
                            this.txtObservaciones = '';
                            //this.txtSearch = this.categoriaPreciosSelected.name;
                            //window.location.href = localStorage.getItem('urlComandaActual');//Redirigimos a la comanda actual
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                filterByLetter(letter){//La letra seleccionada se asignara de forma automatica a la caja de busqueda
                    this.txtSearch = letter;
                },
                goToBack(){
                    //window.location.href = "{{ route('app.comandas') }}";
                    //window.location.href = localStorage.getItem('urlComandaActual');//Redirigimos a la comanda actual
                    localStorage.removeItem('categoriaPreciosId');
                    window.location.href = "{{ route('app.categorias.precios') }}";
                },
                handleBodegaSeleccionada(bodega){//setBodega()
                    this.bs = bodega;

                    this.bodegaSelected.id   = this.bs.id;
                    this.bodegaSelected.name = this.bs.name;

                    localStorage.setItem('bodegaSelectedId',this.bodegaSelected.id);
                    localStorage.setItem('bodegaSelectedName',this.bodegaSelected.name);

                    //this.buscarProductosEnBodega(this.bodegaSelected.id);
                    this.getProducto();
                },
                buscarProductosEnBodega(bodegasId){
                    axios.post("{{ route('precios.buscarProductosEnBodega') }}",{
                        bodegas_id: bodegasId,
                    }).then((r) => {
                        //console.log('Productos en bodega: ',r);
                        this.precios = (r.data.list == null) ? [] : @json($p);
                    }).catch((err) => {
                        console.log('Error JS: ',err);
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
                    }, 10 * 1000);
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
                getProductoaaa(){
                    let regx = new RegExp((this.txtSearch).toLowerCase());

                    return this.precios.filter(c => {
                        //Si hay mas de dos caracteres en la caja de busqueda, hacemos el filtrado de los registros en todo el array, pero...
                        //Si solo hay un caracter en la caja de busqueda, se hace el filtrado del array, pero unicamente de los registros que comiencen por ese caracter nada mas
                        return regx.test((this.txtSearch.length >= 2) ? c.detalle.toLowerCase() : c.detalle.charAt(0).toLowerCase());
                    });

                    /*if(this.txtSearch.length >= 2)
                        return this.precios.filter(c => regx.test(c.detalle.toLowerCase()));
                    else
                        return this.precios.filter(c => regx.test(c.detalle.charAt(0).toLowerCase()));*/
                },
                getLetters(){//Extrae la primer letra de cada registro y devuelve un array de letras unicas ordenadas
                    const arrayLetters = this.precios.map(al => al.detalle.charAt(0).toUpperCase());
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
        app.component('progreso', component.progreso);
        app.mount('#appPreciosMovil');
    </script>
@endsection
