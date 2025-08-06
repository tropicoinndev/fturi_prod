@extends('layouts.mantenimientos')

@section('panel_mantenimiento')
    <section id="appPendientes" class="contenedor-mantenimientos">

        <div>
            <a class="text-uppercase"style="display: inline-flex; justify-content: center; align-items: center; padding: 10px 10px 10px 10px; background-color: light; color: #007bff; text-decoration: none; border: none; margin-left: 13px; font-size: 16px;"
                href="{{ route('mantenimientos.confirmacionMantenimiento') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a mis asignaciones
            </a>
        </div>
        <h3 class="mb-4 m-2">Mantenimientos Pendientes</h3>
        <div class="container">
            <div class="row">
                 <div v-if="mantenimientos.length === 0" class="col-12 text-start mt-5">
                <p>No hay mantenimientos pendientes en este momento.</p>
            </div>
                <div v-for="mt in mantenimientos" :key="mt.id"
                    class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                    <div class="card custom-card border border-success">
                        <div class="card-body" style=" max-width: 400px;">
                            <span class="custom-checkbox"></span>
                            <h5 style="font-size: 14px;" class="card-title text-uppercase ct">
                                @{{ mt.tipo_mantenimientos.mantenimiento }} Habitacion @{{ mt.habitaciones.numero_habitacion }}
                            </h5>
                            <div class="mt-2 mb-2">
                                <span
                                    style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                    class="text-uppercase ct">@{{ mt.observacion }}</span>
                            </div>

                            <div class="d-flex flex-column flex-sm-row align-items-start mt-5">
                                    <a href="{{ route('mantenimientos.confirmacionMantenimiento') }}"
                                    class="btn btn-primary me-2 mb-2 mb-sm-0">
                                        Pendientes
                                    </a>
                                    <button type="button"
                                            class="btn btn-primary mb-2 mb-sm-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#detalleModal"
                                            @click="mostrarDetalles(mt)" style="margin-right:10px;">
                                        Detalle
                                    </button>
                                    <div class="d-flex flex-column flex-sm-row align-items-center mt-2 mt-sm-0">
                                        <span class="me-2">Hace:</span>
                                        <span>@{{ mt.asignatime }}</span>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
                            <div class="col-md-12 text-center">
                                {{ $mantenimientos->links() }}
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
                    <div style="padding: 20px;" class="modal-body text-uppercase ">
                        <div class="detalle-item ">
                            <span class="detalle-label ">Mantenimiento:</span>
                            <p class="detalle-value">@{{ detalleM }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">numero de habitación:</span>
                            <p class="detalle-value">@{{ detalleH }}</p>
                        </div>
                        <div style="max-width: auto; max-height: auto; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; white-space: normal;"
                            class="detalle-item detalle-b">
                            <span class="detalle-label">Observaciones:</span>
                            <p class="detalle-value">@{{ detalleObservacion }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label">Asignado a:</span>
                            <p class="detalle-value">@{{ detalleAsignado }}</p>
                        </div>
                        <div class="detalle-item "
                            style="d-block: display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            <span class="detalle-label">Bitacora de asignado:</span>
                            <p class="detalle-value detalle-b"
                                style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                @{{ detalleBitacora }}</p>
                        </div>
                        <div class="detalle-item">
                            <span class="detalle-label ">Estado del mantenimiento:</span>
                            <p class="detalle-value">@{{ detalleEstado }}</p>
                        </div>
                        <div class="detalle-item ">
                            <span class="detalle-label">Se Inicio hace:</span>
                            <p class="detalle-value">@{{ detalleA }}</p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button style="background-color: #000; color: #fff; border: none; padding: 10px 20px;"
                            type="button" class="btn btn-secondary custom-button" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endsection
    @section('script')
    <script>
        const appPendientes = new Vue({

            el: '#appPendientes',
            data: {

                message: {},
                mantenimientos: @json($mantenimientos).data,
                detalleObservacion: '',
                detalleAsignado: '',
                detalleEstado: '',
                detalleA: '',
                detalleM: '',
                detalleH: '',
                detalleBitacora: '',
                mostrarModal: false,
            },

            methods: {
                mostrarDetalles(mantenimiento) {
                    this.detalleObservacion = mantenimiento.observacion;
                    this.detalleAsignado = mantenimiento.asignado.name;
                    this.detalleEstado = mantenimiento.estado;
                    this.detalleM = mantenimiento.tipo_mantenimientos.mantenimiento;
                    this.detalleH = mantenimiento.habitaciones.numero_habitacion;
                    this.detalleA = mantenimiento.asignatime;
                    this.detalleBitacora = mantenimiento.bitacora_asignado;

                    // Abre el modal
                    this.mostrarModal = true;
                },


            },

        });
    </script>
@endsection
