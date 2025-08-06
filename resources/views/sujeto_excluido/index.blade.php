@extends('layouts.cajas')
@section('panel_caja')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="text-uppercase">Sujetos excluidos</h3>
            </div>
        </div>
        <div class="row my-4">
            <div class="col-3">
                @if ($correlativo != null)
                    <a class="btn btn-primary" href="{{ route('sujeto_excluido.create') }}" role="button">
                        Agregar FESE
                        #{{ $correlativo->actual == 0 ? $correlativo->inicio : $correlativo->actual + 1 }}
                    </a>
                @else
                    <div class="alert alert-danger" role="alert">
                        Para agregar SE debe agregar un correlativo.
                    </div>
                @endif
            </div>
            <div class="col-9">
                <form action="{{ route('sujeto_excluido.search') }}" method="post">
                    @csrf
                    <input type="text" class="form-control" name="busqueda"
                        placeholder="Buscar por titular, o correlativo" value="{{ $busqueda ?? '' }}" />
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">#</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Correlativo</th>
                            <th scope="col">Titular</th>
                            <th scope="col">Renta</th>
                            <th scope="col">Compra</th>
                            <th scope="col">Total</th>
                            <th scope="col">Creado</th>
                            <th scope="col">Autorizado</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $d)
                            <tr>
                                <td>
                                    <div class="dropdown open">
                                        <button class="btn btn-light" type="button" id="triggerId"
                                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <span class="mdi mdi-cog"></span>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="triggerId">
                                            <a class="dropdown-item"
                                                href="{{ route('sujeto_excluido.detalles', ['id' => $d->cid]) }}">Detalle</a>
                                            @if ($d->enviado && $d->estado)
                                                <a class="dropdown-item"
                                                    href="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($d->dte->id)]) }}"
                                                    target="_blank">
                                                    Imprimir
                                                </a>
                                            @endif
                                            @if (!$d->enviado && $d->estado && !$d->completo)
                                                <a class="dropdown-item text-danger"
                                                    href="{{ route('sujeto_excluido.confirm', ['id' => $d->cid]) }}">
                                                    Eliminar
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->fecha }}</td>
                                <td>{{ $d->correlativo }}</td>
                                <td>{{ $d->titular }}</td>
                                <td>$ {{ number_format($d->detalles->sum('renta'), 2) }}</td>
                                <td>$ {{ number_format($d->detalles->sum('compra'), 2) }}</td>
                                <td>$ {{ number_format($d->detalles->sum('compra') + $d->detalles->sum('renta'), 2) }}</td>
                                <td class="text-uppercase">{{ $d->realiza->user }}</td>
                                <td class="text-uppercase">{{ $d->autoriza?->user ?? 'Sin autorizar' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No se han encontrado registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="col-12">
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
