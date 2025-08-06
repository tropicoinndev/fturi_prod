@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Identificación</th>
                <th scope="col">Info</th>
                <th scope="col">Regex</th>
                <th scope="col">Código hacienda</th>
                <th scope="col">Tipo de cliente</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
            <tr>
                <td>
                    <x-acciones :table="$th['table']" :d="$d" />
                </td>
                <th>{{ $loop->index + 1 }}</th>
                <td>{{ $d->identificacion }}</td>
                <td>{{ $d->info }}</td>
                <td>{{ $d->regex }}</td>
                <td>{{ $d->codigo }}</td>
                <td>@if($d->tipo_cliente)
                    <span class="text-success">
                        Natural
                    </span>
                    @else
                    <span class="text-danger">
                        Jurídico
                    </span>
                    @endif
                </td>
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

@endsection
