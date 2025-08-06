@extends('layouts.cajas')

@section('panel_caja')

<h5>Solicitudes de comprobantes</h5>

<div class="col-md-3">
    <a href="{{ route('ordenes.index') }}" class="list-group-item">
        <div class="card border-1 border-success" style="height: 14rem;">

            <div class="card-body d-flex align-content-center flex-wrap">
                <div class="col-12 text-center text-success">
                    <div class="mdi mdi-plus h2"></div> Nueva orden
                </div>

            </div>
        </div>
    </a>
</div>
<div class="col-md-3">
    <div class="card border-1 border-primary" style="height: 14rem;">

        <div class="card-body">
            <h5 class="card-title">Nombre del cliente</h5>
            <p class="card-text">Comanda:#0011</p>
            <p class="card-text">Solicitante:#Rancho</p>
            <p class="card-text">Nombre del mesero</p>
            <a href="{{ route('comprobantes.index') }}" class="btn btn-success">Comprobante</a>
            <a href="{{ route('clientes.index') }}" class="btn btn-light">Editar cliente</a>
        </div>
    </div>
</div>
@endsection
