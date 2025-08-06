@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Inicio</th>
                <th scope="col">Actual</th>
                <th scope="col">Final</th>
                <th scope="col">Cajas</th>
                <th scope="col">Usuarios</th>
                <th scope="col">Tipo de comprobantes</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha Creacion</th>
                <th scope="col">Fecha de Modificacion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->inicio }}</td>
                    <td>{{ $d->actual }}</td>
                    <td>{{ $d->final }}</td>
                    <td>{{ $d->cajas->caja }}</td>
                    <td>{{ $d->usuario->name }}</td>
                    <td>{{ $d->tipo_comprobantes->tipo }}</td>              
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
                <tr><td colspan="12">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
