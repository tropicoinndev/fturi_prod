@extends('layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-10 m-auto">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-uppercase">Anulaciones de comprobantes</h3>
                        <div class="card-text">
                            <form action="{{ route('anulacion_comprobantes.list_search') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 fw-bolder h5 my-3">
                                        Filtros de búsqueda
                                    </div>
                                    <div class="col-3">
                                        <label for="buscar">Buscar</label>
                                        <input type="text" class="form-control" name="busqueda" id="buscar"
                                            aria-describedby="helpId" placeholder="Buscar por titular o correlativo"
                                            value="{{ $busqueda ?? '' }}" />
                                    </div>
                                    <div class="col-3">
                                        <label for="tipo_comprobante">Tipo de comprobante</label>
                                        <select class="form-select" id="tipo_comprobante" name="tipo_comprobante"
                                            aria-label="Default select example">
                                            <option value="0" selected>Todos</option>
                                            @foreach ($tipo_comprobantes as $t)
                                                <option value="{{ $t->id }}"
                                                    {{ isset($tipo_comprobante) && $tipo_comprobante == $t->id ? 'selected' : '' }}>
                                                    {{ $t->tipo }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-2">
                                        <label for="finicio">Fecha de inicio</label>
                                        <input type="date" class="form-control" name="finicio" id="finicio"
                                            placeholder="Fecha de inicio a buscar"
                                            value="{{ $finicio ?? date('Y-m-d') }}" />
                                    </div>
                                    <div class="col-2">
                                        <label for="ffin">Fecha de finalización</label>
                                        <input type="date" class="form-control" name="ffin" id="ffin"
                                            placeholder="Fecha de finalización a buscar"
                                            value="{{ $ffin ?? date('Y-m-d') }}" />
                                    </div>
                                    <div class="col-2 d-flex align-items-end">
                                        <button type="submit" name="tipo" value="1" class="btn btn-primary"
                                            style="margin-right: 8px;">
                                            <span class="mdi mdi-magnify"></span>
                                            Buscar
                                        </button>
                                        <button type="submit" name="tipo" value="2" class="btn btn-secondary ml-2">
                                            <span class="mdi mdi-file-document-outline"></span>
                                            Reporte
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-text mt-4">
                            <table class="table table-light table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Tipo de comprobante</th>
                                        <th>Titular</th>
                                        <th>Correlativo</th>
                                        <th>Total</th>
                                        <th>Anulación</th>
                                        <th>Observación de anulación</th>
                                        <th>Usuario</th>
                                        <th>Realización</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($p as $a)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $a->fecha }}</td>
                                            <td class="text-truncate"
                                                title="{{ $a->comprobantes->tipoComprobantes->tipo }}">
                                                {{ $a->comprobantes->tipoComprobantes->tipo }}</td>
                                            <td>{{ $a->comprobantes->titular }}</td>
                                            <td>{{ $a->correlativo }}</td>
                                            <td>${{ number_format($a->comprobantes->total, 2) }}</td>
                                            <td>{{ $a->anulaciones->anulacion }}</td>
                                            <td>{{ $a->observacion }}</td>
                                            <td>{{ $a->users->name }}</td>
                                            <td>{{ $a->created_at }}</td>
                                            <td>{{ $a->aceptado ? 'Fue aceptado con MH' : 'Aun sin aceptarse por MH' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                        <div class="text-card">
                            {{ $p->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
