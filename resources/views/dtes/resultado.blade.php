@extends('layouts.cajas')
@section('panel_caja')
    <h3 class="card-title">Resultado del DTE</h3>
    <div class="container h-100">
        <div class="row mt-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body text-uppercase">
                            <div class="row mb-2">
                                <div class="col-12">
                                    {{ $json->sujetoExcluido?->nombre }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Código Generación
                                </div>
                                <div class="col-9">
                                    {{ $dte->codigo_generacion }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Correlativo
                                </div>
                                <div class="col-9">
                                    {{ $dte->correlativo }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Sello de recibido
                                </div>
                                <div class="col-9">
                                    {{ $dte->sello_recibido }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Total
                                </div>
                                <div class="col-9">
                                    ${{ number_format($json->resumen->totalPagar, 2) }}
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-uppercase">
                        <div class="card-title">Resultado del DTE</div>
                        <div class="card-text">

                            @php
                                $rs = json_decode($dte->response);
                            @endphp
                            <div class="row">
                                <div class="col-3">
                                    Estado:
                                </div>
                                <div class="col-9 fw-bold {{ $dte->error ? 'text-danger' : 'text-success' }}">
                                    <span class="h3 mdi {{ !$dte->error ? 'mdi-check-bold' : 'mdi-alert-circle' }}"></span>
                                    {{ $rs->estado }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    Procesamiento:
                                </div>
                                <div class="col-9">
                                    {{ $dte->fecha_procesamiento }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3">
                                    Mensaje MH:
                                </div>
                                <div class="col-9">
                                    {{ $rs->descripcionMsg }}
                                </div>
                            </div>
                            @if (!$dte->error)
                                <div class="row">
                                    <div class="col-3">Imprimir representación gráfica</div>
                                    <div class="col-9">
                                        <a href="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($dte->id)]) }}"
                                            target="_blank" class="btn btn-light">
                                            <span class="mdi mdi-printer"></span>
                                            Imprimir
                                        </a>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-12">Enviar a otro correo:</div>
                                    <div class="col-12">
                                        <form method="POST" action="{{ route('comprobantes.api_sendMailNotRegister') }}">
                                            @csrf
                                            <input type="hidden" name="id"
                                                value="{{ Crypt::encryptString($dte->id) }}">
                                            <div class="mb-2">
                                                <input type="email" name="correo" class="form-control"
                                                    placeholder="cliente@empresa.com" aria-describedby="helpId" required />
                                            </div>
                                            <div class="mb-3">
                                                <button class="btn btn-light" type="submit">
                                                    <span class="mdi mdi-email-arrow-right h5"></span>
                                                    Enviar
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
