@extends('layouts.dtes')
@section('css-dte')
    <link rel="stylesheet" href="{{ asset('css/json-viewer.css') }}">
@endsection
@section('script-dte')
    <script src="{{ asset('js/json-viewer.js') }}"></script>
    <script>
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

        const responseView = jsnview(responseData, options);

        document.getElementById('response-render').appendChild(responseView);
    </script>
@endsection

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Lote de DTES</h3>
            <small>
                Envió de comprobantes por lote
            </small>
            <x-message></x-message>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-4 col-12">
            Fecha procesamiento
        </div>
        <div class="col-lg-8 col-12">
            {{ $p->fecha_procesamiento }}
        </div>
        <div class="col-lg-4 col-12">
            Código generación
        </div>
        <div class="col-lg-8 col-12">
            {{ $p->codigo_lote }}
        </div>

    </div>
    <div class="row mb-4">
        <div class="col-12">
            <a class="btn btn-light" href="{{ route('dte.lotes_api_procesados', ['id' => Crypt::encryptString($p->id)]) }}"
                role="button">
                <span class="mdi mdi-download-box h5"></span>
                Descargar procesados
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">


                <button class="nav-link active" id="nav-dtes-tab" data-bs-toggle="tab" data-bs-target="#nav-dtes"
                    type="button" role="tab" aria-controls="nav-dtes" aria-selected="true">
                    DTEs
                </button>
                <button class="nav-link" id="nav-response-tab" data-bs-toggle="tab" data-bs-target="#nav-response"
                    type="button" role="tab" aria-controls="nav-response" aria-selected="false">
                    RESPUESTA MH
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- DTE dtes de envió -->
            <div class="tab-pane fade show active" id="nav-dtes" role="tabpanel" aria-labelledby="nav-dtes-tab"
                tabindex="0">


                <div class="row mt-4">

                    <div class="col-12">
                        <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                            <thead>
                                <tr>

                                    <th scope="col">DTE</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Codigo de generación</th>
                                    <th scope="col">Info</th>
                                    <th scope="col">Response</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($p->items as $d)
                                    <tr>
                                        <td>
                                            {{ $d->dte->id }}
                                        </td>
                                        <td>
                                            {{ $d->dte->comprobante?->titular ?? $d->dte->sujeto?->titular }}
                                        </td>
                                        <td>
                                            {{ $d->dte->comprobante?->fecha ?? $d->dte->sujeto?->fecha }}
                                        </td>
                                        <td>
                                            {{ $d->dte->codigo_generacion }}
                                        </td>

                                        <td>
                                            @php
                                                $msg = json_encode($d->dte->response);
                                                $rs = json_decode($d->dte->response);
                                            @endphp
                                            <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                data-bs-title="Respuesta del DTE" data-bs-content='{{ $msg }}'>
                                                <span class="mdi mdi-information-outline h5"></span>
                                                Info
                                            </button>

                                        </td>
                                        <td>
                                            @if ($rs && isset($rs->descripcionMsg))
                                                {{ $rs->descripcionMsg }}
                                            @else
                                                Sin info.
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade show" id="nav-response" role="tabpanel" aria-labelledby="nav-response-tab"
                tabindex="0">
                <div class="col-12" id="response-render">
                </div>
            </div>

        </div>
    </div>
@endsection
