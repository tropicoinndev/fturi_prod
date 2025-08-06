@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col" class="w-20">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Forma de habitacion</th>
                <th scope="col">Nº Max. de personas</th>
                <th scope="col">Fecha creacion</th>
                <th scope="col">Fecha edicion</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td>
                        <x-acciones :table="$th['table']" :d="$d"/>
                    </td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->forma_habitacion }}</td>
                    <td>{{ $d->max_personas }}</td>
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