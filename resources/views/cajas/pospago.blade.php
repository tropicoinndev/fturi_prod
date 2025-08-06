@extends('layouts.cajas')
@section('css-caja')
    <style>
        body {
            background: #80DEEA !important;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection

@section('panel_caja')
    <div id="appPanelCaja" v-cloak>
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">Panel de estadías en pospago</div>
        </div>
        <div class="container">
            <div class="row mb-2">
                <div class="col-12 mb-2">
                    <div class="mb-1">
                        <label for="search-input" class="form-label">Buscar</label>
                        <input type="text" id="search-input" class="form-control"
                            placeholder="Escriba el numero de registro o el titular..." v-model="txtBusqueda">
                    </div>
                </div>
            </div>
        </div>



        <div class="col-12">
            <div class="col-12">
                <div class="row mb-1">
                    <div class="row mb-1">
                        <div class="col-12 text-uppercase h5" v-show="getRecepciones != null && getRecepciones.length > 0">
                            Estadías
                        </div>
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
                                            @can('recepciones.index')
                                                <li>
                                                    <a class="dropdown-item" target="_blank"
                                                        :href="'/recepciones/impresion/registro/' + h.cid">
                                                        Imprimir
                                                    </a>
                                                </li>
                                            @endcan
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
                                    <div class="text-muted">De @{{ h.fecha_ingreso }} a @{{ h.fecha_salida }}</div>
                                </div>
                                <p class="card-text">
                                    <small v-if="h.pospago.length == 0">Solicita comprobante</small>
                                    <span v-if="h.pospago && h.pospago.length > 0">Agregado a pospago
                                        @{{ h.pospago[0].creacion }}
                                        por
                                        @{{ h.pospago[0].usuario.name }}</span>
                                <div class="col-12 mt-4">
                                    <a class="btn btn-outline-success btn-sm me-1"
                                        :href="`/cobros/crear/${tipo.habitacion}/${h.cid}/${tipo_comprobante.f}`"
                                        :class="{ 'disabled': f == 0 }">
                                        Factura
                                    </a>
                                    <a class="btn btn-outline-primary btn-sm me-1"
                                        :href="`/cobros/crear/${tipo.habitacion}/${h.cid}/${tipo_comprobante.ccf}`"
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

            <div class="col-12" v-show="getRecepciones.length == 0">
                Aun no hay estadías en pospago
            </div>
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

                    recepciones: @json($recepciones),
                    tipo: {
                        habitacion: '{{ \Crypt::encryptString(2) }}',
                    },
                    tipo_comprobante: {
                        f: '{{ \Crypt::encryptString(7002) }}',
                        ccf: '{{ \Crypt::encryptString(7001) }}'
                    },

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
