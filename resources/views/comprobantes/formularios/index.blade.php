@extends('layouts.clientes_panel')
@section('content_cliente')
    <style>
        body {
            background: #B3E5FC !important;
        }
    </style>

    <div class="row">
        <div class="col-12 mb-4">
            <h3>
                Listado de operaciones reguladas
            </h3>
        </div>
        <x-message></x-message>
        <div class="col-12">
            <form class="row" method="POST" action="{{ route('operaciones_reguladas.search') }}">
                @csrf
                <div class="col-11">
                    <div class="mb-3">
                        <input type="text" name="busqueda" id="" class="form-control"
                            placeholder="Buscar por nombre del cliente o fecha" value="{{ $busqueda ?? '' }}" />
                    </div>

                </div>
                <div class="col-1">
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </div>
                </div>
            </form>

        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-hover table-borderless  align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Opciones</th>
                            <th>Fecha</th>
                            <th>Comprobante</th>
                            <th>Titular</th>
                            <th>Monto</th>
                            <th>Monto acumulado</th>
                            <th>Empleado</th>
                            <th>Revisa</th>
                            <th>Autoriza</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($data as $d)
                            <tr>
                                <td>
                                    <div class="dropdown open">
                                        <a class="btn btn-light" type="button" id="triggerId" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="mdi mdi-dots-vertical"></span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="triggerId">
                                            @if (!$d->completado)
                                                <a class="dropdown-item"
                                                    href="{{ route('operaciones_reguladas.create', ['id' => $d->cid]) }}">
                                                    <span class="mdi mdi-file-document-edit"></span>
                                                    Detalles
                                                </a>
                                            @endif
                                            @canany(['operaciones_reguladas.supervisar',
                                                'operaciones_reguladas.autorizacion'])
                                                @if ($d->completado && !$d->revisado)
                                                    <a class="dropdown-item"
                                                        href="{{ route('operaciones_reguladas.formulario', ['id' => $d->cid]) }}">
                                                        <span class="mdi mdi-file-check"></span>
                                                        Revisar formulario
                                                    </a>
                                                @endif
                                            @endcanany
                                            @can('operaciones_reguladas.autorizacion')
                                                @if ($d->completado && $d->revisado && !$d->autorizado)
                                                    <a class="dropdown-item"
                                                        href="{{ route('operaciones_reguladas.formulario', ['id' => $d->cid]) }}">
                                                        <span class="mdi mdi-file-certificate"></span>
                                                        Autorizar formulario
                                                    </a>
                                                @endif
                                                @if ($d->autorizado)
                                                    <a class="dropdown-item"
                                                        href="{{ route('operaciones_reguladas.imprimir', ['id' => $d->cid]) }}">
                                                        <span class="mdi mdi-printer-check"></span>
                                                        Imprimir
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('operaciones_reguladas.excel', ['id' => $d->cid]) }}">
                                                        <span class="mdi mdi-file-excel"></span>
                                                        Exportar excel
                                                    </a>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    {{ $d->fecha }}
                                </td>
                                <td>{{ $d->comprobante->correlativo }}</td>
                                <td>{{ $d->comprobante->titular }}</td>
                                <td>${{ number_format($d->comprobante->total, 2) }}</td>
                                <td>${{ number_format($d->monto_sumatoria, 2) }}</td>
                                <td>{{ $d?->empleado?->name ?? 'Aun no se ha iniciado' }}</td>
                                <td>{{ $d?->supervisa?->name ?? 'Aun no se ha revisado' }}</td>
                                <td>{{ $d?->autoriza?->name ?? 'Aun sin autorización' }}</td>
                                <td>
                                    <span
                                        class="badge badge-pill h5 {{ $d->completado ? ($d->revisado ? ($d->autorizado ? 'bg-success' : 'bg-warning') : 'bg-danger') : 'bg-danger' }}">
                                        {{ $d->completado ? ($d->revisado ? ($d->autorizado ? 'Autorizado' : 'Sin autorizar') : 'Sin revisar') : 'Sin completar' }}
                                    </span>
                                </td>
                            </tr>
                            @if ($d->ob_autoriza !== null && !$d->autorizado)
                                <tr>
                                    <td colspan="10">
                                        <div class="alert alert-danger" role="alert">
                                            {{ $d->ob_autoriza }}
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-12">
            {{ $data->links() }}
        </div>
    </div>
@endsection
