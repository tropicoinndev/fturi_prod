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
                    Reporte de ordenes de servicio
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('ordenes.reporteAcciones') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="cajas_id" class="form-label">Caja </label>
                            <select class="form-select altura-select" name="cajas_id[]" id="cajas_id" required multiple>
                                <option value="0" selected>Todas las cajas</option>
                                @foreach ($cajas as $c)
                                    <option value="{{ $c->id }}">
                                        {{ $c->caja }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha inicio</label>
                            <input type="date" class="form-control" name="inicio" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Fecha finalización</label>
                            <input type="date" class="form-control" name="fin" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Cliente / titular (opcional)</label>
                            <input type="text" class="form-control" name="cliente"
                                placeholder="Escriba el nombre del cliente o titular">
                        </div>
                        <div class="mb-3">
                            <div class="mb-2">Estado</div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="facturada" name="estado" value="1"
                                    checked />
                                <label class="form-check-label" for="facturada">Facturadas</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="anuladas" name="estado"
                                    value="2" />
                                <label class="form-check-label" for="anuladas">Anuladas</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="activas" name="estado"
                                    value="3" />
                                <label class="form-check-label" for="activas">Activas</label>
                            </div>

                        </div>
                        <div class="mb-3">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="detallado" name="detallado"
                                    value="1" />
                                <label class="form-check-label" for="detallado">Agregar detalle de las ordenes de
                                    servicio</label>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-light me-2" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Vista previa
                            </button>
                            <button type="submit" class="btn btn-light me-2" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span> Generar PDF
                            </button>
                            <button type="submit" class="btn btn-light me-2" value="3" name="opcion">
                                <span class="mdi mdi-file-excel-box h5"></span> Generar Excel
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
