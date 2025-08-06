@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col" class="w-20">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Estado de habitacion</th>
                <th scope="col">Token</th>
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
                    <td>{{ $d->estado_habitacion }}</td>
                    <td>
                         @switch($d->token)
                        @case(1401)
                            <span>Vacia Limpia</span>
                            @break
                        @case(1402)
                            <span>Vacia Sucia</span>
                            @break
                        @case(1403)
                            <span>Ocupada Sucia</span>
                            @break
                        @case(1404)
                            <span>Ocupada</span>
                            @break
                        @case(1405)
                            <span>Mantenimiento</span>
                            @break
                        @case(1406)
                            <span>Bloqueo</span>
                            @break
                        @default
                            <span>Sin Definir</span>
                    @endswitch
                    </td>
                    <td>
                        <span class="text-muted">{{ $d->created_at->format('d/m/Y H:i:s') }}</span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $d->updated_at->format('d/m/Y H:i:s') }}</span>
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