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


        <div class="row p-1" v-if="comanda != null">


            <div class="col-12 text-uppercase h3">
                Comanda #@{{ comanda.id }}
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
                <button type="button" class="btn btn-light" :disabled="comandaDetalle.length == 0"
                    @click="ImprimirComanda()">
                    <span class="mdi mdi-printer fs-5"></span>
                    Imprimir comanda
                </button>

                @can('comandas.credito')
                    @if ($p->tipo_comanda == 1 && $p->clientes && $p->clientes->credito)
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#moverCredito">
                            <span class="mdi mdi-file-move fs-5"></span>
                            Mover a crédito
                        </button>


                        <div class="modal fade" id="moverCredito" tabindex="-1" data-bs-backdrop="static"
                            data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalTitleId">
                                            Mover comanda a credito
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('comandas.setCredito') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $p->cid }}">
                                        <div class="modal-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="confirm"
                                                    name="confirm" />
                                                <label class="form-check-label" for="confirm">
                                                    Confirmo que esta comanda debe
                                                    estar en créditos
                                                </label>
                                            </div>

                                            <div class="mt-4">
                                                <button class="btn btn-primary" type="submit">Mover a crédito</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endif
                @endcan
                @can('comandas.anular')
                    <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#anulacionProducto"
                        :disabled="selectedDetalle.length == 0">
                        <span class="mdi mdi-file-document-remove fs-5"></span>
                        Anular
                    </button>
                @endcan
                @can('comandas.delete')
                    <a :href="comandaDetalle.length != 0 ? '#' : delRoute" class="btn btn-light"
                        :class="{ 'disabled': comandaDetalle.length != 0 }">
                        <span class="mdi mdi-file-document-remove fs-5"></span>
                        Eliminar
                    </a>
                @endcan
            </div>

            <!-- Productos agregados a la comanda -->
            <div class="col-12 row">
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
                                            @{{ p.user_comanda?.name }} · @{{ p.creado }} @{{ p.creacion }}
                                        </div>
                                        <div class="col-12 text-uppercase">
                                            Entregado:
                                            <span v-if="p.entregado == null" class="badge text-bg-danger">
                                                Sin solicitar
                                            </span>
                                            <span v-if="p.entregado != null" class="badge text-bg-primary">
                                                @{{ p.user_asignado.user }} ·
                                                @{{ p.entregado ?? 'Sin solicitar' }}
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
                            </div>
                        </div>
                    </label>
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
                                        <input type="hidden" name="detalles[]" :value="s.cid" multiple
                                            v-if="(s.entregado == null && s.users_acepta_id == null) || s.cancelado">
                                        <input type="number" class="form-control" :name="'cantidad-' + s.cid"
                                            :max="s.cantidad" min="1" placeholder="Cantidad a anular"
                                            required
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
                    comanda: @json($p),
                    cliente: @json($p != null ? $p->clientes : []),
                    searchMesa: '',
                    searchProducto: '',
                    comandaDetalle: @json($detalle),
                    cortesia: null,
                    bodegas: null,
                    bodegaSelected: null,
                    productosList: [],
                    selectedDetalle: [],
                    inputObservacion: false,
                    message: '',
                    type: '',
                    confirmAnulacion: 0,
                    confirmSeparar: 0,
                    panelComandas: true,
                    arrayClientes: [],
                    clientesSelected: [],
                    txtBusqueda: '',
                    asignacion: 1,
                    pmessage: '',
                    status: '',
                    notify: true,
                    delRoute: "{{ route('comandas.confirm', ['id' => isset($p->id) ? Crypt::encryptString($p->id) : Crypt::encryptString(0)]) }}",
                }
            },
            mounted() {

                if (localStorage.getItem('panelComandas') != null)
                    this.panelComandas = parseInt(localStorage.getItem('panelComandas')) == 1;
                else localStorage.setItem('panelComandas', this.panelComandas ? 1 : 0);

            },

            methods: {

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



                setPanel: function() {
                    this.panelComandas = !this.panelComandas;
                    localStorage.setItem('panelComandas', this.panelComandas ? 1 : 0);
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
        app.mount("#appComandas");
    </script>
@endsection
