@extends('layouts.list')

@section('list')

<div id="appOrdenes">
    @{{ message }}

    <span class="text-danger">@{{ titulo }}</span>
</div>


<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col" class="w-20">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">No Orden</th>
                <th scope="col">Fecha</th>
                <th scope="col">Titular</th>
                <th scope="col">Descripcion</th>
                <th scope="col">Cliente</th>
                <th scope="col">Caja</th>
                <th scope="col">Estado</th>
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
                    <td>{{ $d->numero_orden }}</td>
                    <td>{{ $d->fecha }}</td>
                    <td>{{ $d->titular }}</td>
                    <td>{{ $d->descripcion }}</td>
                    <td>{{ $d->clientes->nombre }}</td>
                    <td>{{ $d->cajas->caja }}</td>
                    <td>
                        @if($d->estado)
                            <span class="text-success">Activo</span>
                        @else
                            <span class="text-danger">Inactivo</span>
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

<script>
    var app = new Vue({
        el: '#appOrdenes',
        data: {
            message: 'Hola Vue!',
            titulo: 'asdfg'
        }
    })
</script>

@endsection
