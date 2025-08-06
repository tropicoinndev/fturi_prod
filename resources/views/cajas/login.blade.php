@extends('layouts.app')

@section('style')
    <style>
        body {
            background-color: #E0F2F1;
            font-family: sans-serif;
        }

        .caja {
            min-height: 200px;
            transition: background 1s ease-out;
        }

        .pin {
            transition: opacity 1s ease-out;
            opacity: 0;
            height: 0;
            overflow: hidden;
        }

        .formPin:hover .pin {
            opacity: 1;
            height: auto;
        }

        .activo:hover {
            background: #009688;
            color: #fff;
        }

        .inactivo:hover {
            background: #BF360C;
            color: #fff;
        }

        .disabled:hover {
            background: #B0BEC5;
            color: #37474F;
        }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div>
            <div class="row">
                <div class="col-12">
                    <div class="card containerCajas shadow">

                        <div class="card-body">
                            <div class="row mt-2 p-3">
                                <h3 class="card-title mb-1">CAJAS</h3>
                                <div class="col-12 mb-4">
                                    <small>En las cajas sin turno activo al iniciar sesión abrirá el turno.</small>

                                </div>
                                <div class="col-12">
                                    <x-message></x-message>
                                </div>
                                @forelse ($cajas as $c)
                                    @isset($c->cajas->caja)
                                        <div class="col-12 col-sm-6 col-md-4 mb-3 ">
                                            <div
                                                class="card caja border-1  {{ !$c->cajas->estado ? 'disabled' : ($c->cajas?->turnoActivo?->opcion->turno != null ? 'border-dark activo formPin' : 'border-danger inactivo formPin') }}">
                                                <div class="card-body d-flex">
                                                    <div class="col align-self-center text-center">
                                                        <div class="col-12 h2 card-title text-uppercase">
                                                            {{ $c->cajas->caja }}
                                                        </div>
                                                        <div class="col">
                                                            {{ !$c->cajas->estado ? 'CAJA DESHABILITADA' : $c->cajas?->turnoActivo?->opcion->turno ?? 'SIN TURNO' }}
                                                        </div>
                                                        <div class="card-text mb-2">
                                                            CAJA
                                                        </div>
                                                        <div class="card-text pin">
                                                            <form action="{{ route('cajas.auth') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="caja"
                                                                    value="{{ \Crypt::encryptString($c->id) }}">
                                                                <div class="input-group">
                                                                    <input class="form-control border-dark" type="password"
                                                                        name="pin" placeholder="PIN" autocomplete="off">
                                                                    <button class="input-group-text"
                                                                        type="submit">Acceder</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endisset
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning" role="alert">
                                            <strong>
                                                No tienes acceso a ninguna caja.
                                            </strong>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>


    </div>
@endsection
