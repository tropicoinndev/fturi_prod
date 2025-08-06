@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="w-20">Acciones</th>
                    <th scope="col">Glorieta</th>
                    <th scope="col">#</th>
                    <th scope="col">Nº habitación</th>
                    <th scope="col">Tipo habitación</th>
                    <th scope="col">Forma habitación</th>
                    <th scope="col">Estado habitación</th>
                    <th scope="col">Ubicacion habitación</th>
                    <th scope="col">Sucursal habitación</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Extensión</th>
                    <th scope="col">Descripción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td>
                            <x-acciones :table="$th['table']" :d="$d" />
                        </td>
                        <th>
                            <a class="btn btn-light" href="{{ route('habitaciones.glorieta', ['id' => $d->cid]) }}"
                                role="button" title="{{ $d->cid }}">
                                @if ($d->glorieta)
                                    <span class="mdi mdi-toggle-switch text-success h2"></span>
                                @else
                                    <span class="mdi mdi-toggle-switch-off h2"></span>
                                @endif
                            </a>
                        </th>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->numero_habitacion }}</td>
                        <td>{{ $d->relacionTipoHabitaciones->tipo_habitacion }}</td>
                        <td>{{ $d->relacionFormaHabitaciones->forma_habitacion }}</td>
                        <td>{{ $d->relacionEstadoHabitaciones->estado_habitacion }}</td>
                        <td>{{ $d->relacionUbicacionHabitaciones->ubicacion_habitacion }}</td>
                        <td>{{ $d->sucursales->sucursal ?? '' }}</td>
                        <td>{{ $d->telefono }}</td>
                        <td>{{ $d->extension }}</td>
                        <td>{{ $d->descripcion }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
