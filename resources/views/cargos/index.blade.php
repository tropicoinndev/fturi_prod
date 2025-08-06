@extends('layouts.list')

@section('list')
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>
                    <th scope="col">Acciones</th>
                    <th scope="col">#</th>
                    <th scope="col">Cargos</th>
                    <th scope="col">Monto</th>
                    <th scope="col">IVA</th>
                    <th scope="col">CESC</th>
                    <th scope="col">Propina</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha creación</th>
                    <th scope="col">Fecha modificación</th>
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>
                        <td>
                            <x-acciones :table="$th['table']" :d="$d" />
                        </td>

                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->cargo }}</td>
                        <td>${{ number_format($d->precio, 2) }}</td>
                        <td>
                            @if ($d->iva)
                                <span class="text-success">Incluye</span>
                            @else
                                <span class="text-danger">No incluye</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->cesc)
                                <span class="text-success">Incluye</span>
                            @else
                                <span class="text-danger">No incluye</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->propina)
                                <span class="text-success">Incluye</span>
                            @else
                                <span class="text-danger">No incluye</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->estado)
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
        {{ $p->links() }}
    </div>
@endsection
