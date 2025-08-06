@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Creación de anexos
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: Compras a sujetos excluidos
                </div>
            </div>
            <form action="{{ route('anexos.sujetos_accion') }}" method="post">
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

                        <button class="btn btn-light" type="submit" name="accion" value="4">
                            <span class="mdi mdi-file-excel"></span>
                            Excel para Visual
                        </button>

                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
