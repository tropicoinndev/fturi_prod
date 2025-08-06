@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-document-remove text-danger h2"></span>
                Eliminar comprobante
            </h3>
            <small>
                Confirmación
            </small>
            <x-message></x-message>
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
        <div class="col-4">Justificación:</div>
        <div class="col-8 fw-bolder"><i>{{ $p->observacion }}</i></div>

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
        <div class="col-12 mt-2">
            <div class="alert alert-warning" role="alert">
                Este comprobante no fue registrado en el sistema del ministerio de hacienda.
            </div>
        </div>
    </div>

    <form action="{{ route('dte_anulaciones.comprobante_destroy') }}" method="post">
        @csrf
        <input type="hidden" name="id" value="{{ $p->cid }}">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="confirm" name="confirm" required>
            <label class="form-check-label" for="confirm">
                Confirmo que quiero borrar este comprobante, porque no es requerido para ningún turno.
            </label>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn btn-danger">
                <span class="mdi mdi-delete h5"></span>
                Eliminar
            </button>
        </div>
    </form>
@endsection
