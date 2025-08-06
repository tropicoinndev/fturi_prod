@extends('layouts.hab')

@section('content-hab')
    <div class="container">
        <div class="row">
            <div class="col-12 m-auto">
                <div class="card  p-3">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">
                            Anulacion de reservacion #{{ $p->id }}
                        </h5>
                        <div class="card-text">
                            Cliente: {{ $p->titular ?? $p->relacionClientes->nombre }}
                        </div>
                        <div class="card-text pt-2 pb-2">
                            <x-message></x-message>
                            @if (!$p->eliminado)
                                <form action="{{ route('reservaciones.anulacion') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ Crypt::encryptString($p->id) }}" name="id">
                                    <h5>
                                        Seleccione las reservaciones a eliminar:
                                    </h5>
                                    <div class="row">
                                        @forelse ($p->detalleReservaciones as $d)
                                            <div class="col-4 mt-2">
                                                @if (!$d->ingreso || $d->estado)
                                                    <input type="checkbox" class="btn-check"
                                                        id="detalle-{{ $d->id }}" autocomplete="off"
                                                        name="detalle_reservas[]" multiple
                                                        {{ $d->ingreso || !$d->estado ? 'disabled' : '' }}
                                                        value="{{ Crypt::encryptString($d->id) }}">
                                                @endif
                                                <label class="card border-dark btn btn-outline-primary"
                                                    for="detalle-{{ $d->id }}">
                                                    <div class="card-body">
                                                        <div class="card-title h4 text-left">
                                                            Habitacion {{ $d->relacionHabitaciones->numero_habitacion }}
                                                        </div>
                                                        <div class="card-text">
                                                            {{ $d->fecha_ingreso }} - {{ $d->fecha_salida }}
                                                            @if ($d->ingreso)
                                                                <span class="badge text-bg-success">Fue registrada</span>
                                                            @else
                                                                @if ($d->estado)
                                                                    <span class="text-success">Activo</span>
                                                                @else
                                                                    <span class="text-danger">Anulada</span>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    No se agregaron habitaciones a esta reservacion.
                                                </div>
                                            </div>
                                        @endforelse

                                        <div class="col-12 mt-3">
                                            <div class="form-group">
                                                <label for="razon">Razon de anulacion:</label>
                                                <textarea name="razon" id="razon" rows="5" maxlength="150" class="form-control" required></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirmacion" id="confirmacion" required autocomplete="off">
                                                <label class="form-check-label" for="confirmacion">
                                                    Si, estoy seguro de anular las reservaciones sin ingreso.
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                Anular reservacion
                                            </button>
                                            <a href="{{ route('reservaciones.index') }}" class="btn btn-light">
                                                Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-primary" role="alert">
                                    Ya fue anulada esta reservacion.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
