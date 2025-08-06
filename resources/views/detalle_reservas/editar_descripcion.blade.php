@extends('layouts.hab')

@section('content-hab')
    <style>
        .message {
            position: fixed;
            top: 10%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }
    </style>

    <div id="appReservacionesCreate" class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body">

                    <x-message></x-message>

                    <h4 class="card-title text-uppercase mb-4">Edición de reservación</h4>

                    <div class="card-text row">
                        <div class="row mb-1">
                            <div class="col-2">Cliente</div>
                            <div class="col-10 text-uppercase">
                                @if ($p->relacionReservaciones->clientes_id > 0)
                                    {{ $p->relacionReservaciones?->relacionClientes?->nombre }}
                                @elseif($p->relacionReservaciones?->titular != null)
                                    {{ $p->relacionReservaciones?->titular }}
                                @else
                                    No se agrego un cliente o Alias
                                @endif
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">Habitación</div>
                            <div class="col-10">{{ $p->relacionReservaciones->detalleReservaciones[0]->relacionHabitaciones->numero_habitacion }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">Fecha de ingreso</div>
                            <div class="col-10">{{ $p->fecha_ingreso }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">Fecha de salida</div>
                            <div class="col-10">{{ $p->fecha_salida }}</div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">Tarifa actual</div>
                            <div class="col-10">
                                {{ $p->relacionTarifas->tarifa }} ${{ number_format($p->relacionTarifas->precio, 2) }} /
                                {{ $p->relacionTarifas->numero_dias }} dia(s)
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">Ingresado por</div>
                            <div class="col-10 text-uppercase">{{ $p->relacionUsuarios->name }}</div>
                        </div>

                        <form action="{{ route('detalle_reservas.updateDescripcion') }}" method="post">
                            @csrf

                            <input type="hidden" name="id" value="{{ $p->cid }}">

                            <div class="row mt-3">
                                <div class="col-12 h5">Editar descripción: <small class="text-muted"><i>(Este campo es requerido)<span class="text-danger">*</span></i></small></div>

                                <div class="col-6">
                                    <textarea class="form-control" name="descripcion" id="descripcion" cols="15" rows="8" placeholder="Ingrese una breve descripción de la reserva..." required>{{ $p->descripcion }}</textarea>
                                </div>
                                
                                <div class="col-12 mt-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="confirm" id="confirm" required>
                                        <label class="form-check-label" for="confirm">Confirmo que quiero editar la descripción.</label>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Editar descripción</button>
                                    <a class="btn btn-light" href="{{ route('detalle_reservas.index', ['id' => Crypt::encryptString($p->reservaciones_id)]) }}">Cancelar</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
