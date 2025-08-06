@extends('layouts.mantenimientos')
@section('css-mantenimiento')
    <style>
        @media (max-width: 767px) {


            .custom-card .card-title {
                font-size: 14px;
            }

            .custom-card .text-uppercase {
                font-size: 12px;
            }

            .custom-card-wrapper {
                width: 100%;
                margin: 0;
            }

            .custom-card .card-body {
                height: auto;
                width: 100%;
                padding: 15px;
            }

            .text-start {
                display: flex;
                flex-direction: row;
                align-items: flex-start;
            }

            .text-start button {
                margin-top: 10px;
                width: auto;
            }

            .custom-modal {
                width: 90%;
                margin: 0 auto;
            }

            .custom-modal-title {
                font-size: 18px;
            }

            .custom-modal-body {
                padding: 10px;
            }

            .button-container {
                display: inline-block;
                margin-right: 3px;
            }
        }



        .btn-asignado {
            background-color: #ffc107;
            color: #333;
            border-color: #ffc107;
        }

        .btn-iniciado {
            background-color: #17a2b8;
            color: #fff;
            border-color: #17a2b8;
        }

        .btn-completado {
            background-color: #28a745;
            color: #fff;
            border-color: #28a745;
        }

        .btn-pendiente {
            background-color: #007bff;
            color: #fff;
            border-color: #007bff;
        }

        .custom-card-wrapper {
            margin-bottom: 15px;
        }

        .card-body {
            padding: 15px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .custom-card .card-body .text-uppercase.ct {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .modal-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .custom-modal-header {
            background-color: #fff;
            color: #000;
            border-bottom: none;
        }

        .custom-modal-title {
            font-size: 20px;
            padding: 15px;
        }





        .custom-button:hover {
            background-color: #0056b3;
        }

        .custom-modal-footer {
            background-color: #f5f5f5;
            border-top: none;
            text-align: right;
        }

        .custom-modal {
            background-color: #fff;
        }

        .contenedor-mantenimientos {
            min-height: 91vh;
            display: flex;
            flex-direction: column;
        }

        .modal-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }



        #incompletadoModal .modal-dialog {
            max-width: 400px;
            max-height: 60px;
            margin: 5px auto;
        }

        #incompletadoModal .modal-content .custom-modal-title {
            padding: 0px;
            font-size: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #incompletadoModal .modal-footer {
            border-top: none;
            padding: 0;
        }

        .custom-message {
            position: fixed;
            z-index: 1500;
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('panel_mantenimiento')
    <div id="appConfirmacion" class=" contenedor-mantenimientos" v-cloak>
        <div class="row">
            <x-message></x-message>
        </div>
        <!-- Mensajes de alerta. -->
        <div class="col-8" v-if="message.type" :class="['alert', 'alert-' + message.type, 'custom-message']">
            @{{ message.message }}
        </div>
        <h1 class="m-2">Mis asignaciones</h1>
        <div class="button-container mb-4 m-2  ">
            <a
                class="btn btn-outline-secondary text-uppercase mb-1"href="{{ route('mantenimientos.mantenimientosPendientes') }}">
                Pendientes
            </a>
            <a class="btn btn-outline-secondary text-uppercase mb-1"
                href="{{ route('mantenimientos.historialMantenimiento') }}">
                Historial de asignaciones
            </a>

        </div>

        <div v-if="mostrarMensaje">
            <p>No tienes asignaciones por confirmar.</p>
        </div>
        <div class="container" v-else>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 custom-card-wrapper  " v-for="mt in mantenimientos"
                    :key="mt.id">
                    <div class="card custom-card border border-success">
                        <div class="card-body">
                            <div class="row ">
                                <h5 class="card-title text-uppercase " style="width:95%;">
                                    @{{ mt.id }} - @{{ mt.tipo_mantenimientos.mantenimiento }} Habitacion @{{ mt.habitaciones.numero_habitacion }}
                                </h5>
                            </div>
                            <div class=" float-end text-end mt-4  ct ">
                                <span class="">Hace:</span>
                                <span>@{{ mt.asignatime }} </span>
                            </div>
                            <div class="row">
                                <div class=" d-flex flex-wrap justify-content-between align-items-center w-100">
                                    <div class=" text-start  button-container float-start">
                                        <button v-if="mt.estado === 'iniciado'" type="button"
                                            class="btn btn-outline-success" data-bs-toggle="modal"
                                            data-bs-target="#observacionModal" @click="iniciarObservacion(mt)">
                                            Finalizar
                                        </button>
                                        <button v-if="mt.estado === 'iniciado'" type="button"
                                            class="btn btn-outline-warning" data-bs-toggle="modal"
                                            data-bs-target="#incompletadoModal"
                                            @click="prepararIncompletarMantenimiento(mt)">
                                            Incompleto
                                        </button>
                                        <button v-if="mt.estado === 'asignado' && mt.confirmacion_asignacion === null"
                                            type="button" class="btn btn-outline-danger"
                                            @click="confirmarMantenimiento(mt)">
                                            Confirmar
                                        </button>
                                        <button v-if="mt.confirmacion_asignacion !== null && mt.estado === 'asignado'"
                                            type="button" class="btn btn-outline-primary"
                                            @click="iniciarMantenimiento(mt)">
                                            Iniciar
                                        </button>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                            data-bs-target="#detalleModal" @click="mostrarDetalles(mt)">Detalle</button>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Modal para mostrar detalles del mantenimiento -->
        <div class="modal fade " id="detalleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header ">
                        <h5 class="modal-title text-uppercase custom-modal-title" id="exampleModalLabel">Detalles de los
                            mantenimientos</h5>
                    </div>
                    <div class="modal-body text-uppercase ">
                        <div class="detalle-item ">
                            <span class="detalle-label ">Mantenimiento:</span>
                            <p class="detalle-value">@{{ detalleM }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">numero de habitación:</span>
                            <p class="detalle-value">@{{ detalleH }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Observaciones:</span>
                            <p class="detalle-value">@{{ detalleObservacion }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Asignado a:</span>
                            <p class="detalle-value">@{{ detalleAsignado }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label ">Estado del mantenimiento:</span>
                            <p class="detalle-value">@{{ detalleEstado }}</p>
                        </div>
                        <div class="detalle-item ">
                            <span class="detalle-label">Hace:</span>
                            <p class="detalle-value">@{{ detalleA }}</p>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary custom-button" data-bs-dismiss="modal"
                            @click="cerrarModal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal de Observación  es para cuando se le de finalizar aparesca el modal de observaciones de quien resolvio el mantenimiento por el momento bitacora puede ir nulo -->
        <div id="observacionModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title text-uppercase custom-modal-title" id="exampleModalLabel">Agregar Observación
                        </h5>
                    </div>
                    <div class="modal-body text-uppercase custom-modal-body">
                        <div class="form-group">
                            <label for="bitacora_asignado">Observación:</label>
                            <textarea v-model="bitacora_asignado" class="form-control" id="bitacora_asignado" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary custom-button" type="button"
                            class="btn btn-secondary custom-button" data-bs-dismiss="modal"
                            @click="cerrarModal">Cancelar</button>
                        <button type="button" class="btn btn-primary custom-button"
                            @click="finalizarConObservacion(mantenimientoSeleccionado)">Finalizar</button>
                    </div>
                </div>
            </div>
        </div>
        <!--realize dos modales estuve analizando varias formas posibles para reutilizar uno y el detalles es que en uno bitacora es obligatorio y po r ello cree los dos -->
        <div class="modal fade" id="incompletadoModal" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header custom-modal-header">
                        <h5 class="modal-title text-uppercase custom-modal-title" id="exampleModalLabel">Agregar
                            Observación
                        </h5>
                    </div>
                    <div class="modal-body text-uppercase custom-modal-body">
                        <div class="form-group">
                            <label for="bitacora_asignado">Observación:</label>
                            <textarea v-model="bitacora_asignado" class="form-control" id="bitacora_asignado" rows="3"></textarea>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary custom-button"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary custom-button"
                            data-bs-dismiss="modal":disabled="bitacora_asignado === null || bitacora_asignado.trim() === ''"
                            @click="incompletarMantenimiento(mantenimientoSeleccionado ,bitacora_asignado )">Incompletar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        const confirmacionesMantenimientos = new Vue({

            el: '#appConfirmacion',
            data: {

                message: {},
                mantenimientos: @json($mantenimientos),
                detalleObservacion: '',
                detalleAsignado: '',
                detalleEstado: '',
                detalleA: '',
                detalleM: '',
                detalleH: '',
                mostrarModal: false,
                mostrarModalObservacion: false,
                mantenimientoSeleccionado: null,
                bitacora_asignado: null,
                mostrarMensaje: false


            },

            methods: {
                prepararIncompletarMantenimiento(mantenimiento) {
                    this.mantenimientoSeleccionado = mantenimiento;
                    this.bitacora_asignado = '';

                },
                iniciarObservacion(mantenimiento) {
                    this.mantenimientoSeleccionado = mantenimiento;
                    this.bitacora_asignado = '';
                    this.mostrarModalObservacion = true;
                },

                async finalizarConObservacion(mantenimiento) {
                    try {
                        await this.finalizarMantenimiento(mantenimiento, this.bitacora_asignado);

                    } catch (error) {
                        console.error('Error en la gestión del mantenimiento:', error);
                        this.setMessage('Error en la gestión del mantenimiento', 'danger');
                    }
                },
                realizarAccion(mantenimiento) {
                    if (mantenimiento.estado === "asignado") {
                        if (mantenimiento.estado === "asignado" && mantenimiento.confirmacion_asignacion !== null) {
                            // Si hay confirmación, cambia el estado a "iniciado"
                            this.iniciarMantenimiento(mantenimiento);
                        } else {
                            // Si no hay confirmación, realiza la confirmación
                            this.confirmarMantenimiento(mantenimiento);
                        }
                    } else if (mantenimiento.confirmacion_asignacion !== null) {
                        // Si el estado no es "asignado" pero hay confirmación, inicia el mantenimiento
                        this.iniciarMantenimiento(mantenimiento);
                    } else if (mantenimiento.estado === "iniciado") {
                        this.iniciarObservacion(mantenimiento);
                    } else {
                        console.error(`Estado no reconocido: ${mantenimiento.estado}`);
                    }
                },

                mostrarDetalles(mantenimiento) {
                    this.detalleObservacion = mantenimiento.observacion;
                    this.detalleAsignado = mantenimiento.asignado.name;
                    this.detalleEstado = mantenimiento.estado;
                    this.detalleM = mantenimiento.tipo_mantenimientos.mantenimiento;
                    this.detalleH = mantenimiento.habitaciones.numero_habitacion;
                    this.detalleA = mantenimiento.asignatime;
                    this.mostrarModal = true;
                },
                cerrarModal() {

                    this.mostrarModal = false;
                },
                obtenerTextoAccion(estado, confirmacion) {
                    const acciones = {
                        asignado: confirmacion ? 'Iniciar' : 'Confirmar',
                        iniciado: 'Finalizar',
                        'sin asignar': 'Asignarme',
                    };

                    return acciones[estado] || 'otros';

                },
                confirmarMantenimiento(mantenimiento) {
                    axios.post('{{ route('mantenimientos.confirmarEstados') }}', {
                            mantenimiento_id: mantenimiento.id,
                        })
                        .then(response => {
                            if (response.status === 200) {
                                this.setMessage('Mantenimiento confirmado con éxito', 'success');
                                mantenimiento.confirmacion_asignacion = response.data.confirmacion;
                            } else {
                                this.setMessage('Error al confirmar el mantenimiento', 'danger');
                            }
                        })
                        .catch(error => {
                            console.error('Error en la gestión del mantenimiento:', error);
                            this.setMessage('Error en la gestión del mantenimiento', 'danger');
                        });

                },
                async iniciarMantenimiento(mantenimiento) {
                    try {
                        const response = await axios.post(
                            '{{ route('mantenimientos.iniciarMantenimientos') }}', {
                                mantenimiento_id: mantenimiento.id,
                                estado: 'iniciado'
                            });

                        const {
                            type,
                            message
                        } = response.data;

                        if (type === 'success') {
                            mantenimiento.estado = 'iniciado';

                        }

                        this.setMessage(message, type);
                    } catch (error) {
                        console.error('Error en la gestión del mantenimiento:', error);
                        this.setMessage('Error en la gestión del mantenimiento', 'danger');
                    }
                },
                async finalizarMantenimiento(mantenimiento, bitacora_asignado) {
                    try {
                        const response = await axios.post('{{ route('mantenimientos.finalizarEstado') }}', {
                            mantenimiento_id: mantenimiento.id,
                            estado: 'completado',
                            bitacora_asignado: bitacora_asignado,
                        });

                        if (response.status === 200) {
                            mantenimiento.estado = 'completado';
                            this.setMessage('Mantenimiento finalizado con éxito', 'success');
                            // Verificar si todavía hay asignaciones pendientes
                            const asignacionesPendientes = this.mantenimientos.some(mt => mt.estado !==
                                'completado');
                            if (!asignacionesPendientes) {
                                this.mostrarMensaje = true;
                            }
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            this.setMessage('Error al finalizar el mantenimiento', 'danger');
                        }
                    } catch (error) {
                        console.error('Error en la gestión del mantenimiento:', error);
                        this.setMessage('Error en la gestión del mantenimiento', 'danger');
                    }
                },
                async incompletarMantenimiento(mantenimiento, bitacora_asignado) {

                    try {
                        const response = await axios.post(
                            '{{ route('mantenimientos.mantenimientoIncompletado') }}', {
                                mantenimiento_id: mantenimiento.id,
                                estado: 'incompleto',
                                bitacora_asignado: bitacora_asignado,
                            });

                        if (response.status === 200) {
                            mantenimiento.estado = 'incompleto';
                            this.setMessage('Mantenimiento incompletado ', 'success');
                            // Verificar si todavía hay asignaciones pendientes
                            const asignacionesPendientes = this.mantenimientos.some(mt => mt.estado !==
                                'completado');
                            if (!asignacionesPendientes) {
                                this.mostrarMensaje = true;
                            }
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            this.setMessage('Error al incompletar el mantenimiento', 'danger');
                        }
                    } catch (error) {
                        console.error('Error en la gestión del mantenimiento:', error);
                        this.setMessage('Error en la gestión del mantenimiento', 'danger');
                    }
                },

                setMessage(m, t) {
                    this.message = {
                        'message': m,
                        'type': t,
                    };

                    setTimeout(() => {
                        this.message = {};
                        this.txtHabitacion = '';
                    }, 1 * 1000)
                },


            },

            computed: {}
        });
    </script>
@endsection
