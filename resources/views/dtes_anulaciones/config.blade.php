@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-remove text-danger h2"></span>
                Solicitud de anulación
            </h3>
            <small>
                Configuración de envió a MH
            </small>
            <x-message></x-message>


        </div>
        <div class="col-12 mt-4">
            @if ($d && $d->tipo_dte == 3)
                <a class="btn btn-light"
                    href="{{ route('comprobantes.nota_credito_create', ['id' => Crypt::encryptString($d->id)]) }}">
                    <span class="mdi mdi-file-document-plus h4"></span> Crear Nota de Crédito
                </a>
            @endif
            <a class="btn btn-light" href="{{ route('dte_anulaciones.resuelta', ['id' => Crypt::encryptString($p->id)]) }}">
                <span class="mdi mdi-notification-clear-all h4"></span> Cerrar solicitud sin procesar
            </a>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-4">Correlativo interno:</div>
        <div class="col-8">{{ $p->correlativo }}</div>

        <div class="col-4">Fecha en comprobante:</div>
        <div class="col-8">{{ $p->comprobantes->fecha }}</div>

        <div class="col-4">Fecha de solicitud de anulacion:</div>
        <div class="col-8">{{ $p->fecha }}</div>
        <div class="col-4">Tipo de anulacion:</div>
        <div class="col-8">{{ $p->anulaciones->anulacion }}</div>

        <div class="col-4">Codigo de anulacion MH:</div>
        <div class="col-8">{{ $p->anulaciones->codigo }}</div>
        <div class="col-4">Justificación:</div>
        <div class="col-8 fw-bolder"><i>{{ $p->observacion }}</i></div>
        <form class="col-12 my-3" action="{{ route('anulacion_comprobantes.editar_tipo') }}" method="POST">
            @csrf
            <input type="hidden" name="anulacion" value="{{ Crypt::encryptString($p->id) }}">
            <div class="row">
                <div class="col-4">
                    <select class="form-select" aria-label="Default select example" name="tipo_anulacion" required>
                        <option selected>Seleccione un tipo de anulación</option>
                        @foreach ($tipoAnulaciones as $a)
                            <option value="{{ Crypt::encryptString($a->id) }}"
                                {{ $p->anulaciones->id == $a->id ? 'selected' : '' }}>
                                ({{ $a->codigo }})
                                {{ $a->anulacion }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-4">
                    <button class="btn btn-light" type="submit">
                        <span class="mdi mdi-pencil"></span> Cambiar
                    </button>
                </div>
            </div>
        </form>

        @if ($d)
            <div class="col-12 text-uppercase h5 mt-3">Información del dte</div>
            <div class="col-4">Código de generación</div>
            <div class="col-8">{{ $d->codigo_generacion }}</div>

            <div class="col-4">Sello de recibido</div>
            <div class="col-8">{{ $d->sello_recibido }}</div>

            <div class="col-4">Fecha de procesamiento</div>
            <div class="col-8">{{ $d->fecha_procesamiento }}</div>
        @else
            <div class="col-12">Sin registro de DTE</div>
        @endif
        <div class="col-12 text-uppercase h5 mt-3">Información del comprobante</div>
        <div class="col-4">Titular</div>
        <div class="col-8"><b>{{ $p->comprobantes->titular }}</b></div>

        <div class="col-4">Total</div>
        <div class="col-8">${{ number_format($p->comprobantes->total, 2) }}</div>
        <div class="col-4">Gravado</div>
        <div class="col-8">${{ number_format($p->comprobantes->gravado, 2) }}</div>
        <div class="col-4">Exento</div>
        <div class="col-8">${{ number_format($p->comprobantes->exento, 2) }}</div>
        <div class="col-4">IVA</div>
        <div class="col-8">${{ number_format($p->comprobantes->iva, 2) }}</div>
        <div class="col-4">Imp. Turismo</div>
        <div class="col-8">${{ number_format($p->comprobantes->cesc, 2) }}</div>
        <div class="col-4">Ad-Valorem</div>
        <div class="col-8">${{ number_format($p->comprobantes->advalorem, 2) }}</div>
    </div>
    <div class="row mb-4">
        <div class="col-12">
            @if ($p->comprobantes->clientes_id == null)
                <div class="col-12 mb-4">
                    <div class="alert alert-warning" role="alert">
                        <h4 class="alert-heading">Sin cliente registrado</h4>
                        No es permitido realizar invalidaciones en clientes sin registro.
                    </div>
                </div>
            @else
                @if (!$valid)
                    <div class="col-12 mb-4">
                        <div class="alert alert-warning" role="alert">
                            <h4 class="alert-heading">Tiempo de anulacion caducado</h4>
                            No es permitido realizar invalidaciones a este comprobante, porque ha pasado el tiempo
                            permitido, puede realizar una nota de crédito sobre este comprobante.
                        </div>
                    </div>
                @else
                    <form class="row g-3" action="{{ route('dte_anulaciones.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ Crypt::encryptString($d->id) }}">

                        @if ($p->anulaciones->codigo != 2)
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="" class="form-label">Código generación DTE sustituye</label>
                                    <input type="text" class="form-control" name="codigoGeneracionR"
                                        placeholder="Ingrese el código de generación" required
                                        pattern="^[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-4[a-fA-F0-9]{3}-[89abAB][a-fA-F0-9]{3}-[a-fA-F0-9]{12}$" />
                                </div>
                            </div>
                        @endif

                        <div class="col-12">
                            <div class="mb-3">
                                <label for="solicitante" class="form-label">Solicitante de anulación</label>
                                <select class="form-select" id="solicitante" name="solicitante"
                                    aria-label="Default select example" required>
                                    @if ($solicitantes != null && count($solicitantes) == 1)
                                        <option selected value="{{ $solicitantes[0]->solicitantes->id }}">
                                            {{ $solicitantes[0]->solicitantes->nombre_completo }}
                                            ({{ $solicitantes[0]->solicitantes->numero_documento }})</option>
                                    @elseif(count($solicitantes) > 1)
                                        <option selected>Seleccione un solicitante</option>
                                        @foreach ($solicitantes as $s)
                                            <option value="{{ $s->id }}">
                                                {{ $s->nombre_completo }}
                                                ({{ $s->numero_documento }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option>Antes debe agregar uno o mas solicitantes para continuar</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">

                                <label for="solicitante" class="form-label">Responsable de la anulación</label>
                                <select class="form-select" id="responsable" name="responsable"
                                    aria-label="Default select example" required>
                                    @if (count($p->users->empleado) > 0 && $empleados != null && count($empleados) == 1)
                                        <option selected value="{{ $empleados[0]->id }}">
                                            {{ $empleados[0]->nombre_completo }}
                                            ({{ $empleados[0]->numero_documento }})</option>
                                    @elseif(count($empleados) > 0)
                                        <option selected>Seleccione un solicitante</option>
                                        @foreach ($empleados as $e)
                                            <option value="{{ $e->id }}">
                                                {{ $e->nombre_completo }}
                                                ({{ $e->numero_documento }})
                                            </option>
                                        @endforeach
                                    @else
                                        <option>Antes debe agregar uno o mas solicitantes para continuar</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="confirm"
                                    name="confirm">
                                <label class="form-check-label" for="confirm">
                                    Confirmo que la anulación es valida.
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">
                                <span class="mdi mdi-send-check"></span> Procesar anulación
                            </button>

                        </div>
                    </form>
                @endif
            @endif
        </div>
    </div>
@endsection
