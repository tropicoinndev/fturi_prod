@extends('layouts.bodegas')
@section('panel_bodega')
    <div id="appRequisicionesActivas">
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Requisiciones activas</h3>
                </div>

                <!--Tipos de filtrados-->
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-success position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 1">ACTIVA
                        <span v-if="tipoFiltrado == 1" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>

                    <!-- <button type="button" class="btn btn-outline-secondary position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 2">COMPLETA
                        <span v-if="tipoFiltrado == 2" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button> -->

                    <button type="button" class="btn btn-outline-danger position-relative btn-sm" style="margin-right: 10px;" @click="tipoFiltrado = 4">NEGADAS
                        <span v-if="tipoFiltrado == 4" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
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
                                placeholder="Escriba nombre de usuario para buscar..." autocomplete="off" v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
            </div>

            {{-- @{{ arrayRequisiciones }} --}}
            <div class="row">
                <!--Boton de nueva orden.-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                    <div class="card text-center shadow" style="border: none;">
                        <button type="button" class="btn btn-outline-primary btn-lg" style="height: 300px;" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <span class="mdi mdi-plus"></span> NUEVA REQUISICION
                        </button>
                    </div>
                </div>

                <!--Bucle FOR de compras activas.-->
                <div v-for="({id,cid,fecha,solicitud,estado,relacion_bodegas_salida,relacion_user_autorizacion,relacion_user_creacion,observacion_negacion}, index) in funcBuscarRequisiciones"
                    class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3 " >
                    <div class="card border-success p-3 shadow " style="height: 300px;">
                        <div class="card-body ">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="card-title placeholder-glow"><b>@{{ relacion_user_creacion.name.toUpperCase() }}</b></h6>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted float-end">@{{ fecha }}</small>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table align-middle user-select-none mt-3  ">
                                <tbody>
                                    <tr>
                                        <td>Solicitud:</td>
                                        <td><small><b>@{{ solicitud }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Bo. salida: </td>
                                        <td><small><b>@{{ relacion_bodegas_salida.bodega }}</b></small></td>
                                    </tr>
                                    <tr>
                                        <td>Autorizacion: </td>
                                        <td><small><b>@{{ (relacion_user_autorizacion) ? relacion_user_autorizacion.name : '' }}</b></small></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="ct mb-1" v-if="estado == 4" style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 4; -webkit-box-orient: vertical; overflow: hidden;">
                                <strong>Observación:</strong>

                                <span class="badge bg-danger"  style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: auto;">@{{ observacion_negacion }}</span>
                            </div>
                            <div class="float-end">
                                <a :href="'/existencias/'+cid" type="button" class="btn btn-outline-success btn-sm mb-1">Detalles</a>
                                <a :href="'/requisiciones/confirm/'+id" v-if="" type="button" class="btn btn-outline-danger btn-sm mb-1">Eliminar</a>
                            </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div><!--End row.-->
        </div><!--End container-->


        <!--Modal.-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Nueva requisicion</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('requisiciones.store') }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <input type="hidden" class="form-control" name="bodega_entrada_id" value="{{ session('bodega')->id }}">

                            <div class="mb-3">
                                <label for="bodega_salida_id" class="form-label">Bodega de salida:</label>
                                <select class="form-select" name="bodega_salida_id" id="bodega_salida_id">
                                    <option selected disabled>--Seleccione---</option>
                                    @foreach ($data['bodegaUsers'] as $item)
                                        @if(session('bodega')->id != ($item->bodega ? $item->id : null))
                                            <option value="{{ $item->id ?? '' }}">{{ $item->bodega ?? '' }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <x-input-text-area name="solicitud" label="Describa la solicitud:" rows="2"/>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>

        var requisiciones = new Vue({
            el: '#appRequisicionesActivas',
            data: {
                arrayRequisiciones: @json($p),
                tipoFiltrado: 0,
                txtBusqueda: '',
            },
            methods: {

            },
            mounted(){
            },
            computed: {
                funcBuscarRequisiciones(){
                    switch(this.tipoFiltrado){
                        case 1://Activa
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 1) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        break;
                        // case 2://Completa
                        //     return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 2) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        // break;
                        // case 3://Autorizada
                        //     return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 3) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        // break;
                        case 4://Eliminada
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones.estado == 4) && (arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()))));
                        break;
                        default://Caja de busqueda / Todas
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => arrayRequisiciones.relacion_user_creacion.name.includes(this.txtBusqueda.toUpperCase()));
                        break;
                    }
                }
            }
        });
    </script>

@endsection
