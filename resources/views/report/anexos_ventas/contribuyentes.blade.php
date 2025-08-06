@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Creación de anexos
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: Detalle de ventas a contribuyentes
                </div>
            </div>
            <form action="{{ route('anexos.contribuyentes_accion') }}" method="post">
                @csrf
                <div class="row">
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de finalización</label>
                        <input type="date" class="form-control" id="fecha_fin" name="fecha_fin" required>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="anuladas" name="anuladas">
                            <label class="form-check-label" for="anuladas">
                                Incluir comprobantes anulados
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-light" type="submit" name="accion" value="1">
                            <span class="mdi mdi-file-find"></span>
                            Vista previa
                        </button>
                        <button class="btn btn-light" type="submit" name="accion" value="2">
                            <span class="mdi mdi-file-excel"></span>
                            Excel
                        </button>
                        <button class="btn btn-light" type="submit" name="accion" value="3">
                            <span class="mdi mdi-file-download"></span>
                            CSV
                        </button>

                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
