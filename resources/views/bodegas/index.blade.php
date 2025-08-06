@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Bodega</th>
                <th scope="col">Colores</th>
                <th scope="col">Tipo</th>
                <th scope="col">Fecha creación</th>
                <th scope="col">Fecha edición</th>
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->bodega }}</td>
                    <td>
                        <div class="rounded-5 text-center" style="background-color: {{ $d->color_fondo }}; height:25px;"> <p style="color: {{ $d->color_texto }};">prueba</p></div>
                    </td>
                    <td>
                        @if($d->tipo == 1)
                            General
                        @elseif($d->tipo == 2)
                            Ventas
                        @elseif($d->tipo == 3)
                            Producción
                        @elseif($d->tipo == 4)
                            Administrativa
                        @endif
                    </td>
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
