@extends('layouts.anticipos')

@section('panel_anticipo')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 col-lg-6">
                        <h3 class="card-title text-capitalize">
                            Separar Anticipo {{ $p->id }}
                        </h3>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        @if ($p->monto > 0)
                            <form action="{{ route('anticipos.separarStore') }}" method="post">
                                @csrf
                                <input type="hidden" name="id" value="{{ $p->cid }}" required>
                                <div class="mb-2">
                                    <b>Monto disponible:</b> ${{ number_format($p->monto, 2) }}
                                </div>
                                <div class="mb-2">
                                    <b>Monto Histórico:</b> ${{ number_format($p->monto, 2) }}
                                </div>
                                <div class="mb-2 text-uppercase">
                                    <b>Turno:</b> {{ $p->turnos->opcion->turno }} {{ $p->turnos->fecha }}
                                    · {{ $p->turnos->cajas->caja }}
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Monto a separar</label>
                                    <input type="number" class="form-control" min="0.01" step="0.01" name="monto"
                                        max="{{ $p->monto }}" aria-describedby="helpId"
                                        placeholder="Ingrese el monto a separar" required />

                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Justificación</label>
                                    <textarea class="form-control" name="observaciones"
                                        placeholder="Las observaciones a continuación serán agregadas a la descripción del anticipo" required></textarea>

                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                            id="confirm" required>
                                        <label class="form-check-label" for="confirm">
                                            Confirmo que quiero separar el anticipo Nº
                                            {{ $p->id }}
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary " type="submit">Separar</button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-danger" role="alert">
                                Este anticipo no se puede separar por que el monto es igual a
                                ${{ number_format($p->monto, 2) }}
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
