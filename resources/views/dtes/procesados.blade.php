@extends('layouts.dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-check text-success h2"></span>
                DTES Procesados
            </h3>
            <small>
                Listado de {{ $fecha }}
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form class="row g-3" action="{{ route('dte.procesados_search') }}" method="POST">
                @csrf
                <div class="col-3">
                    <input type="date" class="form-control" placeholder="Buscar por fecha" required
                        value="{{ $fecha ?? '' }}" name="fecha">
                </div>
                <div class="col-8">
                    <input type="text" class="form-control" placeholder="Buscar por titular o numero generación"
                        value="{{ $busqueda ?? '' }}" name="busqueda">
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
                        <th scope="col">Correlativo sucursal</th>
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
                                <div class="dropdown">
                                    <button class="btn btn-light" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mdi mdi-cog"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('comprobantes.api_sendMail', ['id' => Crypt::encryptString($d->id)]) }}">
                                                <span class="mdi mdi-email-check h4"></span> Reenviar correo
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($d->id)]) }}">
                                                <span class="mdi mdi-file-pdf-box h4"></span>
                                                Ver PDF
                                            </a>
                                        </li>

                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('dte.documento', ['id' => Crypt::encryptString($d->id)]) }}">
                                                <span class="mdi mdi-file-document-check h4"></span>
                                                Detalles
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $json->identificacion->tipoDte }}</td>
                            <td>{{ $d->fecha_procesamiento }}</td>
                            <td>{{ $d->comprobante?->titular }} {{ $d->sujeto?->titular }}</td>
                            <td>{{ $rs->estado }}</td>
                            <td>{{ $d->codigo_generacion }}</td>
                            <td>{{ $d->correlativo }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
