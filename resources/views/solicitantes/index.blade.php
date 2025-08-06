@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Nombre completo</th>
                    <th scope="col">Identificación</th>
                    <th scope="col">Nº documento</th>
                    <th scope="col">Teléfono</th>
                    <th scope="col">Correo</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha creación</th>
                    <th scope="col">Fecha edición</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td><x-acciones :table="$th['table']" :d="$d" /></td>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->nombre_completo }}</td>
                        <td>{{ $d->identificaciones->identificacion }}</td>
                        <td>{{ $d->numero_documento }}</td>
                        <td>{{ $d->telefono }}</td>
                        <td>{{ $d->correo }}</td>
                        <td>
                            @if($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td><span class="text-muted">{{ $d->created_at }}</span></td>
                        <td><span class="text-muted">{{ $d->updated_at }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="8">Aun no se han agregado datos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
