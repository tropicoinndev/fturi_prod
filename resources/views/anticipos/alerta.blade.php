@extends('layouts.anticipos')

@section('panel_anticipo')
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-12 text-uppercase">
                        <h3 class="card-title">
                            Alertas de Anticipos
                        </h3>
                        <p class="text-muted">
                            Se muestran todos los anticipos que incumplen el periodo de 3 meses para reportarse como ingreso.
                        </p>
                    </div>
                </div>

                <!-- Tabla de anticipos -->
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Nº Anticipo</th>
                                <th scope="col" class="text-truncate">Titular/Concepto</th>
                                <th scope="col" class="text-center">Monto</th>
                                <th scope="col" class="text-center">Monto histórico</th>
                                <th scope="col" class="text-center">Caja</th>
                                <th scope="col" class="text-center">Usuario realiza</th>
                                <th scope="col" class="text-center">Fecha</th>
                                <th scope="col" class="text-center">Fecha aplicación</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($anticipos as $a)
                                <tr>
                                    <td scope="row">{{ $a->id }}</td>
                                    <td>
                                        <b class="mb-0">{{ $a->nombre_cliente }}</b>
                                        <p class="mb-0">
                                            <i class="text-secondary">{{ $a->concepto }}</i>
                                        </p>
                                    </td>
                                    <td class="text-end">${{ number_format($a->monto, 2) }}</td>
                                    <td class="text-end">${{ number_format($a->monto_historico, 2) }}</td>
                                    <td class="text-center">{{ $a->nombre_caja }}</td>
                                    <td class="text-center">{{ $a->user_realiza }}</td>
                                    <td class="text-center">{{ $a->fecha }}</td>
                                    <td class="text-center">{{ $a->fecha_aplicacion }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">
                                        Aún no hay datos para mostrar!!!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
