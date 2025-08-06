@extends('layouts.dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
    <style>
        .btn-clicked {
            background-color: #ffdd57;
            /* Cambia este color al que prefieras */
            color: white;
        }
    </style>
@endsection

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-check text-success h2"></span>
                Comprobantes
            </h3>
            <small>
                Listado de {{ $fecha }}
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <form class="row g-3" action="{{ route('dte.comprobantes_search') }}" method="POST">
                @csrf
                <div class="col-2">
                    <select class="form-select" name="cajas" required>
                        <option value="{{ Crypt::encryptString(0) }}" selected>Todas las cajas</option>
                        @foreach ($cajas as $c)
                            <option value="{{ $c->cid }}" {{ $c->id == $caja ? 'selected' : '' }}>{{ $c->caja }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <select class="form-select" name="tipo_comprobante">
                        <option value="" selected>Todos</option>
                        @foreach ($tipo_comprobantes as $t)
                            <option value="{{ $t->id }}" {{ $t->id == $tipo ? 'selected' : '' }}>
                                {{ $t->tipo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <input type="date" class="form-control" placeholder="Buscar por fecha" value="{{ $fecha ?? '' }}"
                        name="fecha">
                </div>
                <div class="col-3">
                    <input type="text" class="form-control" placeholder="Buscar por titular o correlativo interno"
                        value="{{ $busqueda ?? '' }}" name="busqueda">
                </div>
                <div class="col-2">
                    <input type="text" class="form-control" placeholder="Buscar por codigo de generacion"
                        value="{{ $codigo_generacion ?? '' }}" name="codigo_generacion">
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
                        <th scope="col" colspan="2">Acciones</th>
                        <th scope="col">Tipo comprobante</th>
                        <th scope="col">Correlativo</th>
                        <th scope="col">Titular</th>
                        <th scope="col">Fecha/hora</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Caja</th>
                        <th scope="col">DTE</th>
                        <th scope="col">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($comprobantes as $d)
                        <tr>
                            <th scope="row">{{ $d->id }}</th>
                            <td>
                                <a class="btn btn-light" href="{{ route('comprobantes.show', ['id' => $d->cid]) }}"
                                    target="_blank"
                                    onclick="this.classList.remove('btn-light'); this.classList.add('btn-danger');">
                                    <span class="mdi mdi-file h5"></span>
                                </a>
                            </td>
                            <td>

                                <div class="dropdown">
                                    <button class="btn btn-light" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mdi mdi-cog"></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('comprobantes.show', ['id' => $d->cid]) }}" target="_blank">
                                                <span class="mdi mdi-file-pdf-box h4"></span>
                                                Comprobante
                                            </a>
                                        </li>
                                        @if ($d->dteOne && $d->dteOne->id != null)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('comprobantes.api_pdfDte', ['id' => $d->dteOne?->cid]) }}"
                                                    target="_blank">
                                                    <span class="mdi mdi-file-pdf-box h4"></span>
                                                    Ver PDF
                                                </a>
                                            </li>

                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('dte.documento', ['id' => $d->dteOne?->cid]) }}"
                                                    target="_blank">
                                                    <span class="mdi mdi-file-document-check h4"></span>
                                                    Detalles DTE
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td>{{ $d->tipoComprobantes?->tipo }}</td>
                            <td>{{ $d->correlativo }}</td>
                            <td>{{ $d->titular }}</td>
                            <td>{{ $d->created_at }}</td>
                            <td>{{ $d->users->user }}</td>
                            <td>{{ $d->turnosCajas->cajas->caja }}</td>
                            <td>{{ $d->dteOne?->codigo_generacion }}</td>
                            <td>

                                <button type="button"
                                    class="btn btn-light btn-sm {{ $d->dteOne?->estado == 'PROCESADO' ? '' : 'text-danger' }}"
                                    data-bs-toggle="popover" data-bs-placement="right" data-bs-custom-class="custom-popover"
                                    data-bs-title="Response" data-bs-content="{{ json_encode($d->dteOne?->response) }}">
                                    {{ $d->dteOne?->estado ?? 'RECHAZADO' }}
                                </button>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-12">
            {{ $comprobantes->links() }}
        </div>
    </div>
@endsection
