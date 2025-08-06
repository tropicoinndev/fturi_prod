
@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Municipio</th>
                <th scope="col">Código postal</th>
                <th scope="col">Código Ministerio de Hacienda</th>
                <th scope="col">Departamentos</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->municipio}}</td>
                    <td>{{ $d->codigo_postal}}</td>
                    <td>{{ $d->codigo_mh}}</td>
                    <td>{{ $d->departamentos->departamento }}</td>
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
