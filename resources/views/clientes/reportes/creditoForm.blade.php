@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Reporte de créditos
            </h5>

            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('clientes.reporte.creditosAcciones') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="depto" class="form-label">Departamentos</label>
                                <select class="form-select" name="depto" id="depto">
                                    <option selected value="0">
                                        Todos
                                    </option>
                                    @foreach ($departamentos as $c)
                                        <option value="{{ $c->id }}">
                                            {{ $c->departamento }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="fecha" class="form-label">Hasta</label>
                                <input type="date" class="form-control" id="fecha" name="fecha">
                            </div>
                            <div class="col-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cliente" id="todos"
                                        checked value="0">
                                    <label class="form-check-label" for="todos">
                                        Todas
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cliente" id="clientes"
                                        value="1">
                                    <label class="form-check-label" for="clientes">
                                        Solo clientes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cliente" id="empleados"
                                        value="2">
                                    <label class="form-check-label" for="empleados">
                                        Solo empleados
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_cliente" id="accionistas"
                                        value="3">
                                    <label class="form-check-label" for="accionistas">
                                        Solo accionistas
                                    </label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn btn-light me-2" value="1" name="opcion">
                                    <span class="mdi mdi-magnify h5"></span> Vista previa
                                </button>
                                <button type="submit" class="btn btn-light me-2" value="2" name="opcion">
                                    <span class="mdi mdi-file-pdf-box h5"></span> Generar PDF
                                </button>
                                <!--button type="submit" class="btn btn-light" value="2" name="opcion">
                                                                                                                                <span class="mdi mdi-file-pdf-box h5"></span> Generar PDF
                                                                                                                            </button-->
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
