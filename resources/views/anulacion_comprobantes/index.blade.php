@extends('layouts.cajas')
@section('css-caja')
    <style>
        body {
            background: #E0F7FA;
        }
    </style>
@endsection
@section('panel_caja')
    <div class="row">
        <form action="{{ route('anulacion_comprobantes.search') }}" method="post">
            @csrf
            <div class="row mb-4">
                <div class="col-5">
                    <div class="form-group">
                        <label for="txtBuscar">Buscar</label>
                        <input type="text" class="form-control" name="buscar" id="txtBuscar" aria-describedby="helpId"
                            placeholder="Buscar por el nombre del titular o correlativo"
                            value="{{ old('buscar') ?? ($buscar ?? '') }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="txtBuscar">Fecha de inicio</label>
                        <input type="date" class="form-control" name="finicio"
                            min="{{ Carbon::now()->subDays(90)->format('Y-m-d') }}" placeholder="Fecha inicio"
                            value="{{ old('finicio') ?? ($finicio ?? date('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="txtBuscar">Fecha de finalización</label>
                        <input type="date" class="form-control" name="ffin" id="" aria-describedby="helpId"
                            placeholder="Fecha finalización" value="{{ old('ffin') ?? ($ffin ?? date('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-1 align-items-center d-flex justify-content-evenly">
                    <button type="submit" class="btn btn-light float-end">
                        <span class="mdi mdi-magnify"></span>
                    </button>
                </div>
            </div>
        </form>
        @foreach ($p as $c)
            <div class="col-4 mb-3">
                <div class="card card-comprobantes">
                    <div class="card-body">
                        <div class="col-12 card-title">
                            {{ $c->tipoComprobantes->tipo }} No. <b>{{ $c->correlativo }}</b>
                        </div>
                        <h5 class="col-12 card-text text-uppercase text-truncate"
                            title="{{ $c->titular ?? 'Cliente sin registrar' }}">
                            {{ $c->titular ?? 'Cliente sin registrar' }}
                        </h5>
                        <p class="col-12 card-text">
                            ${{ number_format($c->total, 2) }} · {{ $c->turnos->cajas->caja }}
                        </p>
                        <p class="col-12 card-text">
                            @if ($c->estado)
                                <a class="btn btn-primary"
                                    href="{{ route($th['table'] . '.create_by_id', ['id' => Crypt::encryptString($c->id)]) }}">
                                    Anular comprobante
                                </a>
                            @else
                                <span class="badge badge-pill text-bg-secondary ">Comprobante anulado</span>
                            @endif
                            <span class="float-end">
                                {{ $c->creacion }} · {{ Carbon::parse($c->created_at)->format('d-m-Y') }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
