@extends('layouts.dtes')

@section('dte_content')
    <div class="row  mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">Contingencias</h3>
            <small>
                Creacion de contingencia
            </small>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-12 col-lg-7">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Verificación de contingencias</h5>
                    <p class="card-text">
                    <p>
                        <b>Sucursal:</b> {{ $p->sucursales->sucursal }}
                    </p>
                    <form class="row g-3 needs-validation" method="POST" action="{{ route('mh_contingencias.store') }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ Crypt::encryptString($p->id) }}">
                        <div class="col-12">
                            <label for="fechaInicio" class="form-label">Fecha de inicio</label>
                            <input type="date" class="form-control" name="fecha_inicio" id="fechaInicio"
                                value="{{ $p->fecha_inicio }}" required>
                            <span class="text-muted">
                                La fecha de inicio debe ser igual a la fecha del primer comprobante, o todos los
                                comprobantes deben tener la misma fecha
                            </span>
                        </div>
                        <div class="col-12">
                            <label for="fechaFin" class="form-label">Fecha de finalización</label>
                            <input type="date" class="form-control" name="fecha_fin" id="fechaFin"
                                value="{{ $p->fecha_fin }}" required>
                            <span class="text-muted">
                                La fecha de finalización debe ser igual a la fecha del ultimo comprobante, o todos los
                                comprobantes deben tener la misma fecha
                            </span>
                        </div>
                        <div class="col-12">
                            <label for="hInicio" class="form-label">Hora de inicio</label>
                            <input type="time" class="form-control" name="hora_inicio" id="hInicio"
                                value="{{ Carbon::parse($p->hora_inicio)->format('H:i:01') }}" required>
                            <span class="text-muted">
                                La hora de inicio debe ser una hora menor que la hora del primer comprobante.
                            </span>
                        </div>
                        <div class="col-12">
                            <label for="hFin" class="form-label">Hora de finalización</label>
                            <input type="time" class="form-control" name="hora_fin" id="hFin"
                                value="{{ Carbon::parse($p->hora_fin)->format('H:i:01') }}" required>
                            <span class="text-muted">
                                La hora de finalización debe ser una hora mayor que la hora del ultimo comprobante.
                            </span>
                        </div>
                        <div class="col-12">
                            <label for="motivo" class="form-label">
                                Código contingencias
                            </label>
                            <input type="number" class="form-control" min="1" max="5" step="1"
                                name="tipo_contingencia" id="tipo_contingencia" value="{{ $p->tipo_contingencia }}">
                            @if ($p->tipo_contingencia != 5)
                                <b>Motivo de contingencia:</b> {{ $p->motivoContingencia }}
                            @endif
                        </div>

                        <div class="col-12">
                            <label for="motivo" class="form-label">Motivo de contingencia</label>
                            @if ($p->tipo_contingencia == 5)
                                <span class="text-danger">
                                    *Es requerido
                                </span>
                            @else
                                <span class="text-success">
                                    (No es requerido)
                                </span>
                            @endif
                            <textarea class="form-control" name="motivo_contingencia" id="motivo" rows="6"
                                placeholder="Agregue el motivo de la contingencia max:200 caracteres" maxlength="200"
                                {{ $p->tipo_contingencia == 5 ? '' : '' }}>{{ $p->tipo_contingencia == 5 ? $p->motivoContingencia : '' }}</textarea>
                        </div>


                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="confirmar" value="1"
                                    id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Confirmo que he revisado toda la informacion de la contingencia, y quiero proceder a
                                    modificarla / enviarla al ministerio de hacienda.
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit" name="opcion" value="1">Enviar</button>
                            <button class="btn btn-light" type="submit" name="opcion" value="2">Actualizar
                                contingencia</button>
                        </div>
                    </form>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-5 border border-1 border-info rounded p-3">
            <h4>
                Comprobantes agregados
            </h4>
            <div class="row">
                @foreach ($p->items as $item)
                    <div class="col-12 mb-2">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">
                                    @switch($item->dtes->tipo_dte)
                                        @case(1)
                                            <span class="badge bg-secondary">FC</span>
                                        @break

                                        @case(3)
                                            <span class="badge bg-secondary">CCF</span>
                                        @break
                                    @endswitch
                                    {{ $item->codigo_generacion }}
                                </h5>
                                <p class="card-text">
                                    <b>Fecha</b>
                                    {{ $item->dtes->comprobante?->fecha ?? $item->dtes->sujeto?->fecha }}
                                    <br>
                                    <b>Hora</b>
                                    {{ Carbon::parse($item->dtes->comprobante?->created_at ?? $item->dtes->sujeto?->created_at)->format('H:i:s') }}
                                    <br>
                                    <a class="btn btn-light text-danger"
                                        href="{{ route('dte.contingencias_items_delete', ['id' => Crypt::encryptString($item->id)]) }}">
                                        <span class="mdi mdi-delete"></span>
                                        Eliminar
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
