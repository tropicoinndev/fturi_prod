@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #FF8A65 !important;
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
                    Reporte de estadías en pos pago
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('recepciones.reporte_pospago_acciones') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" required>
                                    <small>
                                        Se mostraran todas las estadías en pos-pago menores o igual a la fecha
                                        seleccionada
                                    </small>
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
                            <button type="submit" class="btn btn-light" value="3" name="opcion">
                                <span class="mdi mdi-file-excel-box h5"></span> Generar Excel
                            </button>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
