@extends('layouts.clientes_panel')

@section('content_cliente')
    <div class="row p-4">
        <div class="col-12 h2 mb-4">
            REPORTES DE OPERACIONES SOSPECHOSAS
        </div>
        <div class="col-2">
            <a class="btn btn-primary " href="{{ route('ros.create') }}" role="button">Agregar</a>
        </div>
        <div class="col-10">
            <form action="{{ route('ros.search') }}" method="post">
                @csrf
                <input type="text" class="form-control" name="busqueda" aria-describedby="helpId"
                    placeholder="Ingrese el titular o fecha" value="{{ $busqueda ?? '' }}" />
            </form>
        </div>
        <div class="col-12 mt-4">
            <div class="table-responsive">
                <table class="table table-light">
                    <thead>
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">Fecha de operación</th>
                            <th scope="col">Titular</th>
                            <th scope="col">Monto</th>
                            <th scope="col">Sucursal</th>
                            <th scope="col">Clase de producto</th>
                            <th scope="col">Empleado</th>
                            <th scope="col">Comprobante</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $d)
                            <tr class="">
                                <td>
                                    <div class="dropdown open">
                                        <a class="btn btn-light" type="button" id="acciones" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <span class="mdi mdi-dots-vertical"></span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="acciones">
                                            <a class="dropdown-item" href="{{ route('ros.print', ['id' => $d->cid]) }}"
                                                target="_blank">
                                                Imprimir
                                            </a>
                                            <a class="dropdown-item" href="{{ route('ros.excel', ['id' => $d->cid]) }}"
                                                target="_blank">
                                                Exportar a excel
                                            </a>
                                            @if ($d->comprobantes_id > 0)
                                                @if ($d?->comprobante?->dteOne?->id > 0)
                                                    <a class="dropdown-item"
                                                        href="{{ route('comprobantes.api_pdfDte', ['id' => $d?->comprobante?->dteOne?->cid]) }}"
                                                        target="_blank">
                                                        Representación gráfica
                                                    </a>
                                                @endif
                                                @if ($d->comprobante->clientes_id > 0)
                                                    <a class="dropdown-item"
                                                        href="{{ route('clientes.show', ['id' => Crypt::encryptString($d->comprobante->clientes_id)]) }}"
                                                        target="_blank">
                                                        Detalles del cliente
                                                    </a>
                                                    <a class="dropdown-item"
                                                        href="{{ route('clientes.comprobantes', ['id' => Crypt::encryptString($d->comprobante->clientes_id), 'mes' => Carbon::parse($d->fecha)->format('Y-m')]) }}"
                                                        target="_blank">
                                                        Transacciones del cliente
                                                    </a>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                </td>
                                <td>
                                    {{ $d->fecha }}
                                </td>
                                <td>
                                    {{ $d->nombre }}
                                </td>
                                <td>
                                    ${{ number_format($d->monto, 2) }}
                                </td>
                                <td>
                                    {{ $d?->sucursales?->sucursal }}
                                </td>
                                <td>
                                    {{ $d->clase_producto }}
                                </td>
                                <td>
                                    {{ $d->users->name }}
                                </td>
                                <td>
                                    {{ $d?->comprobante?->correlativo ?? 'Sin comprobante adjunto' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    Aun no se han agregado registros
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
        <div class="col-12">
            {{ $data->links() }}
        </div>
    </div>
@endsection
