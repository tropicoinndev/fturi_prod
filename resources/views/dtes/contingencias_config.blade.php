@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Configuración de contingencias</h3>
            <small>
                Listado de los DTE en contingencias local
            </small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <form action="{{ route('dte.contingencias_store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-lg-8 mb-3">
                        <button class="btn btn-primary" type="submit" name="opciones" value="1">
                            Configurar contingencia
                        </button>
                        <button class="btn btn-light" type="submit" name="opciones" value="2">
                            Resueltas
                        </button>
                    </div>
                    <div class="col-12 col-lg-4">
                        <div class="row g-3">
                            <div class="col-8">
                                <select class="form-select" aria-label="Default select example" name="contingencias_id">
                                    <option selected>Seleccione un tipo de contingencias</option>
                                    @foreach ($contingencias as $a)
                                        <option
                                            {{ old('contingencias_id') && strlen(old('contingencias_id')) > 200 && Crypt::decryptString(old('contingencias_id')) == $a->id ? 'selected' : '' }}
                                            value="{{ Crypt::encryptString($a->id) }}">{{ $a->valor }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-4">
                                <button class="btn btn-light" type="submit" name="opciones" value="3">
                                    Cambiar
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Tipo contingencia</th>
                                    <th scope="col">Info</th>
                                    <th scope="col">Response</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dtes as $d)
                                    <tr>
                                        <th>
                                            <input class="form-check-input" type="checkbox" name="contingencias[]"
                                                value="{{ Crypt::encryptString($d->id) }}"
                                                id="contingencia_{{ $d->id }}">
                                        </th>
                                        <td>
                                            <label for="contingencia_{{ $d->id }}">
                                                {{ $d->dtes->comprobante?->titular }} {{ $d->dtes->sujeto?->titular }}
                                            </label>
                                        </td>
                                        <td>
                                            <label for="contingencia_{{ $d->id }}">
                                                {{ $d->fecha_comprobante }}
                                            </label>
                                        </td>
                                        <td>
                                            <label for="contingencia_{{ $d->id }}">
                                                {{ $d->contingencias->valor }}
                                            </label>
                                        </td>
                                        <td>
                                            @php
                                                $msg = json_encode($d->dtes->response);
                                                $rs = json_decode($d->dtes->response);
                                            @endphp
                                            <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                data-bs-title="Respuesta del DTE" data-bs-content='{{ $msg }}'>
                                                <span class="mdi mdi-information-outline h5"></span>
                                                Info
                                            </button>

                                        </td>
                                        <td>
                                            @if ($rs && isset($rs->descripcionMsg))
                                                {{ $rs->descripcionMsg }}
                                            @else
                                                Sin info.
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if ($dtes->count() > 15)
                        <div class="col-12 mb-4">
                            <button class="btn btn-primary" type="submit">
                                Configurar contingencia
                            </button>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection
