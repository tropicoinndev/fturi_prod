@extends('layouts.bodegas')

@section('panel_bodega')
    <div id="appExistencias" class="p-4">
        <div class="row mb-4">
            <div class="col-12 text-uppercase h3">
                Reporte de vencimientos
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <form action="{{ route('existencias.reporte_vencimiento_acciones') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <div class="mb-3">
                            <label for="" class="form-label">Fecha de proximidad de vencimientos</label>
                            <input type="text" class="form-control" name="fecha" id=""
                                value="{{ $prox }}" aria-describedby="helpId" placeholder="Escriba aquí..." />
                            <small id="helpId" class="form-text text-muted">
                                Se mostraran los productos con fecha de
                                vencimiento menor o igual a la fecha definida
                            </small>
                        </div>

                    </div>

                    <div class="mb-3">
                        <label for="" class="form-label">Bodegas</label>
                        <select multiple class="form-select form-select-lg" name="bodegas[]" id=""
                            style="height: 300px;">
                            <option selected value="0">TODAS LAS BODEGAS</option>
                            @foreach ($bodegas as $b)
                                <option value="{{ $b->id }}">{{ strtoupper($b->bodega) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-light" type="submit" name="accion" value="1">
                            <span class="mdi mdi-file-find h4"></span>
                            Vista previa
                        </button>
                        <button class="btn btn-light" type="submit" name="accion" value="2">
                            <span class="mdi mdi-file-pdf-box h4"></span>
                            Exportar a PDF
                        </button>
                        <button class="btn btn-light" type="submit" name="accion" value="3">
                            <span class="mdi mdi-file-excel-box h4"></span>
                            Exportar a Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
