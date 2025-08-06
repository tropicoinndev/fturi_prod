@extends('layouts.excel')

@section('content')
    <thead>
        <tr>
            <th colspan="6">
                <div class="titulo">
                    Reporte de anticipos disponibles del dia $fecha
                </div>
                @if (isset($anticipos) && count($anticipos) > 0)
                    <div class="row">

                        <div class="col-12 mb-4">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col"><b>ID</b></th>
                                        <th scope="col"><b>Caja/Turno</b></th>
                                        <th scope="col"><b>Fecha</b></th>
                                        <th scope="col"><b>Aplicación</b></th>
                                        <th scope="col"><b>Monto</b></th>
                                        <th scope="col"><b>Cliente</b></th>
                                        <th scope="col"><b>Usuario</b></th>
                                        <th scope="col"><b>Creado</b></th>
                                        <th scope="col"><b>Concepto:</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($anticipos) && count($anticipos) > 0)
                                        @foreach ($anticipos as $anticipo)
                                            <tr>
                                                <td>{{ $anticipo->id }}</td>
                                                <td>{{ $anticipo->turnos->cajasSucursales->caja }}/{{ $anticipo->turnos->opcion->turno }}
                                                </td>
                                                <td>{{ $anticipo->fecha }}</td>
                                                <td>{{ $anticipo->fecha_aplicacion }}</td>
                                                <td>${{ number_format($anticipo->monto_historico, 2, ',', '.') }}</td>
                                                <td>{{ $anticipo->clientes->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    <span class="text-muted">{{ $anticipo->users->name }}</span>
                                                </td>
                                                <td>{{ $anticipo->created_at }}</td>
                                                <td>
                                                     {{ $anticipo->concepto }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center text-uppercase">
                                                No hay anticipos para el cliente seleccionado en la fecha seleccionada
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                @else
                    <div class="row">
                        <div class="col-12">
                            <p class="text-center text-uppercase">No hay anticipos para el cliente seleccionado en la fecha
                                seleccionada</p>
                        </div>
                    </div>
                @endif
            </th>
        </tr>
    </thead>

@endsection
