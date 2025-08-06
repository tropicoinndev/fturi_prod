@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appRequisicionesHistorial">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Historial de requisiciones de todas las bodegas</h3>
                </div>

                <!--Tipos de filtrados-->
                <div class="col-md-6">
                    <!-- <button type="button" class="btn btn-outline-success position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">ACTIVA
                        <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button> -->

                    <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 2">COMPLETA
                        <span v-if="tipoFiltrado == 2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-danger position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 3">AUTORIZADA
                        <span v-if="tipoFiltrado == 3" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-primary position-relative btn-sm" @click="tipoFiltrado = 0">TODOS
                        <span v-if="tipoFiltrado == 0" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            @{{ funcBuscarRequisiciones.length }}
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
                                placeholder="Escriba nombre de usuario, bodega y requisicion para buscar..." autocomplete="off" v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
            </div>

            {{-- @{{ arrayRequisiciones }} --}}
            <div class="row">

                <!--Bucle FOR de compras activas.-->
                <div v-for="({id,cid,fecha,solicitud,relacion_bodegas_salida,relacion_bodegas_entrada,relacion_user_autorizacion,relacion_user_creacion, estado}, index) in funcBuscarRequisiciones"
                    class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3 " >
                    <div class="card border-success p-3 shadow h-100" >
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="card-title placeholder-glow"><b>@{{ relacion_user_creacion.name.toUpperCase() }}</b></h6>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted float-end">@{{ fecha }}</small>
                                </div>
                            </div>

                            <table class="table table-sm table align-middle  mt-3">
                                <tbody >
                                    <tr >

                                        <td colspan="2" >Solicitud: <b>@{{ solicitud }}</b></td>
                                    </tr>
                                    <tr>
                                        <td>Bo. salida: </td>
                                        <td><small><b>@{{ relacion_bodegas_salida.bodega }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Bo. entrada: </td>
                                        <td><small><b>@{{ relacion_bodegas_entrada.bodega }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Autorizacion: </td>
                                        <td><small><b>@{{ (relacion_user_autorizacion) ? relacion_user_autorizacion.name : '' }}</b></small></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="float-end">
                                <template v-if="estado === 2">
                                    <a @click="mostrarDetalleRequisicion(id)" data-bs-toggle="modal" data-bs-target="#modalDetalleRequisicion" type="button" class="btn btn-outline-success btn-sm mb-1">Detalles</a>
                                </template>
                                <template v-else-if="estado === 3">
                                    <a :href="'/existencias/'+cid" class="btn btn-outline-success btn-sm mb-1">Detalles</a>
                                     <a @click="imprimirRequisicion(id)" class="btn btn-outline-primary btn-sm mb-1">Imprimir</a>
                                </template>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $p->links() }}
                            </div>
        </div><!--End row.-->
        </div><!--End container-->
            <div class="modal fade" id="modalDetalleRequisicion" tabindex="-1" role="dialog" aria-labelledby="modalDetalleRequisicionLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalDetalleRequisicionLabel">Detalle de la Requisición</h5>

                        </div>
                        <div class="modal-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Cantidad</th>
                                        <th>Producto</th>
                                        <th>Precio de costo</th>
                                        <th>Fecha de Vencimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="detalle in detalleRequisicion">
                                        <td>@{{ detalle.cantidad }}</td>
                                        <td>@{{ detalle.relacion_productos.nombre }}</td>
                                        <td>$ @{{ detalle.relacion_existencias.precio_costo ?? 'no dispone' }}</td>
                                        <td>@{{  detalle.relacion_existencias.vencimiento }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button @click="cerrarModal()" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <script>
        var requisiciones = new Vue({
            el: '#appRequisicionesHistorial',
            data: {
                arrayRequisiciones: @json($p).data,
                tipoFiltrado: 0,
                txtBusqueda: '',
                detalleRequisicion:'',
                 showModal: false,
            },
            methods: {
                cerrarModal() {
                    location.reload(); // Cierra el modal
                },
                    imprimirRequisicion(id) {
                    axios.post("{{ route('requisiciones.previsualizarRequisicion') }}", {
                            id: id,
                        })
                        .then(rs => {
                            if (rs.data.type === 'success') {
                                 const redirectUrl = rs.data.redirect_url;
                                if (redirectUrl) {
                                    window.location.href = redirectUrl;
                                } else {
                                    this.setMessage('Autorizacion no completada exitosamente', 'danger');
                                }
                            } else {
                                this.setMessage(rs.data.msj, rs.data.type);
                            }
                        })
                        .catch(error => {
                            this.setMessage('Error al traer la redirect url: ' + error);
                        });
                },
                mostrarDetalleRequisicion(id) {
                    axios.post("{{ route('requisiciones.mostrarDetalle') }}", {
                        id: id,
                    })
                    .then(rs => {
                        if (rs.data.type === 'success') {
                            this.detalleRequisicion = rs.data.detalleRequisicion;

                        } else {
                            this.setMessage(rs.data.msj, rs.data.type);
                        }
                    })
                    .catch(error => {
                        this.setMessage('Error al traer el detalle: ' + error);
                    });
                },
            },
            mounted(){

            },
            computed: {
                funcBuscarRequisiciones(){
                    switch(this.tipoFiltrado){
                        // case 1://Activa
                        //     return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 1) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        // break;
                        case 2://Completa
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 2) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        break;
                        case 3://Autorizada
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 3) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        break;
                        case 4://Eliminada
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 4) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        break;
                        default://Caja de busqueda / Todas
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => arrayRequisiciones.relacion_user_creacion.name.toLowerCase().includes(this.txtBusqueda.toLowerCase()) ||
                             arrayRequisiciones.relacion_bodegas_entrada.bodega.toLowerCase().includes(this.txtBusqueda.toLowerCase()) ||
                             arrayRequisiciones.relacion_bodegas_salida.bodega.toLowerCase().includes(this.txtBusqueda.toLowerCase()) || (arrayRequisiciones.solicitud && arrayRequisiciones.solicitud.toLowerCase().includes(this.txtBusqueda.toLowerCase())));
                        break;
                    }
                }
            }
        });
    </script>

@endsection
