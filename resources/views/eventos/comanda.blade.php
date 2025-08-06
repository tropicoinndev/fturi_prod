@extends('layouts.app')
@section('style')
    <style>
        .btn-light {
            background: #DAE0E5;
        }

        .card-comanda {
            background: #E3F2FD;
            border: none;
            color: #263238;
            box-shadow: 1px 5px 2px #BBDEFB;
            E0F7FA
        }

        .c1 {
            background: #E3F2FD;
            color: #263238;
        }

        ::placeholder {
            color: #808080;
            opacity: 0.8;
        }

        .at {

            color: #000;
            font-weight: bold;
            text-decoration: none;
            font-size: 2em;
        }

        .subtitulo {

            color: #37474F;
        }

        input,
        textarea {
            background: #A7A4A4;
            color: #A7A4A4;
        }

        label {
            color: #455A64;
        }



        .boton-mas {
            display: inline-block;
            padding: 2px 15px;
            background-color: #D9D9D9;
            color: #000;
            cursor: not-allowed;
            text-align: center;
            font-size: 12px;
            border-radius: 5px;
            cursor: pointer;
            pointer-events: none;
        }

        .boton-mas i {
            margin-right: 5px;
        }

        .text-creacion {
            color: #37474F;
        }

        .text-observacion {
            color: #455A64;
            font-size: 10pt;

        }

        body {
            background-color: #E0F2F1;
        }

        .panel-body {
            background: #fff;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .card-comanda {
            background: #E3F2FD;
            border: none;
            box-shadow: 1px 5px 2px #BBDEFB;
            E0F7FA
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

        .text-creacion {
            color: #37474F;
            font-size: 10pt;
        }

        .panel {
            min-height: 91vh;
        }

        .panelAlertas {
            position: fixed;
            bottom: 1%;
            right: 1%;
            widows: 35%;
            min-height: 60px;
            z-index: 2000;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('content')
    <div id="appEventoComanda" v-cloak>
        <div class="container ">
            <div class="row justify-content-center">
                <x-message></x-message>
                <div class="col-md-12">

                    <div class="card panel shadow p-3">
                        <div class="alert alert-info alert-dismissible fade show float-end" role="alert"
                            v-if="message.length > 0">
                            @{{ message }}
                        </div>
                        <div class="row p-4">
                            <h3>Comanda #{{ $c->id }} </h3>

                            <div class="row mb-3 text-uppercase">
                                <div class="col-12 h7 text-muted subtitulo">
                                    Comanda de evento

                                </div>

                            </div>

                            <div class="col-12 mb-3 aling-center">
                                <a class="btn btn-light  m-1" :href="'/eventos/detalle/' + eventoId.cid" role="button">
                                    <span class="mdi mdi-arrow-left"></span> Volver
                                </a>
                                @can('comandas.solicitar')
                                    <button type="button" class="btn btn-light"
                                        :disabled="selectedDetalle.length == 0 || (cortesia && cortesia.id != null)"
                                        @click="setSolicitud()">
                                        <span class="mdi mdi-check fs-5"></span>
                                        Solicitar producto
                                    </button>
                                @endcan
                                @can('comandas.bloquear')
                                    <a class="btn btn-light  m-1" :href="'/comandas/bloquear/' + comanda.cid" role="button"
                                        :class="{ 'disabled': comanda.comprobante }">
                                        <span class="mdi mdi mdi-lock"></span> Bloquear
                                    </a>
                                @endcan
                                @can('comandas.separar')
                                    <button type="button" class="btn btn-light m-1" data-bs-toggle="modal"
                                        data-bs-target="#separarProducto"
                                        :disabled="selectedDetalle.length == 0 || comanda.comprobante">
                                        <span class="mdi mdi-file-document-edit-outline fs-5"></span>
                                        Separar
                                    </button>
                                @endcan
                                @can('comandas.anular')
                                    <button type="button" class="btn btn-light m-1" data-bs-toggle="modal"
                                        data-bs-target="#anulacionProducto"
                                        :disabled="selectedDetalle.length == 0 || comanda.comprobante">
                                        <span class="mdi mdi-file-document-remove fs-5"></span>
                                        Anular
                                    </button>
                                @endcan

                                <button type="button" class="btn btn-light" :disabled="comandaDetalle.length == 0"
                                    @click="ImprimirComanda()">
                                    <span class="mdi mdi-printer fs-5"></span>
                                    Imprimir comanda
                                </button>

                                @can('comandas.cortesia')
                                    <a :href="comandaDetalle.length == 0 ? '#' : cortesiaRoute" class="btn btn-light"
                                        :class="{ 'disabled': comandaDetalle.length == 0 || comanda.facturada }">
                                        <span class="mdi mdi-file-document-check fs-5"></span>
                                        Cortesías
                                    </a>
                                @endcan
                                @can('comandas.delete')
                                    <a :href="comandaDetalle.length != 0 ? '#' : delRoute" class="btn btn-light"
                                        :class="{ 'disabled': comandaDetalle.length != 0 }">
                                        <span class="mdi mdi-file-document-remove fs-5"></span>
                                        Eliminar
                                    </a>
                                @endcan

                            </div>
                            <div class=" h-100 w-100 p-4 mb-4">
                                <div class="row">
                                    <div class="row mb-3" v-if="!comanda.comprobante">

                                        <h5>Agregar productos</h5>

                                        <div class="col-12 mb-2 input-group">
                                            <input type="text" class="form-control" id="searchComanda"
                                                aria-describedby="helpId" placeholder="Escriba el nombre del produto ..."
                                                v-model="searchProducto" @keyup="getProducto()">
                                            <div class="input-group-appened">
                                                <select class="form-select" v-model="bodegaSelected"
                                                    @change="setBodega(bodegaSelected)">
                                                    <option value="1" v-for="b in bodegas" :value="b.bodegas_id">
                                                        @{{ b.bodegas.bodega }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 row" v-if="searchProducto.length < 4">
                                        <div class="col-12 mb-3 h4">
                                            PRODUCTOS AGREGADOS
                                        </div>

                                        <div class="col-12 mb-4" v-for="p in comandaDetalle">
                                            <input type="checkbox" class="btn-check" :id="'producto-' + p.id"
                                                autocomplete="off" multiple :value="p"
                                                v-model="selectedDetalle">
                                            <label class="card card-comanda" :for="'producto-' + p.id">
                                                <div class="card-body">
                                                    <div class="row align-items-center">
                                                        <div class="col-1 text-center m-auto">
                                                            @{{ p.cantidad }}
                                                        </div>
                                                        <div class="col-9">
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
                                                                <div class="col-12 mt-2"
                                                                    v-if="p.solicitud && p.user_solicita_id && p.aceptacion && p.espera && p.entregado == null">
                                                                    <progreso :aceptacion="p.aceptacion"
                                                                        :espera="p.espera" :prioridad="p.prioridad"
                                                                        :incremento="p.incremento_tiempo" />

                                                                </div>
                                                                <div class="col-12 mt-2" v-if="p.entregado">
                                                                    <div class="progress ">
                                                                        <div class="progress-bar  bg-tiempo"
                                                                            role="progressbar" style="width: 100%;"
                                                                            aria-valuenow="100" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                            Completado @{{ p.entregado }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 mt-2" v-if="p.cancelado">
                                                                    <div class="alert alert-danger" role="alert">
                                                                        Producto cancelado: @{{ p.observacion_negacion }}
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 col-lg-8 offset-lg-1 mt-2"
                                                                    v-if="p.solicitud && p.aceptacion == null"
                                                                    :title="'Solicitado: ' + p.solicitud">

                                                                    <div class="spinner-border spinner-border-sm me-2"
                                                                        role="status">
                                                                        <span class="visually-hidden">Loading...</span>
                                                                    </div>
                                                                    Esperando respuesta @{{ p.solicitud }}

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-2 align-items-center">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="row text-end">
                                                                    <div class="col-12 caja fw-bolder">
                                                                        $@{{ parseFloat(p.precio * p.cantidad).toFixed(2) }}
                                                                    </div>
                                                                </div>
                                                                @can('comandas.aumentar')
                                                                    <div v-if="eventoId.modificacion == true && !comanda.facturada"
                                                                        title="Agregar cantidad">
                                                                        <button class="btn btn-light btn-lg m-1 h-10 w-20"
                                                                            href="#editarCantidadProducto"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#editarCantidadProducto"
                                                                            :data-id="p.id" role="button">+</button>
                                                                    </div>
                                                                @endcan

                                                            </div>
                                                        </div>

                                                        <div class="col-12 mt-2" v-if="p.solicitado > 0">

                                                            <div class="progress" role="progressbar"
                                                                aria-label="Example with label"
                                                                :aria-valuenow="p.porcentaje" aria-valuemin="0"
                                                                aria-valuemax="100">
                                                                <div class="progress-bar overflow-visible bg-tiempo"
                                                                    :style="{ 'width': p.porcentaje + '%' }">
                                                                    Faltan 10 minutos / 1 hora
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 row" v-if="searchProducto.length > 4 && !comanda.comprobante">
                                        <div clas="col-12 mb-3 h4">Resultado de busqueda</div>
                                        <div class="col-12 mb-4" v-for="l in productosList"
                                            v-if="searchProducto.length > 4">

                                            <div class="col-12 mb-4" v-for="l in productosList"
                                                v-if="searchProducto.length > 4">
                                                <comanda-evento :l="l" :comanda="comanda.cid"
                                                    :comprobante="comanda.comprobante" @detalle="setDetalle"
                                                    url="{{ route('detalle_comandas.crearDetalle') }}"
                                                    :can-edit-prices="{{ json_encode(auth()->user()->can('precios.edit')) }}" />

                                            </div>
                                            <div class="col-12 mb-4"
                                                v-if="searchProducto.length > 4 && productosList.length == 0">
                                                No se encontró ningún precio con ese nombre, busque con un sinónimo o use
                                                otro precio.
                                            </div>
                                        </div>
                                    </div>



                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <!-- Modal Anulación de productos-->
                <div class="modal fade" id="anulacionProducto" tabindex="-1" aria-labelledby="anulacionProducto"
                    aria-hidden="true">
                    <div
                        class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Anulación de producto
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
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
                                            <input type="hidden" name="detalles[]" :value="s.cid" multiple>
                                            <input type="number" class="form-control" :name="'cantidad-' + s.cid"
                                                :max="s.cantidad" min="1" placeholder="Cantidad a anular"
                                                required />

                                        </div>
                                        <div class="col-10 p-2 fw-bold">
                                            @{{ s.precios.detalle }}
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
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="confirmAnulacion" v-model="confirmAnulacion">
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
                    <div
                        class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-uppercase" id="exampleModalLabel">Separar productos</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('comandas.separar_producto_evento') }}" method="post"
                                    v-if="selectedDetalle.length > 0">
                                    @csrf
                                    <input type="hidden" name="comanda" value="{{ isset($c->cid) ? $c->cid : '0' }}">
                                    <input type="hidden" name="eventos_id"
                                        value="{{ isset($evento->cid) ? $evento->cid : '0' }}">
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
                                        <input type="number" class="form-control" id="mesa" name="mesa"
                                            min="1" placeholder="Mesa existente o nueva" required />

                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="confirmAnulacion" v-model="confirmSeparar">
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
                <!-- Modal editar cantidad de producto del detalle de comanda-->
                <div class="modal fade" id="editarCantidadProducto" tabindex="-1"
                    aria-labelledby="editarCantidadProducto" aria-hidden="true">
                    <div
                        class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-md-down">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">
                                    Edicion de la cantidad del producto en el detalle de comanda
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('comandas.edicion_cantidad_detalle') }}" method="post">
                                    @csrf

                                    <h5>
                                        Ingrese la cantidad
                                    </h5>
                                    <div class="row mb-3" v-for="s in detallesFiltrados">
                                        <div class="col-2">
                                            <input type="hidden" id="detalle_id" name="detalles[]"
                                                :value="s.cid" multiple>
                                            <input type="number" class="form-control" :name="'cantidad-' + s.cid"
                                                :min="s.cantidad" v-model="s.cantidad" min="1"
                                                placeholder="Cantidad a anular" required />

                                        </div>
                                        <div class="col-10 p-2 fw-bold">
                                            @{{ s.precios.detalle }}
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="confirmEdicion" v-model="confirmEdicion">
                                            <label class="form-check-label" for="confirmEdicion">
                                                Confirmo la edicion de la cantidad del producto en el detalle de comanda
                                                seleccionado.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-primary" :disabled="confirmEdicion == 0">
                                            Editar
                                        </button>
                                    </div>

                                </form>

                            </div>
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
    </div>

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
                    comanda: @json($c),
                    comandaDetalle: @json($c_detalle),
                    eventoId: @json($evento),
                    bodegas: @json($bodegas),
                    selectedDetalle: [],
                    inputObservacion: false,
                    txtBusqueda: '',
                    searchProducto: '',
                    productosList: [],
                    bodegaSelected: null,
                    message: '',
                    type: '',
                    id_detalle: '',
                    confirmAnulacion: 0,
                    confirmEdicion: 0,
                    confirmSeparar: 0,
                    delRoute: "{{ route('comandas.confirm', ['id' => isset($c->id) ? Crypt::encryptString($c->id) : Crypt::encryptString(0)]) }}",
                    cortesiaRoute: "{{ route('comandas.descargo_comanda', ['id' => isset($c->id) ? Crypt::encryptString($c->id) : Crypt::encryptString(0)]) }}",
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
                let editar = document.getElementById('editarCantidadProducto');
                editar.addEventListener('show.bs.modal', (event) => {
                    let button = event.relatedTarget;
                    let detalleId = button.getAttribute('data-id');
                    this.id_detalle = detalleId;

                });

            },
            computed: {
                getSalida: function() {
                    return this.bodegas.find(f => f.bodegas_id == this.bodegaSelected).bodegas.bodega
                },
                detallesFiltrados: function() {

                    return this.comandaDetalle.filter(detalle => this.id_detalle.includes(detalle.id));
                }
            },
            methods: {
                getProducto: function() {
                    if (this.searchProducto.length > 4)
                        axios.post("{{ route('precios.apiGetProductos') }}", {
                            busqueda: this.searchProducto,
                            bodega: this.bodegaSelected,
                        }).then((r) => {
                            this.productosList = {
                                ...r.data.list_1,
                                ...r.data.list_5
                            };
                            this.selectedDetalle = [];
                        }).catch((err) => {
                            console.log(err);
                        });

                },
                setBodega: function(id) {
                    console.log(id)
                    localStorage.setItem('bodegaSelected', id)
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
                },
                setResponsePedidos: function(d) {
                    this.selectedDetalle = [];
                    if (d.comanda_detalle)
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
                    }, 3 * 1000);
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
        app.component('comanda-evento', component.evento);
        app.component('progreso', component.progreso);
        app.component('respuestas', component.rspedidos);
        app.mount("#appEventoComanda");
    </script>
@endsection
