@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: #B2DFDB;
        }

        .panel {
            min-height: 90vh;
        }

        .cortesia {
            background: #0277BD;
            color: #FAFAFA;
        }
    </style>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card panel shadow p-3">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 col-lg-6 mb-4">
                                <h3 class="card-title text-uppercase">
                                    Autorización de cortesias
                                </h3>
                            </div>
                            <div class="col-12">

                                <form class="row">
                                    <div class="col">
                                        <div class="mb-3">
                                            <input type="text" name="" id="" class="form-control"
                                                placeholder="Buscar por nombre de titular..." />

                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-light" type="submit">
                                            <span class="mdi mdi-magnify"></span>
                                            Buscar
                                        </button>
                                    </div>
                                </form>

                            </div>
                            <!--Mensajes de alerta alerta-->
                            <div class="col-12 col-lg-12">
                                <x-message></x-message>
                            </div>
                            <div class="col-12 my-3">
                                <h5>Listado de cortesias pendientes de autorización</h5>
                            </div>
                            @forelse ($p as $c)
                                <div class="col-12 col-lg-4 mb-3">
                                    <div class="card cortesia">
                                        <div class="card-body">
                                            <h5 class="card-title text-truncate">{{ $c->titular->titular }}</h5>
                                            <p class="card-text">Monto: ${{ number_format($c->monto, 2) }}</p>
                                            <p class="card-text">
                                                <a class="btn btn-light"
                                                    href="{{ route('cortesias.detalle', ['id' => Crypt::encryptString($c->id)]) }}">
                                                    Detalles cortesía
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="alert alert-primary" role="alert">
                                        <h4 class="alert-heading">Información</h4>
                                        Aun no se han agregado cuentas a cortesía, cuando se agreguen aparecerán aquí.
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
