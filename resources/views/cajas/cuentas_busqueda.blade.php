@extends('layouts.dtes')
@section('dte_content')
    <div class="row">
        <div class="col-12 h3">
            BUSCADOR DE CUENTAS
        </div>
        <x-message></x-message>
    </div>
    <form action="{{ route('cajas.buscar_cuentas_resultado') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="" class="form-label">Tipo de cuenta</label>
            <select class="form-select" name="tipo_cuenta" id="">
                <option value="1">Ordenes</option>
                <option value="2">Estadías</option>
                <option value="3">Comandas</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="" class="form-label">Identificador</label>
            <input type="number" min="1" step="1" class="form-control" name="id" id=""
                aria-describedby="helpId" placeholder="Escriba aquí..." />
        </div>
        <div class="mb-3">
            <button class="btn btn-primary" type="submit">Buscar</button>
        </div>

    </form>
@endsection
