@extends('layouts.cajas')
@section('panel_caja')
    <div id="appComprobantes">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 h3 text-uppercase mb-2">
                    Creación de Nota de crédito
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-12 col-lg-2 text-uppercase">
                    DTE Relacionado:
                </div>
                <div class="col-12 col-lg-10">
                    {{ $dte->codigo_generacion }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Sello de recibido:
                </div>
                <div class="col-12 col-lg-10">
                    {{ $dte->sello_recibido ?? 'DTE no fue recibido' }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Correlativo interno:
                </div>
                <div class="col-12 col-lg-10">
                    {{ $dte->comprobante->correlativo }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Tipo de comprobante:
                </div>
                <div class="col-12 col-lg-10">
                    {{ $dte->comprobante->tipoComprobantes->tipo }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Titular:
                </div>
                <div class="col-12 col-lg-10">
                    {{ $dte->comprobante->titular }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Neto:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->neto, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    {{ env('cesc') * 100 }}% Imp. Turismo:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->cesc, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    {{ env('advalorem') * 100 }}% Ad-Valorem:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->advalorem, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    {{ env('iva') * 100 }}% IVA:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->iva, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    (-) {{ env('percepcion') * 100 }}% IVA:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->percepcion, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Gravadas:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->gravado, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    Exentas:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->exento, 2) }}
                </div>
                <div class="col-12 col-lg-2 text-uppercase">
                    TOTAL:
                </div>
                <div class="col-12 col-lg-10">
                    ${{ number_format($dte->comprobante->total, 2) }}
                </div>
            </div>
            <div class="row">
                <div class="col-12 h4">
                    Detalles del comprobante
                    <!-- Modal trigger button -->
                    <button type="button" class="btn btn-light float-end" data-bs-toggle="modal" data-bs-target="#modalId">
                        <span class="mdi mdi-file-document-multiple-outline h5"></span>
                        Representación gráfica
                    </button>
                </div>

                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Detalles</th>
                                <th scope="col">Unitario</th>
                                <th scope="col">No gravadas</th>
                                <th scope="col">Exentas</th>
                                <th scope="col">Gravadas</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dte->comprobante->detalles as $d)
                                <tr>
                                    <th scope="row">{{ $loop->index + 1 }}</th>
                                    <td>{{ $d->cantidad }}</td>
                                    <td>{{ $d->concepto }}</td>
                                    <td>${{ number_format($d->neto, 2) }}</td>
                                    <td>${{ number_format($d->cantidad * $d->propina, 2) }}</td>
                                    <td>${{ number_format($d->cantidad * $d->exento, 2) }}</td>
                                    <td>${{ number_format($d->cantidad * $d->gravado, 2) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
            @if ($dte->estado == null || $dte->estado == 'RECHAZADO' || $dte->sello_recibido == null)
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No se encontró registrado este DTE, solicite una actualización a soporte si esta seguro que este
                            DTE se
                            encuentra registrado en el ministerio de hacienda.
                            <a class="btn btn-light"
                                href="mailto:{{ env('MAIL_SOPORTE') }}?subject=Actualización de DTE&body=Solicito la actualización del DTE: {{ $dte->codigo_generacion }}, no tiene los datos necesarios para realizar una Nota de crédito."
                                role="button">
                                <span class="mdi mdi-email-arrow-right-outline h5"></span> Solicitar actualización
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row mt-4">
                    <div class="col-12 h3">
                        Confirmación y envió
                    </div>
                    <div class="col-12">
                        @if ($correlativo == null)
                            <div class="alert alert-danger" role="alert">
                                Antes de crear una nota de crédito debe agregar un correlativo interno para Notas de
                                crédito.
                            </div>
                        @else
                            <form action="{{ route('comprobantes.nota_credito_store') }}" method="post">
                                @csrf
                                <input type="hidden" name="dtes_id" value="{{ Crypt::encryptString($dte->id) }}">

                                <div class="mb-3">
                                    <label for="observaciones" class="form-label">Observaciones del
                                        comprobantes</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                        placeholder="Agregue todas las observaciones necesarias para la creación de este comprobante" maxlength="200"></textarea>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="confirm"
                                            name="confirm" required>
                                        <label class="form-check-label" for="confirm">
                                            Confirmo que revise todos los datos en el comprobante, y procede de acuerdo a
                                            las
                                            necesidades de {{ env('empresa') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">
                                        <span class="mdi mdi-content-save-check h5"></span>
                                        Guardar y enviar DTE
                                    </button>
                                </div>

                            </form>
                        @endif
                    </div>
                </div>
            @endif
        </div>


        <div class="modal fade" id="modalId" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
            role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-fullscreen" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            REPRESENTACIÓN GRÁFICA
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <iframe src="{{ route('comprobantes.api_pdfDte', ['id' => Crypt::encryptString($dte->id)]) }}"
                            frameborder="0" style="width: 100%; height: 100%;" class="mt-3"></iframe>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cerrar
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
