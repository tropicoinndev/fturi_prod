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
            <div class="col-12 text-uppercase h3">COMANDAS EN CRÉDITOS</div>
        </div>


        <div class="container">
            <div class="row mb-2">
                <!-- Buscar -->
                <div class="col-12 mb-2">
                    <div class="mb-1">
                        <label for="search-input" class="form-label">Buscar</label>
                        <input type="text" id="search-input" class="form-control"
                            placeholder="Escriba el numero de orden/comanda o el titular..." v-model="txtBusqueda">
                    </div>
                </div>
            </div>




            <div class="col-12">
                <div class="row mb-1" v-show="filtro == 2 || filtro === null">
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

                                            {{--
                                            @can('clientes.cuentas')
                                                <li><a class="dropdown-item" href="#"
                                                        @click="editarCliente(tipo.comanda, c.cid)" data-bs-toggle="modal"
                                                        data-bs-target="#clientes">Editar cliente</a></li>
                                            @endcan
                                            --}}
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
                                    <div class="text-muted">
                                        @{{ c.clientes && c.clientes.nombre ? c.clientes.nombre : (c.titular ?? 'Aún no se ha agregado un cliente o titular') }}
                                    </div>
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
                                                    :href="f > 0 ?
                                                        `/cobros/crear/${tipo.comanda}/${c.cid}/${tipo_comprobante.f}` :
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
                                        <div class="col-12 mt-4"
                                            v-if="c.cortesia[0] != undefined && !c.cortesia[0].estado ">
                                            <a class="btn btn-outline-success btn-sm me-1" href="#"
                                                data-bs-toggle="modal" data-bs-target="#cortesiaNegada"
                                                @click="observacionNegacion = c.cortesia[0].observacion">Observación
                                            </a>
                                            <a class="btn btn-outline-primary btn-sm me-1" :href="`/comandas/${c.cid}`">
                                                Ir a comanda
                                            </a>
                                            <small class="float-end">@{{ c.modificacion }}</small>
                                        </div>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-12" v-show="getComandas.length == 0">
                    Aun no hay datos
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
                    comandas: @json($comandas),
                    tipo: {
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

            },

            computed: {
                getcliente: function(c) {
                    return this.cliente ?? 'Aun sin seleccionar el cliente';
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


            },

        });
        app.component('clientes', component.clientes);
        app.mount("#appPanelCaja");
        //cSpell:ignore regx, Busqueda, habitacion
    </script>
@endsection
