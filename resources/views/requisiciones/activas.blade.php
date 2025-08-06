@extends('layouts.app')

@section('content')
    <style>
        #contenedor {
            width: 90%;
            margin: auto;
        }

        @media print {
            #appRequisicion {
                width: 100% !important;
            }



            .noPrint {
                display: none;
            }

            .card {
                border: none;
                box-shadow: none;
                padding: 0;
            }

            #contenedor {
                width: 100% !important;
                margin: 0 auto !important;
                padding: 0 !important;
                border: none !important;
                box-shadow: none !important;
            }

            main {
                margin: 0 !important;
            }
        }
    </style>
    <div id="appRequisicion">
        <div class="card p-5 shadow" id="contenedor" style="min-height: 90vh;">
            <div class="row mb-3">
                <div class="col-12 h3">
                    ALERTA DE REQUISICIONES
                </div>
            </div>
            <div class="row mb-3 noPrint">
                <div class="col-12 mb-3">
                    Filtro
                    <button class="btn btn-light" type="button" onclick="window.print()">
                        <span class="mdi mdi-printer h5"></span>
                        Imprimir
                    </button>
                </div>
                <div class="col-12 mb-3">
                    <select class="form-select" name="" id="" v-model="bodegaSelected">
                        <option selected value="0">Todas las bodegas</option>
                        <option :value="b.id" v-for="b in bodegas">@{{ b.bodega }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-12 table-responsive">
                    <table class="table table-light">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Fecha</th>
                                <th>Detalle</th>
                                <th>Bodega solicitante</th>
                                <th>Bodega salida</th>
                                <th>Usuario solicitante</th>
                                <th>Productos</th>
                                <th class="noPrint">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="i in getData">
                                <td>
                                    @{{ i?.id }}
                                </td>
                                <td>
                                    @{{ i?.fecha }}
                                </td>
                                <td class="text-uppercase">
                                    <i>
                                        @{{ i?.solicitud ?? '-----' }}
                                    </i>
                                </td>
                                <td>
                                    @{{ i?.relacion_bodegas_entrada?.bodega }}
                                </td>
                                <td>
                                    @{{ i?.relacion_bodegas_salida?.bodega }}
                                </td>
                                <td>
                                    @{{ i?.relacion_user_creacion?.name }}
                                </td>
                                <td>
                                    @{{ i?.detalle?.length }}
                                </td>
                                <td class="noPrint">

                                    <button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                                        data-bs-target="#detalleRequi" aria-controls="detalleRequi"
                                        @click="setProductos(i.detalle)">
                                        Info
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="getData.length == 0">
                                <td colspan="8">
                                    No se encontraron registros
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="detalleRequi" aria-labelledby="detalleRequiLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="detalleRequiLabel">DETALLE DE REQUISICIONES</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <table class="table table-light">
                    <thead>
                        <tr>
                            <th>Cantidad</th>
                            <th>Producto</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr v-for="d in p">
                            <td>@{{ d.cantidad }}</td>
                            <td>@{{ d.productos.nombre }}</td>
                        </tr>
                        <tr v-if="!p || p.length == 0">
                            <td colspan="2">
                                Sin productos agregados
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    p: null,
                    data: @json($p),
                    bodegaSelected: 0,
                    bodegas: @json($bodegas),
                }
            },
            methods: {
                setProductos: function(i) {
                    console.log(i);
                    this.p = i;
                }
            },
            computed: {
                getData() {
                    if (this.bodegaSelected > 0) {
                        return this.data.filter(i => i.bodega_entrada_id == this.bodegaSelected);
                    }
                    return this.data;
                }
            }
        });
        app.mount("#appRequisicion")
    </script>
@endsection
