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
            height: 143px !important;
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
            margin-top: 25px;
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
            transition: transform 0.4s ease;/*Transicion para el cambio de tamaño*/
        }
        .btn-check:checked+.panel-card,
        .panel-card-activa {
            background: #BBDEFB !important;
            border: none;
            border-bottom: solid 5px #90CAF9;
        }
        .panel-card:hover {
            border-bottom: solid 5px #90CAF9;
        }
        /*.panel-card:hover {/*Aninacion de las card*/
            /*cursor: pointer;
            transform: scale(0.97);/*Hacer el elemento un poco mas pequeño en hover*/
            /*box-shadow: 3px 3px 5px rgba(0, 0, 0, 0.1);/*Aplicar sombra abajo y a la derecha*/
        /*}*/
        /*.panel-card:active {
            background-color: #90CAF9;
            border-bottom: solid 5px #90CAF9;
        }*/

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

        /*Configuraciones de la modal del titular*/
        .modal-content-titular {
            background: #37474F;
            position: fixed;/*Indica la posicion inferior a la que se colocara la modal*/
            top: 0;
            left: 0;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        /*Configuraciones de la modal de la informacion*/
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

        
        .bg-tiempo {
            background: #26A69A;
            color: #ECEFF1;
        }

        /*Resolucion tablet*/
        @media(min-width: 768px){
            .modal-content-titular {
                right: 0;      /*Nos aseguramos que la modal no tenga margen a la derecha*/
                margin: 0 auto;/*Centramos la modal en dispositivos tablet*/
                width: 500px;  /*En resolucion movil tendra un ancho especifico*/
                height: 300px;
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
    <div id="appComandasProductosMovil" class="container-fluid">
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
                        <button style="margin-top: -5px;" type="button" class="btn">
                            <i style="font-size: 20px; color: #FAFAFA;" class="mdi mdi-arrow-left"></i>
                        </button>
                    </div>

                    <div class="col-8">
                        <h1 class="title text-uppercase">Comanda #{{ $comanda->mesa }}</h1>
                        <h2 style="margin-top: -7px;" class="subtitle text-truncate text-uppercase">{{ auth()->user()->name }} · <span style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#modalCambiarBodega">@{{ bodegaSelected.name ?? 'Seleccione Una Bodega' }}</span></h2>

                        <div id="information" v-show="!scrollInit && !busquedaShow" class="mt-3"><!--Esta informacion se ocultara al hacer scroll-->
                            <h1 style="cursor: pointer;" class="title text-uppercase" data-bs-toggle="modal" data-bs-target="#modalAgregarTitular">
                                @{{ comandaaa.titular != null ? comandaaa.titular : (comandaaa.clientes_id != null ? comandaaa.clientes.nombre : 'CLIENTE / TITULAR ASIGNADO') }}
                            </h1>
                        </div>
                    </div>
                    
                    <div class="col-2 text-end">
                        <button style="margin-top: -7px;" type="button" class="btn">
                            {{-- <i v-show="!busquedaShow" class="mdi mdi-magnify" @click="busquedaShow = true"></i> --}}
                            <i v-show="busquedaShow" class="mdi mdi-close" @click="busquedaShow = false"></i>
                        </button>
                    </div>
                </div>

                <!--Caja de busqueda-->
                <div class="row mt-1 mb-2" :class="{ active: !busquedaShow }" v-show="busquedaShow">
                    <div class="col-10 m-auto">
                        <input v-model="txtSearch" @keyup="getProducto" ref="searchInput" style="border: 1px solid #26C6DA;" type="text" class="form-control rounded-pill" aria-label="First name" placeholder="Buscar un producto"/>

                        <!--Listado desplegable de clientes-->
                        {{-- <div style="position: absolute; z-index: 150 !important; margin-top: 8px; width: 80%;" class="shadow">
                            <ul class="list-group">
                                <li v-for="producto in listaProductos" @click="setProducto(producto)" style="cursor: pointer;" class="list-group-item d-flex justify-content-between align-items-center">@{{ producto.detalle }}
                                    <span class="badge text-bg-primary rounded-pill">$ @{{ producto.precio }}</span>
                                </li>
                                <li v-if="listaProductos.length == 0" class="list-group-item d-flex justify-content-between align-items-center">
                                    No se encontró ningún producto con ese nombre.
                                </li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
            </nav>

            <!-- panel-main -->
            <section id="panel-main" class="mb-5"><!--Margen inferior entre la ultima card y el panel inferior de los botones-->
                {{-- <p style="color: #546E7A; font-weight: 500; font-size: 10.5pt;" class="text-uppercase mt-3">Productos agregados <small style="color: #8c8e8f;">(@{{ getComanda.length }})</small></p> --}}

                <div style="margin-top: 18px;" class="row">
                    <div class="col-8">
                        <p style="color: #546E7A; font-weight: 500; font-size: 10.5pt;" class="color-gris text-uppercase">Productos agregados <small style="color: #8c8e8f;">(@{{ getComanda.length }})</small></p>
                    </div>
                    <div class="col-4 text-end">
                        <button type="button" class="btn btn-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalInfo">
                            <span class="mdi mdi-information-outline"></span>
                        </button>
                    </div>
                </div>

                <!--Alerta-->
                <div class="row">
                    <div class="col-12">
                        <x-message></x-message>
                    </div>
                </div>
                
                {{-- @{{ getComanda }} --}}
                <div v-show="getComanda.length > 0" id="panel-main-opacity"><!--Se usa para la opacidad-->
                    <div class="row">
                        <!--Listado de detalle de comanda-->
                        <div v-if="listaProductos.length == null">
                            <div v-for="c in getComanda" class="col-12 mb-3"><!--Margen inferior entre cada card-->
                                <input type="checkbox" class="btn-check" :id="'producto-'+c.id" autocomplete="off" multiple :value="c" v-model="selectedDetalle">
                                <label class="panel-card" style="cursor: pointer; width: 100%;" :for="'producto-'+c.id">
                                    <div style="margin-bottom: -5px;" class="row">
                                        <div style="margin-top: 15px;" class="col-1 text-center">
                                            <p style="color: #546E7A; font-weight: 500; font-size: 9pt;" class="lunasima-bold ms-2">@{{ c.cantidad }}</p>
                                        </div>
                
                                        <div class="col-8">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="lunasima-bold text-truncate text-uppercase ms-2">@{{ c.precios.detalle }}</p>
                                            {{-- <p v-if="c.precios.categorias_precios.token == 1105" style="color: #546E7A; font-weight: 500; font-size: 8.5pt; margin-top: -17px;" class="lunasima-regular text-truncate ms-2">NO REQUIERE EXISTENCIAS</p> --}}
                                            {{-- <p v-if="c.precios.categorias_precios.token == 1101" style="color: #546E7A; font-weight: 500; font-size: 8.5pt; margin-top: -17px;" class="lunasima-regular text-uppercase text-truncate ms-2">@{{ c.observaciones }}</p> --}}
                                            <p style="color: #546E7A; font-weight: 500; font-size: 8.5pt; margin-top: -17px;" class="lunasima-regular text-uppercase text-truncate ms-2">@{{ c.observaciones }}</p>
                                        </div>
            
                                        <div class="col-3 text-end">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="lunasima-bold me-1">$ @{{ c.total.toFixed(2) }}</p>
                                        </div>
                                    </div>

                                    <div v-if="c.solicitud && c.user_solicita_id && c.aceptacion && c.espera && c.entregado == null" class="row">
                                        <div style="font-size: 9pt;" class="col-12 text-center">
                                            {{-- <b>Aceptado: </b>2024-06-11 08:20:55 · 3 min/30 minutos · 10.58% · <b>Faltante:</b> 26 min
                                            <div class="progress" role="progressbar" aria-label="Basic example" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                                                <div class="progress-bar overflow-visible bg-tiempo" style="width: 85%">Entrega 09:52</div>
                                            </div> --}}

                                            <progreso :aceptacion="c.aceptacion" :espera="c.espera" :prioridad="c.prioridad" :incremento="c.incremento_tiempo" :actualizar="2"/>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" v-if="c.entregado">
                                        <div class="progress">
                                            <div class="progress-bar bg-tiempo" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                Completado @{{ c.entregado }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 mt-2" v-if="c.cancelado">
                                        <div class="alert alert-danger" role="alert">
                                            Producto cancelado: @{{ c.observacion_negacion }}
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-8 offset-lg-1 mt-1" v-if="c.solicitud && c.aceptacion == null" :title="'Solicitado: ' + c.solicitud">
                                        <div class="spinner-border spinner-border-sm me-2" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        Esperando respuesta @{{ c.solicitud }}
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!--Listado de productos-->
                        {{-- <div v-else>
                            <div v-for="producto in listaProductos" class="col-12 mb-2"><!--Margen inferior entre cada card-->
                                <div style="cursor: pointer;" @click="setProducto(producto)" class="panel-card">
                                    <div style="margin-bottom: -15px;" class="row">
                                        <div class="col-9">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="text-uppercase text-truncate ms-3">@{{ producto.detalle }}</p>
                                            <p style="color: #546E7A; font-weight: 500; font-size: 8.5pt; margin-top: -17px;" class="ms-3 text-uppercase">@{{ producto.categoria }}</p>
                                        </div>
                                        <div class="col-3 text-end">
                                            <p style="color: #546E7A; font-weight: 600; font-size: 10.5pt;" class="me-2">$ @{{ producto.precio }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
                <div v-show="getComanda.length <= 0" style="color: #546E7A;">
                    <hr>
                    {{-- <p style="margin-bottom: 1px;">¡No se encontraron registros disponibles o no coincidieron los resultados con tu búsqueda!</p> --}}
                    <p><b><small>¡Aún no hay productos agregados a esta comanda!.</small></b></p>
                </div>
            </section>

            <!-- panel-bottom -->
            <!--El estilo dinamico se usa para dejar fijo el panel-bottom solo si ha seleccionado almenos un producto-->
            <div id="panel-bottom" :style="{ bottom: hayProductosSeleccionado ? '0px' : '' }">
                <div class="row text-center">
                    <div class="col-12">
                        <div class="btn-group" role="group" aria-label="Basic example">

                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle" title="Ir a Categorias Precios">
                                <a href="{{ route('app.categorias.precios') }}" class="text-decoration-none"><!--Aun retorna al diseño anterior-->
                                    <span class="mdi mdi-format-list-bulleted text-white"></span>
                                </a>
                            </button>
                            <button @click="setSolicitud" type="button" class="btn btn-primary btn-lg" title="Solicitar productos">
                                <span class="mdi mdi-cart-outline"></span>
                            </button>

                            <span @click="ImprimirComanda" style="background: #0d6efd; padding: 3px;">
                                <button style="height: 50px; background: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                    <span style="color: #0d6efd;" class="mdi mdi-printer"></span>
                                </button>
                            </span>

                            <button @click="solicitarComprobante" type="button" class="btn btn-primary btn-lg" title="Solicitar comprobante">
                                <span class="mdi mdi-file-document-outline"></span>
                            </button>
                            {{-- <button @click="busquedaShow = true" type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <a href="javascript:void(0);" class="text-decoration-none">
                                    <i class="bi bi-search text-white"></i>
                                </a>
                            </button> --}}
                            <button type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                <a href="{{ route('precios.app') }}" class="text-decoration-none">
                                    <span class="mdi mdi-magnify"></span>
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



            <!--Modal cambiar bodega-->
            {{-- <div class="modal fade" id="modalCambiarBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content modal-content-bodega">

                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">Seleccione Una Bodega</h1>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <div style="background: #fff; margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">
                                
                                <div class="row">
                                    <div v-for="b in bodegas" class="col-12">
                                        <!--No se mostrara en el listado la bodega que haya sido seleccionada-->
                                        <div v-if="b !== bs" @click="setBodega(b)" data-bs-dismiss="modal"><!--Se a grego el atributo para cerrar la modal-->
                                            <div style="cursor: pointer;" class="panel-card mb-2">
                                                <div class="row">

                                                    <div class="col-10">
                                                        <b style="font-size: 12px; color: #37474F;" class="ms-3">@{{ b.bodegas.bodega }}</b>
                                                    </div>

                                                    <div class="col-2 text-end">
                                                        <i style="font-size: 15px; color: #37474F;" class="bi bi-chevron-right me-3"></i>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                    </div>
                </div>
            </div> --}}

            <div class="modal fade" id="modalCambiarBodega" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <!--Hacemos uso del componente modal para cambiar bodega-->
                <!-- bodega-seleccionada es el evento y esta ejecuta el metodo handleBodegaSeleccionada-->
                <modal_cambiar_bodega :bodegas="bodegas" @bodega-seleccionada="handleBodegaSeleccionada" />
            </div>

            <!--Modal agregar titular-->
            <div class="modal fade" id="modalAgregarTitular" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content modal-content-titular">

                        <div style="border-bottom: none;" class="modal-header">
                            <h1 class="modal-title fs-5 text-white text-uppercase ms-3" id="staticBackdropLabel">@{{ cliente != null ? 'Editar' : 'Asignar' }} clientes</h1>
                            <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
                                <i class="mdi mdi-close"></i>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('comandas.clientes') }}" method="post">
                                @csrf

                                <div style="margin-top: -25px; border-bottom: solid 5px rgba(84, 110, 122, 51%);" class="panel-card-modal p-3 mb-3">
                                    {{-- <label style="color: #546E7A; font-weight: 500; font-size: 12.5pt;" for="titular" class="form-label">Titular: <span style="color: #546E7A; font-weight: 600; font-size: 10.5pt;">@{{ clienteSelected.titular }}</span></label> --}}
                                    
                                    <!--Inputs ocultos-->
                                    <input type="hidden" name="comanda" :value="(comandaId != null ? comandaId : 0)">
                                    <input type="hidden" name="clientes_id" :value="(clienteSelected.id != null ? clienteSelected.id : 0)">

                                    <div class="row">
                                        <div class="col-4">
                                            <div class="input-group mb-3">
                                                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                                                    <input v-model="asignacion" id="asignacion1" type="radio" class="btn-check" name="asignacion" value="1" autocomplete="off" checked>
                                                    <label for="asignacion1" class="btn btn-outline-dark">Cliente</label>
        
                                                    <input v-model="asignacion" id="asignacion2" type="radio" class="btn-check" name="asignacion" value="2" autocomplete="off">
                                                    <label for="asignacion2" class="btn btn-outline-dark">Titular</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-8 mt-2">
                                            <span class="text-end">@{{ comandaaa.titular != null ? 'Titular asignado: ' + comandaaa.titular : (comandaaa.clientes_id != null ? 'Cliente asignado: ' + comandaaa.clientes.nombre : 'CLIENTE / TITULAR ASIGNADO') }}</span>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-12">
                                            <input v-model="txtTitular" id="titular" name="titular" @keyup="getClientes" style="border-color: #26C6DA;" type="text" class="form-control mb-3" :placeholder="clienteSelected.titular ?? (asignacion == 1 ? 'Escriba para buscar un cliente...' : 'Escriba un titular para esta comanda...')" autocomplete="off">
                                            {{-- <button v-if="clienteSelected.id" @click="setTitular" class="btn btn-outline-secondary" type="button" id="button-addon2">Editar</button>
                                            <button v-if="!clienteSelected.id && txtTitular.length > 2" @click="setTitular" class="btn btn-outline-secondary" type="button" id="button-addon2">Agregar titular</button> --}}

                                            {{-- <div style="background: red;" v-show="clienteSelected.id > 0">
                                                Cliente seleccionado: @{{ clienteSelected.titular }}
                                                <span class="btn mdi mdi-close" @click="clienteSelected = []"
                                                    title="Quitar asignacion"></span>
                                            </div> --}}

                                            <div v-show="clienteSelected.id > 0" style="font-size: 12px;" class="alert alert-success alert-dismissible fade show" role="alert">
                                                <strong>Cliente seleccionado:</strong> @{{ clienteSelected.titular }}
                                                <button @click="clienteSelected = []" type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                            </div>
        
                                            <!--Listado desplegable de clientes-->
                                            <div v-if="txtTitular.length > 2 && asignacion == 1" style="position: absolute; z-index: 1000; margin-top: -12px; width: 87%;" class="shadow">
                                                <ul class="list-group">
                                                    <li v-for="cliente in clientes" @click="setCliente(cliente)" style="cursor: pointer;" class="list-group-item d-flex justify-content-between align-items-center">@{{ cliente.cliente }}
                                                        {{-- <span class="badge text-bg-primary rounded-pill">14</span> --}}
                                                    </li>
                                                    <li v-if="clientes.length == 0 && clienteSelected.id == null" class="list-group-item d-flex justify-content-between align-items-center">
                                                        No se encontró ningún cliente con ese nombre / identificación
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
    
                                </div>
    
                                <div class="row">
                                    <div class="col-12 text-end">
                                        {{-- <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cerrar</button> --}}
                                        <button type="submit" class="btn btn-primary" :disabled="txtTitular.length <= 3 && !clienteSelected.id">Guardar</button>
                                    </div>
                                </div>
                            </form>
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
                                    <button type="button" style="margin-left: 0%;" class="position-absolute top-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn1" style="width: 2rem; height:2rem;">1</button>
                                    <button type="button" style="margin-left: 25%;" class="position-absolute top-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn2" style="width: 2rem; height:2rem;">2</button>
                                    <button type="button" style="margin-left: 50%;" class="position-absolute top-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn3" style="width: 2rem; height:2rem;">3</button>
                                    <button type="button" style="margin-left: 75%;" class="position-absolute top-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn4" style="width: 2rem; height:2rem;">4</button>
                                    <button type="button" style="margin-left: 100%;" class="position-absolute top-0 translate-middle btn btn-sm rounded-pill" :class="colorBtn5" style="width: 2rem; height:2rem;">5</button>
                                </div>
                                <hr style="margin-top: -1px;">

                                <!--Descripciones-->
                                <div class="row text-center">
                                    <div class="col-12">
                                        <div class="btn-group" role="group" aria-label="Basic example">

                                            <button type="button" class="btn btn-primary btn-lg rounded-start-circle">
                                                <a href="javascript:void(0);" class="text-decoration-none">
                                                    <span class="mdi mdi-format-list-bulleted text-white"></span>
                                                </a>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-lg">
                                                <span class="mdi mdi-cart-outline"></span>
                                            </button>
                                            <span style="background: #0d6efd; padding: 3px;">
                                                <button style="height: 50px; background: #FAFAFA;" type="button" class="btn btn-outline-primary btn-lg rounded-start-circle rounded-end-circle">
                                                    <span style="color: #0d6efd;" class="mdi mdi-printer"></span>
                                                </button>
                                            </span>
                                            <button type="button" class="btn btn-primary btn-lg">
                                                <span class="mdi mdi-file-document-outline"></span>
                                            </button>
                                            <button type="button" class="btn btn-primary btn-lg rounded-end-circle">
                                                <a href="javascript:void(0);" class="text-decoration-none">
                                                    <span class="mdi mdi-magnify"></span>
                                                </a>
                                            </button>
                                        </div>
                                    </div>

                                    <!--Escreen 1-->
                                    <div v-if="screen === 1" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-left: -205px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El primer ícono que tiene tres puntitos y tres rayas, tiene como funcionalidad principal, listar todas las diferentes categorías que puede tener un producto;
                                            Como por ejemplo: categorías de bebidas preparadas, desayunos, cenas, cafés, promociones, combos, etc. Al seleccionar una categoría en especifico, se mostrarán los productos relacionados a esa categoría.</p>
                                    </div>

                                    <!--Escreen 2-->
                                    <div v-if="screen === 2" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-left: -105px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El segundo ícono que está representando con un carrito de compras, permite solicitar productos al area de producción; Es importante mencionar que antes de hacer la solcitud de algun producto, se deba seleccionar al menos uno.</p>
                                    </div>

                                    <!--Escreen 3-->
                                    <div v-if="screen === 3" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-right: 0px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El tercer ícono que tiene el dibujo de una impresora, le permitirá instantaneamente imprimir la factura/ticket con el detalle de los productos agregados;
                                            Esta impresión saldrá en la caja desde donde se esté comandando.</p>
                                    </div>

                                    <!--Escreen 4-->
                                    <div v-if="screen === 4" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-right: -110px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> El cuarto ícono en forma de una página o un documento, tiene la funcionalidad de solicitar el comprobante de la comanda,
                                            una vez hecho click en éste botón, le llegará una notificación al cajero y luego le redigirá al listado de todas las comandas.</p>
                                    </div>

                                    <!--Escreen 5-->
                                    <div v-if="screen === 5" style="height: 200px;">
                                        <p style="font-size: 15pt; margin-right: -210px;"><span class="mdi mdi-arrow-up text-primary"></span></p>

                                        <p><b>Descripción:</b> Y por último, pero no menos importante, es el botón de la lupa, al hacer click sobre este ícono, lo llevará a una nueva ventana en la cual prodrá buscar un producto en general sin estar relacionado a una categoría en específico.</p>
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
        let app = window.appVue({
            data(){
                return {
                    //---Modal info---
                    screen: 1,

                    porcentaje: null,
                    colorBtn1: null,
                    colorBtn2: null,
                    colorBtn3: null,
                    colorBtn4: null,
                    colorBtn5: null,
                    //----------------

                    //---Scroll---
                    scrollInit:   false,//Se inicia sin scroll
                    busquedaShow: false,//Se usa para mostrar u ocultar la caja de busqueda y los iconos de equis y lupa
                    //-----

                    //---Data---
                    comanda: @json($comanda->detalles_comanda),
                    usuario: @json($comanda->usuarios),
                    comandaaa: @json($comanda),
                    comandaDetalle: @json($comanda->detalles_comanda),
                    bodegas: @json($bodegas),
                    sucursal: @json($sucursal->sucursales->sucursal),
                    caja: @json($sucursal->caja),
                    ip: @json($sucursal->ip),//IP de la caja actual
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

                    comandaId: null,
                    //-----

                    //---Titular o clientes---
                    txtTitular: '',
                    clientes: [],
                    clienteSelected: {
                        id: null,
                        titular: null,
                    },
                    asignacion: 1,//1 = Cliente, 2 = Titular
                    //-----

                    //---Solicitar productos---
                    selectedDetalle: [],
                    //-----

                    //---Alerta flotante---
                    message: {},
                    bordeIzquierdoAlerta: null,
                    //-----

                    //---Busqueda de productos---
                    listaProductos: {},
                    //-----

                    //---Dejar fijo el panel-bottom si se ha seleccionado un producto---
                    hayProductosSeleccionado: null,
                    //---
                }
            },
            created(){
                window.Echo.private('pedidos.response.'+{{ session('caja')->id }})
                    .listen('ResponsePedidos',(d) => {
                        console.log('Evento desde produccion: ',d.comanda_detalles.comandas.detalles_comanda);

                        this.sound();
                        this.setMessage(d.mensaje, 'success');

                        this.selectedDetalle = d.comanda_detalles.comandas.detalles_comanda;//Obtenemos la data que nos trae el evento, luego...
                        this.setSolicitudRender(this.selectedDetalle);//Renderizamos la vista nuevamente con la barra de progreso del producto que se ha indicado , y por ultimo...

                        this.selectedDetalle = [];//Limpiar el array (checkbox) de los productos, para que no quede ninguno seleccionado
                    });

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

                //5.- Almacenar URL y el ID encriptado de la comanda actual en el localStorage
                this.saveUrlComandaToLocalStorage();

                localStorage.setItem('categoriaPreciosName',' ');//Limpiamos la variable del localStorage
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
                            this.colorBtn4 = 'btn-secondary';
                            this.colorBtn5 = 'btn-secondary';
                            this.porcentaje = '0%';
                            this.screen = 1;
                        break;
                        case 2:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-secondary';
                            this.colorBtn4 = 'btn-secondary';
                            this.colorBtn5 = 'btn-secondary';
                            this.porcentaje = '25%';
                        break;
                        case 3:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-primary';
                            this.colorBtn4 = 'btn-secondary';
                            this.colorBtn5 = 'btn-secondary';
                            this.porcentaje = '50%';
                        break;
                        case 4:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-primary';
                            this.colorBtn4 = 'btn-primary';
                            this.colorBtn5 = 'btn-secondary';
                            this.porcentaje = '75%';
                        break;
                        case 5:
                            this.colorBtn1 = 'btn-primary';
                            this.colorBtn2 = 'btn-primary';
                            this.colorBtn3 = 'btn-primary';
                            this.colorBtn4 = 'btn-primary';
                            this.colorBtn5 = 'btn-primary';
                            this.porcentaje = '100%';
                        break;
                    }
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

                            //Al seleccionar uno o mas productos dejar estático el panel-bottom
                            if(this.selectedDetalle.length != 0){
                                this.hayProductosSeleccionado = true;
                            }
                            else{
                                //True = scroll up, False = scroll down, Contiene una variable negativa
                                panelBottom.style.bottom = (currentScroll > lastScrollTop) ? -navbarHeightBottom + 'px' : '0';
                            }
                        
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
                //Logica
                saveUrlComandaToLocalStorage(){
                    //console.log('Protocolo:',window.location.protocol);//http
                    //console.log('Host:',window.location.host);         //Con puerto: 192.168.3.214:8005
                    //console.log('HostName:',window.location.hostname); //Sin puerto: 192.168.3.214
                    //console.log('Pathname:',window.location.pathname); //app/comandas/productos/eyJpdiI6ImxocHlLRlBK

                    let currentUrl = window.location.pathname;    //Obtener la URl actual
                    let splitUrl   = currentUrl.split('/');       //Separar la URL por el caracter '/'
                    let comandaId  = splitUrl[splitUrl.length -1];//El ultimo elemento del array sera el ID encriptado de la comanda

                    //Almacenar la URL de la comanda actual y el id encriptado en el localStorage
                    localStorage.setItem('urlComandaActual',window.location.href);
                    localStorage.setItem('comandaId',comandaId);
                    this.comandaId = localStorage.getItem('comandaId');
                    localStorage.setItem('cajaId',{{ session('caja')->id }});
                },
                /*setBodega(b){
                    this.bs = b;
                    this.saveBodegaToLocalStorage();
                },
                saveBodegaToLocalStorage(){
                    //Siempre se almacenaran datos en el localStorage,
                    //Ya sea si es una bodega por defecto o una pre-seleccionada
                    this.bodegaSelected.id   = this.bs.bodegas.id;
                    this.bodegaSelected.name = this.bs.bodegas.bodega;

                    localStorage.setItem('bodegaSelectedId',this.bodegaSelected.id);
                    localStorage.setItem('bodegaSelectedName',this.bodegaSelected.name);
                },*/
                handleBodegaSeleccionada(bodega){
                    this.bs = bodega;

                    this.bodegaSelected.id   = this.bs.id;
                    this.bodegaSelected.name = this.bs.name;

                    localStorage.setItem('bodegaSelectedId',this.bodegaSelected.id);
                    localStorage.setItem('bodegaSelectedName',this.bodegaSelected.name);
                },
                getProducto(){
                    if(this.txtSearch.length > 3){
                        axios.post("{{ route('precios.apiGetProductos') }}", {
                            busqueda: (this.txtSearch).toLowerCase(),//nombre del producto
                            bodega:   localStorage.getItem('bodegaSelectedId'),
                        }).then((r) => {
                            console.log('r: ', ...r.data.list_1);

                            if(r.data.list_1.length > 0){
                                this.listaProductos = r.data.list_1;
                            }
                        }).catch((err) => {
                            console.log('Error JS: ',err);
                        });
                    }
                },
                setProducto(producto){
                    //console.log('Producto: ',producto);

                    //Una vez seleccionado el cliente, se deja vacio el array para ya no mostrar la etiqueta <ul></ul>
                    this.txtSearch = '';
                    this.listaProductos = {};

                    axios.post("{{ route('precios.addProductoApp') }}",{
                        comandas_id:   localStorage.getItem('comandaId'),//Id encriptado
                        precios_id:    producto.categorias_precios_id,//Id no encriptado
                        precio:        parseFloat(producto.precio),
                        cantidad:      1,
                        observaciones: null,
                        productos_id:  producto.productos_id,//Id del producto que se usara para hacer el descargo de la tabla 'existencias'
                        bodegas_id:    producto.bodegas_id,//Id no encriptado
                    }).then((r) => {
                        //console.log('r2: ',r);
                        this.setMessage(r.data.message, r.data.status ? 'success' : 'danger');

                        this.getComandaDetalle();
                    }).catch((err) => {
                        console.log('Error JS: ', err);
                    });
                },
                getComandaDetalle(){
                    axios.post("{{ route('comandas.detalle.app') }}",{
                        id: localStorage.getItem('comandaId'),
                    }).then((rr) => {
                        //console.log('rr: ',rr);
                        this.comanda = rr.data;
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                getClientes(){
                    axios.post("{{ route('clientes.api_search') }}",{
                        busqueda: (this.txtTitular).toUpperCase(),
                    }).then((r) => {
                        this.clientes = r.data.clientes ?? [];
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
                setCliente(cliente){
                    //Se asignan los datos del cliente seleccionado a las variables
                    this.clienteSelected.id      = cliente.id;
                    this.clienteSelected.titular = cliente.nombre;

                    //Colocamos en esa misma caja de busqueda el nombre del cliente
                    //this.txtTitular = this.clienteSelected.titular;
                    this.txtTitular = '';

                    //Una vez seleccionado el cliente, se deja vacio el array para ya no mostrar la etiqueta <ul></ul>
                    this.clientes = [];
                },
                setTitular(){
                    this.clienteSelected.id = 1;
                    this.clienteSelected.titular = this.txtTitular;
                },
                goToBack(){
                    window.location.href = "{{ route('app.comandas') }}";
                },

                //Solictar productos
                setSolicitud(){
                    //console.log('selectedDetalle: ',this.selectedDetalle);

                    if(this.selectedDetalle.length <= 0){//No se podra solicitar productos si no hay ningun producto agregado o seleccionado
                        this.setMessage('Para solicitar un producto, debe haber agregado y/o seleccionado almenos uno.','danger');
                        return;
                    }

                    //if(this.selectedDetalle.length > 0){
                        const detalle = this.selectedDetalle.map(d => d.id);//De todo el array, solo selecciona los id's

                        axios.post("{{ route('comandas.solicitar') }}",{
                            detalle: detalle,
                        }).then((r) => {
                            //console.log('r: ',r);

                            this.setMessage(r.data.message,(r.data ? 'success' : 'danger'));

                            if(r.data.status && r.data.list){
                                this.selectedDetalle = r.data.list;
                                this.setSolicitudRender(this.selectedDetalle);
                                this.solicitarProductos();
                            }
                        }).catch((err) => {
                            console.log('Error JS: ',err);
                        });
                    //}
                },
                setSolicitudRender: function(list) {
                    let detalle = this.comanda.filter(d => !list.find(l => d.id == l.id));
                    this.comanda = [...list, ...detalle];

                },
                async solicitarProductos(){
                    const ticketBarDetalle = [];
                    const ticketCocinaDetalle = [];

                    this.selectedDetalle.map((s) => {
                        if(s.precios && s.precios.categorias_precios && s.precios.categorias_precios.rubros){
                            let descripcion = s.precios.detalle;

                            if(s.observaciones && s.observaciones.length > 0)
                                descripcion = descripcion + ' -OBS. ' + s.observaciones;
                            
                            switch(s.precios.categorias_precios.rubros.token){
                                case 12001:
                                    ticketCocinaDetalle.push({
                                        cantidad: s.cantidad,
                                        descripcion: descripcion.toUpperCase(),
                                        precio: parseFloat(0),
                                    });
                                break;
                                case 12004:
                                    ticketBarDetalle.push({
                                        cantidad: s.cantidad,
                                        descripcion: descripcion.toUpperCase(),
                                        precio: parseFloat(0),
                                    });
                                break;
                            }
                        }
                    });

                    this.selectedDetalle = [];

                    try{
                        if(ticketCocinaDetalle.length > 0 || ticketBarDetalle.length > 0){
                            const socket = await this.connSocket();

                            if(ticketBarDetalle.length > 0){
                                const ticketEncabezado = this.getEncabezadoTicket('** PREPARACIÓN DE BEBIDAS **',2);
                                socket.send(JSON.stringify(this.getDataSocket(ticketEncabezado,ticketBarDetalle,4)));
                            }

                            if(ticketCocinaDetalle.length > 0){
                                const ticketEncabezado = this.getEncabezadoTicket('** PREPARACIÓN DE ALIMENTOS **',1);
                                socket.send(JSON.stringify(this.getDataSocket(ticketEncabezado,ticketCocinaDetalle,4)));
                            }

                            socket.close();
                        }
                    }
                    catch(error){
                        alert('Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página.');
                        console.log('Error al establecer la conexion WebSocket: ',error);
                    }
                },
                setResponsePedidos: function(d) {
                    //console.log('DDD: ',d);

                    /*this.sound();
                    this.setMessage(d.message, 'success');

                    this.selectedDetalle = [];
                    
                    if (d.comanda_detalle)
                        this.comandaDetalle = d.comanda_detalle;*/
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
                async connSocket(){
                    return new Promise((resolve, reject) => {
                        //Hugo: ws://192.168.22.21:8000
                        const socket = new WebSocket('ws://'+this.ip+':8000');

                        socket.onopen = function(openEvent){
                            console.log('Conexión WebSocket establecida');
                            resolve(socket);
                        };

                        socket.onerror = function(err){
                            console.log('Error en la conexión WebSocket: ',err);
                            reject(err);
                        };
                    });
                },
                getEncabezadoTicket(tipoTicket, tipo = 0){
                    const clienteName = this.comandaaa.clientes_id > 0 ? this.comandaaa.clientes.nombre : (this.comandaaa.titular ?? 'SIN CLIENTE/TITULAR');

                    return {
                        sucursal:    (this.sucursal).toUpperCase(),//Si
                        caja:        (this.caja).toUpperCase(),//Si
                        mesa:        this.comandaaa.mesa,//Si
                        id:          this.comandaaa.id,//Si
                        username:    (''+this.usuario.user).toUpperCase(),//Si
                        cliente:     clienteName.toUpperCase(),//Si
                        tipo_ticket: tipoTicket,
                        tipo:        tipo,
                        created_at:  this.comandaaa.fecha_creacion,//Si
                        user:        (this.usuario.user).toUpperCase(),//Si
                    };
                },
                getDataSocket(TicketEncabezado, TicketDetalle, tipo = 3){
                    const json = {
                        comanda: TicketDetalle,
                        cabecera: TicketEncabezado,
                    }

                    const data = {
                        tipo: tipo,
                        data: JSON.stringify(json)
                    }

                    return data;
                },
                setSocket(TicketEncabezado, TicketDetalle, tipo = 3) {
                    const json = {
                        comanda: TicketDetalle,
                        cabecera: TicketEncabezado
                    }
                    const data = {
                        tipo: tipo,
                        data: JSON.stringify(json)
                    }

                    const socket = new WebSocket('ws://' + this.ip + ':8000');
                    socket.onopen = function(openEvent) {
                        console.log('enviando datos', data, openEvent)
                        socket.send(JSON.stringify(data));
                        socket.close();
                    }
                    socket.onerror = function(err) {
                        alert('Ocurrió un problema al imprimir verifique que el driver este activo y recargue la pagina');
                        console.log(err.isTrusted);
                    }
                    socket.message = function(event) {
                        console.log('Message from server', event.data);
                    }
                },
                async ImprimirComanda(){
                    if(this.comanda.length <= 0){//No se podra imprimir la comanda si no hay ningun producto agregado a ella
                        this.setMessage('No hay productos comandados.','danger');
                        return;
                    }

                    const TicketEncabezado = this.getEncabezadoTicket('** COMPROBANTE DE PRE-FACTURACIÓN **');

                    const TicketDetalle = [];
                    this.comandaDetalle.forEach((d) => {
                        const detalle = {
                            cantidad: d.cantidad,
                            descripcion: d.precios.detalle,
                            precio: parseFloat(d.precio)
                        };
                        TicketDetalle.push(detalle)
                    })

                    //this.setSocket(TicketEncabezado, TicketDetalle)
                    try {
                        const socket = await this.connSocket();

                        const resp = socket.send(JSON.stringify(this.getDataSocket(TicketEncabezado,TicketDetalle)));

                        this.setMessage('Se envió la comanda a impresión.','success');

                        socket.close();
                    }catch(error){
                        alert("Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página.");
                        console.error('Error al establecer la conexión WebSocket:', error);
                    }
                },
                async enviarDatosPorWebSocket(data) {
                    try {
                        const socket = await this.connSocket();
                        console.log('Enviando datos:', data);
                        socket.send(JSON.stringify(data));
                        socket.close();
                    } catch (error) {
                        alert(
                            "Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página."
                        );
                        console.error('Error al establecer la conexión WebSocket:', error);
                    }
                },
                solicitarComprobante(){
                    axios.post("{{ route('comandas.comprobanteApp') }}",{
                        id: this.comandaaa.cid,
                    }).then((r) => {
                        //console.log('r: ',r);
                        this.setMessage(r.data.message, r.data.status ? 'success' : 'danger');

                        if(r.data.status){
                            //setTimeout(() => {
                                window.location.href = '/app/comandas';
                            //}, 3 * 1000);
                        }
                    }).catch((err) => {
                        console.log('Error JS: ',err);
                    });
                },
            },
            computed: {
                getComanda(){
                    /*let regx = new RegExp((this.txtSearch).toLowerCase());
                    return this.comanda.filter(c => regx.test(c.precios.detalle.toLowerCase()));*/

                    return this.comanda;
                },
            },
            watch: {//Estar al pendiente de la variable 'busquedaShow' para cuando cambie de estado, establecer el foco en la caja de busqueda
                busquedaShow: function(newValue){
                    if(newValue){
                        this.$nextTick(() => {
                            this.$refs.searchInput.focus();
                        });

                        document.getElementById('panel-main').style.zIndex = '1';
                    }
                    else{
                        document.getElementById('panel-main').style.zIndex = '125';
                    }
                }
            },
        });
        app.component('modal_cambiar_bodega',component.modal_cambiar_bodega);
        app.component('progreso', component.progreso);
        app.mount('#appComandasProductosMovil');
    </script>
@endsection
