@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">DTE</h3>
            <small>
                Consulta de DTE
            </small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-4">


        @if ($status == 500)
            <div class="col-12">
                No se encontró el comprobante
            </div>
        @else
            <div class="col-12 text-bold">

                Respuesta de MH:
            </div>
            <div class="col-4">
                Estado:
            </div>
            <div class="col-8">
                {{ $json?->estado }}
            </div>

            <div class="col-4">
                Código de generación:
            </div>
            <div class="col-8">
                {{ $json?->codigoGeneracion }}
            </div>
            <div class="col-4">
                Sello de recibido:
            </div>
            <div class="col-8">
                {{ $json?->selloRecibido }}
            </div>
            <div class="col-4">
                Fecha de procesamiento:
            </div>
            <div class="col-8">
                {{ $json?->fhProcesamiento }}
            </div>
            <div class="col-4">
                Mensaje de MH:
            </div>
            <div class="col-8">
                ({{ $json?->codigoMsg }}) -> {{ $json?->descripcionMsg }}
            </div>
            <div class="col-4">
                Observaciones
            </div>
            <div class="col-8">
                ({{ $json?->clasificaMsg }}) -> {{ json_encode($json?->observaciones) }}
            </div>

            <div class="col-12">
                <button type="button" class="btn btn-light" data-bs-toggle="popover" data-bs-title="Respuesta completa"
                    data-bs-content='@json($rs)'>
                    <span class="mdi mdi-information-outline"></span>
                    Info
                </button>
            </div>
            <div class="col-12 mt-4">
                <h5>Actualizar DTE</h5>
                <form action="{{ route('dte.mh_api_update_consulta') }}" method="post">
                    @csrf
                    <input type="hidden" name="rs" value="{{ Crypt::encryptString($rs) }}">
                    <input type="hidden" name="id" value="{{ $dte->cid }}">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="confirm" name="confirm"
                            required>
                        <label class="form-check-label" for="confirm">
                            Confirmo que la información difiere con la guardada de este DTE.
                        </label>
                    </div>
                    <button type="submit" class="btn btn-light">Actualizar DTE</button>
                </form>

            </div>
        @endif


    </div>
@endsection
