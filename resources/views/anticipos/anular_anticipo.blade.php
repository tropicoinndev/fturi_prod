@extends('layouts.anticipos')
@section('css-anticipos')
    <style>
        :root {
            --color-primary: #263238;
            --color-secondary: #37474F;
            --color-light: #E3F2FD;
            --color-highlight: #BBDEFB;
            --color-dark: #455A64;
            --shadow-light: 1px 5px 2px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--color-background);
        }

        .panel-body {
            min-height: 91vh;
            color: #FAFAFA;
        }

        .btn-light {
            background: #DAE0E5;
        }

        .card-eventos,
        .dpl {
            background: var(--color-light);
            border: none;
            box-shadow: var(--shadow-light);
        }

        .card-anticipo {
            background-color: #FFFFFF;
            border: 1px solid var(--color-highlight);
            box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .titulo,
        .titulo-anticipo {
            color: var(--color-primary);
            font-weight: bold;
        }

        .text-eventos,
        .text-cliente,
        .total,
        .monto {
            color: var(--color-secondary);
        }

        .contactos,
        .tipo-evento,
        .text-observacion,
        .concepto {
            color: var(--color-dark);
        }

        [v-cloak] {
            display: none;
        }
    </style>
@endsection
@section('panel_anticipo')
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-12 ">
                <div class="card p-3 ">
                    <div class="card-body ">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h3>Anulación del anticipo #{{ $p->id }}</h3>
                            </div>
                            <div class="col-md-6 text-md-end mt-2 mt-md-0">
                                <a href="{{ route('anticipos.index') }}" class="btn btn-light">
                                    <span class="mdi mdi-arrow-left fs-5 me-2"></span>
                                    Volver
                                </a>
                            </div>
                        </div>
                        <div class="card-text mt-3">
                            Cliente: {{ $p->clientes->nombre }}
                        </div>
                        <div class="card-text">
                            <p>Identificación:
                                {{ $p->clientes?->identificaciones[0]?->identificaciones?->identificacion ?? 'Sin identificación' }}:{{ $p->clientes?->identificaciones[0]?->numero ?? 'Sin número' }}
                            </p>
                        </div>
                        <div class="col-12">
                            <div class="card-anticipo mt-4">
                                <div class="titulo-anticipo">Detalles del Anticipo</div>
                                <div class="monto">Fecha de aplicacion: {{ $p->aplicacion }}</div>
                                <div class="monto">Monto: ${{ number_format($p->monto, 2) }}</div>
                                <div class="monto">Turno: {{ $p->turnos->opcion->turno }}</div>
                                <div class="monto">Caja: {{ $p->turnos->cajasSucursales->caja }}</div>
                                <div class="concepto">Concepto: {{ $p->concepto }}</div>
                                <div class="concepto">Creado por: {{ $p->users->name }}</div>
                                <div class="concepto">Fecha y hora de creacion: {{ $p->creacion }}</div>
                            </div>
                        </div>
                        <div class="card-text pt-2 pb-2">
                            @if (!$p->anulado)
                                <form action="{{ route('anticipos.anulacion_anticipo') }}" method="post">
                                    @csrf
                                    <input type="hidden" value="{{ Crypt::encryptString($p->id) }}" name="id">

                                    <div class="row">
                                        <div class="col-12 mt-3">
                                            <div class="form-group">
                                                <label for="motivo_anulacion">Escriba el motivo de anulación del
                                                    anticipo:</label>
                                                <textarea name="motivo_anulacion" id="motivo_anulacion" rows="5"
                                                    placeholder="Escriba el motivo de la anulación del anticipo No. {{ $p->id }}" maxlength="300"
                                                    class="form-control" required></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-3">
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirmacion" id="confirmacion" required autocomplete="off">
                                                <label class="form-check-label" for="confirmacion">
                                                    Sí, estoy seguro de anular el anticipo por el motivo que se presentó.
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">
                                                Anular anticipo
                                            </button>
                                            <a href="{{ route('anticipos.index') }}" class="btn btn-light">
                                                Cancelar
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-primary" role="alert">
                                    Ya fue anulado este anticipo.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
