@extends('layouts.bodegas   ')

@section('panel_bodega')
    <h2 style="text-transform: uppercase;">HISTORIAL DE SOLICITUDES</h2>
    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
        <form action="{{ route($th['table'] . '.search') }}" method="post">
            @csrf
            <input type="text" class="form-control" placeholder="Buscar por id de requisicion y por bodegas ..." id="txtBusqueda" name="txtBusqueda"
                value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr>

                    <th scope="col">N#</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Solicitud</th>
                    <th scope="col">Bodega entrada</th>
                    <th scope="col">Bodega salida</th>
                    <th scope="col">Usuario crea</th>
                    <th scope="col">Usuario autoriza</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Imprimir</th>
                    {{-- <th scope="col">Fecha Creacion</th>
                <th scope="col">Fecha de Modificacion</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse($p as $d)
                    <tr>

                        <th>{{ $loop->index + 1 }}</th>
                        <td>{{ $d->fecha }}</td>
                        <td>{{ $d->solicitud }}</td>
                        <td>{{ $d->relacionBodegasEntrada->bodega ?? '' }}</td>
                        <td>{{ $d->relacionBodegasSalida->bodega ?? '' }}</td>
                        <td>{{ $d->relacionUsuarios->name }}</td>
                        <td>{{ $d->relacionUserAutorizacion->name ?? 'sin autorizar' }}</td>
                        <td>
                            {{ $d->estado == 1 ? 'Activa' : ($d->estado == 2 ? 'Completada' : ($d->estado == 3 ? 'Autorizada' : 'Anulada')) }}
                        </td>
                        <td>
                            <a href="{{ route('requisiciones.printRequisicion', ['id' => Crypt::encryptString($d->id)]) }}"
                                class="btn btn-primary">
                                Imprimir
                            </a>
                        </td>
                        {{-- <td><span class="text-muted">{{ $d->created_at }}</span></td>
                    <td><span class="text-muted">{{ $d->updated_at }}</span></td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="12">Aun no se han agregado datos.</td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
    {{ $p->links() }}
@endsection
