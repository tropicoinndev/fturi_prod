@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Periodo</th>
                <th scope="col">Nº días</th>
                <th scope="col">Estado</th>
                <th scope="col">Creación</th>
                <th scope="col">Edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->periodo }}</td>
                    <td>{{ $d->dias }}</td>
                    <td>
                        <span class="text-{{ $d->estado ? 'success' : 'danger' }}">{{ $d->estado ? 'Activo' : 'Inactivo' }}</span>
                    </td>
                    <td>{{ $d->created_at }}</td>
                    <td>{{ $d->updated_at }}</td>
                </tr>
            @empty
                <tr><td colspan="7">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
