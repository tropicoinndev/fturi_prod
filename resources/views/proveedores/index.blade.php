@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col" class="w-20">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Proveedor</th>
                <th scope="col">NRC</th>
                <th scope="col">NIT</th>
                <th scope="col">DUI</th>
                <th scope="col">P. Crédito</th>
                <th scope="col">Dirección</th>
                <th scope="col">Municipio</th>
                <th scope="col">Contactos</th>
                <th scope="col">Información</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->proveedor }}</td>
                    <td>{{ $d->nrc }}</td>
                    <td>{{ $d->nit }}</td>
                    <td>{{ $d->dui }}</td>
                    <td>{{ ($d->permite_credito) ? 'Si' : 'No' }}</td>
                    <td>{{ $d->direccion }}</td>
                    <td>{{ $d->relacionMunicipios->municipio }}</td>
                    <td>{{ $d->contactos }}</td>
                    <td>{{ $d->informacion }}</td>
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
