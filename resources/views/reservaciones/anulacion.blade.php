@extends('layouts.hab')

@section('content-hab')
    <div class="container">
        <div class="row">
            <div class="col-12 m-auto">
                <div class="card  p-3">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase">Detalle de anulacion de reserva #{{ $p->id }}</h5>
                        <div class="card-text pt-2 pb-2 row">
                            <x-message></x-message>
                            <div class="col-2">
                                <b>Cliente / Titular:</b>
                            </div>
                            <div class="col-10">
                                {{ $p->titular ?? $p->relacionClientes->nombre }}
                            </div>
                            <div class="col-2">
                                <b>Creada:</b>
                            </div>
                            <div class="col-10">
                                {{ $p->created_at }}
                            </div>
                            <div class="col-2">
                                <b>Razon de eliminacion:</b>
                            </div>
                            <div class="col-10">
                                <i>{{ $p->razon_eliminacion }}</i>
                            </div>

                            <div class="col-12 table-responsive mt-3">
                                <table class="table table-light">
                                    <thead>
                                        <tr>
                                            <th>Habitacion</th>
                                            <th>Ingreso</th>
                                            <th>Salida</th>
                                            <th>Tarifa</th>
                                            <th>Usuario</th>
                                        </tr>
                                    </thead>
                                    <tbody>


                                        @forelse ($p->detalleReservaciones as $d)
                                            @if (!$d->ingreso)
                                                <tr>
                                                    <td>{{ $d->relacionHabitaciones->numero_habitacion }}</td>
                                                    <td>{{ $d->fecha_ingreso }}</td>
                                                    <td>{{ $d->fecha_salida }}</td>
                                                    <td>{{ $d->relacionTarifas->tarifa }}
                                                        ${{ number_format($d->relacionTarifas->precio, 2) }}
                                                    <td>{{ $d->relacionUsuarios->email }}</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-info" role="alert">
                                                    No se agregaron habitaciones a esta reservacion.
                                                </div>
                                            </div>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-12">
                                <a href="{{ route('reservaciones.history') }}" class="btn btn-light">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
