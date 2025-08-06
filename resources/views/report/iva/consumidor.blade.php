@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Creación de libro de ventas a consumidor final
                </div>
            </div>
            <form action="{{ route('libros.consumidor_report') }}" method="post">
                @csrf
                <div class="row">
                    <div class="mb-3">
                        <label for="" class="form-label">
                            Sucursales
                        </label>
                        <select class="form-select" name="sucursales_id" required>
                            <option selected value="">Seleccione una sucursal</option>
                            @foreach ($sucursales as $c)
                                <option value="{{ $c->cid }}">
                                    {{ $c->sucursal }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="mes" class="form-label">Mes y año</label>
                        <input type="month" class="form-control" id="mes" name="mes" required>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Numero de registro por pagina</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" value="30" required>
                    </div>
                    <div class="mb-3">
                        <button class="btn btn-primary" type="submit">Generar</button>
                    </div>

                </div>

            </form>
        </div>
    </div>
@endsection
