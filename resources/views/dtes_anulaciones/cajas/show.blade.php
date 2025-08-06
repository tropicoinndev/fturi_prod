@extends('layouts.user_dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection

@section('user_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">INVALIDACIÓN DE DTE</h3>
            <small class="text-uppercase">
                Código de generación de invalidación: {{ $p->codigo_generacion }}
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row  mb-4">
        <div class="col-12">
            @if ($p->error)
                <a href="{{ route('dte_anulaciones.cajas.config', ['id' => Crypt::encryptString($p->anulacion_comprobantes_id)]) }}"
                    class="btn btn-accion" title="Reenviar sin cambios">
                    Configurar
                </a>
            @endif
            @if (!$p->error)
                <a href="{{ route('comprobantes.api_sendMail', ['id' => Crypt::encryptString($p->dtes_id)]) }}"
                    class="btn btn-accion" title="Reenviar correo electrónico">Reenviar correo</a>
            @endif
        </div>
    </div>
    <div class="row mb-4">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">


                <button class="nav-link active" id="nav-response-tab" data-bs-toggle="tab" data-bs-target="#nav-response"
                    type="button" role="tab" aria-controls="nav-response" aria-selected="true">
                    Respuesta
                </button>
                <button class="nav-link" id="nav-comprobante-tab" data-bs-toggle="tab" data-bs-target="#nav-comprobante"
                    type="button" role="tab" aria-controls="nav-comprobante" aria-selected="false">
                    Comprobante
                </button>

            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-response" role="tabpanel" aria-labelledby="nav-response-tab"
                tabindex="0">
                <div class="col-12 mt-4">
                    @php
                        $json = json_decode($p->response);
                    @endphp

                    <div class="row">
                        <div class="col-4">
                            Estado
                        </div>
                        <div class="col-8">
                            {{ $json->estado }}
                        </div>

                        <div class="col-4">
                            Sello de recibido
                        </div>
                        <div class="col-8">
                            {{ $json->selloRecibido ?? 'No recibido' }}
                        </div>
                        <div class="col-4">
                            Código de generación
                        </div>
                        <div class="col-8">
                            {{ $json->codigoGeneracion }}
                        </div>
                        @isset($json->descripcionMsg)
                            <div class="col-4">
                                Observaciones
                            </div>
                            <div class="col-8">
                                {{ $json->descripcionMsg }}
                            </div>
                        @endisset
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="nav-comprobante" role="tabpanel" aria-labelledby="nav-comprobante-tab"
                tabindex="0">
                <div class="col-12" id="comprobante-render">
                    <iframe src="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($p->dtes_id)]) }}"
                        frameborder="0" style="width: 100%; height: 90vh;" class="mt-3"></iframe>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-cliente" role="tabpanel" aria-labelledby="nav-cliente-tab" tabindex="0">

            </div>
        </div>
    </div>
@endsection
