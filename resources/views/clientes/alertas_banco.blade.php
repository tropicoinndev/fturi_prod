@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #EF9A9A !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container">
        <div class="card-body p-2">
            <h5 class="card-title text-uppercase mb-4 fw-bold">
                Clientes con alerta de ventas en trasferencias bancarias (Tarjetas Débito / Crédito o Cheques)
            </h5>

            <div class="row mb-2">
                <div class="col-12 mb-4">
                    <form action="{{ route('clientes.alertasBancoSearch') }}" method="post">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-2">
                                <label for="monto" class="form-label">Monto</label>
                                <input type="number" step="0.01" class="form-control" name="monto" id="monto"
                                    placeholder="Seleccione un mes" value="{{ $monto ?? 0 }}" />
                            </div>
                            <div class="col-3">
                                <label for="fecha" class="form-label">Mes</label>
                                <input type="month" class="form-control" name="fecha" id="fecha"
                                    placeholder="Seleccione un mes" value="{{ $mes }}" />
                            </div>
                            <div class="col-5">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" name="nombre" id="nombre"
                                    value="{{ $nombre ?? '' }}" placeholder="Buscar por nombre" />
                            </div>
                            <div class="col-2">
                                <button type="submit" class="btn btn-primary justify-self-end">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Monto en bancos</th>
                                <th>Numero comprobantes</th>
                                <th>Actividad económica</th>
                                <th>Identificaciones</th>
                                <th>Ver cliente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clientes as $c)
                                <tr>
                                    <td>{{ $c->id }}</td>

                                    <td>{{ $c->nombre }}</td>
                                    @php
                                        $monto = $data->where('clientes_id', $c->id)->sum('total_bancos');
                                        $comprobantes = $data->where('clientes_id', $c->id)->sum('numero_comprobantes');
                                    @endphp
                                    <td>${{ number_format($monto, 2) }}</td>
                                    <td>{{ $comprobantes }}</td>
                                    <td>{{ $c?->actividades?->actividad ?? 'Sin actividad' }}</td>
                                    <td>
                                        @forelse ($c->identificaciones as $i)
                                            {{ $i->identificaciones->identificacion }} {{ $i->numero }}
                                        @empty
                                            Sin identificaciones
                                        @endforelse
                                    </td>
                                    <td>
                                        <a class="btn btn-light"
                                            href="{{ route('clientes.comprobantes_mes', ['id' => $c->cid, 'mes' => $mes]) }}"
                                            role="button" target="_blank">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
