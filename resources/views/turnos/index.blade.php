@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Apertura</th>
                    <th scope="col">Cierre</th>
                    <th scope="col">Apertura usuario</th>
                    <th scope="col">Cierre de usuarios</th>
                    <th scope="col">Cajas</th>
                    <th scope="col">Opcion de turnos</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha Creacion</th>
                    <th scope="col">Fecha de Modificacion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>

                        <th>{{ $d->id }}</th>
                        <td>{{ $d->fecha }}</td>
                        <td>{{ $d->apertura }}</td>
                        <td>{{ $d->cierre ?? 'sin cierre' }}</td>
                        <td>{{ $d->uapertura->name ?? '---' }}</td>
                        <td>{{ $d->ucierre->name ?? 'Aun sigue abierto' }}</td>
                        <td>{{ $d->cajas->caja }}</td>
                        <td>{{ $d->opcion->turno }}</td>
                        <td>
                            @if ($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td><span class="text-muted">{{ $d->created_at }}</span></td>
                        <td><span class="text-muted">{{ $d->updated_at }}</span></td>
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
