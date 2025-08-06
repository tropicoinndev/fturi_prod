@extends('layouts.ajustes_inventarios')

@section('ajustes_inventarios_content')
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="card-title text-uppercase">
                <span class="mdi mdi-file-chart text-success h2"></span>
                Reporte de existencias por bodega
            </h3>
            <x-message></x-message>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-12">
            <form class="row align-items-center" action="{{ route('ajustes_inventarios.reportExisByBodegaSearch') }}" method="POST">
                @csrf
                
                <div class="col-4">
                    <label for="bodegaId" class="form-label">Filtrar por bodega:</label>
                    <select class="form-select rounded-5" id="bodegaId" name="bodegaId" aria-label="Default select example" required>
                        <option value="" selected disabled>--Seleccione---</option>
                        @foreach($bodegas as $b)
                            <option value="{{ $b->id }}" {{ isset($bodegaId) && $bodegaId == $b->id ? 'selected' : '' }} class="text-uppercase">{{ $b->bodega }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-4 text-end">
                    <button style="margin-top: 30px;" type="submit" class="btn btn-primary rounded-5 me-3" value="{{ Crypt::encryptString(1) }}" name="opcion"><span class="mdi mdi-magnify"></span> Buscar</button>
                    <button @if(!isset($reporteExistencias)) disabled @endif style="margin-top: 30px;" type="submit" class="btn btn-outline-success rounded-5 me-2" value="{{ Crypt::encryptString(2) }}" name="opcion"><span class="mdi mdi-file-pdf-box"></span> Generar reporte</button>
                </div>
            </form>
        </div>
    </div>

    @isset($reporteExistencias)
        <div class="row mb-4">
            <div class="col-12">
                <div class="col-12">
                    <table class="table table-hover table-sm table-responsive-sm">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Vencimiento</th>
                                <th>Producto</th>
                                <th>Ingreso</th>
                                <th>Bodega entra producto</th>
                                <th>Salio</th>
                                <th>Bodega sale producto</th>
                                <th>Existencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reporteExistencias as $e)
                                <tr>
                                    <td scope="row">#{{ $e['existencia']->id }}</td>
                                    <td scope="row">{{ $e['existencia']->vencimiento }}</td>
                                    <td scope="row">{{ $e['existencia']->productosExistencias->nombre }}</td>
                                    <td scope="row">{{ $e['requisicion_detalles_id']->relacionRequisiciones->created_at }}</td>
                                    <td scope="row">
                                        {{ $e['requisicion_detalles_id']->relacionRequisiciones->relacionBodegasEntrada->bodega }}
                                    </td>
                                    <td scope="row">{{ $e['requisicion_detalles_id']->relacionRequisiciones->updated_at }}</td>
                                    <td scope="row">
                                        {{ $e['requisicion_detalles_id']->relacionRequisiciones->relacionBodegasSalida->bodega }}
                                    </td>
                                    <td scope="row">{{ $e['existencia']->existencia }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-uppercase">No hay existencias disponibles del producto que se ha seleccionado.</td>
                                </tr>
                                @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endisset
@endsection
