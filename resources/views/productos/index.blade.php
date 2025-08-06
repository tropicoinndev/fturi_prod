@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col" class="w-20">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Categorías</th>
                <th scope="col">Mínimos</th>
                <th scope="col">Máximos</th>
                <th scope="col">Estado</th>
                <th scope="col">Vencimiento</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td>
                        <x-acciones :table="$th['table']" :d="$d"/>
                    </td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->nombre }}</td>
                    <td>{{ $d->categoria->categoria }}</td>
                     <td>{{ $d->minimos }}</td>
                     <td>{{ $d->maximos }}</td>
                    <td>
                        @if($d->estado)
                            <span class="text-success">Activo</span>
                        @else
                            <span class="text-danger">Inactivo</span>
                        @endif
                    </td>
                    <td>
                         @if($d->vencimiento)
                            <span class="text-success">Con fecha de vencimiento</span>
                        @else
                            <span class="text-danger">Sin fecha de vencimiento</span>
                        @endif
                    </td>
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