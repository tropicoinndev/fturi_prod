@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appComprasActivas">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Compras activas</h3>
                </div>

                <!--Tipos de filtrados.-->
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-success position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">CONTADO
                        <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            @{{ funcBuscarCompras.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 2">CRÉDITO
                        <span v-if="tipoFiltrado == 2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarCompras.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-primary position-relative btn-sm" @click="tipoFiltrado = 0">TODOS
                        <span v-if="tipoFiltrado == 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            @{{ funcBuscarCompras.length }}
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
                                placeholder="Escriba nombre del proveedor..." autocomplete="off" id="proveedores_id"
                                v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!--Boton de nueva orden.-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                    <div class="card text-center shadow" style="border: none;">
                        <button type="button" class="btn btn-outline-primary btn-lg" style="height: 17.5em;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <span class="mdi mdi-plus"></span> NUEVA COMPRA
                        </button>
                    </div>
                </div>

                <!--Bucle FOR de compras activas.-->
                <div v-for="({id,relacion_proveedores,relacion_usuarios,relacion_tipo_pagos,fecha_factura,correlativo,completado}, index) in funcBuscarCompras"
                    class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                    <div class="card border-success p-3 shadow">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5 class="card-title placeholder-glow"><b>@{{ relacion_proveedores.proveedor }}</b></h5>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="card-title placeholder-glow float-end text-danger">N° @{{ (index + 1) }}</h6>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <small class="text-muted float-end">@{{ fecha_factura }}</small>
                                </div>
                            </div>

                            <table class="table table-sm table align-middle user-select-none mt-3">
                                <tbody>
                                    <tr>
                                        <td colspan="2">Correlativo: 
                                            <small><b>@{{ correlativo }}</b></small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ingresado por: </td>
                                        <td><small><b>@{{ relacion_usuarios.name }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Tipo de pago: </td>
                                        <td><small><b>@{{ relacion_tipo_pagos.tipo_pago }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Completada: </td>
                                        <td><small><b>@{{ (completado) ? 'Si' : 'No' }}</b></small></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="float-end">
                                <a :href="'/lotes/'+id" type="button" class="btn btn-outline-success btn-sm mb-1">Detalles</a>
                                <a v-if="!completado" :href="'/compras/confirm/'+id" type="button" class="btn btn-outline-danger btn-sm mb-1">Anular</a>
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
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva compra</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('compras.store') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            
                            <div class="mb-3">
                                <label for="proveedor" class="form-label">Nombre proveedor: <b>@{{ proveedorSelected.proveedor }}</b> <a href="{{route('proveedores.create')}}" type="button" target="_blank">Agregar proveedor</a></label>
                                <!--Input proveedor.-->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Buscar proveedor"
                                        aria-label="Buscar proveedor" aria-describedby="basic-addon2" v-model="txtProveedor" @keyup="apiSearchProveedores">
                                    {{-- <span class="input-group-text" id="basic-addon2" style="cursor: pointer;">Buscar</span> --}}
                                    <input type="hidden" class="form-control" id="proveedores_id" name="proveedores_id" :value="proveedorSelected.id">
                                    
                                </div>

                                <!--Desplegable / Listado de proveedores.-->
                                <div class="result shadow"
                                    style="position: absolute;z-index: 10;margin-top: 0px;margin-left: 0px; width: 32.5em; background:white; padding:2px;"
                                    v-if="arrayProveedores.length > 0">
                                    <ul class="list-group">
                                        <li v-for="proveedor in arrayProveedores" class="list-group-item"
                                            style="border-radius: 0px; border: none; cursor:pointer;" @click="selected(proveedor)">@{{ proveedor.proveedor }} - 
                                            <small v-if="proveedor.permite_credito" class="badge rounded-pill text-bg-primary">Si crédito</small>
                                            <small v-else class="badge rounded-pill text-bg-secondary">No crédito</small>

                                            <small>- @{{ (proveedor.dui) ? 'DUI: '+proveedor.dui : 'NRC: '+proveedor.nrc }}</small>
                                        </li>
                                    </ul>
                                </div>
                                    <div v-if="arrayProveedores.length ===0" class="result shadow" >
                                                @{{mensaje }}
                                            </div>

                                <div class="mb-3">
                                    <label for="fecha_factura" class="form-label">Fecha factura: </label>
                                    <input type="date" class="form-control" id="fecha_factura" name="fecha_factura" v-model="fecha_factura">
                                </div>
                                <div class="mb-3">
                                    {{-- <label for="fovial" class="form-label">Impuesto Fovial: </label>
                                    <input type="number" class="form-control" id="fovial" name="fovial" v-model="fovial" step="any" min="0.01" max="100"> --}}
                                    <x-input-number
                                        name="fovial"
                                        label="Fovial:"
                                        placeholder="0"
                                        min="0.01"
                                        max="100"
                                        val="{{ old('fovial') ?? '' }}"
                                        maxDigitos="4"
                                    />
                                </div>

                                <div class="mb-3">
                                    {{-- <label for="correlativo" class="form-label">Correlativo (32) caracteres alfanumericos: </label>
                                    <input type="text" class="form-control" placeholder="4NdXnP1D6RDbBT7L088zQFtnqoK6Ux5D" maxlength="32" id="correlativo" name="correlativo"> --}}
                                    <x-input-text name="correlativo" label="Correlativo (32) carácteres alfanuméricos:" val="{{ old('correlativo') ?? ''}}" placeholder="4NdXnP1D6RDbBT7L088zQFtnqoK6Ux5D" required maxlength="32"/>
                                </div>

                                <div class="mb-3">
                                    {{-- <label for="serie" class="form-label">Serie: </label>
                                    <input type="text" class="form-control" placeholder="Escriba aqui..." id="serie" name="serie"> --}}
                                    <x-input-text name="serie" label="Serie:" val="{{ old('serie') ?? ''}}" required maxlength="50"/>
                                </div>

                                <div class="mb-3">
                                    <x-input-select
                                        name="tipo_pagos_id"
                                        label="Tipo pago:"
                                        :data="$data['tipoPagos']"
                                        table="tipo_pagos"
                                        showName="tipo_pago"
                                        val="{{ $p[0]->tipo_pagos_id ?? '' }}"
                                    />
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button :disabled="txtProveedor.length <= 3 || fecha_factura.length == 0" type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        var compras = new Vue({
            el: '#appComprasActivas',
            data: {
                arrayCompras     : @json($p),
                arrayProveedores : [],

                proveedorSelected: [],

                tipoFiltrado     : 0,
                txtBusqueda      : '',
                txtProveedor     : '',
                mensaje: '',
                fecha_factura    : null,
            },
            methods: {
                async apiSearchProveedores(){
                    if(this.debouncedSearch){
                        clearTimeout(this.debouncedSearch);
                    }
                    this.debouncedSearch = setTimeout(async () => {
                    if(this.txtProveedor.trim().length > 2){
                        try {
                            const rs = await axios.post("{{ route('compras.api_search_proveedores') }}",{
                            txtProveedor: this.txtProveedor,
                        });
                        
                            this.arrayProveedores = rs.data.proveedores || [];
                            this.mensaje = rs.data.mensaje || '';
                    
                        } catch (error) {
                            console.error(error);
                        }
                    }
                    },100);
                },
                selected(s){
                    this.proveedorSelected = { id: s.id, proveedor: s.proveedor };
                    this.txtProveedor = this.proveedorSelected.proveedor;
                    this.arrayProveedores = [];
                    this.fechaActual();
                },
                fechaActual(){
                    const d = new Date();
                    const anio = d.getFullYear();
                    const mes  = ('0'+(d.getMonth()+1)).slice(-2);
                    const dia  = ('0'+d.getDate()).slice(-2);

                    const hoy = (anio)+'-'+(mes)+'-'+(dia);
                    this.fecha_factura = hoy;
                },
            },
            mounted(){

            },
            computed: {
                funcBuscarCompras(){
                    if(this.tipoFiltrado == 1){//CONTADO
                        return this.arrayCompras.filter((arrayCompras) => ((arrayCompras.tipo_pagos_id == 1) && (arrayCompras.relacion_proveedores.proveedor.includes(this.txtBusqueda.toUpperCase()))));
                    }
                    else if(this.tipoFiltrado == 2){//CREDITO
                        return this.arrayCompras.filter((arrayCompras) => ((arrayCompras.tipo_pagos_id == 2) && (arrayCompras.relacion_proveedores.proveedor.includes(this.txtBusqueda.toUpperCase()))));
                    }
                    else{//CAJA DE BUSQUEDA.
                        return this.arrayCompras.filter((arrayCompras) => arrayCompras.relacion_proveedores.proveedor.toLowerCase().includes(this.txtBusqueda.toLowerCase()));
                    }
                }
            }
        })
    </script>
@endsection
