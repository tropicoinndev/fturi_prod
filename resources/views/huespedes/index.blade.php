@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Fecha de nacimiento</th>
                    <th scope="col">Identificacion</th>
                    <th scope="col">Telefono</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Bloqueado</th>
                    <th scope="col">Direccion</th>
                    <th scope="col">Pais</th>
                    <th scope="col">Fecha de creacion</th>
                    <th scope="col">Fecha de Modificacion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td>
                            <x-acciones :table="$th['table']" :d="$d" />
                        </td>

                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->nombre }}</td>
                        <td>
                            @if ($d->nacimiento != null)
                                {{ \Carbon::parse($d->nacimiento)->format('d-m-Y') }} nació
                                {{ \Carbon::parse($d->nacimiento)->diffForHumans() }}
                            @else
                                --No se agrego --
                            @endif
                        </td>
                        <td>
                            @if ($d->identificaciones_id > 0)
                                {{ $d->identificaciones->identificacion }}: {{ $d->identificacion }}
                            @else
                                -- No se agrego una identificacion --
                            @endif
                        </td>
                        <td>{{ $d->telefono }}</td>
                        <td>
                            @if ($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->bloqueado)
                                <span class="text-danger">Bloqueado ingreso</span>
                            @else
                                <span class="text-success">Permitido el ingreso</span>
                            @endif
                        </td>
                        <td>{{ $d->municipios->municipio ?? '-- No se agregó direccion' }}, {{ $d->municipios->departamentos->departamento ?? '' }},
                            {{ $d->municipios->departamentos->paises->pais ?? '' }}</td>
                        <td>

                                {{ optional($d->paises)->pais ?? '-- No se agregó país' }}

                        </td>
                        <td>

                            <span class="text-muted">{{ $d->created_at }}</span>
                        </td>
                        <td>
                            <span class="text-muted">{{ $d->updated_at }}</span>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
@endsection
