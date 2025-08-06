@extends('layouts.dtes_list')

@section('list')
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="w-20">Acciones</th>
                            <th scope="col">#</th>
                            <th scope="col">Caja</th>
                            <th scope="col">Colores</th>

                            <th scope="col">IP</th>
                            <th scope="col">Sucursal</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Hospedaje</th>
                            <th scope="col">Codigo MH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($p as $d)
                            <tr>
                                <td>
                                    <x-acciones :table="$th['table']" :d="$d" />
                                </td>
                                <th>{{ $loop->index + 1 }}</th>
                                <td>{{ $d->caja }}</td>
                                <td>
                                    <div class="rounded-5 text-center"
                                        style="background-color: {{ $d->color_fondo }}; color: {{ $d->color_texto }};"
                                        title="Fondo:{{ $d->color_fondo }} Color de texto:{{ $d->color_texto }}">
                                        {{ $d->caja }}
                                    </div>

                                </td>

                                <td>{{ $d->ip ?? 'No se imprimirán tickets' }}</td>
                                <td>{{ $d->Sucursales->sucursal }}</td>
                                <td>
                                    @if ($d->estado)
                                        <span class="text-success">Activo</span>
                                    @else
                                        <span class="text-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($d->hospedaje)
                                        <span class="text-success">Cobros habilitados</span>
                                    @else
                                        <span class="text-danger">Cobros deshabilitados</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $d->codigo_punto_venta ?? 'no posee' }}

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
        </div>
    </div>
@endsection
