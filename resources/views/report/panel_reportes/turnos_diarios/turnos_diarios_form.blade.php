@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #E1F5FE;
        }

        .table {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }

        .altura-select {
            min-height: 180px;
        }
    </style>
@endsection

@section('panel_reportes')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Reporte de turnos diarios
                </div>
            </div>

            {{--Formulario--}}
            <form action="{{ route('cajas.reporteTurnosDiariosAcciones') }}" method="POST" class="mb-4">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <div class="mb-2">
                            <label for="cajas_id" class="form-label">Caja (Presione CTR y seleccione los necesarios)</label>
                            <select class="form-select altura-select" id="cajas_id" name="cajas_id[]" multiple>
                                <option selected value="{{ Crypt::encryptString(0) }}">Todas las cajas</option>
                                @foreach ($cajas as $c)
                                    <option value="{{ $c->cid }}" {{ isset($caja->id) && $caja->id == $c->id ? 'selected' : '' }}>{{ $c->caja }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-2">
                            <label for="opcion_turnos_id" class="form-label">Turnos (Presione CTR y seleccione los necesarios)</label>
                            <select class="form-select altura-select" id="opcion_turnos_id" name="opcion_turnos_id[]" multiple>
                                <option selected value="{{ Crypt::encryptString(0) }}">Todos los turnos</option>
                                @foreach ($opcionesTurnos as $ot)
                                    <option value="{{ $ot->cid }}" {{ isset($opcionTurno->id) && $opcionTurno->id == $ot->id ? 'selected' : '' }}>{{ $ot->turno }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-8">
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Día</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ old('fecha', $fecha ?? '') }}">
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-start">
                        <div class="mt-4 mb-3">
                            <button class="btn btn-light mt-1 me-2" type="submit" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Vista previa
                            </button>
                            <button class="btn btn-light mt-1" type="submit" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span> PDF
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
@endsection
