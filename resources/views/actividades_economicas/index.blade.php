@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Código</th>
                <th scope="col">Actividades</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d" /></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->codigo }}</td>
                    <td>{{ $d->actividad }}</td>
                    <td>
                        <span class="text-muted">{{ $d->created_at }}</span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $d->updated_at }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
