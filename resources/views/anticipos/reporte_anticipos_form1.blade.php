@extends('layouts.anticipos')

@section('panel_anticipo')
<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12 col-lg-6 text-uppercase">
                    <h3 class="card-title">
                        Reporte de anticipos activos
                    </h3>
                    <p class="text-muted">
                        Reportes
                    </p>
                </div>
            </div>

            {{--Botones y caja de búsqueda--}}
            <div class="row mb-3">
                <div class="col-12">
                    <form action="{{ route('anticipos.reporteAnticiposAcciones1') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="cajas_id" class="form-label">Caja (Presione CTR y seleccione los necesarios)</label>
                                <select class="form-select altura-select" name="cajas_id[]" id="cajas_id" multiple>
                                    <option selected value="{{ Crypt::encryptString(0) }}">Todas las cajas</option>
                                    @foreach($cajas as $c)
                                        <option value="{{ $c->cid }}" {{ isset($caja->id) && $caja->id == $c->id ? 'selected' : '' }}>{{ $c->caja }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="inicio" class="form-label">Fecha inicio:</label>
                                <input type="date" class="form-control" id="inicio" name="inicio">
                            </div>
                            <div class="col-12 mb-3">
                                <label for="fin" class="form-label">Fecha fin:</label>
                                <input type="date" class="form-control" id="fin" name="fin">
                            </div>

                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-light me-2" value="1" name="opcion">
                                    <span class="mdi mdi-magnify h5"></span> Vista previa
                                </button>
                                <button type="submit" class="btn btn-light" value="2" name="opcion">
                                    <span class="mdi mdi-file-pdf-box h5"></span> Generar PDF
                                </button>
                                <button type="submit" class="btn btn-light" value="3" name="opcion">
                                    <span class="mdi mdi-file-excel-box h5"></span> Generar EXCEL
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
