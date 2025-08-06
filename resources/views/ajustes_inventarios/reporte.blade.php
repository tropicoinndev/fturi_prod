@extends('layouts.ajustes_inventarios')

@section('ajustes_inventarios_content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-chart text-success h2"></span>
                Reporte de ajustes
            </h3>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <form class="row align-items-center" action="{{ route('ajustes_inventarios.reporteOpcion') }}" method="POST">
                @csrf
                
                <div class="col-3">
                    <label for="bodegaId" class="form-label">Bodega:</label>
                    <select class="form-select rounded-5" id="bodegaId" name="bodegaId" aria-label="Default select example" required>
                        <option value="" selected disabled>--Seleccione---</option>
                        @foreach($bodegas as $b)
                            <option value="{{ $b->id }}" {{ isset($bodegaId) && $bodegaId == $b->id ? 'selected' : '' }} class="text-uppercase">{{ $b->bodega }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-3">
                    <label for="usuarioId" class="form-label">Usuario solicitante:</label>
                    <select class="form-select rounded-5" id="usuarioId" name="usuarioId" aria-label="Default select example" required>
                        <option value="" selected disabled>--Seleccione---</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}" {{ isset($usuarioId) && $usuarioId == $u->id ? 'selected' : '' }} class="text-uppercase">{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-2">
                    <label for="fechaProceso" class="form-label">Fecha de proceso:</label>
                    <input type="date" class="form-control rounded-5" required id="fechaProceso" name="fechaProceso" value="{{ $fechaProceso ?? '' }}">
                </div>

                <div class="col-4 text-end">
                    <button style="margin-top: 30px;" type="submit" class="btn btn-primary rounded-5 me-5" value="{{ Crypt::encryptString(1) }}" name="opcion"><span class="mdi mdi-magnify"></span> Buscar</button>
                    <button style="margin-top: 30px;" type="submit" class="btn btn-outline-danger rounded-5 me-2" value="{{ Crypt::encryptString(2) }}" name="opcion"><span class="mdi mdi-file-pdf-box"></span> PDF</button>
                    <button style="margin-top: 30px;" type="submit" class="btn btn-outline-success rounded-5" value="{{ Crypt::encryptString(3) }}" name="opcion"><span class="mdi mdi-file-excel"></span> EXCEL</button>
                </div>
            </form>
        </div>
    </div>

    @isset($data)
        <div class="row mb-4">
            <div class="col-12">
                <div class="col-12">
                    <table class="table table-hover table-sm table-responsive-sm">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th style="width: 45%;" scope="col">Observación</th>
                                <th scope="col">Fecha proceso</th>
                                <th scope="col">Solicitante</th>
                                <th scope="col">Realizado por</th>
                                <th scope="col">Autorizado por</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $d)
                                <tr>
                                    <td>{{ $d->ajustes_existencias_id }}</td>
                                    <td>
                                        Se realiza el 
                                        <b>{{ $d->accion === 1 ? 'AUMENTO' : 'DESCARTE' }}</b> 
                                        del producto/insumo 
                                        <b class="text-uppercase">{{ $d->nombre_producto }}</b> 
                                        perteneciente a <b>{{ $d->bodega }}</b> 
                                        ingresada en el <b>LOTE # {{ $d->existencias_id }},</b> 
                                        {{ $d->accion === 1 ? 'aumentando' : 'descargando' }} <b>{{ number_format($d->cantidad, 2) }}</b> unidades.
                                    </td>
                                    <td>{{ $d->fecha_proceso }}</td>
                                    <td>{{ $d->user_solicitante }}</td>
                                    <td>{{ $d->user_realiza }}</td>
                                    <td>{{ $d->user_autoriza }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endisset
@endsection
