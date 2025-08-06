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
                    Reporte de crédito a empleados
                </div>
            </div>

            {{--Formulario--}}
            <form action="{{ route('clientes.reporteCreditoEmpleadosAcciones') }}" method="POST" class="mb-4">
                @csrf

                <div class="row mb-3">
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
                    <div class="col-12">
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="sinAbonos" name="sinAbonos">
                                <label class="form-check-label" for="sinAbonos">
                                    Solo sin abonos registrados
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 d-flex align-items-center justify-content-start">
                        <div class="mb-3">
                            <button class="btn btn-light mt-1 me-2" type="submit" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span> Vista previa
                            </button>
                            {{-- <button class="btn btn-light mt-1" type="submit" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span> PDF
                            </button> --}}
                            <button class="btn btn-light mt-1" type="submit" value="3" name="opcion">
                                <span class="mdi mdi-file-excel-box h5"></span> Excel
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
@endsection
