@extends('layouts.cajas')
@section('css-caja')
    <style>
        body {
            background: #E0F7FA;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('panel_caja')
    <div id="appPanelCaja" v-cloak>
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">Panel de cajero</div>
        </div>
        @can('correlativos.create')
            <div class="row mb-2 text-uppercase">
                <div class="col-4 col-lg-3">CORRELATIVOS CCF</div>
                <div class="col-8 col-lg-3 fw-bold">
                    @if ($correlativo_ccf == 0)
                        <small class="p-2 bg-warning rounded-1">¡Agregue correlativos!</small>
                    @else
                        #{{ $correlativo_ccf }}
                    @endif
                </div>
                <div class="col-4 col-lg-3">Correlativos factura</div>
                <div class="col-8 col-lg-3 fw-bold">
                    @if ($correlativo_f == 0)
                        <small class="p-2 bg-warning rounded-1">¡Agregue correlativos!</small>
                    @else
                        #{{ $correlativo_f }}
                    @endif
                </div>
            </div>
        @endcan
        @if (session('turno'))
            @php
                $fApertura = \Carbon::parse(session('turno')->fecha . ' ' . session('turno')->opcion->apertura);
                $cierre = \Carbon::parse(session('turno')->fecha . ' ' . session('turno')->opcion->cierre);
                if (session('turno')->opcion->cierre < session('turno')->opcion->apertura) {
                    $cierre = $cierre->addDay();
                }
                $horas = $fApertura->diffInHours($cierre);
                $fCierre = $fApertura->addHours($horas);
            @endphp
            <div class="row my-4">
                @if (date('Y-m-d H:i:s') >= $fCierre)
                    <div class="col-12">
                        <div class="card text-bg-warning">
                            <div class="card-body">
                                @can('cajas.index')
                                    <a href="{{ route('cajas.cierre') }}" class="btn btn-light float-end align-middle">Cerrar
                                        turno</a>
                                @endcan
                                <h5 class="card-title">{{ session('turno')->opcion->turno }}</h5>
                                <p class="card-text">Debe cerrar este turno:
                                    {{ Carbon::parse(date('Y-m-d H:i:s'))->diffForHumans(session('turno')->apertura) }}</p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-12">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h5 class="card-title">{{ session('turno')->opcion->turno }}</h5>
                                <p class="card-text">
                                    <span title="{{ session('turno')->apertura }}">Apertura
                                        {{ Carbon::parse(session('turno')->apertura)->diffForHumans() }}</span>·
                                    <span title="{{ $fCierre }}">Cierre {{ $fCierre->diffForHumans() }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="container">
            <div class="row mb-2">
                <!-- Buscar -->
                <div class="col-12 col-md-7 mb-2">
                    <div class="mb-1">
                        <label for="search-input" class="form-label">Buscar</label>
                        <input type="text" id="search-input" class="form-control"
                            placeholder="Escriba el numero de orden/comanda o el titular..." v-model="txtBusqueda">
                    </div>
                </div>

                <!-- Filtrar por -->
                <div class="col-12 col-md-5 mb-2">
                    <div class="mb-1">
                        <label for="filter-group" class="form-label">Filtrar por:</label>
                        <div id="filter-group" class="btn-group btn-group-toggle d-flex flex-wrap w-100" role="group"
                            aria-label="Filtro">
                            <button type="button" class="btn btn-outline-primary flex-fill mb-1"
                                :class="{ 'active': filtro === null }" @click="seleccionarCuentas(null)">Todas</button>
                            <button type="button" class="btn btn-outline-primary flex-fill mb-1"
                                :class="{ 'active': filtro == 2 }" @click="seleccionarCuentas(2)">Comandas</button>
                            <button type="button" class="btn btn-outline-primary flex-fill mb-1"
                                :class="{ 'active': filtro == 3 }" @click="seleccionarCuentas(3)">Estadías</button>
                            <button type="button" class="btn btn-outline-primary flex-fill mb-1"
                                :class="{ 'active': filtro == 1 }" @click="seleccionarCuentas(1)">Órdenes</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="row mb-1" v-show="filtro == 2 || filtro === null">
                <div class="row mb-1">
                    <div class="col-12 text-uppercase h5" v-show="getComandas.length > 0">COMANDAS</div>
                </div>

                <div class="col-12 col-lg-4" v-for="c in sortedComandas">
                    <div class="card border-1 border-success mb-3">
                        <div class="card-body">
                            <div class="card-title text-uppercase">
                                <div class="dropdown float-end">
                                    <a class="btn" href="#" role="button" id="dropdownMenuLink"
                                        data-bs-toggle="dropdown" aria-expanded="true"
                                        title="Opciones para aplicar a esta cuenta">
                                        <i class="mdi mdi-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                        <li>
                                            <a class="dropdown-item" :href="`/comandas/print/${c.cid}`">Imprimir</a>
                                        </li>
                                        <li v-if="c.cortesia[0] == undefined">
                                            <a class="dropdown-item"
                                                :href="`/cortesias/aplicar/${c.cid}/${tipo.comanda}`">Cortesía</a>
                                        </li>

                                        @can('clientes.cuentas')
                                            <li><a class="dropdown-item" href="#"
                                                    @click="editarCliente(tipo.comanda, c.cid)" data-bs-toggle="modal"
                                                    data-bs-target="#clientes">Editar cliente</a></li>
                                        @endcan
                                        @can('comandas.desbloquear')
                                            <li v-if="c.comprobante"><a class="dropdown-item"
                                                    :href="'/comandas/desbloquear/' + c.cid">Desbloquear cuenta</a></li>
                                        @endcan
                                        @can('comandas.comprobante')
                                            <li v-if="!c.comprobante"><a class="dropdown-item"
                                                    :href="'/comandas/comprobante/' + c.cid">Solicitar comprobante</a></li>
                                        @endcan
                                    </ul>
                                </div>
                                <div>Comanda No. @{{ c.id }} - Mesa #@{{ c.mesa }}</div>
                                <div class="text-muted">@{{ c.clientes && c.clientes.nombre ? c.clientes.nombre : (c.titular ?? 'Aún no se ha agregado un cliente o titular') }}</div>
                            </div>
                            <p class="card-text">
                                <small>Solicita comprobante | <span
                                        class="text-uppercase">@{{ c.cajas.caja }}</span></small>

                                <span
                                    v-if="c.cortesia[0] != undefined && !c.cortesia[0].estado && !c.cortesia[0].autorizado"
                                    class="text-danger">
                                    Cortesía negada - <span class="text-uppercase">@{{ c.cajas.caja }}
                                    </span>
                                    <small v-if="c.cortesia[0] == undefined">
                                        <span v-if="c.comprobante"> Solicita comprobante</span>
                                        <span v-if="!c.comprobante">Comanda de @{{ c.fecha }}</span> |
                                        <span class="text-uppercase">@{{ c.cajas.caja }}</span>
                                    </small>
                                    <div class="col-12 mt-4"
                                        v-if="c.comprobante && (c.cortesia[0] == undefined || c.cortesia[0].autorizado)">
                                        @can('comprobantes.create')
                                            <a class="btn btn-outline-success btn-sm me-1"
                                                :href="f > 0 ? `/cobros/crear/${tipo.comanda}/${c.cid}/${tipo_comprobante.f}` :
                                                    `#`"
                                                :class="{ 'disabled': f == 0 }">Factura
                                            </a>
                                            <a class="btn btn-outline-primary btn-sm me-1"
                                                :href="ccf > 0 ?
                                                    `/cobros/crear/${tipo.comanda}/${c.cid}/${tipo_comprobante.ccf}` :
                                                    `#`"
                                                v-if="c.clientes != null && !c.clientes.tipo_cliente"
                                                :class="{ 'disabled': ccf == 0 }">Crédito Fiscal
                                            </a>
                                        @endcan
                                        <small class="float-end">@{{ c.modificacion }}</small>
                                    </div>
                                    <div class="col-12 mt-4" v-if="!c.comprobante && c.cortesia[0] == undefined">
                                        <a class="btn btn-outline-success btn-sm me-1"
                                            :href="'/comandas/comprobante/' + c.cid"
                                            :class="{ 'disabled': f == 0 }">Solicitar comprobante</a>
                                    </div>
                                    <div class="col-12 mt-4" v-if="c.cortesia[0] != undefined && !c.cortesia[0].estado ">
                                        <a class="btn btn-outline-success btn-sm me-1" href="#"
                                            data-bs-toggle="modal" data-bs-target="#cortesiaNegada"
                                            @click="observacionNegacion = c.cortesia[0].observacion">Observación
                                        </a>
                                        <a class="btn btn-outline-primary btn-sm me-1" :href="`/comandas/${c.cid}`">Ir a
                                            comanda</a>
                                        <small class="float-end">@{{ c.modificacion }}</small>
                                    </div>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12" v-show="filtro == 1 || filtro === null">
                <div class="row mb-1">
                    <div class="row mb-1">
                        <div class="col-12 text-uppercase h5" v-show="getOrdenes.length > 0">ORDENES</div>
                    </div>

                    <div class="col-12 col-lg-4" v-for="orden in sortedOrdenes">
                        <div class="card border-1 border-success mb-3">
                            <div class="card-body">
                                <div class="card-title text-uppercase">
                                    <div class="dropdown float-end">
                                        <a class="btn" href="#" role="button" id="dropdownMenuLink"
                                            data-bs-toggle="dropdown" aria-expanded="true"
                                            title="Opciones para aplicar a esta cuenta">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li>
                                                <a class="dropdown-item" :href="`/detalle_ordenes/print/detalle/pdf/${orden.id}`" target="_blank">Imprimir</a>
                                            </li>
                                            <li><a class="dropdown-item"
                                                    :href="`/cortesias/aplicar/${orden.id}/${tipo.orden}`">Cortesía</a>
                                            </li>
                                            @can('clientes.cuentas')
                                                <li><a class="dropdown-item" href="#"
                                                        @click="editarCliente(tipo.orden, orden.id)" data-bs-toggle="modal"
                                                        data-bs-target="#clientes">Editar cliente</a></li>
                                            @endcan
                                            @can('recepciones.desbloquear')
                                                <li><a class="dropdown-item"
                                                        :href="'/ordenes/desbloquear/' + orden.id">Desbloquear
                                                        cuenta</a></li>
                                            @endcan
                                        </ul>
                                    </div>
                                    <div>@{{ `Orden #${orden.orden}` }}</div>
                                    <div class="text-muted">@{{ orden.titular ?? orden.clientes.nombre }}</div>
                                </div>
                                <p class="card-text">
                                    <small>Solicita comprobante | <span
                                            class="text-uppercase">@{{ orden.cajas.caja }}</span></small>
                                <div class="col-12 mt-4">
                                    <a class="btn btn-outline-success btn-sm me-1"
                                        :href="f > 0 ? `/cobros/crear/${tipo.orden}/${orden.id}/${tipo_comprobante.f}` : `#`"
                                        :class="{ 'disabled': f == 0 }">
                                        Factura
                                    </a>
                                    <a class="btn btn-outline-primary btn-sm me-1"
                                        :href="ccf > 0 ? `/cobros/crear/${tipo.orden}/${orden.id}/${tipo_comprobante.ccf}` :
                                            `#`"
                                        v-if="orden.clientes != null && !orden.clientes.tipo_cliente"
                                        :class="{ 'disabled': ccf == 0 }">Credito Fiscal
                                    </a>
                                    <small class="float-end">@{{ orden.updated_at }}</small>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-12" v-show="filtro == 3 || filtro === null">
                <div class="row mb-1">
                    <div class="row mb-1">
                        <div class="col-12 text-uppercase h5" v-show="getRecepciones.length > 0">Estadías </div>
                    </div>

                    <div class="col-12 col-lg-4" v-for="h in sortedRecepciones">
                        <div class="card border-1 border-success mb-3">
                            <div class="card-body">
                                <div class="card-title text-uppercase">
                                    <div class="dropdown float-end">
                                        <a class="btn" href="#" role="button" id="dropdownMenuLink"
                                            data-bs-toggle="dropdown" aria-expanded="true"
                                            title="Opciones para aplicar a esta cuenta">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <li><a class="dropdown-item"
                                                    :href="`/cortesias/aplicar/${h.cid}/${tipo.habitacion}`">Cortesía</a>
                                            </li>
                                            @can('clientes.cuentas')
                                                <li><a class="dropdown-item" href="#"
                                                        @click="editarCliente(tipo.habitacion, h.cid)" data-bs-toggle="modal"
                                                        data-bs-target="#clientes">Editar cliente</a></li>
                                            @endcan
                                            @can('recepciones.desbloquear')
                                                <li v-if='h.comprobante && h.estado'><a class="dropdown-item"
                                                        :href="'/recepciones/desbloquear/' + h.cid">Desbloquear cuenta</a></li>
                                            @endcan
                                            @can('recepciones.anulaciones_pospago')
                                                <li>
                                                    <a class="dropdown-item" :href="'/recepciones/anulaciones/' + h.cid">
                                                        Anular
                                                    </a>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                    <div>Estadía No. @{{ h.id }} - Habitacion @{{ h.habitaciones.numero_habitacion }}</div>
                                    <div class="text-muted">@{{ h.titular ?? h.clientes.nombre }}</div>
                                </div>
                                <p class="card-text">
                                    <small v-if="h.pospago.length == 0">Solicita comprobante</small>
                                    <span v-if="h.pospago && h.pospago.length > 0">Agregado a pospago
                                        @{{ h.pospago[0].creacion }}
                                        por
                                        @{{ h.pospago[0].usuario.name }}</span>
                                <div class="col-12 mt-4">
                                    <a class="btn btn-outline-success btn-sm me-1"
                                        :href="f > 0 ? `/cobros/crear/${tipo.habitacion}/${h.cid}/${tipo_comprobante.f}` : `#`"
                                        :class="{ 'disabled': f == 0 }">
                                        Factura
                                    </a>
                                    <a class="btn btn-outline-primary btn-sm me-1"
                                        :href="ccf > 0 ? `/cobros/crear/${tipo.habitacion}/${h.cid}/${tipo_comprobante.ccf}` :
                                            `#`"
                                        v-if="h.clientes != null && !h.clientes.tipo_cliente && h.clientes.ccf"
                                        :class="{ 'disabled': ccf == 0 }">Crédito Fiscal
                                    </a>
                                    <small class="float-end">@{{ h.modificacion }}</small>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="col-12" v-show="getOrdenes.length == 0 && getComandas.length == 0 && getRecepciones.length == 0">
                Aun no hay solicitudes de comprobantes</div>
        </div>

        <!-- Modal observación de cortesias -->
        <div class="modal fade" id="cortesiaNegada" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="modalTitleId">Cortesía negada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">@{{ observacionNegacion ?? 'Sin observaciones, solicite mayor informacion a la persona que autoriza las cortesias' }}</div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Cerrar</button></div>
                </div>
            </div>
        </div>

        <!-- Modal Editar cliente -->
        <div class="modal fade" id="clientes" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-uppercase" id="modalTitleId">Editar cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('clientes.cuentas') }}" method="post">
                        @csrf
                        <input type="hidden" name="clientes_id" :value="cliente != null ? cliente.id : ''">
                        <input type="hidden" name="origen" :value="origenSelected">
                        <input type="hidden" name="origen_id" :value="origenIdSelected">
                        <div class="modal-body">
                            <clientes url="{{ route('clientes.api_search_list') }}" @cliente="setcliente"></clientes>
                            <!--AQUI...-->
                            <div v-if="cliente && cliente.cliente" class="text-uppercase text-success"><span
                                    class="mdi mdi-check"></span> Cliente seleccionado</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" :disabled="cliente == null">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="module">
        var app = window.appVue({
            emits: ['cliente'],
            data() {
                return {
                    txtBusqueda: '',
                    ordenes: @json($ordenes),
                    comandas: @json($comandas),
                    recepciones: @json($recepciones),
                    tipo: {
                        orden: '{{ \Crypt::encryptString(1) }}',
                        habitacion: '{{ \Crypt::encryptString(2) }}',
                        comanda: '{{ \Crypt::encryptString(3) }}'
                    },
                    tipo_comprobante: {
                        f: '{{ \Crypt::encryptString(7002) }}',
                        ccf: '{{ \Crypt::encryptString(7001) }}'
                    },
                    ccf: {{ $correlativo_ccf }},
                    f: {{ $correlativo_f }},
                    cuenta: null,
                    cliente: null,
                    origenSelected: null,
                    origenIdSelected: null,
                    observacionNegacion: '',
                    filtro: null,

                }
            },
            methods: {
                setcliente: function(c) {
                    this.cliente = c;
                },
                editarCliente: function(origen, origenId) {
                    this.origenSelected = origen;
                    this.origenIdSelected = origenId;
                },
                seleccionarCuentas: function(tipo) {
                    this.filtro = tipo;
                    localStorage.setItem('filtro', tipo);
                    switch (tipo) {
                        case 1:
                            localStorage.setItem('orden', tipo);
                            localStorage.removeItem('comanda');
                            localStorage.removeItem('estadia');
                            break;
                        case 2:
                            localStorage.setItem('comanda', tipo);
                            localStorage.removeItem('orden');
                            localStorage.removeItem('estadia');
                            break;
                        case 3:
                            localStorage.setItem('estadia', tipo);
                            localStorage.removeItem('orden');
                            localStorage.removeItem('comanda');
                            break;
                        default:
                            localStorage.removeItem('orden');
                            localStorage.removeItem('comanda');
                            localStorage.removeItem('estadia');
                            break;
                    }
                }
            },

            computed: {
                getcliente: function(c) {
                    return this.cliente ?? 'Aun sin seleccionar el cliente';
                },
                getOrdenes: function() {
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.ordenes.filter(o => regx.test(o.orden) || regx.test((o.titular ?? o.clientes
                        .nombre).toLowerCase()));
                },
                sortedOrdenes: function() {
                    return this.getOrdenes.toSorted((a, b) => {
                        if (!a.clientes && !b.clientes) return 0;
                        if (!a.clientes) return 1;
                        if (!b.clientes) return -1;
                        if (a.clientes.tipo !== b.clientes.tipo) {
                            return a.clientes.tipo - b.clientes.tipo;
                        }
                    });
                },
                getComandas: function() {
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.comandas.filter(o => regx.test(o.mesa) || regx.test(o.id) ||
                        (o.titular != null && regx.test((o.titular).toLowerCase())) ||
                        (o.clientes_id > 0 && regx.test((o.clientes.nombre).toLowerCase()))
                    );
                },
                sortedComandas: function() {
                    return this.getComandas.toSorted((a, b) => {
                        if (!a.clientes && !b.clientes) return 0;
                        if (!a.clientes) return 1;
                        if (!b.clientes) return -1;
                        if (a.clientes.tipo !== b.clientes.tipo) {
                            return b.clientes.tipo - a.clientes.tipo;
                        }

                        return a.clientes.nombre.localeCompare(b.clientes.nombre);
                    });

                },
                getRecepciones: function() {
                    let regx = new RegExp((this.txtBusqueda).toLowerCase());
                    return this.recepciones.filter(o => regx.test(o.id) ||
                        regx.test(o.habitaciones.numero_habitacion) ||
                        (o.titular != null && regx.test((o.titular).toLowerCase())) ||
                        (o.clientes_id > 0 && regx.test((o.clientes.nombre).toLowerCase()))
                    );
                },
                sortedRecepciones: function() {
                    return this.getRecepciones.sort((a, b) => {
                        const pospagoA = a.pospago && a.pospago.length > 0;
                        const pospagoB = b.pospago && b.pospago.length > 0;

                        if (pospagoA !== pospagoB) {
                            return pospagoB - pospagoA;
                        }
                        if (a.clientes && b.clientes) {
                            return a.clientes.tipo - b.clientes.tipo;
                        } else if (a.clientes) {
                            return -1;
                        } else if (b.clientes) {
                            return 1;
                        }
                        return 0;


                    });

                },

            },

        });
        app.component('clientes', component.clientes);
        app.mount("#appPanelCaja");
        //cSpell:ignore regx, Busqueda, habitacion
    </script>
@endsection
