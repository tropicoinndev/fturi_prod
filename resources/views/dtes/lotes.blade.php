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
            <h3 class="card-title text-uppercase">Contingencias</h3>
            <small>
                Envió de comprobantes en contingencias
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
            Sello de recibido
        </div>
        <div class="col-lg-8 col-12">
            {{ $p->sello_recibido ?? 'Sin recibir' }}
        </div>
        <div class="col-lg-4 col-12">
            Código generación
        </div>
        <div class="col-lg-8 col-12">
            {{ $p->codigo_generacion }}
        </div>
        @if ($p->sello_recibido == null)
            <div class="col-12 mt-2">
                <a class="btn btn-light" type="button"
                    href="{{ route('dte.contingencias_mh', ['id' => Crypt::encryptString($p->id)]) }}">Volver a
                    configurar</a>
            </div>
        @endif
        @if (strlen($p->sello_recibido) > 30 && !$p->resuelto && !$p->mh)
            <div class="col-12 mt-2">
                <a href="{{ route('mh_contingencias.status', ['id' => Crypt::encryptString($p->id)]) }}"
                    class="btn btn-light">
                    <span class="mdi mdi-check h5"></span>
                    Aplicar como resuelto
                </a>
            </div>
        @endif
    </div>
    <div class="row mb-4">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">


                <button class="nav-link active" id="nav-pendientes-tab" data-bs-toggle="tab"
                    data-bs-target="#nav-pendientes" type="button" role="tab" aria-controls="nav-pendientes"
                    aria-selected="true">
                    DTES PENDIENTES
                </button>
                <button class="nav-link" id="nav-lote-tab" data-bs-toggle="tab" data-bs-target="#nav-lote" type="button"
                    role="tab" aria-controls="nav-lote" aria-selected="false">
                    LOTES PROCESADOS
                </button>
                <button class="nav-link" id="nav-dte-tab" data-bs-toggle="tab" data-bs-target="#nav-dte" type="button"
                    role="tab" aria-controls="nav-dte" aria-selected="false">
                    DTE PROCESADOS
                </button>
                <button class="nav-link" id="nav-contingencia-tab" data-bs-toggle="tab" data-bs-target="#nav-contingencia"
                    type="button" role="tab" aria-controls="nav-contingencia" aria-selected="false">
                    DETALLES DE CONTINGENCIA
                </button>
                <button class="nav-link" id="nav-response-tab" data-bs-toggle="tab" data-bs-target="#nav-response"
                    type="button" role="tab" aria-controls="nav-response" aria-selected="false">
                    RESPUESTA MH
                </button>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <!-- DTE Pendientes de envió -->
            <div class="tab-pane fade show active" id="nav-pendientes" role="tabpanel" aria-labelledby="nav-pendientes-tab"
                tabindex="0">
                @if (count($p->items_pendientes) == 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                Sin DTEs pendientes de envió
                            </div>
                        </div>
                    </div>
                @else
                    <form action="{{ route('dte.lote_store') }}" method="post">
                        @csrf
                        <div class="row mt-4">
                            <div class="col-12 col-lg-8 mb-3">
                                <button class="btn btn-primary" type="submit" name="opciones" value="1">
                                    Enviar lote
                                </button>
                                <button class="btn btn-primary" type="submit" name="opciones" value="2">
                                    Envió individual
                                </button>
                            </div>
                            <div class="col-12">
                                <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col"></th>
                                            <th scope="col">DTE</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Codigo de generación</th>
                                            <th scope="col">Info</th>
                                            <th scope="col">Response</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($p->items_pendientes as $d)
                                            <tr>
                                                <th>
                                                    @if ($loop->index < 100)
                                                        <input class="form-check-input" type="checkbox" name="dtes[]"
                                                            value="{{ Crypt::encryptString($d->dtes->id) }}" checked
                                                            id="dtes_{{ $d->id }}">
                                                    @endif
                                                </th>
                                                <td>
                                                    <label for="dtes_{{ $d->id }}">
                                                        {{ $d->dtes->id }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="dtes_{{ $d->id }}">
                                                        {{ $d->dtes->comprobante?->titular ?? $d->dtes->sujeto?->titular }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="dtes_{{ $d->id }}">
                                                        {{ $d->dtes->comprobante?->fecha ?? $d->dtes->sujeto?->fecha }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <label for="dtes_{{ $d->id }}">
                                                        {{ $d->dtes->codigo_generacion }}
                                                    </label>
                                                </td>

                                                <td>
                                                    @php
                                                        $msg = json_encode($d->dtes->response);
                                                        $rs = json_decode($d->dtes->response);
                                                    @endphp
                                                    <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                        data-bs-title="Respuesta del DTE"
                                                        data-bs-content='{{ $msg }}'>
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
                            @if (count($p->items_pendientes) > 15)
                                <div class="col-12 mb-4">
                                    <!--button class="btn btn-primary" type="submit" name="opciones" value="1">Enviar lote</button-->
                                    <button class="btn btn-primary" type="submit" name="opciones" value="2">
                                        Envió individual
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
            <div class="tab-pane fade" id="nav-lote" role="tabpanel" aria-labelledby="nav-lote-tab" tabindex="0">
                @if (count($p->items_lotes_procesados) == 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                Sin DTEs procesados aun
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">DTE</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Codigo de generación</th>
                                        <th scope="col">Info</th>
                                        <th scope="col">Response</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($p->items_lotes_procesados as $d)
                                        <tr>
                                            <th>
                                                @if ($loop->index < 100)
                                                    <input class="form-check-input" type="checkbox" name="dtes[]"
                                                        value="{{ Crypt::encryptString($d->dtes->id) }}" checked
                                                        id="dtes_{{ $d->id }}">
                                                @endif
                                            </th>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->id }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->comprobante?->titular ?? $d->dtes->sujeto?->titular }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->comprobante?->fecha ?? $d->dtes->sujeto?->fecha }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->codigo_generacion }}
                                                </label>
                                            </td>

                                            <td>
                                                @php
                                                    $msg = json_encode($d->dtes->response);
                                                    $rs = json_decode($d->dtes->response);
                                                @endphp
                                                <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                    data-bs-title="Respuesta del DTE"
                                                    data-bs-content='{{ $msg }}'>
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
                    </form>
                @endif
            </div>
            <div class="tab-pane fade" id="nav-dte" role="tabpanel" aria-labelledby="nav-dte-tab" tabindex="0">
                @if (count($p->items_procesados) == 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-success" role="alert">
                                Sin DTEs procesados aun
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row mt-4">
                        <div class="col-12">
                            <table class="table table-light table-striped table-hover table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">DTE</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Codigo de generación</th>
                                        <th scope="col">Info</th>
                                        <th scope="col">Response</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($p->items_procesados as $d)
                                        <tr>
                                            <th>
                                                @if ($loop->index < 100)
                                                    <input class="form-check-input" type="checkbox" name="dtes[]"
                                                        value="{{ Crypt::encryptString($d->dtes->id) }}" checked
                                                        id="dtes_{{ $d->id }}">
                                                @endif
                                            </th>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->id }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->comprobante?->titular ?? $d->dtes->sujeto?->titular }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->comprobante?->fecha ?? $d->dtes->sujeto?->fecha }}
                                                </label>
                                            </td>
                                            <td>
                                                <label for="dtes_{{ $d->id }}">
                                                    {{ $d->dtes->codigo_generacion }}
                                                </label>
                                            </td>

                                            <td>
                                                @php
                                                    $msg = json_encode($d->dtes->response);
                                                    $rs = json_decode($d->dtes->response);
                                                @endphp
                                                <button type="button" class="btn btn-light" data-bs-toggle="popover"
                                                    data-bs-title="Respuesta del DTE"
                                                    data-bs-content='{{ $msg }}'>
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
                    </form>
                @endif
            </div>
            <div class="tab-pane fade" id="nav-contingencia" role="tabpanel" aria-labelledby="nav-contingencia-tab"
                tabindex="0">
                <div class="col-12" id="json-render">
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
