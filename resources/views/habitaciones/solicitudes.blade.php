@extends('layouts.hab')

@section('content-hab')
    <div class="container" id="appEstadoHabitaciones">
        <div class="card p-4" style="min-height: 85vh;">
            <div class="card-body">
                <h5 class="card-title">Solicitudes de cambios en habitaciones</h5>
                <div class="card-text">
                    <div class="row">
                        <div class="col-12 mb-4 mt-4">
                            <x-message />
                            <a class="btn btn-primary" href="{{ route('habitaciones.cambio_estado_crear') }}"
                                role="button">Agregar
                                solicitud</a>
                        </div>
                        @forelse ($data as $d)
                            <div class="col-12 col-lg-4 mb-4">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <h4 class="card-title">Habitacion {{ $d->habitacion->numero_habitacion }}</h4>
                                        <p class="card-text">
                                            Cambio solicitado: {{ $d->estado->estado_habitacion }}
                                        </p>
                                        <p class="card-text">
                                            {{ $d->usuario->user }} ·
                                            <small>
                                                {{ $d->created_at }}
                                            </small>
                                        </p>
                                        <confirmacion id="{{ $d->cid }}"
                                            url="{{ route('habitaciones.cambio_estado_confirmar') }}"
                                            reenvio="{{ route('habitaciones.cambio_estado_reenviar', ['id' => $d->cid]) }}"
                                            token="{{ csrf_token() }}" />

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-success" role="alert">
                                    Aun no hay nada para mostrar aquí. Aquí aparecerán las solicitudes de cambios pendientes
                                    de completar.
                                </div>
                            </div>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script-hab')
    <script type="module">
        var app = window.appVue();
        app.component('confirmacion', component.confirmacion);
        app.mount("#appEstadoHabitaciones");
    </script>
@endsection
