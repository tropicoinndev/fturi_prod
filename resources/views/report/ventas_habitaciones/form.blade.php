@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #FFCC80;
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
                    Reporte de ventas de habitaciones
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('cajas.ventasHabitacionesAcciones') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12 mb-2">
                                <label for="sucursal" class="form-label">Sucursal</label>
                                <select class="form-select" name="sucursal" id="sucursal">
                                    <option selected value="{{ Crypt::encryptString(0) }}">
                                        Todas
                                    </option>
                                    @foreach ($sucursales as $c)
                                        <option value="{{ $c->cid }}">
                                            {{ $c->sucursal }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="inicio" class="form-label">Del</label>
                                    <input type="date" class="form-control" id="inicio" name="inicio">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="fin" class="form-label">Al</label>
                                    <input type="date" class="form-control" id="fin" name="fin">
                                </div>
                            </div>

                            <div class="col-12 mb-3">

                                <label for="vendedores" class="form-label">Seleccione uno o mas empleados/as (Presione
                                    CTR y seleccione los necesarios)
                                </label>
                                <select class="form-select altura-select" name="vendedores[]" id="vendedores" multiple>
                                    <option selected value="{{ Crypt::encryptString(0) }}">Todos/as</option>
                                    @foreach ($vendedores as $r)
                                        <option value="{{ $r->cid }}">
                                            {{ $r->name }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-12 mb-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="resumen" name="resumen"
                                    checked>
                                <label class="form-check-label" for="resumen">
                                    Resumen de ventas
                                </label>
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
