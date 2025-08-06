@extends('layouts.mantenimientos')
@section('css-mantenimiento')
<style>
        .custom-message {
            position: fixed;
            z-index: 1500;
        }
        [v-cloak]{
            display: none;
        }
    </style>
@endsection
@section('panel_mantenimiento')
    <section id="appMantenimiento" class="contenedor-mantenimientos" v-cloak>
        <div class="col-8"v-if="message.type" :class="['alert', 'alert-' + message.type, 'custom-message']">
            @{{ message.message }}
        </div>
        <div class="container">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h3>Mantenimientos activos</h3>
                </div>

                <!--Tipos de filtrados.-->
                <div class="col-md-6">
                    <button type="button" class="btn btn-outline-danger position-relative btn-sm mb-1 "
                        style="margin-right: 5PX;" @click="tipoFiltrado = 1"> SIN ASIGNAR
                        <span v-if="tipoFiltrado == 1"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            @{{ funcBuscarMantenimientos.length }}
                        </span>
                    </button>

                    <button type="button" class="btn btn-outline-secondary position-relative btn-sm mb-1"
                        style="margin-right: 5PX;" @click="tipoFiltrado = 2">ASIGNADOS
                        <span v-if="tipoFiltrado == 2"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarMantenimientos.length }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-outline-info position-relative btn-sm mb-1"
                        style="margin-right: 5PX;" @click="tipoFiltrado = 3">INICIADOS
                        <span v-if="tipoFiltrado == 3"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">
                            @{{ funcBuscarMantenimientos.length }}
                        </span>
                    </button>
                    <button type="button" class="btn btn-outline-primary position-relative btn-sm mb-1"
                        @click="tipoFiltrado = 0">TODOS
                        <span v-if="tipoFiltrado == 0"
                            class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">
                            @{{ funcBuscarMantenimientos.length }}
                        </span>
                    </button>
                </div>
            </div>

            <!--Caja de busqueda.-->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3 row">
                        <label for="txtBusqueda" class="col-sm-2 col-form-label">Buscar:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control form-control-lg"
                                placeholder="Escriba el mantenimiento..." autocomplete="off" id="mantentimientos_id"
                                v-model="txtBusqueda">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!--Boton de nueva orden.-->
                <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-3 ">
                    <div class="card text-center shadow" style="border: none;">
                        <button type="button" class="btn btn-outline-primary btn-lg" style="height: 350px;"
                            data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <span class="mdi mdi-plus"></span> NUEVO MANTENIMIENTO/SERVICIOS
                        </button>
                    </div>
                </div>

                <!-- Bucle FOR de mantenimientos activos. -->
                <div v-for="(mantenimiento, index) in funcBuscarMantenimientos"
                            class="col-12 col-sm-12 col-md-6 col-lg-4 mb-3">
                            <div class="card border-success p-3 shadow custom-card h-100">
                                <div class="card-body">
                                    <h6 class="card-title text-danger">N° @{{ (index + 1) }}</h6>
                                    <small class="text-muted float-end">@{{ mantenimiento.fecha }}</small>
                                    <table class="table table-sm table align-middle user-select-none">
                                        <tbody>
                                            <tr>
                                                <td>Tipo de mantenimiento:</td>
                                                <td><b>@{{ mantenimiento.tipo_mantenimientos.mantenimiento }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>Número de habitación:</td>
                                                <td><b>@{{ mantenimiento.habitaciones.numero_habitacion }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>Estado:</td>
                                                <td><b>@{{ mantenimiento.estado }}</b></td>
                                            </tr>
                                            <tr>
                                                <td>Observación:</td>
                                                <td>
                                                    <span class="mdi mdi-details h6 text-muted pointer"
                                                        data-bs-toggle="popover"
                                                        :data-bs-content=" mantenimiento.observacion"
                                                        data-bs-placement="bottom" data-bs-trigger="click" data-bs-html="true"
                                                        data-bs-title="Observaciones del mantenimiento"> Detalles
                                                    </span>
                                                </td>
                                            </tr>
                                            @can('mantenimientos.asignar')
                                            <tr v-if="mantenimiento.estado === 'sin asignar'">
                                            <td colspan="2">
                                                <div class="d-flex align-items-center">
                                                    <span>Asignar al empleado:</span>
                                                    <select v-model="mantenimiento.selectedUser" @focus="getUsuarios(mantenimiento.tipo_mantenimientos.id)" class="form-select mx-2" style="width: auto;">
                                                        <option disabled >seleccione</option>
                                                        <option v-for="u in users" :value="u.id">@{{ u.name }}</option>
                                                    </select>
                                                </div>
                                            </td>
                                            </tr>
                                        @endcan
                                        <tr>
                                                <td>Asignado a:</td>
                                                <td><b>@{{ mantenimiento.asignado ? mantenimiento.asignado.name : 'Sin asignarse aun'}}</b></td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    @can('mantenimientos.asignar')
                                    <div v-if="mantenimiento.estado === 'sin asignar'" class="d-flex justify-content-end mt-3">
                                        <button type="button" class="btn btn-primary btn-sm text-uppercase"
                                                @click="asignarMantenimiento(mantenimiento)" :disabled="!mantenimiento.selectedUser">Asignar mantenimiento</button>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </div>

            </div>


        </div>
        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $p->links() }}
                            </div>
            </div>

        </div>

        <!--End row.-->
        </div>
        <!--End container.-->


        <!--Modal. falta realizar cambios pendientes-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropLabel"> <span class="mdi mdi-plus"></span> Nuevo Mantenimiento</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="mantenimientoForm" action="{{ route('mantenimientos.store') }}" method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">
                                <div class="mb-3">
                                    <x-input-select id="tipo_mantenimientos_id" name="tipo_mantenimientos_id"
                                        label="Tipo de mantenimientos:" :data="$data['tipo_mantenimientos']" table="tipo_mantenimientos"
                                        showName="mantenimiento" val="{{ $p[0]->tipo_mantenimientos_id ?? '' }}" />
                                </div>
                                <div class="mb-3">
                                    <x-input-select id="habitaciones_id" name="habitaciones_id"
                                        label="Numero de habitacion:" :data="$data['habitaciones']" table="habitaciones"
                                        showName="numero_habitacion" val="{{ $p[0]->habitaciones_id ?? '' }}" />
                                </div>
                                <div class="mb-3">
                                    <label for="observacion" class="form-label">Observacion:
                                    </label>
                                    <textarea class="form-control" id="observacion" name="observacion" v-model="observacion"></textarea>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button id="guardarBtn" type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const mantenimientos = new Vue({
            el: '#appMantenimiento',
            data: {
                mantenimientos: @json($p).data,
                users:[],
                tipoFiltrado: 0,
                txtBusqueda: '',
                observacion: null,
                selectedUser: '',
                load:'',
                message: {
                    type: '',
                    message: ''
                },

                filtradoEstados: {
                    1: mantenimiento => mantenimiento.estado === 'sin asignar',
                    2: mantenimiento => mantenimiento.estado === 'asignado',
                    3: mantenimiento => mantenimiento.estado === 'iniciado',
                    4: mantenimiento => mantenimiento.estado === 'completado',
                    5: mantenimiento => mantenimiento.estado === 'finalizado',
                    6: mantenimiento => mantenimiento.estado === 'incompleto',
                    7: mantenimiento => mantenimiento.estado === 'negado',
                },
            },
            methods: {
                asignarMantenimiento: function(mantenimiento) {

                    axios.post(
                            '{{ route('mantenimientos.asignarMantenimientos') }}', {
                                userId: mantenimiento.selectedUser,
                                mantenimientoId: mantenimiento.id,
                            })
                        .then((rs) => {
                            if (rs.data.success) {
                                mantenimiento.asignado_users_id = this.selectedUser;
                                location.reload();
                                this.setMessage('Mantenimiento asignado correctamente', 'success');
                            } else {
                                this.setMessage('Error al asignar el mantenimiento', 'error');
                            }
                        })
                        .catch((error) => {
                            console.error('Error al asignar el mantenimiento:', error);
                            this.setMessage('Error al asignar el mantenimiento', 'error')
                        });
                },
                 getUsuarios: function(tipoMantenimientoId) {
                    this.load = true;
                     axios.post('{{ route('mantenimientos.cargar_usuario') }}', {
                            id: tipoMantenimientoId,
                        })
                        .then(response => {
                            this.users = response.data.usuarios;
                            this.load =false;
                        })
                        .catch(error => {
                            console.error('Error al cargar usuarios asignados:', error);
                        });
                },
                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };
                    setTimeout(() => {
                        this.message = {};
                    }, 1 * 1000)
                },

            },
            mounted() {
                const formulario = document.getElementById('mantenimientoForm');

                formulario.addEventListener('submit', (event) => {
                    console.log('El formulario se ha enviado por completo.');
                });
                document.getElementById('observacion').value = '';
                document.getElementById('tipo_mantenimientos_id').value = '';
                document.getElementById('habitaciones_id').value = '';

            },
            computed: {
                funcBuscarMantenimientos() {
                    const filtradoE = this.filtradoEstados[this.tipoFiltrado];
                    const buscar = this.txtBusqueda.toLowerCase(); // Convertir búsqueda a minúsculas

                    return this.mantenimientos.filter(mantenimiento => {
                        const cumpleFiltroEstado = filtradoE ? filtradoE(mantenimiento) : true;
                        const tipoMantenimientoLower = mantenimiento.tipo_mantenimientos.mantenimiento
                            .toLowerCase();
                        const numeroHabitacionLower = mantenimiento.habitaciones.numero_habitacion
                            .toString().toLowerCase();

                        const cumpleFiltroTipo = tipoMantenimientoLower.includes(buscar);
                        const cumpleFiltroNumeroHabitacion = numeroHabitacionLower.includes(buscar);

                        return cumpleFiltroEstado && (cumpleFiltroTipo || cumpleFiltroNumeroHabitacion);
                    });

                }
            }
        })
    </script>
@endsection
