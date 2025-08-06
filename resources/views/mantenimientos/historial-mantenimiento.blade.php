@extends('layouts.mantenimientos')
@section('css-mantenimiento')
<style>
    .custom-card {
    border: 1px solid #28a745;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /
    transition: transform 0.1s ease, box-shadow 0.3s ease;
}

.custom-card:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
}

.card-title {
    font-size: 16px;
    font-weight: bold;
}

.card-body {
    padding: 20px;
}

.text-uppercase {
    text-transform: uppercase;
}



.overflow-hidden {
    text-overflow: ellipsis;
    overflow: hidden;
}

.button-detail {
    background-color: #0056b3;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.button-detail:hover {
    background-color: #004494;
    transform: scale(1.05);
}

</style>
@endsection

@section('panel_mantenimiento')
    <section id="appHistorial" class="contenedor-mantenimientos">

        <div>
            <a class="text-uppercase"style="display: inline-flex; justify-content: center; align-items: center; padding: 10px 10px 10px 10px; background-color: light; color: #007bff; text-decoration: none; border: none; margin-left: 13px; font-size: 16px;"
                href="{{ route('mantenimientos.confirmacionMantenimiento') }}">
                <span class="mdi mdi-arrow-left-box icono"></span> Volver a mis asignaciones
            </a>
        </div>
        <h3 class="mb-4 m-2">Historial de asignaciones</h3>
        <div class="container">
            <div class="row">
                <div v-for="mt in mantenimientos" :key="mt.id"
                    class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-4 mb-4">
                    <div class="card custom-card border border-success">
                        <div class="card-body">
                            <span class="custom-checkbox"></span>
                            <h5 style="font-size: 14px;" class="card-title text-uppercase ct">
                                @{{ mt.tipo_mantenimientos.mantenimiento }} Habitacion @{{ mt.habitaciones.numero_habitacion }}
                            </h5>
                            <div class="mt-2 mb-2">
                                <span
                                    style="font-size: 12px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"
                                    class="text-uppercase ct">@{{ mt.observacion }}</span>
                            </div>
                            <div class="float-end text-end mt-5 ct">
                                <span class="ct">Hace:</span>
                                <span>@{{ mt.transcurrido }}</span>
                            </div>
                            <div class="text-start mt-5 me-2">
                                <button style="background-color: #0056b3; color: #fff; border: none; padding: 10px 20px;"
                                    type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#detalleModal" @click="mostrarDetalles(mt)">Detalle</button>
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
                            <span class="detalle-label">Se completo hace:</span>
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
    <script>
        const historialMantenimientos = new Vue({

            el: '#appHistorial',
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
                    this.detalleA = mantenimiento.transcurrido;
                    this.detalleBitacora = mantenimiento.bitacora_asignado;
                    this.mostrarModal = true;
                },


            },

        });
    </script>
@endsection
