@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Turno</th>
                <th scope="col">Apertura</th>
                <th scope="col">Cierre</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha creacion</th>
                <th scope="col">Fecha edicion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->turno }}</td>
                    <td><span class="text-muted">{{ $d->apertura }}</span></td>
                    <td><span class="text-muted">{{ $d->cierre }}</span></td>
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
