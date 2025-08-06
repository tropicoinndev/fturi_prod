@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appComprasActivas">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>historial de compras</h3>
                </div>

                <!--Tipos de filtrados.-->
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-success position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">CONTADO
                        <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            @{{ funcBuscarCompras.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 2">CREDITO
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

            <!--Mensaje de alerta.-->
            <div class="col-12">
                <x-message></x-message>
            </div>

            <div class="row">

                <!--Bucle FOR de compras activas.-->
                <div v-for="({ id, relacion_proveedores, relacion_usuarios, relacion_tipo_pagos, fecha_factura, correlativo, completado }, index) in funcBuscarCompras" class="col-12 col-sm-6 col-md-6 col-lg-4 col-xl-4 mb-4">
                    <div class="card border-success p-3 shadow h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12" >
                                    <h5 class="card-title placeholder-glow  mb-1">@{{ relacion_proveedores.proveedor }}</b></h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-danger bold"><b> @{{ fecha_factura }}</b></small>
                                </div>
                                <div class="col-md-6 text-end">
                                    <small class="text-danger"><b>N° @{{ (index + 1) }}</b></small>
                                </div>
                            </div>

                            <table class="table table-sm table align-middle mt-3">
                                <tbody>
                                    <tr>
                                        <td colspan="2"><b>Correlativo:</b> @{{ correlativo }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Ingresado por:</b></td>
                                        <td>@{{ relacion_usuarios.name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Tipo de pago:</b></td>
                                        <td>@{{ relacion_tipo_pagos.tipo_pago }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Completada:</b></td>
                                        <td>@{{ (completado) ? 'Si' : 'No' }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="text-end">
                                <a :href="'/lotes/'+id" class="btn btn-success btn-sm mb-1">Detalles</a>
                                <a v-if="!completado" :href="'/compras/confirm/'+id" class="btn btn-danger btn-sm mb-1">Anular</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $compras->links() }}
                            </div>
                </div><!--End row.-->
        </div><!--End container.-->
    </div>

    <script>
        var compras = new Vue({
            el: '#appComprasActivas',
            data: {
                arrayCompras     : @json($compras).data,
                tipoFiltrado     : 0,
                txtBusqueda      : '',
                txtProveedor     : '',

                fecha_factura    : null,
            },
            methods: {
            },
            mounted(){

            },
            computed: {
                funcBuscarCompras(){
                    const buscar = this.txtBusqueda.toLowerCase();
                     return this.arrayCompras.filter(compra => {
                            const proveedor = compra.relacion_proveedores.proveedor.toLowerCase();
                            const correlativo = compra.correlativo.toLowerCase();
                            const usuario = compra.relacion_usuarios.name.toLowerCase();
                            const pago = compra.relacion_tipo_pagos.tipo_pago.toLowerCase();
                            switch (this.tipoFiltrado) {
                                case 1: // CONTADO
                                    return compra.tipo_pagos_id === 1;
                                case 2: // CRÉDITO
                                    return compra.tipo_pagos_id === 2;
                                default: // CAJA DE BUSQUEDA
                                    return [proveedor, correlativo, usuario, pago].some(item => item.includes(buscar));
                            }
                        });
                }
            }
        })
    </script>
@endsection
