@extends('layouts.cajas')
@section('css-caja')
    <style>
        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            background: #fff;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .progress {
            height: 1.25rem;
        }

        .card-comanda {
            background: #E3F2FD;
            border: none;
            box-shadow: 1px 5px 2px #BBDEFB;
            E0F7FA
        }

        .btn-check:checked+.card-comanda,
        .card-comanda-activa {
            background: #BBDEFB !important;
            border: none;
            box-shadow: 1px 7px 2px #90CAF9;
        }

        .card-comanda:hover {

            box-shadow: 1px 5px 2px #90CAF9;
        }

        .card-comanda .producto {
            color: #263238;
        }

        .card-comanda .caja {
            color: #37474F;
        }

        .card-comanda .creacion {
            color: #546E7A;
            font-size: 10pt;
            font-weight: lighter;
        }

        .card-comanda .tiempo {
            color: #37474F;
        }

        .card-comanda .bg-tiempo {
            background: #26A69A;
            color: #ECEFF1;
        }

        .panel-mesas {
            position: fixed;
            height: 92vh;
            width: 250px;
            right: 6px;
            bottom: 12px;
            background: #FFF;
            border-radius: 6px;
            overflow-y: scroll;
            z-index: 1200;
        }


        .text-mesa {
            color: #263238;
        }

        .text-cliente {
            color: #37474F;
        }

        .text-creacion {
            color: #37474F;
        }

        .text-observacion {
            color: #455A64;
        }

        .btn-mesas {
            position: fixed;
            bottom: 16px;
            right: 16px;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: #26A69A;
            color: #FAFAFA;
            font-size: 22pt;
        }

        .btn-mesas:hover {
            background: #239589;
            color: #FFF;
        }

        body::-webkit-scrollbar {
            width: 8px;

        }

        body::-webkit-scrollbar-thumb {
            background-color: #ababab;
            border-radius: 6px;
        }

        body::-webkit-scrollbar-thumb:hover {
            background-color: #9f9f9f;

        }

        * {
            scrollbar-width: thin;
            scrollbar-color: #ababab #f5f5f5;
        }

        *:hover {
            scrollbar-color: #9f9f9f #f5f5f5;
        }

        .panel-mesas::-webkit-scrollbar {
            width: 8px;

        }

        .panel-mesas::-webkit-scrollbar-thumb {
            background-color: #888;
            border-radius: 4px;
        }

        .panel-mesas::-webkit-scrollbar-thumb:hover {
            background-color: #555;
        }

        .panelAlertas {
            position: fixed;
            bottom: 1%;
            right: 1%;
            widows: 35%;
            min-height: 60px;
            z-index: 2000;
        }

        .progress-bar.bg-tiempo {
            background-color: #26A69A !important;
        }


        .bg-tiempo {
            background: #26A69A !important;
            color: #ECEFF1;

        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('panel_caja')
    <div id="appComandas" v-cloak>
        <button class="btn btn-mesas" type="button" v-show="!panelComandas" @click="setPanel()">
            <span class="mdi mdi-table-chair"></span>
        </button>
        <div class="panel-mesas p-4 shadow" v-show="panelComandas">
            <div class="row">
                <div class="col-12">
                    <span class="h4">
                        Agregar mesa
                    </span>
                    <span class="mdi mdi-minus btn float-end" @click="setPanel()"></span>
                </div>
                <div class="col-12 mb-2">
                    <div class="form-check">
                        <input class="form-check-input d-none" type="checkbox" name="flexRadioDefaultName"
                            id="flexRadioDefault" v-model="notify">
                        <label class="form-check-label" for="flexRadioDefault"
                            :class="{ 'text-success': notify, 'text-danger': !notify, }">
                            <span class="mdi h5" :class="{ 'mdi-bell-cancel': !notify, 'mdi-bell-check': notify }"></span>
                            Notificaciones
                        </label>
                    </div>
                </div>
                <div class="col-12 mb-3">
                    <div class="card text-decoration-none card-comanda">
                        <div class="card-body">
                            <form action="{{ route('comandas.store') }}" method="post">
                                @csrf
                                <div class="row text-center">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <input type="number" class="form-control" name="mesa" min="1"
                                                step="1" aria-describedby="helpId" placeholder="Mesa" required />
                                        </div>
                                        <button type="submit" class="btn btn-light">
                                            <span class="mdi mdi-plus"></span> Agregar
                                        </button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <span class="h4">
                        Comandas
                    </span>
                </div>
                <div class="col-12">
                    <div class="mb-3">
                        <input type="text" class="form-control" name="" id="" aria-describedby="helpId"
                            placeholder="Buscar por numero de mesa" v-model="searchMesa" @keyup="buscarMesa()" />
                    </div>
                </div>
                <div class="col-12">
                    <div class="row" id="mesas">
                        @foreach ($comandas as $c)
                            <div class="col-12 mb-3" mesa="{{ $c->mesa }}">
                                <a href="{{ route('comandas.index_id', ['id' => Crypt::encryptString($c->id)]) }}"
                                    class="card text-decoration-none {{ isset($comanda->id) && $comanda->id == $c->id ? 'card-comanda-activa' : 'card-comanda' }}"
                                    @click="getDetalleComanda(c.cid)">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-12 text-uppercase text-mesa fw-bold">#{{ $c->mesa }}</div>
                                            <div class="col-12 text-uppercase text-cliente">
                                                {{ $c->clientes->nombre ?? ($c->titular ?? 'Agregue cliente/titular') }}
                                            </div>
                                            <div class="col-12 text-creacion">
                                                <small class="text-muted">Creada {{ $c->creacion }} </small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row p-1" v-if="comanda != null">
            <div class="alert alert-info alert-dismissible fade show float-end" role="alert" v-if="message.length > 0">
                @{{ message }}
            </div>

            <div class="col-12 text-uppercase h3">
                @{{ comanda.id }} · Mesa #@{{ comanda.mesa }}
            </div>
            <div class="col-12 text-uppercase fw-medium" v-if="cortesia == null">
                Cliente: @{{ cliente && cliente.nombre ? cliente.nombre : (comanda.titular ?? 'Aún no se ha agregado un cliente o titular') }}
            </div>
            <div class="col-12 text-uppercase fw-medium" v-if="cortesia && cortesia.id != null">
                Cortesía: @{{ cortesia.titular.titular }} <br>
                <span class="text-danger" v-if="!cortesia.estado"> Observación de negación: @{{ cortesia.observacion ?? 'Sin observación' }}</span>
            </div>
            <!-- Botones de acción -->
            <div class="col-12 my-3">
                <button type="button" class="btn btn-light"
                    :disabled="selectedDetalle.length == 0 || (cortesia && cortesia.id != null)" @click="setSolicitud()">
                    <span class="mdi mdi-check fs-5"></span>
                    Solicitar producto
                </button>
                <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#separarProducto"
                    :disabled="selectedDetalle.length == 0">
                    <span class="mdi mdi-file-document-edit-outline fs-5"></span>
                    Separar
                </button>
                @can('comandas.anular')
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#anulacionProducto"
                        :disabled="selectedDetalle.length == 0">
                        <span class="mdi mdi-file-document-remove fs-5"></span>
                        Anular
                    </button>
                @endcan
                <button type="button" data-bs-toggle="modal" data-bs-target="#agregarCliente" class="btn btn-light"
                    :disabled="comandaDetalle.length == 0">
                    <span class="mdi mdi-plus fs-5"></span>
                    @{{ cliente != null ? 'Editar' : 'Asignar' }} cliente
                </button>
                <button type="button" class="btn btn-light" :disabled="comandaDetalle.length == 0"
                    @click="ImprimirComanda()">
                    <span class="mdi mdi-printer fs-5"></span>
                    Imprimir comanda
                </button>
                @isset($comanda)
                    <a type="button" class="btn btn-light"
                        :class="{ 'disabled': comandaDetalle.length == 0 || (cortesia && cortesia.id != null) }"
                        href="{{ route('comandas.comprobante', ['id' => Crypt::encryptString($comanda->id)]) }}">
                        <span class="mdi mdi-file-document-plus fs-5"></span>
                        Comprobante
                    </a>
                @else
                    <button type="button" class="btn btn-light" :disabled="comandaDetalle.length == 0">
                        <span class="mdi mdi-file-document-plus fs-5"></span>
                        Comprobante
                    </button>
                @endisset
                <a :href="comandaDetalle.length == 0 ? '#' : cortesiaRoute" class="btn btn-light"
                    :class="{ 'disabled': comandaDetalle.length == 0 }" v-if="cortesia == null">
                    <span class="mdi mdi-file-document-check fs-5"></span>
                    Cortesías
                </a>
                @if ($comanda && $comanda->id > 0 && $cortesia && $cortesia->id > 0)
                    <button id="my-dropdown" class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="mdi mdi-file-document-check fs-5"></span>
                        Cortesías
                    </button>
                    <div class="dropdown-menu" aria-labelledby="my-dropdown">
                        <a class="dropdown-item fw-bolder"
                            href="{{ route('cortesias.correccion', ['id' => Crypt::encryptString($cortesia->id)]) }}">
                            Solicitar cortesia nuevamente
                        </a>
                        <div class="dropdown-divider"></div>
                        @can('comandas.tipo_comandas')
                            <a class="dropdown-item"
                                href="{{ route('comandas.tipo_comanda', ['tipo' => Crypt::encryptString(1), 'id' => $comanda->cid]) }}">
                                Cambiar a comanda de cliente
                            </a>
                        @endcan

                    </div>
                @endif
                @can('comandas.delete')
                    <a :href="comandaDetalle.length != 0 ? '#' : delRoute" class="btn btn-light"
                        :class="{ 'disabled': comandaDetalle.length != 0 }">
                        <span class="mdi mdi-file-document-remove fs-5"></span>
                        Eliminar
                    </a>
                @endcan

            </div>
            <!-- Agregar productos -->
            <div class="row mb-3">
                <div class="mb-3">
                    <label for="" class="form-label">
                        Agregar productos
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" aria-describedby="helpId"
                            placeholder="Escriba el nombre del producto" v-model="searchProducto"
                            @keyup="getProducto()" />
                        <div class="input-group-append">
                            <select class="form-select" v-model="bodegaSelected" @change="setBodega(bodegaSelected)">
                                <option v-for="b in bodegas" :value="b.bodegas_id">
                                    @{{ b.bodegas.bodega }}
                                </option>
                            </select>
                        </div>
                    </div>


                </div>
            </div>
            <!-- Productos agregados a la comanda -->
            <div class="col-12 row" v-if="searchProducto.length < 4">
                <div class="col-12 mb-3 h4">
                    PRODUCTOS AGREGADOS
                </div>

                <div class="col-12 mb-4" v-for="p in comandaDetalle" :key="p.id">
                    <input type="checkbox" class="btn-check" :id="'producto-' + p.id" autocomplete="off" multiple
                        :value="p" v-model="selectedDetalle">
                    <label class="card card-comanda" :for="'producto-' + p.id">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-1 col-lg-1 text-lg-center m-auto">
                                    @{{ p.cantidad }}
                                </div>
                                <div class="col-10 col-lg-8">
                                    <div class="row">
                                        <div class="col-12 fs-5 fw-light producto text-uppercase">
                                            @{{ p.precios.detalle }}
                                        </div>
                                        <div class="col-12 caja text-uppercase"
                                            v-if="p.precios.categorias_precios.token == 1105">
                                            No requiere existencias
                                        </div>
                                        <div class="col-12 caja text-uppercase"
                                            v-if="p.precios.categorias_precios.token == 1101">
                                            Solicitado a @{{ p.lotes != null && p.lotes.length > 0 ? p.lotes[0].existencias.bodegas.bodega : "---" }}
                                        </div>
                                        <div class="col-12 creacion text-uppercase">
                                            @{{ p.creado }} @{{ p.creacion }}
                                        </div>
                                        <div class="col-12 tiempo" v-if="p.solicitado > 0">
                                            Tiempo de preparación:
                                            <span class="creacion">
                                                A tiempo
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-lg-3">
                                    <div class="row text-lg-end">

                                        <div class="col-12 text-uppercase caja"
                                            v-if="p.precios.categorias_precios.token == 1101">
                                            Ven.
                                            <span
                                                :title="p.lotes != null && p.lotes.length > 0 ? (p.lotes[0].existencias
                                                    .vencimiento ?? 'Sin vencimiento') : 'Sin vencimiento'">

                                                @{{ p.lotes && p.lotes != null && p.lotes.length > 0 ? p.lotes[0].existencias.ven : "--/--/--" }}
                                            </span>
                                        </div>
                                        <div class="col-12 creacion">
                                            $@{{ parseFloat(p.precio).toFixed(2) }}
                                        </div>
                                        <div class="col-12 caja fw-bolder">
                                            $@{{ parseFloat(p.precio * p.cantidad).toFixed(2) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2"
                                    v-if="p.solicitud && p.user_solicita_id && p.aceptacion && p.espera && p.entregado == null">
                                    <progreso :aceptacion="p.aceptacion" :espera="p.espera" :prioridad="p.prioridad"
                                        :incremento="p.incremento_tiempo" />

                                </div>
                                <div class="col-12 mt-2" v-if="p.entregado">
                                    <div class="progress ">
                                        <div class="progress-bar  bg-tiempo" role="progressbar" style="width: 100%;"
                                            aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            Completado @{{ p.entregado }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-2" v-if="p.cancelado">
                                    <div class="alert alert-danger" role="alert">
                                        Producto cancelado: @{{ p.observacion_negacion }}
                                    </div>
                                </div>
                                <div class="col-12 col-lg-8 offset-lg-1 mt-2" v-if="p.solicitud && p.aceptacion == null"
                                    :title="'Solicitado: ' + p.solicitud">

                                    <div class="spinner-border spinner-border-sm me-2" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    Esperando respuesta @{{ p.solicitud }}

                                </div>

                            </div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="col-12 row" v-if="searchProducto.length > 4">
                <div class="col-12 mb-3 h4">
                    Resultado de búsqueda
                </div>
                <div class="col-12 mb-4" v-for="l in productosList" v-if="searchProducto.length > 4">
                    <card-comanda :l="l" :comanda="comanda.cid" @detalle="setDetalle"
                        url="{{ route('detalle_comandas.store') }}" />
                </div>
                <div class="col-12 mb-4" v-if="searchProducto.length > 4 && productosList.length == 0">
                    No se encontró ningún precio con ese nombre, busque con un sinónimo o use otro precio.
                </div>
            </div>
        </div>
        <div class="row p4" v-if="comanda == null">
            <div class="col-12">
                <div class="alert alert-primary" role="alert">
                    Aquí aparecerá el detalle de las comandas. Agregue una si es requerido.
                </div>
            </div>
        </div>



        <!-- Modal Anulación de productos-->
        <div class="modal fade" id="anulacionProducto" tabindex="-1" aria-labelledby="anulacionProducto"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">
                            Anulación de producto
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('comandas.anulacion_producto') }}" method="post"
                            v-if="selectedDetalle.length > 0">
                            @csrf
                            <input type="hidden" name="comanda"
                                value="{{ isset($comanda->cid) ? $comanda->cid : '0' }}">
                            <h5>
                                Listado de producto para anulación
                            </h5>
                            <div class="row mb-3" v-for="s in selectedDetalle">
                                <div class="col-2">
                                    <input type="hidden" name="detalles[]" :value="s.cid" multiple
                                        v-if="(s.entregado == null && s.users_acepta_id == null) || s.cancelado">
                                    <input type="number" class="form-control" :name="'cantidad-' + s.cid"
                                        :max="s.cantidad" min="1" placeholder="Cantidad a anular" required
                                        :disabled="(s.entregado != null || s.users_acepta_id != null) && !s.cancelado" />

                                </div>
                                <div class="col-10 p-2 fw-bold">
                                    @{{ s.precios.detalle }}
                                    <span class="badge badge-pill text-bg-danger"
                                        v-show="(s.entregado != null || s.users_acepta_id != null) && !s.cancelado">
                                        ESTE PRODUCTO NO PUEDE ANULARSE
                                    </span>

                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="razonAnulacion" class="form-label">
                                    Razón para anular
                                </label>
                                <textarea class="form-control" id="razonAnulacion" rows="3" name="observacion"
                                    placeholder="Escriba aquí las razones de la anulación (max 200 caracteres)" maxlength="200"></textarea>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="confirmAnulacion"
                                        v-model="confirmAnulacion">
                                    <label class="form-check-label" for="confirmAnulacion">
                                        Confirmo que anulo este o estos productos comandados.
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary" :disabled="confirmAnulacion == 0">
                                    Anular
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Anulacion de productos-->
        <div class="modal fade" id="separarProducto" tabindex="-1" aria-labelledby="separarProducto"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">Separar productos</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('comandas.separar_producto') }}" method="post"
                            v-if="selectedDetalle.length > 0">
                            @csrf
                            <input type="hidden" name="comanda"
                                value="{{ isset($comanda->cid) ? $comanda->cid : '0' }}">
                            <h5>Listado de producto para separar</h5>
                            <div class="row mb-3" v-for="s in selectedDetalle">
                                <div class="col-2">
                                    <input type="hidden" name="detalles[]" :value="s.cid" multiple>
                                    <input type="number" class="form-control" :name="'cantidad-' + s.cid"
                                        :max="s.cantidad" min="1" placeholder="Cantidad a separar"
                                        required />

                                </div>
                                <div class="col-10 p-2 fw-bold">
                                    @{{ s.cantidad }} max. - @{{ s.precios.detalle }}
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="mesa" class="form-label">Mesa a donde se moverá:</label>
                                <input type="number" class="form-control" id="mesa" name="mesa" min="1"
                                    placeholder="Mesa existente o nueva" required />

                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="confirmAnulacion"
                                        v-model="confirmSeparar">
                                    <label class="form-check-label" for="confirmAnulacion">
                                        Confirmo que separo este o estos productos comandados.
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">

                                <button type="submit" class="btn btn-primary"
                                    :disabled="confirmSeparar == 0">Separar</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>

        <!--Modal para agregar/editar clientes/titular -->
        <div class="modal fade" id="agregarCliente" tabindex="-1" aria-labelledby="agregarCliente" aria-hidden="true">
            <div class="modal-dialog  modal-lg modal-fullscreen-md-down">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">@{{ cliente != null ? 'Editar' : 'Asignar' }} clientes</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('comandas.clientes') }}" method="post">
                            @csrf
                            <input type="hidden" name="comanda" value="{{ isset($comanda->id) ? $comanda->cid : 0 }}">
                            <input type="hidden" name="clientes_id" :value="clientesSelected.id">

                            <div class="btn-group mb-3 me-2" role="group" aria-label="Basic radio toggle button group">
                                <input type="radio" class="btn-check" name="asignacion" value="1"
                                    v-model="asignacion" id="asignacion1" autocomplete="off" checked>
                                <label class="btn btn-outline-dark" for="asignacion1">
                                    Cliente
                                </label>

                                <input type="radio" class="btn-check" name="asignacion" value="2"
                                    v-model="asignacion" id="asignacion2" autocomplete="off">
                                <label class="btn btn-outline-dark" for="asignacion2">
                                    Titular
                                </label>
                            </div>

                            @{{ comanda != null && comanda.titular != null ? 'Titular asignado: ' + comanda.titular : (cliente != null ? 'Cliente asignado: ' + cliente.nombre : '') }}
                            <div class="cliente-search">
                                <div class="mb-3">
                                    <input type="text" class="form-control ms-2" autocomplete="off"
                                        :placeholder="clientesSelected.nombre ??
                                            (asignacion == 1 ?
                                                'Escriba para buscar un cliente...' :
                                                'Escriba un titular para esta comanda...')"
                                        id="txtBusqueda" name="titular" v-model="txtBusqueda"
                                        @keyup="apiSearchClientes">

                                    <div class="cliente-selected" v-show="clientesSelected.id > 0">
                                        Cliente seleccionado: @{{ clientesSelected.cliente }}
                                        <span class="btn mdi mdi-close" @click="clientesSelected = []"
                                            title="Quitar asignacion"></span>
                                    </div>
                                    <!--Desplegable / Listado de clientes.-->
                                    <div v-if="txtBusqueda.length > 4 && asignacion == 1" class="result shadow"
                                        style="position: absolute;z-index: 1; width: 98%; background:white; padding: 2px; margin-top: 5px;">
                                        <ul class="list-group">
                                            <li v-for="cliente in arrayClientes"
                                                class="list-group-item d-flex justify-content-between align-items-center"
                                                style="border-radius: 0px; border: none; cursor: pointer;"
                                                @click="selected(cliente)">
                                                @{{ cliente.cliente }}
                                            </li>
                                            <li v-if="arrayClientes.length == 0"
                                                class="list-group-item d-flex justify-content-between align-items-center"
                                                style="border-radius: 0px; border: none;">
                                                No se encontró ningún cliente con ese nombre / identificación
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"
                                    :disabled="(asignacion == 1 && clientesSelected.length == 0) ||
                                    (asignacion == 2 && txtBusqueda.length < 3)">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="panelAlertas" v-if="pmessage && pmessage.length > 0">
            <div class="alert  alert-dismissible fade show" :class="{ 'alert-info': status, 'alert-danger': !status }"
                role="alert">
                <strong>@{{ pmessage }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                    @click="pmessage = ptype = ''"></button>
            </div>
        </div>
        <respuestas id="respuestas" chanel="pedidos.response.{{ session('caja')->id }}" listen="ResponsePedidos"
            @rspedidos="setResponsePedidos">
        </respuestas>
    </div>
@endsection
@section('script')
    <script type="module">
        const app = appVue({

            data() {
                return {
                    caja: '{{ session('caja')->caja }}',
                    ip: '{{ session('caja')->ip }}',
                    sucursal: '{{ session('caja')->sucursales->sucursal }}',
                    user: '{{ Auth::user()->user }}',
                    comanda: @json($comanda),
                    cliente: @json($comanda != null ? $comanda->clientes : []),
                    searchMesa: '',
                    searchProducto: '',
                    comandaDetalle: @json($comandas_detalle),
                    cortesia: @json($cortesia),
                    bodegas: @json($bodegas),
                    bodegaSelected: null,
                    productosList: [],
                    selectedDetalle: [],
                    inputObservacion: false,
                    message: '',
                    type: '',
                    confirmAnulacion: 0,
                    confirmSeparar: 0,
                    delRoute: "{{ route('comandas.confirm', ['id' => isset($comanda->id) ? Crypt::encryptString($comanda->id) : Crypt::encryptString(0)]) }}",
                    cortesiaRoute: "{{ route('cortesias.aplicar', ['id' => isset($comanda->id) ? Crypt::encryptString($comanda->id) : Crypt::encryptString(0), 'origen' => Crypt::encryptString(3)]) }}",
                    panelComandas: true,
                    arrayClientes: [],
                    clientesSelected: [],
                    txtBusqueda: '',
                    asignacion: 1,
                    pmessage: '',
                    status: '',
                    notify: true,
                }
            },
            mounted() {
                if (this.bodegaSelected == null) {
                    if (localStorage.getItem('bodegaSelected') != null) {
                        if (this.bodegas.find(i => i.bodegas_id == localStorage.getItem('bodegaSelected')))
                            this.bodegaSelected = localStorage.getItem('bodegaSelected');
                    } else {
                        this.bodegaSelected = this.bodegas[0].bodegas_id;
                        localStorage.setItem('bodegaSelected', this.bodegaSelected)
                    }
                }
                if (localStorage.getItem('panelComandas') != null)
                    this.panelComandas = parseInt(localStorage.getItem('panelComandas')) == 1;
                else localStorage.setItem('panelComandas', this.panelComandas ? 1 : 0);

            },
            computed: {

                getSalida: function() {
                    return this.bodegas.find(f => f.bodegas_id == this.bodegaSelected).bodegas.bodega
                }
            },
            methods: {
                getProducto: function() {
                    if (this.searchProducto.length > 4 && this.bodegaSelected && this.bodegaSelected != null)
                        axios.post("{{ route('precios.apiGetProductos') }}", {
                            busqueda: this.searchProducto,
                            bodega: this.bodegaSelected,
                        }).then((r) => {
                            if (r.data.error)
                                return alert('Error: ' + r.data.error)

                            this.productosList = [];
                            this.productosList.push(...r.data.list_1);
                            this.productosList.push(...r.data.list_5);
                            this.selectedDetalle = [];
                        }).catch((err) => {
                            console.log(err);
                        });
                },
                setBodega: function(id) {
                    localStorage.setItem('bodegaSelected', id)
                    this.getProducto()
                },
                setDetalle: function(d) {
                    if (d.comanda_detalle)
                        this.comandaDetalle = d.comanda_detalle;

                    if (d.message)
                        this.setMessage(d.message);


                    if (d.type)
                        this.type = d.type;

                    this.searchProducto = '';
                    this.productosList = [];
                    this.selectedDetalle = [];
                },
                setResponsePedidos: function(d) {
                    this.selectedDetalle = [];
                    if (d.comanda_detalle)
                        if (d.comanda_detalle[0].comandas_id == this.comanda.id)
                            this.comandaDetalle = d.comanda_detalle;

                    if (d.message)
                        this.pmessage = d.message;
                    if (d.type)
                        this.status = d.type;
                    this.sound()
                },
                sound: function() {
                    if (this.notify) {
                        if (Notification.permission !== "granted") {
                            Notification.requestPermission();
                        } else {
                            let notification = new Notification("¡Nuevo pedido!", {
                                body: "Hay una nueva solicitud de pedidos"
                            });
                        }
                        const sound = "{{ asset('sonidos/notify.wav') }}";
                        new Audio(sound).play();
                        console.log('sonando');
                    }
                },
                setMessage: function(m) {
                    this.message = m;
                    setTimeout(() => {
                        this.message = '';
                    }, 6 * 1000);
                },
                setPanel: function() {
                    this.panelComandas = !this.panelComandas;
                    localStorage.setItem('panelComandas', this.panelComandas ? 1 : 0);
                },
                apiSearchClientes() {
                    if (this.txtBusqueda.length > 4 && this.asignacion == 1) {
                        axios.post("{{ route('clientes.apiGetClientes') }}", {
                                busqueda: (this.txtBusqueda).toUpperCase(),
                            })
                            .then((rs) => {
                                this.arrayClientes = rs.data.clientes;
                            })
                            .catch(error => {
                                console.log('Error JS: ', error);
                            })
                    }

                },
                selected(s) {
                    this.clientesSelected = s;
                    this.arrayClientes = [];
                    this.txtBusqueda = '';
                },
                buscarMesa: function() {
                    const elementos = document.querySelectorAll('[mesa]');
                    elementos.forEach(elemento => {
                        if (elemento.getAttribute('mesa') === this.searchMesa || this.searchMesa
                            .length == 0)
                            elemento.style.display = 'block';
                        else
                            elemento.style.display = 'none';

                    });
                },
                setSolicitud: function() {
                    if (this.selectedDetalle.length > 0) {
                        const detalle = this.selectedDetalle.map(d => d.id);
                        this.pmessage = '';

                        axios.post("{{ route('comandas.solicitar') }}", {
                            detalle: detalle
                        }).then(r => {
                            if (r.data) {
                                this.pmessage = r.data.message;
                                this.status = r.data.status;
                            }
                            if (r.data.status && r.data.list) {
                                this.selectedDetalle = r.data.list;
                                this.setSolicitudRender(this.selectedDetalle);
                                this.solicitarProductos();
                            }

                        }).catch(e => {
                            console.log(e);
                        })
                    }
                },
                setSolicitudRender: function(list) {
                    let detalle = this.comandaDetalle.filter(d => !list.find(l => d.id == l.id));
                    this.comandaDetalle = [...list, ...detalle];

                },
                async solicitarProductos() {
                    //TODO socket para panel de producción
                    //cSpell:ignore categorias, descripcion
                    console.log('Iniciando el proceso de impresión');

                    const ticketBarDetalle = [];
                    const ticketCocinaDetalle = [];

                    this.selectedDetalle.map((s) => {
                        if (s.precios &&
                            s.precios.categorias_precios &&
                            s.precios.categorias_precios.rubros
                        ) {
                            let descripcion = s.precios.detalle;
                            if (s.observaciones && s.observaciones.length > 0)
                                descripcion = descripcion + ' -OBS. ' + s.observaciones;

                            switch (s.precios.categorias_precios.rubros.token) {
                                case 12001:
                                    ticketCocinaDetalle.push({
                                        cantidad: s.cantidad,
                                        descripcion: descripcion.toUpperCase(),
                                        precio: parseFloat(0)
                                    })
                                    break;
                                case 12004:
                                    ticketBarDetalle.push({
                                        cantidad: s.cantidad,
                                        descripcion: descripcion.toUpperCase(),
                                        precio: parseFloat(0)
                                    })
                                    break;
                                default:
                                    break;
                            }
                        }
                    });

                    this.selectedDetalle = [];

                    try {
                        if (ticketCocinaDetalle.length > 0 || ticketBarDetalle.length > 0) {
                            const socket = await this.connSocket();
                            console.log('Enviar a imprimir', socket)
                            if (ticketBarDetalle.length > 0) {
                                const ticketEncabezado = this.getEncabezadoTicket(
                                    '** PREPARACIÓN DE BEBIDAS **', 2);
                                socket.send(JSON.stringify(this.getDataSocket(ticketEncabezado,
                                    ticketBarDetalle, 4)));
                            }
                            if (ticketCocinaDetalle.length > 0) {
                                const ticketEncabezado = this.getEncabezadoTicket(
                                    '** PREPARACIÓN DE ALIMENTOS **', 1);
                                socket.send(JSON.stringify(this.getDataSocket(ticketEncabezado,
                                    ticketCocinaDetalle, 4)));
                            }

                            socket.close();
                        }
                    } catch (error) {
                        alert(
                            "Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página."
                        );
                        console.error('Error al establecer la conexión WebSocket:', error);
                    }

                },
                async ImprimirComanda() {

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
                        socket.send(JSON.stringify(this.getDataSocket(TicketEncabezado, TicketDetalle)));
                        socket.close();
                    } catch (error) {
                        alert(
                            "Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página."
                        );
                        console.error('Error al establecer la conexión WebSocket:', error);
                    }

                },

                getEncabezadoTicket: function(tipoTicket, tipo = 0) {
                    const clienteName = this.comanda.clientes_id > 0 ?
                        this.comanda.clientes.nombre : (this.comanda.titular ?? 'SIN CLIENTE/TITULAR');
                    return {
                        sucursal: this.sucursal.toUpperCase(),
                        caja: this.caja.toUpperCase(),
                        mesa: this.comanda.mesa,
                        id: this.comanda.id,
                        username: ('' + this.comanda.usuarios.user).toUpperCase(),
                        cliente: clienteName.toUpperCase(),
                        tipo_ticket: tipoTicket,
                        tipo: tipo,
                        created_at: this.comanda.fecha_creacion,
                        user: (this.user).toUpperCase(),
                    };

                },
                getDataSocket: function(TicketEncabezado, TicketDetalle, tipo = 3) {
                    const json = {
                        comanda: TicketDetalle,
                        cabecera: TicketEncabezado
                    }
                    const data = {
                        tipo: tipo,
                        data: JSON.stringify(json)
                    }
                    return data;
                },
                setSocket: function(TicketEncabezado, TicketDetalle, tipo = 3) {
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
                        alert(
                            "Ocurrió un problema al imprimir verifique que el driver este activo y recargue la pagina"
                        );
                        console.log(err.isTrusted);
                    }
                    socket.message = function(event) {
                        console.log('Message from server', event.data);
                    }
                },
                async connSocket() {
                    return new Promise((resolve, reject) => {
                        const socket = new WebSocket('ws://' + this.ip + ':8000');
                        socket.onopen = function(openEvent) {
                            console.log('Conexión WebSocket establecida');
                            resolve(socket);
                        };
                        socket.onerror = function(err) {
                            console.error('Error en la conexión WebSocket:', err);
                            reject(err);
                        };
                    });
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
                }

            },
        });
        app.component('card-comanda', component.card);
        app.component('progreso', component.progreso);
        app.component('respuestas', component.rspedidos);
        app.mount("#appComandas");
    </script>
@endsection
