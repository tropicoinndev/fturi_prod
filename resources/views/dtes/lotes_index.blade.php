@extends('layouts.dtes')
@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Enviados por lotes</h3>
            <small>
                Envió de comprobantes en forma de lotes
            </small>
            <x-message></x-message>
        </div>
    </div>


    <div class="row mb-4">

        <div class="col-12">
            <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">ID</th>
                        <th scope="col">Código de lote</th>
                        <th scope="col">Creación</th>
                        <th scope="col">Enviado</th>
                        <th scope="col">Fecha Procesado</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Response</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($lotes as $d)
                        <tr>
                            <td>
                                <a class="btn btn-light"
                                    href="{{ route('dte.lotes_details', ['id' => Crypt::encryptString($d->id)]) }}">
                                    <span class="mdi mdi-text-box-search-outline h5"></span>
                                </a>
                            </td>
                            <td>
                                {{ $d->id }}
                            </td>
                            <td>
                                {{ $d->codigo_lote }}
                            </td>
                            <td>
                                {{ $d->created_at }}
                            </td>
                            <td>
                                {{ $d->recibido ? 'Recibido por MH' : 'Sin recibir por MH' }}
                            </td>

                            <td>
                                {{ $d->fecha_procesamiento }}
                            </td>
                            <td>
                                {{ $d->users->user }}
                            </td>
                            <td>
                                @if ($d->recibido)
                                    @php

                                        $rs = json_encode($d->response);
                                    @endphp
                                    <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                        data-bs-title="Respuesta del DTE" data-bs-content='{{ $rs ?? 'Sin respuesta' }}'>
                                        <span class="mdi mdi-information-outline h5"></span>
                                        Info
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
@endsection
