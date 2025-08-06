@extends('layouts.cajas')

@section('panel_caja')
<div id="appOrdenesActivas">
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-6">
                <h3>Ordenes activas</h3>
            </div>

            <!--Tipos de filtrados.-->
            <div class="col-md-6">
                {{-- <span class="badge bg-success" @click="tipoFiltrado = 1" style="cursor: pointer;">CONSUMIDOR FINAL</span> --}}
                {{-- <span class="badge bg-secondary" @click="tipoFiltrado = 0" style="cursor: pointer;">CREDITO FISCAL</span> --}}
                {{-- <span class="badge bg-info" @click="tipoFiltrado = 2" style="cursor: pointer;">TODOS</span> --}}

                <button type="button" class="btn btn-outline-success position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">CONSUMIDOR FINAL
                    <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                        @{{ funcBuscarOrdenes.length }}
                    </span>
                </button>

                <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 0">CREDITO FISCAL
                    <span v-if="tipoFiltrado == 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                        @{{ funcBuscarOrdenes.length }}
                    </span>
                </button>

                <button type="button" class="btn btn-outline-primary position-relative btn-sm" @click="tipoFiltrado = 2">TODOS
                    <span v-if="tipoFiltrado == 2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                        @{{ funcBuscarOrdenes.length }}
                    </span>
                </button>
            </div>
        </div>

        <hr>

        <!--Caja de busqueda.-->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3 row">
                    <label for="txtBusqueda" class="col-sm-2 col-form-label">Buscar:</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-lg"
                            placeholder="Escriba nombre del titular..." autocomplete="off" id="clientes_id"
                            :disabled="clientesSelected.length == 1" v-model="txtTitular">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!--Boton de nueva orden.-->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                <div class="card text-center shadow" style="border:none;">
                    <button type="button" class="btn btn-outline-primary btn-lg" style="height: 260px;"
                        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        <span class="mdi mdi-plus"></span> NUEVA ORDEN
                    </button>
                </div>
            </div>

            {{-- @{{ arrayOrdenes }} --}}
            {{-- <div v-for="p in arrayOrdenes">
                <p v-if="p.clientes != null">@{{ p.clientes.nombre }}.</p>
            </div> --}}

            <!--Bucle FOR de ordenes activas.-->
            <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3"
                v-for="({ id, fecha, titular, descripcion, clientes, cajas, estado, conceptos }, index) in funcBuscarOrdenes">
                <div class="card border-success p-3 shadow" style="height: 260px; overflow-x: auto;">
                    <div class="card-body">
                        <h4 class="card-title placeholder-glow"><b>@{{ titular }}</b></h4>
                        <p class="card-text">
                            <span class="text-muted" v-if="descripcion">@{{ descripcion }}</span>
                            <span class="text-muted" v-else>@{{ conceptos }} concepto (s).</span>
                            <br>
                            <small v-if="clientes">@{{ clientes.nombre }}</small>
                            <small v-else>Aun no se ha asignado un cliente.</small>
                        </p>
                    </div>

                    <div class="card-footer bg-transparent" style="border: none;">
                        <div class="row">
                            <div class="col-md-6 text-start">
                                <span class="badge bg-success">@{{ cajas.caja }}</span>
                            </div>

                            <div class="col-md-6 text-end">
                                <a v-if="(conceptos >= 0)" :href="'/detalle_ordenes/'+id" type="button"
                                    class="btn btn-outline-success mb-1">Detalles</a>
                                <a v-if="(conceptos == 0)" :href="'/ordenes/confirm/'+id" type="button"
                                    class="btn btn-outline-danger mb-1">Anular</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <small class="text-muted">@{{ fecha }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!--End row.-->
    </div><!--End container.-->



    <!--Modal.-->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva orden</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('ordenes.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="cliente" :value="clientesSelected.id">
                    <input type="hidden" name="titular" :value="clientesSelected.titular">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="titular" class="form-label">Titular: <b>@{{ clientesSelected.titular
                                    }}</b></label>

                            <div v-if="clientesSelected.id >= 0" class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Editar titular"
                                    aria-label="Editar titular" aria-describedby="basic-addon2" v-model="txtCliente">
                                <span class="input-group-text" id="basic-addon2" style="cursor: pointer;"
                                    @click="AddTitular">Editar</span>
                            </div>

                            <!--Input cliente.-->
                            <div v-if="!(clientesSelected.id >= 0)" class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Buscar Cliente o Titular"
                                    autocomplete="off" id="titular" name="clientes_id" v-model="txtCliente"
                                    @keyup="getClientes">

                                <span class="input-group-text" id="basic-addon2" style="cursor: pointer;"
                                    v-if="clientes.length == 0 && txtCliente.length > 2" @click="AddTitular">Agregar
                                    titular
                                </span>
                            </div>

                            <!--Desplegable / Listado de clientes.-->
                            {{-- @{{ clientes }} --}}
                            <div class="result shadow"
                                style="position: absolute;z-index: 10;margin-top: 0px;margin-left: 0px; width: 32.5em; background:white; padding:2px;"
                                v-if="clientes.length > 0">
                                <ul class="list-group" v-for="cliente in clientes">
                                    <li class="list-group-item"
                                        style="border-radius: 0px; border: none; cursor:pointer;"
                                        @click="selected(cliente)">@{{ cliente.cliente }}</small>
                                    </li>
                                </ul>
                            </div>

                            {{--input observación--}}
                            <div class="mb-3">
                                <label class="form-label" for="descripcion">Observaciones: (opcional)</label>
                                <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="3" placeholder="Escriba una observación para ésta orden"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary" :disabled="clientesSelected.length == 0">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
    var ordenes = new Vue({
        el: '#appOrdenesActivas',
        data: {
            //Titular
            titular: '',
            txtTitular: '',

            //Cliente.
            txtCliente: '',
            clientes: [],
            clientesSelected: [],

            arrayOrdenes: @json($p),
            tipoFiltrado: 2,
        },
        methods: {
            AddTitular(){
                this.clientesSelected = {id: 0, titular: this.txtCliente}
            },
            getClientes(){
                //if(this.txtCliente.length > 2)
                    axios.post("{{ route('clientes.api_search') }}",{
                        busqueda: (this.txtCliente).toUpperCase(),
                    })
                    .then((rs) => {

                        this.clientes = rs.data.clientes ?? [];
                        console.log(rs.data);
                    })
                    .catch(error => {
                        console.log(error);
                    });

            },

            selected(s){
                this.clientesSelected = {id: s.id, titular: s.nombre};
                this.clientes = [];
            },
        },
        mounted(){

        },
        computed: {
            funcBuscarOrdenes(){
                if(this.tipoFiltrado == 0){//JURIDICO
                    return this.arrayOrdenes.filter((arrayOrdenes) => arrayOrdenes.clientes && arrayOrdenes.clientes.tipo_cliente == true);
                }
                else if(this.tipoFiltrado == 1){//NATURAL
                    return this.arrayOrdenes.filter((arrayOrdenes) => arrayOrdenes.clientes == null || arrayOrdenes.clientes.tipo_cliente == false);
                }
                else{//CAJA DE BUSQUEDA
                    return this.arrayOrdenes.filter((arrayOrdenes) => arrayOrdenes.titular != null && arrayOrdenes.titular.toLowerCase().includes(this.txtTitular));
                }
            },
        }
    })
</script>
@endsection
