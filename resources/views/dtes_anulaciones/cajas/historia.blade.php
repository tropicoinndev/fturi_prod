@extends('layouts.user_dtes')

@section('user_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-remove text-danger h2"></span>
                DTEs INVALIDADOS
            </h3>
            <small>
                COMPROBANTES ELECTRÓNICOS INVALIDADOS EN MH.
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">

        <div class="col-3 mb-2">
            <a class="btn btn-light" href="{{ route('dte_anulaciones.cajas.solicitudes') }}" role="button">
                <span class="mdi mdi-file-edit"></span>
                Solicitudes de anulación
            </a>
        </div>
        <div class="col-9">
            <form class="row g-3" action="{{ route('dte_anulaciones.cajas.historia_search') }}" method="POST">
                @csrf

                <div class="col-11">
                    <input type="text" class="form-control" placeholder="Buscar por titular"
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
                        <th scope="col">Detalles</th>
                        <th scope="col">Fecha de invalidación</th>
                        <th scope="col">Titular</th>
                        <th scope="col">Tipo de comprobante</th>
                        <th scope="col">Código de generación</th>
                        <th scope="col">Sello de recibido</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($p as $d)
                        <tr>
                            <th scope="row">{{ $d->dte->comprobante->id }}</th>
                            <td>

                                <a class="btn btn-light btn-sm"
                                    href="{{ route('dte_anulaciones.cajas.show', ['id' => Crypt::encryptString($d->id)]) }}">
                                    <span class="mdi mdi-cog"></span>
                                </a>


                            </td>
                            <td>{{ $d->fecha_procesamiento }}</td>
                            <td>{{ $d->dte->comprobante->titular }}</td>

                            <td>{{ $d->dte->comprobante->tipoComprobantes->tipo }}</td>
                            <td>
                                {{ $d->codigo_generacion }}
                            </td>
                            <td>
                                {{ $d->sello_recibido }}
                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
        <div class="col-12">
            {{ $p->links() }}
        </div>
    </div>
@endsection
