@extends('layouts.hab')

@section('content-hab')
    <style>
        body {
            background: #E1F5FE;
        }

        .panelCalendar {
            min-height: 90vh;
        }

        .align-items-end {
            align-items: flex-end !important;
        }
    </style>

    <div class="container" id="detalleHuesped">
        <div class="card panelCalendar">
            <div class="card-body p-5 text-uppercase">
                <div class="row mb-4">
                    <div class="card-title col-12 h5">
                        BITÁCORA DE RECEPCIONES - DETALLES
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-2"><b>Usuario:</b></div>
                    <div class="col-10">{{ $detalles->usuarios->name }}</div>

                    <div class="col-2"><b>Fecha de creación:</b></div>
                    <div class="col-10">{{ $detalles->created_at }}</div>
                </div>

                <div class="row">
                    {{-- {{ $detalles }} --}}
                    <div class="col-12 text-uppercase">

                        {{--Tabla 1--}}
                        <h5>Información del cliente</h5>
                        <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm mb-5">
                            <thead>
                                <tr>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Tipo cliente</th>
                                    <th scope="col">Actividad</th>
                                    <th scope="col">Contacto</th>
                                    <th scope="col">Identificaciones</th>
                                    <th scope="col">Dirección</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $detalles->clientes->nombre }}</td>
                                    <td>{{ $detalles->clientes->tipo_cliente ? 'Natural' : 'Jurídico' }}</td>
                                    <td>{{ $detalles->clientes->actividades->actividad ?? '---' }}</td>
                                    <td>
                                        @forelse($detalles->clientes->contactos as $c)
                                            {{ $c->contactos->contacto }} · {{ $c->observaciones }} · {{ $c->valor }}
                                        @empty
                                            ---
                                        @endforelse
                                    </td>
                                    <td>
                                        @forelse($detalles->clientes->identificaciones as $i)
                                            {{ $i->identificaciones->identificacion }} · {{ $i->numero }}
                                        @empty
                                            ---
                                        @endforelse
                                    </td>
                                    <td>
                                        @if($detalles->clientes->municipios && $detalles->clientes->extranjero)
                                            {{ $detalles->clientes->municipios->municipio ?? $detalles->clientes->extranjero->pais }} - {{ $detalles->clientes->direccion }}
                                        @else
                                            {{ $detalles->clientes->direccion ?? '---' }}
                                        @endisset
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        {{--Tabla 2--}}
                        <h5>Información de recepción</h5>
                        <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm mb-5">
                            <thead>
                                <tr>
                                    <th scope="col">Nº Recepción</th>
                                    <th scope="col">Habitación</th>
                                    <th scope="col">Forma</th>
                                    <th scope="col">Tarifa</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Nº días</th>
                                    <th scope="col">Monto total</th>
                                    @if($detalles->userElimina)
                                        <th scope="col">Usuario anula</th>
                                    @endif
                                    {{-- <th scope="col">Fecha ingreso</th>
                                    <th scope="col">Fecha salida</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Nº {{ $detalles->id }}</td>
                                    <td>Nº {{ $detalles->habitaciones->numero_habitacion }}</td>
                                    <td>{{ $detalles->habitaciones->relacionFormaHabitaciones->forma_habitacion }}</td>
                                    <td>{{ $detalles->tarifas->tarifa }}</td>
                                    <td>$ {{ $detalles->tarifas->precio }}</td>
                                    <td>{{ $detalles->dias }}</td>
                                    <td>$ {{ $detalles->dias * $detalles->tarifas->precio }}</td>
                                    @if($detalles->userElimina)
                                        <td>{{ $detalles->userElimina->name ?? '---' }}</td>
                                    @endif
                                    {{-- <td>{{ $detalles->fecha_ingreso }}</td>
                                    <td>{{ $detalles->fecha_salida }}</td> --}}
                                </tr>
                            </tbody>
                        </table>

                        {{--Tabla 3--}}
                        <h5>Información de reservación</h5>
                        @if(isset($detalles->reservaciones) && $detalles->reservaciones)
                            <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm mb-5">
                                <thead>
                                    <tr>
                                        <th scope="col">Nº reservación</th>
                                        <th scope="col">Nº personas</th>
                                        <th scope="col">Detalle</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Nº días</th>
                                        <th scope="col">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nº {{ $detalles->reservaciones->reservaciones_id ?? '---' }}</td>
                                        <td>{{ $detalles->reservaciones->cantidad_personas ?? '---' }}</td>
                                        <td>{{ $detalles->reservaciones->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado' }}</td>
                                        <td>{{ $detalles->reservaciones->descripcion ?? '---' }}</td>
                                        <td>{{ $detalles->reservaciones->dias }}</td>
                                        <td>{{ $detalles->reservaciones->relacionUsuarios->name }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p class="mb-5">--Sin reservación--</p>
                        @endif

                        {{--Tabla 4--}}
                        <h5>Información de huéspedes</h5>
                        @if($detalles->huespedes->count() > 0)
                            <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm mb-5">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Identificaciones</th>
                                        <th scope="col">Dirección</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detalles->huespedes as $hp)
                                        <tr>
                                            <td>{{ $hp->huesped->nombre }}</td>
                                            <td>{{ $hp->huesped->telefono ?? '---' }}</td>
                                            <td>
                                                @isset($hp->huesped->identificaciones )
                                                    {{ $hp->huesped->identificaciones->identificacion }} · {{ $hp->huesped->identificacion }}
                                                @endisset
                                            </td>
                                            <td>
                                                @if($hp->huesped->municipios_id && $hp->huesped->paises)
                                                    {{ $hp->huesped->municipios->municipio .' · '. $hp->huesped->paises->pais }}
                                                @else
                                                    {{ $hp->huesped->paises->pais ?? '---' }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="mb-5">--Sin huéspedes registrados--</p>
                        @endif

                        {{--Tabla 5--}}
                        <h5>Detalles</h5>
                        @if($detalles->descripcion)
                            <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $detalles->descripcion }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        @else
                            <p>--Sin detalles--</p>
                        @endif
                    </div>
                </div>
                
            </div>
        </div>
    </div>

    <script>
        
    </script>
@endsection
