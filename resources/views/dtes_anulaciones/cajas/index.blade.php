@extends('layouts.user_dtes')

@section('user_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-remove text-danger h2"></span>
                Solicitud de anulaciones
            </h3>
            <small>
                Comprobantes anulados sin enviar a MH.
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-3 mb-2">
            <a class="btn btn-light" href="{{ route('dte_anulaciones.cajas.historia') }}" role="button">
                <span class="mdi mdi-history"></span>
                Historial de invalidaciones
            </a>
        </div>
        <div class="col-9">
            <form class="row g-3" action="{{ route('dte_anulaciones.cajas.search') }}" method="POST">
                @csrf


                <div class="col-11">
                    <input type="number" step="1" class="form-control"
                        placeholder="Buscar por correlativo interno. Debe escribir el correlativo exacto"
                        value="{{ $busqueda ?? '' }}" name="busqueda" required>
                </div>

                <div class="col-1">
                    <button class="btn btn-primary" type="submit">
                        <span class="mdi mdi-magnify"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <table class="table table-light table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Configurar</th>
                        <th scope="col">Correlativo interno</th>
                        <th scope="col">Fecha de comprobante</th>
                        <th scope="col">Fecha de anulación</th>
                        <th scope="col">Titular</th>
                        <th scope="col">Tipo de comprobante</th>
                        <th scope="col">Código de generación</th>
                        <th scope="col">Sello de recibido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anulaciones as $d)
                        <tr>
                            <th scope="row">{{ $d->comprobantes->id }}</th>
                            <td>

                                <a class="btn btn-light btn-sm"
                                    href="{{ route('dte_anulaciones.cajas.config', ['id' => Crypt::encryptString($d->id)]) }}">
                                    <span class="mdi mdi-cog"></span>
                                </a>


                            </td>
                            <td>{{ $d->correlativo }}</td>
                            <td>{{ $d->comprobantes->fecha }}</td>
                            <td>{{ $d->fecha }}</td>
                            <td>{{ $d->comprobantes->titular }}</td>
                            <td>{{ $d->comprobantes->tipoComprobantes->tipo }}</td>
                            <td>
                                @if ($d->comprobantes->dte != null && $d->comprobantes->dte->count() > 0)
                                    {{ $d->comprobantes->dte[0]->codigo_generacion }}
                                @else
                                    Sin procesar en MH
                                @endif
                            </td>
                            <td>
                                @if ($d->comprobantes->dte != null && $d->comprobantes->dte->count() > 0)
                                    {{ $d->comprobantes->dte[0]->sello_recibido }}
                                @else
                                    Sin procesar en MH
                                @endif
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        <div class="col-12">
            {{ $anulaciones->links() }}
        </div>
    </div>
@endsection
