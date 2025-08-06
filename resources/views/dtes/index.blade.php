@extends('layouts.dtes')
@section('dte_content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Documentos Tributarios electrónicos</h3>
            <small>
                Dashboard
            </small>
            <x-message></x-message>
        </div>
    </div>
    @if ($contingencia != null)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-indicador card-contingencia h-100">
                    <div class="row">
                        <div class="col-2 icon">
                            <span class="mdi mdi-server-network-off"></span>
                        </div>
                        <div class="col-8">
                            <div class="card-header">{{ Carbon::parse($contingencia->inicio)->diffForHumans() }}</div>
                            <div class="card-body">
                                <p class="card-text text-uppercase">
                                    Contingencia: ({{ $contingencia->contigencias->codigo }}) ->
                                    {{ $contingencia->contigencias->valor }}
                                </p>
                            </div>
                        </div>
                        <div class="col-2 m-auto">
                            <button type="button" class="btn btn-light" data-bs-toggle="modal"
                                data-bs-target="#desactivarContingencias">
                                Desactivar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="desactivarContingencias" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Desactivar contingencia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <form class="row g-3 needs-validation" method="POST"
                        action="{{ route('job_contingencias.desactivar') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ Crypt::encryptString($contingencia->id) }}">
                        <div class="modal-body row">
                            <div class="col-12 mb-4 mt-4">
                                <h5>Contingencia ({{ $contingencia->contigencias->codigo }}) ->
                                    {{ $contingencia->contigencias->valor }} </h5>
                                Iniciada:
                                <b>
                                    {{ Carbon::parse($contingencia->inicio)->diffForHumans() }}
                                </b>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="true" name="confirm"
                                        id="invalidCheck" required>
                                    <label class="form-check-label" for="invalidCheck">
                                        Confirmo que quiero desactivar la contingencia activa, porque ya es posible utilizar
                                        el sistema de transición de MH.
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Desactivar contingencias</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <!--Token-->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador {{ $api != null ? 'card-gray' : 'card-disable' }} h-100">
                <div class="row">
                    <div class="col-9">
                        @if ($api != null)
                            <div class="card-header">{{ $api->terminacion }}</div>
                        @else
                            <div class="card-header">--</div>
                        @endif
                        <div class="card-body">
                            @if ($api != null)
                                <p class="card-text text-uppercase">
                                    Renovación de token en MH
                                </p>
                            @else
                                <p class="card-text text-uppercase">
                                    <a class="btn btn-light me-1 h4" href="{{ route('mh.login') }}">
                                        <span class="mdi mdi-account-key"></span>
                                    </a>
                                    Aun sin autenticación
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-3 icon">
                        <span class="mdi mdi-shield-lock"></span>
                    </div>
                </div>
            </div>
        </div>
        <!--DTE Procesados-->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador card-green h-100">
                <div class="row">
                    <div class="col-9">
                        <div class="card-header">{{ $procesados }}</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                DTE Procesados
                            </p>
                        </div>
                    </div>
                    <div class="col-3 icon">
                        <span class="mdi mdi-file-document-check"></span>
                    </div>
                </div>
            </div>
        </div>
        <!--DTE Rechazados-->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador {{ $errores > 0 ? 'card-orange' : 'card-disable' }} h-100">
                <div class="row">
                    <div class="col-9">
                        <div class="card-header">{{ $errores }}</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                DTE Rechazados
                            </p>
                        </div>
                    </div>
                    <div class="col-3 icon">
                        <span class="mdi mdi-file-document-remove"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!--Consulta a MH-->
        <div class="col-12 col-lg-5 mb-4">
            <div class="card card-indicador h-100" :class="{ 'card-green': statusMh, 'card-orange': !statusMh, }">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">API MH</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                @{{ messageMh }}
                            </p>
                            <p class="card-text text-uppercase">
                                <button class="btn btn-light me-2 text-uppercase" type="button" @click="getStatusMh()">
                                    <div class="spinner-border text-dark spinner-border-sm" role="status"
                                        v-if="progres">
                                        <span class="visually-hidden">..</span>
                                    </div>
                                    <span v-if="!progres">
                                        Consulta
                                    </span>
                                </button>
                                <a href="{{ env('HOST_API') }}/fesv/status" class="btn btn-light" type="button"
                                    target="_blank">
                                    URL
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-server" v-if="statusMh"></span>
                        <span class="mdi mdi-server-off" v-if="!statusMh"></span>
                    </div>
                </div>
            </div>
        </div>
        <!--Consulta a Firmador-->
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador h-100" :class="{ 'card-green': statusFirma, 'card-orange': !statusFirma, }">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">API Firmado</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                @{{ messageFirma }}
                            </p>
                            <p class="card-text text-uppercase">
                                <button class="btn btn-light me-2 text-uppercase" type="button"
                                    @click="getStatusFirma()">
                                    <div class="spinner-border text-dark spinner-border-sm" role="status"
                                        v-if="progresFirma">
                                        <span class="visually-hidden">..</span>
                                    </div>
                                    <span v-if="!progresFirma">
                                        Consulta
                                    </span>
                                </button>
                                <a :href="firmaStatus" class="btn btn-light" type="button" target="_blank">
                                    URL
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-server-outline" v-if="statusFirma"></span>
                        <span class="mdi mdi-server-off" v-if="!statusFirma"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-3 mb-4 ">
            <div class="card card-indicador card-red h-100">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">{{ $comprobantes->count() }}</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                Comprobantes sin enviar
                            </p>
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-file-document-alert"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4 mb-4">
            <div
                class="card card-indicador  h-100 {{ $contingencia == null ? 'card-light-green' : 'card-contingencia' }}">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">Contingencia</div>
                        <div class="card-body">

                            @if ($contingencia == null)
                                <p class="card-text text-uppercase">
                                    Evitar el envió a MH
                                </p>
                                <p class="card-text text-uppercase">
                                    <button class="btn btn-light" data-bs-toggle="modal"
                                        data-bs-target="#addContingencia" type="button">Agregar</button>
                                </p>
                            @else
                                <p>
                                    Contingencia iniciada
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-send-clock"></span>
                    </div>
                </div>
            </div>
        </div>

        @if ($contingencia == null)
            <div class="modal fade" id="addContingencia" tabindex="-1" aria-labelledby="addContingenciaLabel"
                aria-hidden="true">
                <div class="modal-dialog        ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Agregar contingencia</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <form class="row g-3 needs-validation" action="{{ route('job_contingencias.store') }}"
                            method="POST">
                            @csrf
                            <div class="modal-body">
                                <x-message></x-messa>
                                    <div class="col-12 mb-4">
                                        <label for="contingencia" class="form-label">Contingencia</label>
                                        <select class="form-select" id="contingencia" name="contingencias_id" required>
                                            <option selected disabled value="">Seleccione una contingencia</option>
                                            @forelse ($contingencias as $cg)
                                                <option value="{{ Crypt::encryptString($cg->id) }}">{{ $cg->valor }}
                                                </option>
                                            @empty
                                                <option value="">
                                                    No se han agregado contingencias
                                                </option>
                                            @endforelse
                                        </select>
                                    </div>

                                    <div class="col-12 mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="confirm" id="invalidCheck" required>
                                            <label class="form-check-label" for="invalidCheck">
                                                Confirmo que quiero habilitar la transmisión en contingencia. Al activarse
                                                no se
                                                enviara ningún DTE, deberán enviarse individualmente o en forma de lotes.
                                            </label>
                                        </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-primary">Iniciar contingencia</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador card-purple h-100">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">Horizon</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                Jobs
                            </p>
                            <p class="card-text text-uppercase">
                                <a href="{{ route('horizon.index') }}" class="btn btn-light" type="button"
                                    target="_blank">Dashboar</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-email-check"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 mb-4">
            <div class="card card-indicador card-telescope h-100">
                <div class="row">
                    <div class="col-8">
                        <div class="card-header">Telescope</div>
                        <div class="card-body">
                            <p class="card-text text-uppercase">
                                Monitoreo
                            </p>
                            <p class="card-text text-uppercase">
                                <a href="{{ route('telescope') }}" class="btn btn-light" type="button"
                                    target="_blank">Dashboar</a>
                            </p>
                        </div>
                    </div>
                    <div class="col-4 icon">
                        <span class="mdi mdi-server-security"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        @foreach ($sucursales as $s)
            @php
                $card = $loop->index % 2 == 0 ? 'card-sucursal-1' : 'card-sucursal-2';
            @endphp
            <div class="col-12 col-lg-6 mb-4">
                <div class="card card-indicador {{ $card }} h-100 m-auto">
                    <div class="row">
                        <div class="col-2 icon">
                            <span class="mdi mdi-bank"></span>
                        </div>
                        <div class="col-10">
                            <div class="card-body">
                                <span class="float-end text-uppercase">
                                    @if ($s->matriz)
                                        Casa matriz
                                    @else
                                        Sucursal
                                    @endif
                                </span>
                                <div class="h4">{{ $s->sucursal }}</div>
                                <div class="row">
                                    <div class="col-6">
                                        Código de establecimiento:
                                    </div>
                                    <div class="col-6">
                                        {{ $s->codigo_establecimiento }}
                                    </div>
                                </div>
                                <div class="card-text">
                                    Correlativos
                                </div>

                                @forelse ($s->allCorrelativoSucursal as $c)
                                    <div class="row">
                                        <div class="col-10">
                                            @isset($c->documento[$c->tipo_dte])
                                                {{ $c->documento[$c->tipo_dte] }}
                                            @else
                                                Documento sin clasificar
                                            @endisset
                                        </div>
                                        <div class="col-2">
                                            {{ $c->actual }}
                                        </div>
                                    </div>
                                @empty
                                    <div class="row">
                                        <div class="col-12">
                                            Sin correlativos
                                        </div>
                                    </div>
                                @endforelse

                            </div>
                        </div>

                    </div>


                </div>
            </div>
        @endforeach


    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card  p-4 border-0 card-table">
                <h4>
                    ERRORES DE PROCESAMIENTO
                </h4>
                @if (count($dteError) > 0)
                    <table class="table table-striped  table-inverse mt-4">
                        <thead class="thead-inverse">
                            <tr>
                                <th>#</th>
                                <th>Revisar</th>
                                <th>Titular</th>
                                <th>Procesamiento</th>
                                <th>Estado</th>
                                <th>Mensaje</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dteError as $e)
                                @php
                                    $rs = json_decode($e->response);
                                @endphp
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a class="btn nav-link"
                                            href="{{ route('dte.documento', ['id' => Crypt::encryptString($e->id)]) }}"
                                            role="button">
                                            <span class="mdi mdi-text-box-search h4"></span>
                                        </a>
                                    </td>
                                    <td>{{ $e->comprobante?->titular }} {{ $e->sujeto?->titular }}</td>
                                    <td>{{ $e->created_at }}</td>
                                    <td>{{ $rs->estado ?? '' }}</td>
                                    <td>({{ $rs->codigoMsg ?? '' }}) -> {{ $rs->descripcionMsg ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-light" role="alert">
                        <strong>Sin errores de procesamiento</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card  p-4 border-0 card-table-1">
                <h4>
                    COMPROBANTES SIN DTES
                </h4>
                @if (count($comprobantes) > 0)
                    <table class="table table-striped  table-inverse mt-4">
                        <thead class="thead-inverse">
                            <tr>
                                <th>#</th>
                                <th>Reintentar</th>
                                <th>Titular</th>
                                <th>Creación</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($comprobantes as $c)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <a class="btn nav-link"
                                            href="{{ route('comprobantes.api_reenviar', ['id' => $c->cid]) }}"
                                            role="button">
                                            <span class="mdi mdi-refresh h4"></span>
                                        </a>
                                    </td>
                                    <td>{{ $c->titular }}</td>
                                    <td>{{ $c->creacion }}</td>
                                    <td>Error, comprobante sin DTE</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-light" role="alert">
                        <strong>Sin errores de procesamiento</strong>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

@section('script-dte')
    <script type="module">
        var app = window.appVue({
            data() {
                return {
                    statusMh: false,
                    messageMh: 'SIN RESPUESTA',
                    urlStatus: "{{ env('HOST_API') . '/fesv/status' }}",
                    statusFirma: false,
                    messageFirma: 'SIN RESPUESTA',
                    firmaStatus: "{{ route('firmador.status') }}",
                    progres: false,
                    progresFirma: false,
                }
            },
            mounted() {
                this.getStatusMh()
                this.getStatusFirma()
            },
            methods: {
                getStatusMh: function() {
                    if (!this.progres) {
                        this.progres = true;
                        axios.get(this.urlStatus).then((r) => {
                            if (r.data == "SERVICIO ACTIVO")
                                this.statusMh = true;
                            this.messageMh = r.data;
                            this.setProgres();
                        }).catch((err) => {
                            this.messageMh = r.data;
                            this.setProgres();
                        });
                    } else alert('Solicitud en progreso, espere hasta que termine');
                },
                setProgres: function() {
                    setTimeout(() => {
                        this.progres = false;
                    }, 1000);
                },
                getStatusFirma: function() {
                    if (!this.progresFirma) {
                        this.progresFirma = true;
                        axios.get(this.firmaStatus).then((r) => {
                            if (r.data && r.data.status)
                                this.statusFirma = r.data.status;
                            this.messageFirma = r.data.response;
                            this.setProgresFirma();
                        }).catch((err) => {
                            this.messageFirma = "Sin conexion";
                            this.setProgresFirma();
                        });
                    } else alert('Solicitud en progreso, espere hasta que termine');
                },
                setProgresFirma: function() {
                    setTimeout(() => {
                        this.progresFirma = false;
                    }, 1000);
                },

            },
        }).
        mount("#appDte");
    </script>
@endsection
