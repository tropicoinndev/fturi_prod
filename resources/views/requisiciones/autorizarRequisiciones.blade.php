@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appRequisicionesAutorizar">
        <div class="alert show message" :class="'alert-' + message.type" usuario="alert"
            v-show="message.message && message.type">
            <strong>@{{ message.message }}</strong>
        </div>
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Solicitudes de requisiciones para autorizar</h3>
                </div>

                <!--Tipos de filtrados-->
                <div class="col-md-6">

                    <button type="button" class="btn btn-outline-secondary position-relative btn-sm"
                        style="margin-right: 10px;" @click="tipoFiltrado = 2">COMPLETADAS
                        <span v-if="tipoFiltrado == 2"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-danger position-relative btn-sm"
                        style="margin-right: 10px;" @click="tipoFiltrado = 3">AUTORIZADAS
                        <span v-if="tipoFiltrado == 3"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-outline-warning position-relative btn-sm"
                        style="margin-right: 10px;" @click="tipoFiltrado = 4">NEGADAS
                        <span v-if="tipoFiltrado == 4"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            @{{ funcBuscarRequisiciones.length }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-outline-primary position-relative btn-sm"
                        @click="tipoFiltrado = 0">TODOS
                        <span v-if="tipoFiltrado == 0"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
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
                                placeholder="Escriba nombre de usuario para buscar..." autocomplete="off"
                                v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
            </div>

            {{-- @{{ arrayRequisiciones }} --}}
            <div class="row">
                <!--Bucle FOR de Requisiciones activas.-->
                <div v-for="({id,fecha,estado,solicitud,relacion_bodegas_salida,relacion_user_autorizacion,relacion_user_creacion}, index) in funcBuscarRequisiciones"
                    class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3">
                    <div class="card border-success p-3 shadow" style="height: 280px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 text-uppercase">
                                    <h6 class="card-title placeholder-glow"><b>@{{ relacion_user_creacion.name }}</b></h6>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted float-end">@{{ fecha }}</small>
                                </div>
                            </div>

                            <table class="table table-sm table align-middle user-select-none mt-3 ">
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
                                        <td class="text-uppercase"><small><b>@{{ (relacion_user_autorizacion) ? relacion_user_autorizacion.name: '' }}</b></small></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="float-end">
                                <button @click="previsualizar(id)" class="btn btn-outline-success btn-sm mb-1"
                                    type="button">Previsualizar</button>
                                <button @click="mostrarDetalleRequisicion(id)" type="button"
                                    class="btn btn-outline-success btn-sm mb-1" data-bs-toggle="modal"
                                    data-bs-target="#modalDetalleRequisicion">Detalle</button>

                                <a v-if="estado !== 4" @click="mostrarModalIngresar(id)"data-bs-toggle="modal" data-bs-target="#modalIngresar"
                                    type="button" class="btn btn-outline-success btn-sm mb-1">Autorizar</a>

                                <a v-if="estado !== 4" @click="mostrarModalNegar(id)"data-bs-toggle="modal" data-bs-target="#modalNegar"
                                    type="button" class="btn btn-outline-success btn-sm mb-1">Negar</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $requisiciones->links() }}
                            </div>
                </div>
            <!--End row.-->
        </div><!--End container-->
        <div class="modal fade" id="modalDetalleRequisicion" tabindex="-1" role="dialog"
            aria-labelledby="modalDetalleRequisicionLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalDetalleRequisicionLabel">Detalle de la Requisición</h5>

                    </div>
                    <div class="modal-body">
                        <p  v-for="d in detalleRequisicion" v-if="d.relacion_requisiciones.estado === 4" class="badge bg-danger">
                                Observación de Negación: @{{ d.relacion_requisiciones.observacion_negacion ?? '' }}
                            </p>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Precio de costo</th>
                                    <th>Fecha de Vencimiento</th>
                                </tr>
                            </thead>
                            <tbody v-for="detalle in detalleRequisicion">
                                <tr >

                                    <td>@{{ detalle.cantidad }}</td>
                                    <td>@{{ detalle.relacion_productos.nombre }}</td>
                                    <td>$ @{{ detalle.relacion_existencias.precio_costo ?? 'no dispone' }}</td>
                                    <td>@{{ detalle.relacion_existencias.vencimiento ?? 'no vence' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button @click="cerrarModal()" type="button" class="btn btn-secondary"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- modal para para ingresar contrasena para autorizaar -->
    <div v-if="showModalIngresar" class="modal fade" id="modalIngresar" tabindex="-1" role="dialog"
        aria-labelledby="modaLIngresarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalIngresarLabel">Autorizar</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('requisiciones.autorizar') }}" method="post">
                        @csrf
                        <div class="mb-3 row">
                            <input type="hidden" name="requisicionId" v-model="requisicionId">
                            <label for="password" class="col-sm-2 col-form-label">Contraseña:</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" name="password" v-model="password"
                                    placeholder="Ingrese su contraseña" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" @click="cerrarModal()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> -->
                            <button type="submit" class="btn btn-success">Autorizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- modal para negacion de requisicion  -->
    <div v-if="showModalNegar" class="modal fade" id="modalNegar" tabindex="-1" role="dialog"
        aria-labelledby="modaLIngresarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNegarLabel">Negar</h5>
                </div>
                <div class="modal-body">
                    <form action="{{ route('requisiciones.negar') }}" method="post">
                        @csrf
                        <div class="mb-3 row">
                            <input type="hidden" name="requisicion" v-model="requisicion">
                            <label for="password" class="col-sm-2 col-form-label">Observacion:</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" name="observacionNegacion" v-model="observacionNegacion" rows="3" maxlength="200"
                                        placeholder="Ingrese sus observaciones" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" @click="cerrarModal()" class="btn btn-secondary" data-dismiss="modal">Cancelar</button> -->
                            <button type="submit" class="btn btn-success">Negar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        var requisiciones = new Vue({
            el: '#appRequisicionesAutorizar',
            data: {
                arrayRequisiciones: @json($requisiciones).data,
                detalleRequisicion: '',
                tipoFiltrado: 0,
                txtBusqueda: '',
                message: {},
                showModal: false,
                password: '',
                observacionNegacion: '',
                showModalIngresar: false,
                showModalNegar: false,
                requisicionId: '',
                requisicion: '',
            },
            methods: {
                cerrarModal() {
                    location.reload();
                },

                mostrarModalIngresar(id) {
                    this.password = '';
                    this.requisicionId = id;
                    this.showModalIngresar = true;
                    document.querySelector('input[name="requisicionId"]').value = id;

                },
                mostrarModalNegar(id) {
                    this.password = '';
                    this.requisicionId = id;
                    this.showModalIngresar = true;
                    document.querySelector('input[name="requisicion"]').value = id;

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
                previsualizar(id) {
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

                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 3 * 1000)
                },
            },
            mounted() {

            },
            computed: {
                funcBuscarRequisiciones() {
                    switch (this.tipoFiltrado) {
                        case 1: //Activa
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones
                                .estado == 1) && (arrayRequisiciones.relacion_user_creacion.name
                                .includes(this.txtBusqueda.toUpperCase()))));
                            break;
                        case 2: //Completa
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones
                                .estado == 2) && (arrayRequisiciones.relacion_user_creacion.name
                                .toLowerCase()
                                .includes(this.txtBusqueda.toLowerCase()))));
                            break;
                        case 3: //Autorizada
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones
                                .estado == 3) && (arrayRequisiciones.relacion_user_creacion.name
                                .includes(this.txtBusqueda.toUpperCase()))));
                            break;
                        case 4: //Eliminada
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => ((arrayRequisiciones
                                .estado == 4) && (arrayRequisiciones.relacion_user_creacion.name
                                .toLowerCase()
                                .includes(this.txtBusqueda.toLowerCase()))));
                            break;
                        default: //Caja de busqueda / Todas
                            return this.arrayRequisiciones.filter((arrayRequisiciones) => arrayRequisiciones
                                .relacion_user_creacion.name.toLowerCase().includes(this.txtBusqueda
                                    .toLowerCase()));
                            break;
                    }
                }
            }
        });
    </script>
@endsection
