@extends('layouts.hab')

@section('content-hab')
    <style>
        .btn-card {
            text-align: left !important;
        }
    </style>
    <div id="appRecepcionCheckOut" class="container">
        <div class="row justify-content-center">
            <div class="col-12 m-auto">
                <div class="card shadow p-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 h4">
                                CHECK OUT
                            </div>
                            <div class="col-12">
                                LISTADO DE HABITACIONES QUE REQUIEREN SALIDA
                                <x-message></x-message>
                            </div>
                            @if (count($p) > 0)
                                <div class="col-12">
                                    Verifique todas las salidas necesarias para este dia, seleccione las habitaciones y
                                    confirme
                                    que hayan salido de la habitación.
                                </div>
                                <form action="{{ route('recepciones.checkoutStore') }}" method="post">
                                    @csrf
                                    <div class="row mt-4">
                                        @foreach ($p as $r)
                                            <div class="col-4 mb-3">
                                                <input type="checkbox" class="btn-check" id="check-{{ $r->id }}"
                                                    autocomplete="off" name="recepciones[]"
                                                    value="{{ $r->facturada ? $r->cid : '' }}" multiple>
                                                <label
                                                    class="card btn btn-dark btn-card {{ !$r->facturada ? 'disabled' : '' }}"
                                                    for="check-{{ $r->id }}">
                                                    <div class="card-body">
                                                        <div class="card-title h5">Habitación
                                                            {{ $r->habitaciones->numero_habitacion }}</div>
                                                        <div class="card-text text-uppercase">{{ $r->clientes->nombre }}
                                                        </div>
                                                        <div class="card-text">
                                                            DEL {{ $r->fecha_ingreso }} AL
                                                            {{ $r->fecha_salida }} · {{ $r->dias }}
                                                            {{ $r->dias == 1 ? 'dia' : 'días' }}
                                                        </div>
                                                        <div
                                                            class="card-text {{ $r->facturada ? 'text-success' : 'text-danger' }}">
                                                            {{ $r->facturada ? 'Facturada' : 'Sin facturar' }}
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach

                                        <div class="col-12">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    id="confirm" required>
                                                <label class="form-check-label" for="confirm">
                                                    Confirmo que todas las habitaciones están vacías y se realizo el cobro.
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Realizar salida</button>
                                        </div>
                                    </div>



                                </form>
                            @else
                                <div class="col-12 align-items-text-bottom">
                                    <div class="alert alert-success" role="alert">
                                        <strong>
                                            <span class="mdi mdi-check"></span>
                                            Se realizaron todas las salidas para hoy.
                                        </strong>
                                    </div>
                                </div>

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- cSpell:ignore endsection, Recepcion, endforeach, dias, habitacion, csrf -->
@endsection
