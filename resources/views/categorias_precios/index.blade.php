@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Categoría</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Token</th>
                    <th scope="col">Rubro</th>
                    <th scope="col">Descripción</th>
                    <th scope="col">Fecha creación</th>
                    <th scope="col">Fecha edición</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td><x-acciones :table="$th['table']" :d="$d"/></td>
                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->categoria }}</td>
                        <td>
                            @if($d->estado)
                                <span class="text-success">Activo</span>
                            @else
                                <span class="text-danger">Inactivo</span>
                            @endif
                        </td>
                        <td>
                            @if($d->token =="1101")
                                <span class="text-success">Productos bajo inventario</span>
                            @elseif($d->token =="1102")
                                <span class="text-success">Bebidas preparadas</span>
                            @elseif($d->token =="1103")
                                <span class="text-success">Platos</span>
                            @elseif($d->token =="1104")
                                <span class="text-success">Combos-promoción</span>
                            @elseif($d->token =="1105")
                                <span class="text-success">Productos sin existencia</span>
                            @endif
                        </td>
                        <td>{{ $d->rubros->rubro }}</td>
                        <td>{{ $d->descripcion }}</td>
                        <td><span class="text-muted">{{ $d->created_at }}</span></td>
                        <td><span class="text-muted">{{ $d->updated_at }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3">Aun no se han agregado datos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
