@extends('layouts.panel_reportes')
@section('panel_reportes')
    <div id="appReporteContribuyentes" class="container">
        <div class="card-body p-2 border-success">
            <div class="row mb-4">
                <div class="col-12 text-uppercase h3">
                    Vista previa
                </div>
                <div class="col-12 text-uppercase h5">
                    F-07 V14: DOCUMENTOS LEGALES Y ELECTRÓNICOS, ANULADOS Y/O EXTRAVIADOS
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('anexos.invalidados') }}" role="button">Volver</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-borderless align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Numero de resolución</th>
                                    <th>Clase Doc.</th>
                                    <th>Desde (PREIMPRESO)</th>
                                    <th>Hasta (PREIMPRESO)</th>
                                    <th>Tipo de documento</th>
                                    <th>Tipo de detalle</th>
                                    <th>Serie</th>
                                    <th>Desde</th>
                                    <th>Hasta</th>
                                    <th>Codigo de generación</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider">

                                @foreach ($data as $d)
                                    <tr>
                                        <td>{{ $d->numero_resolucion }}</td>
                                        <td>{{ $d->clase_documento }}</td>
                                        <td>{{ $d->desde }}</td>
                                        <td>{{ $d->hasta }}</td>
                                        <td>{{ $d->tipo_documento }}</td>
                                        <td>{{ $d->tipo_detalle }}</td>
                                        <td>{{ $d->serie }}</td>
                                        <td>{{ $d->desdec }}</td>
                                        <td>{{ $d->hastac }}</td>
                                        <td>{{ $d->codigo_generacion }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
