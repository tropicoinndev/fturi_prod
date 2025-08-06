@extends('layouts.cajas')
@section('css-caja')
    <style>
        body {
            background: #E0F7FA;
        }
    </style>
@endsection
@section('panel_caja')
    <div class="row">
        <div class="col-7  border border-dark-subtle rounded p-4">
            @php
                $isValid = true;
                $messages = [];

                if ($p->clientes_id == null) {
                    $isValid = false;
                    array_push($messages, 'No se pueden invalidar DTE sin un cliente registrado.');
                } else {
                    $dte = $p->dteOne;
                    if ($dte == null) {
                        $isValid = false;
                        array_push(
                            $messages,
                            'Este comprobante no tiene un DTE, solicite la eliminación del comprobante en el caso que no sea requerido.',
                        );
                    } else {
                        $json = json_decode($dte->json);
                        if (
                            $dte->tipo_dte == 1 &&
                            (!isset($json?->receptor?->numDocumento) || $json?->receptor?->numDocumento == null)
                        ) {
                            $isValid = false;
                            array_push($messages, 'No es posible anular DTEs sin un documento de identidad.');
                        } else {
                            $cliente = $p->clientes_solicitante;
                            if ($cliente->solicitantes == null || count($cliente->solicitantes) < 1) {
                                $isValid = false;
                                array_push(
                                    $messages,
                                    'Este cliente no tiene un solicitante agregado, debe agregar un solicitante para poder crear la solicitud de anulación. (Actualice esta pagina posterior al registro del solicitante)',
                                );
                            }
                        }
                    }
                }
            @endphp
            @if (!$isValid)
                <div class="alert alert-danger" role="alert">
                    <strong>Se encontraron errores que no permiten anular este comprobante:</strong>
                    <ul>
                        @foreach ($messages as $msg)
                            <li>
                                {{ $msg }}
                            </li>
                        @endforeach
                    </ul>
                    @if ($p->clientes_id != null)
                        <a href="{{ route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)]) }}"
                            target="_blank" class="btn btn-light">Ver cliente</a>
                    @endif
                </div>
            @else
                <form action="{{ route('anulacion_comprobantes.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{ $p->id }}">
                    <div class="row">
                        <div class="col-12 h5 text-uppercase">
                            Formulario de anulación
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="anulaciones_id">Motivo de anulación:</label>
                                <select class="form-select" name="anulaciones_id" id="anulaciones_id"
                                    aria-label="Default select example" required>
                                    <option selected value="">Seleccione el motivo de la anulación</option>
                                    @foreach ($anulaciones as $a)
                                        <option value="{{ Crypt::encryptString($a->id) }}">
                                            {{ $a->anulacion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="form-group">
                                <label for="observacion">Observaciones:</label>
                                <textarea class="form-control" name="observacion" id="observacion" rows="8" required
                                    placeholder="Escriba aqui la razon por la que se realizara la anulación del comprobante No. {{ $p->correlativo }} (max. 200 caracteres)"
                                    maxlength="200">{{ old('observacion') }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" name="confirm" id="confirm"
                                    required>
                                <label class="form-check-label" for="confirm">
                                    Confirmo, quiero anular este comprobante.
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Anular comprobantes</button>
                            <a href="{{ route('anulacion_comprobantes.index') }}" class="btn btn-light">Cancelar</a>
                            <a href="{{ route('clientes.show', ['id' => Crypt::encryptString($p->clientes_id)]) }}"
                                target="_blank" class="btn btn-light">Ver cliente</a>
                        </div>
                    </div>
                </form>
            @endif
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-body border-1 border-info text-bg-light">
                    <div class="card-text">
                        <div class="row">
                            <div class="col-12 text-uppercase fw-bolder h5">
                                Información del comprobante
                            </div>
                            <div class="col-4">
                                Tipo de comprobante:
                            </div>
                            <div class="col-8">
                                {{ $p->tipoComprobantes->tipo }}
                            </div>

                            <div class="col-4">
                                Titular:
                            </div>
                            <div class="col-8">
                                {{ $p->titular }}
                            </div>
                            <div class="col-4">
                                Neto:
                            </div>
                            <div class="col-8">

                                ${{ number_format($p->neto, 2) }}
                            </div>
                            <div class="col-4">
                                Propina:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->propina, 2) }}
                            </div>
                            <div class="col-4">
                                IVA:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->iva, 2) }}
                            </div>
                            <div class="col-4">
                                CET:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->cesc, 2) }}
                            </div>
                            <div class="col-4">
                                Retencion:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->precepcion, 2) }}
                            </div>
                            <div class="col-4">
                                Ad-Valorem:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->advalorem, 2) }}
                            </div>
                            <div class="col-4">
                                Gravado:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->gravado, 2) }}
                            </div>
                            <div class="col-4">
                                Exento:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->exento, 2) }}
                            </div>
                            <div class="col-4">
                                Total:
                            </div>
                            <div class="col-8">
                                ${{ number_format($p->total, 2) }}
                            </div>
                            <div class="col-12 fw-bolder">
                                Detalles de creación
                            </div>
                            <div class="col-4">
                                Fecha:
                            </div>
                            <div class="col-8">
                                {{ $p->fecha }}
                            </div>
                            <div class="col-4">
                                Turno:
                            </div>
                            <div class="col-8">
                                {{ $p->turnos->opcion->turno }}
                            </div>
                            <div class="col-4">
                                Caja:
                            </div>
                            <div class="col-8">
                                {{ $p->turnos->cajas->caja }}
                            </div>
                            <div class="col-4">
                                Usuario:
                            </div>
                            <div class="col-8">
                                {{ $p->users->name }} · {{ $p->users->email }}
                            </div>
                            <div class="col-4">
                                Creación:
                            </div>
                            <div class="col-8">
                                {{ $p->created_at }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="card">
            <div class="card-header text-uppercase">
                Detalle de activación de cuentas
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($cobro->detalleCobros as $d)
                        <div class="col-3 mb-3">
                            @switch($d->origen)
                                @case(1)
                                    <div class="card border border-dark-subtle">
                                        <div class="card-body">
                                            <h5 class="card-title">Orden</h5>
                                            <p class="card-text">
                                                No. {{ $d->origen_id }}
                                            </p>
                                        </div>
                                    </div>
                                @break

                                @case(2)
                                    <div class="card border border-dark-subtle">
                                        <div class="card-body">
                                            <h5 class="card-title">Estadía</h5>
                                            <p class="card-text">
                                                No. {{ $d->origen_id }}
                                            </p>
                                        </div>
                                    </div>
                                @break

                                @case(3)
                                    <div class="card border border-dark-subtle">
                                        <div class="card-body">
                                            <h5 class="card-title">Comanda</h5>
                                            <p class="card-text">
                                                No. {{ $d->origen_id }}
                                            </p>
                                        </div>
                                    </div>
                                @break
                            @endswitch
                        </div>
                    @endforeach
                    @foreach ($cobro->anticipos as $a)
                        <div class="col-3 mb-3">
                            <div class="card border border-dark-subtle">
                                <div class="card-body">
                                    <h5 class="card-title">Anticipos</h5>
                                    <p class="card-text">
                                        No. {{ $a->anticipos_id }} · Monto: ${{ number_format($a->monto, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
@endsection
