@extends('layouts.dtes')
@section('style-dte')
    <style>
        .check-lg {
            height: 30px !important;
            width: 30px !important;
        }
    </style>
@endsection
@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Anticipos Visual</h3>
            <small>
                Consulta de anticipos visual
            </small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-4">
        <form action="{{ route('anticiposVisual.search') }}" method="post">
            @csrf
            <div class="mb-3">
                <label for="titular" class="form-label">Titular</label>
                <input type="text" class="form-control" name="titular" id="titular" aria-describedby="helpId"
                    placeholder="Escriba el nombre del titular / ID" value="{{ $titular ?? '' }}" />

            </div>
            <div class="mb-3">
                <label for="anticipos" class="form-label">Buscar por ID (separar por ;)</label>
                <textarea class="form-control" name="anticipos" id="anticipos" rows="3"
                    placeholder="Escriba los id separado por punto y coma, Ej.: 1;200;201 ">{{ $anticipos ?? '' }}</textarea>
            </div>
            <div class="mb-3">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </form>

        @if (isset($data) && $data->count() > 0)
            <h3 class="mt-5">Anticipos</h3>
            <small class="mb-3">Seleccione los anticipos, y presione en el botón guardar para desactivarlos</small>
            <form action="{{ route('anticiposVisual.desactivar') }}" method="post">
                @csrf
                <div class="row">
                    @foreach ($data as $d)
                        <div class="col-12 mb-4">
                            <div class="card {{ (bool) $d->activo && !(bool) $d->eliminado ? '' : 'text-bg-danger' }}">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-1 d-flex align-items-center">
                                            @if ((bool) $d->activo && !(bool) $d->eliminado)
                                                <input type="checkbox" class="form-check-input check-lg"
                                                    id="{{ $d->cveanticipo }}"
                                                    value="{{ Crypt::encryptString($d->cveanticipo) }}" autocomplete="off"
                                                    name="anticipos[]" />
                                            @else
                                                <input class="form-check-input" type="checkbox" value="" disabled>
                                            @endif
                                        </div>
                                        <div class="col-11">


                                            <h4 class="card-title">Anticipo Nº {{ $d->cveanticipo }}</h4>
                                            <p class="card-text">{{ $d->cuenta }}</p>
                                            <div class="list-group-item">
                                                <b>
                                                    Estado
                                                    {{ $d->activo }}
                                                </b>
                                                {{ $d->activo ? 'Aun sin usar' : 'Este anticipo ya fue usado' }}
                                            </div>
                                            <div class="list-group-item">
                                                <b>
                                                    Fecha
                                                </b>
                                                {{ $d->fecha }}
                                            </div>
                                            <div class="list-group-item">
                                                <b>
                                                    Monto:
                                                </b>
                                                ${{ number_format($d->monto, 2) }}
                                            </div>
                                            <div class="list-group-item">
                                                <b>
                                                    Concepto:
                                                </b>
                                                {{ $d->observaciones }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="col-12">
                        <button class="btn btn-danger" type="submit">Desactivar anticipos</button>
                    </div>
                </div>
            </form>
        @elseif(isset($data) && $data->count() == 0)
            <div class="alert alert-light" role="alert">
                <strong>No se encontraron datos</strong> Intente con otros datos.
            </div>
        @endif
    </div>
@endsection
