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
                    <h4 class="card-title text-uppercase mb-4">
                        Edición de reservación
                    </h4>
                    <div class="card-text row">
                        <div class="row mb-1">
                            <div class="col-2">
                                Cliente
                            </div>
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
                            <div class="col-2">
                                Habitación
                            </div>
                            <div class="col-10">
                                {{ $habitacion->numero_habitacion }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">
                                Fecha de ingreso
                            </div>
                            <div class="col-10">
                                {{ $p->fecha_ingreso }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">
                                Fecha de salida
                            </div>
                            <div class="col-10">
                                {{ $p->fecha_salida }}
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">
                                Tarifa actual
                            </div>
                            <div class="col-10">
                                {{ $p->relacionTarifas->tarifa }} ${{ number_format($p->relacionTarifas->precio, 2) }} /
                                {{ $p->relacionTarifas->numero_dias }} dia(s)
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-2">
                                Ingresado por
                            </div>
                            <div class="col-10 text-uppercase">
                                {{ $p->relacionUsuarios->name }}
                            </div>
                        </div>
                        <form action="{{ route('detalle_reservas.update_tarifa') }}" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{ $p->cid }}">
                            <div class="row mt-3">
                                <div class="col-12 h5">
                                    Elija una tarifa
                                </div>

                                @foreach ($tarifas as $t)
                                    @php
                                        $isValid = false;
                                        if (
                                            $p->fecha_ingreso >= $t->tarifas->temporadas->fecha_inicio &&
                                            $p->fecha_ingreso <= $t->tarifas->temporadas->fecha_finalizacion
                                        ) {
                                            $isValid = true;
                                        }
                                    @endphp
                                    <div class="col-3 mb-3">
                                        @if ($isValid)
                                            <input class="btn-check" type="radio" name="tarifas_id"
                                                id="tarifa_{{ $t->tarifas->id }}" value="{{ $t->tarifas->cid }}">
                                        @endif
                                        <label class="card p-0 {{ !$isValid ? 'disabled' : 'btn btn-primary' }}"
                                            for="tarifa_{{ $t->tarifas->id }}">
                                            <div class="card-header h5">
                                                ${{ number_format($t->tarifas->precio, 2) }} /
                                                {{ $t->tarifas->numero_dias }} dia(s)
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{ $t->tarifas->tarifa }}</h5>
                                                <p>

                                                </p>
                                                <p class="card-text">{{ $t->tarifas->temporadas->fecha_inicio }} al
                                                    {{ $t->tarifas->temporadas->fecha_finalizacion }}
                                                    · <small>{{ $isValid ? 'Aplicable' : 'No aplicable' }}</small>
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                                <div class="col-12 mt-3">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                            id="confirm" required>
                                        <label class="form-check-label" for="confirm">
                                            Confirmo que quiero cambiar la tarifa.
                                        </label>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Editar tarifa</button>
                                    <a class="btn btn-light"
                                        href="{{ route('detalle_reservas.index', ['id' => Crypt::encryptString($p->reservaciones_id)]) }}">Cancelar</a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
