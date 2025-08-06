@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #FFAB91 !important;
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
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Reporte de turnos diarios
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('cajas.reporteTurnosDiariosAcciones') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-2">
                                    <label for="cajas_id" class="form-label">Caja </label>
                                    <select class="form-select altura-select" name="cajas_id[]" id="cajas_id" required
                                        multiple>
                                        <option value="{{ Crypt::encryptString(0) }}">Todas las cajas</option>
                                        @foreach ($cajas as $c)
                                            <option value="{{ $c->cid }}"
                                                {{ isset($caja->id) && $caja->id == $c->id ? 'selected' : '' }}>
                                                {{ $c->caja }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="inicio" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha">
                                </div>
                            </div>


                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dia" value="1"
                                        id="dia_s">
                                    <label class="form-check-label" for="dia_s">
                                        Mostrar según dia seleccionado
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="dia" value="0"
                                        id="dia_n" checked>
                                    <label class="form-check-label" for="dia_n">
                                        Mostrar según turnos del dia seleccionado
                                    </label>
                                </div>


                            </div>

                        </div>

                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-light me-2" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Vista previa
                            </button>
                            <button type="submit" class="btn btn-light" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span> Generar PDF
                            </button>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
