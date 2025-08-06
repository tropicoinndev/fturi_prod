@extends('layouts.hab')

@section('content-hab')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Solicitud de cambio en habitaciones</h5>
                <p class="card-text">
                <form action="{{ route('habitaciones.cambio_estado_store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <x-input-select :data="$habitaciones" showName="numero_habitacion" name="habitaciones_id"
                            label="Seleccione una habitacion" />
                    </div>
                    <div class="mb-3">
                        <x-input-select :data="$estados" showName="estado_habitacion" name="estado_habitaciones_id"
                            label="Seleccione un estado" />
                    </div>
                    <div class="mb-3">
                        <label for="justificacion" class="form-label">Justificación del cambio</label>
                        <textarea class="form-control" name="justificacion" id="justificacion" rows="3" required maxlength="200"
                            placeholder="Escriba aquí"></textarea>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Solicitar cambio</button>
                    </div>
                </form>
                </p>
            </div>
        </div>
    </div>
@endsection
