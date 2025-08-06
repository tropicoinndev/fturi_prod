@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Fecha</th>
                <th scope="col">Solicitud</th>
                <th scope="col">Bodega entrada</th>
                <th scope="col">Bodega salida</th>
                <th scope="col">Usuario crea</th>
                <th scope="col">Usuario autoriza</th>
                <th scope="col">Estado</th>
                {{-- <th scope="col">Fecha Creacion</th>
                <th scope="col">Fecha de Modificacion</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->fecha }}</td>
                    <td>{{ $d->solicitud }}</td>
                    <td>{{ $d->relacionBodegasEntrada->bodega ?? '' }}</td>
                    <td>{{ $d->relacionBodegasSalida->bodega ?? '' }}</td>
                    <td>{{ $d->relacionUsuarios->name }}</td>
                    <td>{{ $d->relacionUserAutorizacion->name }}</td>
                    <td>
                        {{ $d->estado == 1 ? 'Activa' : ($d->estado == 2 ? 'Completada' : ($d->estado == 3 ? 'Autorizada' : 'Anulada')) }}
                    </td>
                    {{-- <td><span class="text-muted">{{ $d->created_at }}</span></td>
                    <td><span class="text-muted">{{ $d->updated_at }}</span></td> --}}
                </tr>
            @empty
                <tr><td colspan="12">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
