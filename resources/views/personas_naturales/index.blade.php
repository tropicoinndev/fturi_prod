@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 col-lg-6">
                    <h3 class="card-title text-capitalize">{{ $th['title'] ?? 'Listado' }}</h3>
                    <p class="text-uppercase text-muted">{{ $th['sub'] ?? '' }}</p>
                </div>
            </div>

            {{--Alerta--}}
            <div class="col-12 col-lg-6"><x-message></x-message></div>
            
            {{--Caja de búsqueda--}}
            <div class="row mb-3 justify-content-end">
                <div class="col-6">
                    <form action="{{ route($th['table'] . '.search') }}" method="post">
                        @csrf
                        <input type="text" class="form-control" placeholder="Buscar por nombre, apellido o número de identificación" id="txtBusqueda"
                            name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
                    </form>
                </div>
            </div>

            {{--Tabla--}}
            <div class="table-responsive mb-3" style="min-height: 50vh;">
                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Acciones</th>
                                <th scope="col">#</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">Nombres</th>
                                <th scope="col">Profesión</th>
                                <th scope="col">Identificación</th>
                                <th scope="col">País</th>
                                <th scope="col">Departamento</th>
                                <th scope="col">Nacimiento</th>
                                <th scope="col">Fecha nacimiento</th>
                                <th scope="col">Estado civil</th>
                                <th scope="col">Persona de riesgo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td class="align-content-center">
                                        <x-acciones :table="$th['table']" :d="$d"/>
                                    </td>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $d->apellidos }}</td>
                                    <td>{{ $d->nombre }}</td>
                                    <td>{{ $d->profesion }}</td>
                                    <td>{{ $d->identificaciones->identificacion }}: {{ $d->identificacion ?? '---' }}</td>
                                    <td>{{ ($d->paises_id !== null) ? $d->paises->pais : 'El Salvador' }}</td>
                                    <td>{{ ($d->departamentos_id != null) ? $d->departamentos->departamento : '---' }}</td>
                                    <td>{{ $d->nacimiento }}</td>
                                    <td>{{ $d->fecha_nacimiento }}</td>
                                    <td>{{ $d->estado_civil }}</td>
                                    <td class="{{ $d->persona_riesgo ? 'text-danger' : '' }}">{{ $d->persona_riesgo ? 'Si es persona de riesgo' : 'No es persona de riesgo' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{--Paginación--}}
            @if (isset($p) && $p instanceof \Illuminate\Pagination\LengthAwarePaginator && $p->links() != null)
                <div class="col-12">
                    {{ $p->links() }}
                </div>
            @endif
            @if (isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->links() != null)
                <div class="col-12">
                    {{ $data->links() }}
                </div>
            @endif

        </div>
    </div>
@endsection

