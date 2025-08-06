@extends('layouts.cajas')
@section('panel_caja')
    <h3 class="card-title">Resultado del comprobante</h3>
    <div class="container h-100" id="appResultado">
        <div class="row mt-4">
            @if ($comprobante->regulada != null)
                <div class="col-12 mb-4">

                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Operación regulada</h4>
                        <p>
                            Esta operación requiere el llenado del FORMULARIO DE OPERACIONES REGULADAS
                            <a class="btn btn-light"
                                href="{{ route('operaciones_reguladas.create', ['id' => $comprobante->regulada->cid]) }}"
                                role="button" target="_blank">
                                Ir al formulario
                            </a>

                        </p>
                        <hr>
                        <p class="mb-0">
                            <b>Art. 9 Ley de Lavado de Dinero y Activos:</b>
                            Las instituciones deberán completar este formulario por operaciones realizadas por
                            los clientes, sea individual o multiple, que en un mismo dia sobrepasen los
                            $10,000.00 en efectivo o $25,000.00 en cheque.
                        </p>
                    </div>
            @endif

            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-body text-uppercase">
                            <div class="row mb-2">
                                <div class="col-12">
                                    {{ $comprobante->titular }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    CORRELATIVO
                                </div>
                                <div class="col-9">
                                    {{ $comprobante->correlativo }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    CLIENTE
                                </div>
                                <div class="col-9">
                                    {{ $comprobante->clientes != null ? $comprobante->clientes->nombre : 'Sin cliente registrado' }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    NETO
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->neto, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    IVA ({{ env('iva', '0.13') * 100 }}%)
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->iva, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Ad-Valorem ({{ env('advalorem', '0.05') * 100 }}%)
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->advalorem, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    CET ({{ env('cesc', '0.05') * 100 }}%)
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->cesc, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Propina ({{ env('propina', '0.1') * 100 }}%)
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->propina, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    retencion (-{{ env('percepcion', '0.01') * 100 }}%)
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->percepcion, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    total
                                </div>
                                <div class="col-9">
                                    ${{ number_format($comprobante->total, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Recibe
                                </div>
                                <div class="col-9">
                                    ${{ number_format($recibe, 2) }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 text-muted">
                                    Cambio
                                </div>
                                <div class="col-9 fw-bold">
                                    ${{ number_format($cambio, 2) }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-uppercase">
                        <div class="card-title">Formas de pagos</div>
                        <div class="card-text">
                            @foreach ($pagos as $p)
                                <div class="row">
                                    <div class="col-3">
                                        {{ $p->forma_pagos->forma }}
                                    </div>
                                    <div class="col-9">
                                        ${{ number_format($p->monto, 2) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body text-uppercase">
                        <div class="card-title">Resultado del DTE</div>
                        <div class="card-text">
                            @if (count($comprobante->dte) > 0)
                                @php
                                    $dte = $comprobante->dte[0];
                                    $rs = json_decode($comprobante->dte[0]->response);
                                @endphp
                                <div class="row">
                                    <div class="col-3">
                                        Estado:
                                    </div>
                                    <div class="col-9 fw-bold {{ $dte->error ? 'text-danger' : 'text-success' }}">
                                        <span
                                            class="h3 mdi {{ !$dte->error ? 'mdi-check-bold' : 'mdi-alert-circle' }}"></span>
                                        {{ $rs?->estado }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Código de generación:
                                    </div>
                                    <div class="col-9">
                                        {{ $dte->codigo_generacion }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Correlativo:
                                    </div>
                                    <div class="col-9">
                                        {{ $comprobante->dte[0]->correlativo }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        Sello de recibido:
                                    </div>
                                    <div class="col-9">
                                        {{ $dte->sello_recibido }}
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
                                        {{ $rs?->descripcionMsg }}
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
                                            <button type="button" @click="setPrint()" class="btn btn-light">
                                                <span class="mdi mdi-invoice-list-outline"></span>
                                                Imprimir ticket
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-12">Enviar a otro correo:</div>
                                        <div class="col-12">
                                            <form method="POST"
                                                action="{{ route('comprobantes.api_sendMailNotRegister') }}">
                                                @csrf
                                                <input type="hidden" name="id"
                                                    value="{{ Crypt::encryptString($dte->id) }}">
                                                <div class="mb-2">
                                                    <input type="email" name="correo" class="form-control"
                                                        placeholder="cliente@empresa.com" aria-describedby="helpId"
                                                        required />
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
                    @else
                        <div class="alert alert-danger" role="alert">
                            Este correlativo no se registro en nuestra base de datos, por favor verifique que se
                            haya creado en el ministerio de hacienda. Antes de intentar reenviar.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>


    </div>
    </div>
@endsection
@section('script-caja')
    @if (count($comprobante->dte) > 0)
        @php
            $dte = $comprobante->dte[0];
            $json = json_decode($dte->json);
        @endphp
        <script type="module">
            var app = appVue({
                data() {
                    return {
                        correlativo: '{{ $comprobante->correlativo }}',
                        identificacion: @json($json->identificacion),
                        receptor: @json($json->receptor),
                        emisor: @json($json->emisor),
                        cuerpo: @json($json->cuerpoDocumento),
                        resumen: @json($json->resumen),
                        sello: '{{ $dte->sello_recibido }}',

                        ip: '{{ session('caja')->ip }}',
                    }

                },
                methods: {
                    setPrint: function() {
                        let url = "https://admin.factura.gob.sv/consultaPublica?ambiente=" +
                            this.identificacion.ambiente +
                            "&codGen=" + this.identificacion.codigoGeneracion +
                            "&fechaEmi=" + this.identificacion.fecEmi;
                        this.identificacion.url = url;
                        this.identificacion.selloRecibido = this.sello;

                        const json = {
                            correlativo: this.correlativo,
                            identificacion: this.identificacion,
                            receptor: this.receptor,
                            cuerpo: this.cuerpo,
                            resumen: this.resumen,
                            emisor: this.emisor,
                        }
                        const data = {
                            tipo: 1,
                            data: JSON.stringify(json)
                        }
                        this.enviarDatosPorWebSocket(data);
                    },
                    async connSocket() {
                        return new Promise((resolve, reject) => {
                            const socket = new WebSocket('ws://' + this.ip + ':8000');
                            socket.onopen = function(openEvent) {
                                console.log('Conexión WebSocket establecida');
                                resolve(socket);
                            };
                            socket.onerror = function(err) {
                                console.error('Error en la conexión WebSocket:', err);
                                reject(err);
                            };
                        });
                    },

                    async enviarDatosPorWebSocket(data) {
                        try {
                            console.log(JSON.stringify(data));
                            console.log(JSON.stringify(data).length)
                            const socket = await this.connSocket();
                            socket.send(JSON.stringify(data));
                            socket.close();

                        } catch (error) {
                            alert(
                                "Ocurrió un problema al imprimir. Verifique que el driver esté activo y recargue la página."
                            );
                            console.error('Error al establecer la conexión WebSocket:', error);
                        }
                    },
                },
            });

            app.mount("#appResultado");
        </script>
    @endif
@endsection
