@extends('layouts.dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection
@section('script-dte')
    <script src="{{ asset('js/json-viewer.js') }}"></script>
    <script>
        var jsonData = JSON.parse(@json($p->json));
        var responseData = JSON.parse(@json($p->response));

        const options = {
            showLen: false,
            showType: false,
            showBrackets: true,
            showFoldmarker: true,
            colors: {
                boolean: '#ff2929',
                null: '#ff2929',
                string: '#690',
                number: '#905',
                float: '#002f99'
            }
        }
        const jsonView = jsnview(jsonData, options);
        const responseView = jsnview(responseData, options);
        document.getElementById('json-render').appendChild(jsonView);
        document.getElementById('response-render').appendChild(responseView);
    </script>
@endsection
@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">DTE</h3>
            <small>
                DOCUMENTO {{ $p->codigo_generacion }}
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row  mb-4">
        <div class="col-12">
            @if ($p->error)
                @if ($p->comprobantes_id != null)
                    <a href="{{ route('comprobantes.api_reenviar', ['id' => Crypt::encryptString($p->comprobantes_id)]) }}"
                        class="btn btn-accion" title="Reenviar sin cambios">
                        Reenviar a MH
                    </a>
                @elseif ($p->sujeto_excluidos_id != null)
                    <a href="{{ route('sujeto_excluido.api_reenviar', ['id' => Crypt::encryptString($p->sujeto_excluidos_id)]) }}"
                        class="btn btn-accion" title="Reenviar sin cambios">
                        Reenviar a MH
                    </a>
                @endif

                <a href="{{ route('dte.nuevoCorrelativo', ['id' => Crypt::encryptString($p->id)]) }}" class="btn btn-accion"
                    title="Se borrara el correlativo y se enviara con un nuevo correlativo a MH">
                    Borrar correlativo y reenviar a MH
                </a>
            @endif
            @if ($p->sello_recibido != null)
                <a href="{{ route('comprobantes.api_sendMail', ['id' => Crypt::encryptString($p->id)]) }}"
                    class="btn btn-accion" title="Reenviar correo electrónico">Reenviar correo</a>
            @endif
            <a href="{{ route('dte.mh_api_procesados', ['id' => Crypt::encryptString($p->id)]) }}" class="btn btn-accion"
                title="Consultar en API de MH">
                Consultar DTE en MH
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">


                <button class="nav-link active" id="nav-response-tab" data-bs-toggle="tab" data-bs-target="#nav-response"
                    type="button" role="tab" aria-controls="nav-response" aria-selected="true">
                    Respuesta MH
                </button>
                <button class="nav-link" id="nav-dte-tab" data-bs-toggle="tab" data-bs-target="#nav-dte" type="button"
                    role="tab" aria-controls="nav-dte" aria-selected="false">
                    DTE
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
                <div class="col-12" id="response-render">
                </div>
            </div>
            <div class="tab-pane fade" id="nav-dte" role="tabpanel" aria-labelledby="nav-dte-tab" tabindex="0">
                <div class="col-12" id="json-render">
                </div>
            </div>
            <div class="tab-pane fade" id="nav-comprobante" role="tabpanel" aria-labelledby="nav-comprobante-tab"
                tabindex="0">
                <div class="col-12" id="comprobante-render">
                    <iframe src="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($p->id)]) }}"
                        frameborder="0" style="width: 100%; height: 90vh;" class="mt-3"></iframe>
                </div>
            </div>

        </div>
    </div>
@endsection
