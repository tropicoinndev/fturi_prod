@extends('layouts.list')

@section('list')

<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Acciones</th>
                <th scope="col">#</th>
                <th scope="col">Rubros</th>
                <th scope="col">Servicio</th>
                <th scope="col">P. Unitario</th>
                <th scope="col">P. Sugerido</th>
                <th scope="col">IVA</th>
                <th scope="col">CESC</th>
                <th scope="col">Advalorem</th>
                <th scope="col">Propina</th>
                <th scope="col">Descuento</th>
                <th scope="col">Precios</th>
                <th scope="col">Estado</th>
                {{-- <th scope="col">Fecha creacion</th>
                <th scope="col">Fecha edicion</th> --}}
            </tr>
        </thead>
        <tbody>
            @forelse($p as $d)
                <tr>
                    <td><x-acciones :table="$th['table']" :d="$d"/></td>
                    <th>{{ $loop->index + 1 }}</th>
                    <td>{{ $d->rubros->rubro ?? 'no posee' }}</td>
                    <td>{{ $d->servicio }}</td>
                    <td>$ {{ number_format($d->precio_unitario, 2) }}</td>
                    <td>$ {{ number_format($d->sugerido, 2) }}</td>
                    <td>
                        @if($d->iva)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->cesc)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->advalorem)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->propina)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->descuento)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->precios)
                            <span class="badge bg-success">Si</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </td>
                    <td>
                        @if($d->estado)
                            <span class="text-success">Activo</span>
                        @else
                            <span class="text-danger">Inactivo</span>
                        @endif
                    </td>
                    {{-- <td>
                        <span class="text-muted">{{ $d->created_at }}</span>
                    </td>
                    <td>
                        <span class="text-muted">{{ $d->updated_at }}</span>
                    </td> --}}
                </tr>
            @empty
                <tr><td colspan="12">Aun no se han agregado datos.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
