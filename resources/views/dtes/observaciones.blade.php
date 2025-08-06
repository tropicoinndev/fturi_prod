@extends('layouts.dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-check text-success h2"></span>
                DTES con observaciones
            </h3>
            <small>
                Listado de a partir de {{ $fecha }}
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form class="row g-3" action="{{ route('dte.observacionesSearch') }}" method="POST">
                @csrf
                <div class="col-3">
                    <input type="date" class="form-control" placeholder="Buscar por fecha" required
                        value="{{ $fecha ?? '' }}" name="fecha">
                </div>
                <div class="col-1">
                    <button class="btn btn-primary" type="submit">
                        <span class="mdi mdi-magnify"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <table class="table table-light table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Acciones</th>
                        <th scope="col">Tipo DTE</th>
                        <th scope="col">Procesamiento</th>
                        <th scope="col">Titular</th>
                        <th scope="col">Estado</th>
                        <th scope="col">Codigo de generación</th>
                        <th scope="col">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dtes as $d)
                        @php
                            $rs = json_decode($d->response);
                            $json = json_decode($d->json);
                        @endphp
                        <tr>
                            <th scope="row">{{ $d->id }}</th>
                            <td>
                                <a class="btn btn-light"
                                    href="{{ route('dte.documento', ['id' => Crypt::encryptString($d->id)]) }}"
                                    target="_blank">
                                    <span class="mdi mdi-file-document-check h4"></span>
                                    Detalles
                                </a>
                            </td>
                            <td>{{ $json->identificacion->tipoDte }}</td>
                            <td>{{ $d->fecha_procesamiento }}</td>
                            <td>{{ $d->comprobante?->titular }} {{ $d->sujeto?->titular }}</td>
                            <td>{{ $rs->estado }}</td>
                            <td>{{ $d->codigo_generacion }}</td>
                            @php
                                $observacionesArray = json_decode($d->observaciones, true);
                            @endphp
                            <td>
                                <ol>

                                    @forelse ($observacionesArray as $o)
                                        <li>{{ $o }}</li>
                                    @empty
                                        <li>Sin observaciones</li>
                                    @endforelse
                                </ol>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
