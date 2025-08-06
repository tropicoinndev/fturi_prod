@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Contingencias enviadas</h3>
            <small>
                Listado de contingencias enviadas a MH
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" id="nav-pendientes-tab" data-bs-toggle="tab" data-bs-target="#nav-pendientes"
                    type="button" role="tab" aria-controls="nav-pendientes" aria-selected="true">
                    PENDIENTES
                </button>
                <button class="nav-link" id="nav-dte-tab" data-bs-toggle="tab" data-bs-target="#nav-dte" type="button"
                    role="tab" aria-controls="nav-dte" aria-selected="false">
                    PROCESADOS {{ date('M') }}
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-pendientes" role="tabpanel" aria-labelledby="nav-pendientes-tab"
                tabindex="0">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Opc.</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Motivo</th>
                                    <th scope="col">Inicio</th>
                                    <th scope="col">Finalización</th>

                                    <th scope="col">Info</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($pendientes as $d)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-light" type="button" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="mdi mdi-cog"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('dte.enviarLote', ['id' => Crypt::encryptString($d->id)]) }}">
                                                            Detalles
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('dte.contingencias_mh', ['id' => Crypt::encryptString($d->id)]) }}">
                                                            Editar
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item text-danger"
                                                            href="{{ route('dte.contingencias_confirm', ['id' => Crypt::encryptString($d->id)]) }}">Eliminar</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>

                                            {{ $d->id }}

                                        </td>
                                        <td>
                                            {{ $d->tipo_contingencia }}
                                        </td>
                                        <td>
                                            {{ $d->motivoContingencia }}
                                        </td>
                                        <td>
                                            {{ $d->fecha_inicio }}
                                            {{ $d->hora_inicio }}
                                        </td>

                                        <td>
                                            {{ $d->fecha_fin }}
                                            {{ $d->hora_fin }}
                                        </td>

                                        <td>
                                            @if ($d->response)
                                                @php
                                                    $m = json_decode($d->response);
                                                @endphp
                                                {{ $m->mensaje }}
                                            @else
                                                Sin info
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-dte" role="tabpanel" aria-labelledby="nav-dte-tab" tabindex="0">
                <div class="row mt-4">
                    <div class="col-12">
                        <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Opc.</th>
                                    <th scope="col">ID</th>
                                    <th scope="col">Procesamiento</th>
                                    <th scope="col">Info</th>
                                    <th scope="col">Motivo</th>
                                    <th scope="col">Sello de recibido</th>
                                    <th scope="col">Código de generación</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($procesados as $d)
                                    <tr>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-light" type="button" id="dropdownMenuButton1"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <span class="mdi mdi-cog"></span>
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('dte.enviarLote', ['id' => Crypt::encryptString($d->id)]) }}">
                                                            Detalle
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>

                                            {{ $d->id }}

                                        </td>
                                        <td>
                                            {{ $d->fecha_procesamiento }}
                                        </td>
                                        <td>
                                            @if ($d->response)
                                                @php
                                                    $m = json_decode($d->response);
                                                @endphp
                                                {{ $m->mensaje }}
                                            @else
                                                Sin info
                                            @endif
                                        </td>
                                        <td>
                                            {{ $d->motivoContingencia }}
                                        </td>
                                        <td>
                                            {{ $d->sello_recibido }}
                                        </td>

                                        <td>
                                            {{ $d->codigo_generacion }}
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
