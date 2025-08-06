@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div class="container">
        <div class="p-2">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Creación de anexos
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: DOCUMENTOS LEGALES Y ELECTRÓNICOS, ANULADOS Y/O EXTRAVIADOS
                </div>
            </div>
            <form action="{{ route('anexos.invalidados_accion') }}" method="post">
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

                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
