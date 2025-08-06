@extends('layouts.anticipos')

@section('panel_anticipo')
<div class="col-md-12 p-3">
    <div class="card">
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-12 col-lg-6 text-uppercase">
                    <h3 class="card-title">
                        Vista previa de anticipos activos
                    </h3>
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('anticipos.reporteAnticiposForm2') }}" role="button">Volver</a>
                </div>
            </div>

            <div class="row text-uppercase mb-4">
                <div class="col-12">
                    <b class="text-decoration-underline">Cajas:</b>
                    @foreach($cajas as $caja)
                        {{ $caja->caja }},
                    @endforeach
                </div>
                <div class="col-12">
                    <b class="text-decoration-underline">Se muestran todos los anticipos con fecha de aplicación menores o iguales a:</b> {{ $fecha_aplicacion }}
                </div>
            </div>
            @if(isset($anticipos) && count($anticipos) > 0)
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Anticipo Nº</th>
                                        <th scope="col" class="text-truncate">Titular</th>
                                        <th scope="col">Monto</th>
                                        <th scope="col">Monto histórico</th>
                                        <th scope="col">Caja</th>
                                        <th scope="col">Usuario realiza</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Fecha aplicación</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($anticipos as $a)
                                        <tr>
                                            <td scope="row">{{ $a->id }}</td>
                                            <td>
                                                <b class="mb-0">{{ $a->clientes->nombre }}</b>
                                                <p class="mb-0">
                                                    <i class="text-secondary">{{ $a->concepto }}</i>
                                                </p>
                                            </td>
                                            <td class="text-end">${{ number_format($a->monto, 2) }}</td>
                                            <td class="text-end">${{ number_format($a->monto_historico, 2) }}</td>
                                            <td>{{ $a->turnos->cajas->caja }}</td>
                                            <td>{{ $a->users->name }}</td>
                                            <td>{{ $a->fecha }}</td>
                                            <td>{{ $a->fecha_aplicacion }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @else
                <p class="text-center">No se encontraron registros para mostrar!!!</p>
            @endif

        </div>
    </div>
</div>
@endsection
