@extends('layouts.hab')

@section('content-hab')
    <div id="appReservacionesIndex" class="container">
        <div class="row justify-content-center">
            <div class="card mb-3">
                <div class="card-body">
                    <h3 class="card-title">{{ $th['title'] ?? 'Listado' }}</h3>
                    <p class="text-uppercase text-muted">{{ $th['sub'] ?? '' }}</p>
                    <x-message></x-message>
                    <!--Boton agregar y caja de busqueda-->
                    <div class="row mb-4">
                        @if ($th['btnAdd'])
                            <div class="col-12 col-lg-6">
                                <a href="{{ route('reservaciones.create') }}" class="btn btn-primary">
                                    <span class="mdi mdi-plus"></span> Nueva reservación
                                </a>
                            </div>
                        @endif
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">


                            <form action="{{ route('reservaciones.search') }}" method="post">
                                @csrf
                                <input type="text" class="form-control"
                                    placeholder="Buscar por nombre de cliente o numero de reservacion..." name="txtBusqueda"
                                    value="{{ $txtBusqueda ?? '' }}" autocomplete="off" autofocus>

                            </form>
                        </div>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Acciones</th>
                                <th scope="col">#</th>
                                <th scope="col">Cliente</th>
                                <th scope="col">Habitaciones</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Vendedor</th>
                                <th scope="col">Creada</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($p as $i)
                                <tr class="{{ $i->completa ? 'text-muted' : '' }}">
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <span class="mdi mdi-cog"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if (!$i->eliminado)
                                                    @if (!$i->completa)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('detalle_reservas.index', ['id' => \Crypt::encryptString($i->id)]) }}"
                                                                {{ $i->completa ? 'disabled' : '' }}>
                                                                <span class="mdi mdi-plus"></span>
                                                                Agregar habitaciones
                                                            </a>
                                                        </li>
                                                    @endif
                                                    @if ($i->completa)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('reservaciones.imprimir_container', ['id' => Crypt::encryptString($i->id)]) }}">
                                                                <span class="mdi mdi-printer"></span>
                                                                Imprimir
                                                            </a>
                                                        </li>
                                                        @can('reservaciones.desbloquear')
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('reservaciones.desbloquear', ['id' => Crypt::encryptString($i->id)]) }}">
                                                                    <span class="mdi mdi-lock-off"></span>
                                                                    Desbloquear
                                                                </a>
                                                            </li>
                                                        @endcan
                                                    @endif
                                                    @if (!$i->completa)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('reservaciones.anular', ['id' => \Crypt::encryptString($i->id)]) }}">
                                                                <span class="mdi mdi-delete"></span>
                                                                Eliminar reservacion
                                                            </a>
                                                        </li>
                                                    @endif
                                                @else
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('reservaciones.anulacion_detalle', ['id' => \Crypt::encryptString($i->id)]) }}">
                                                            <span class="mdi mdi-details"></span>
                                                            Detalle de anulacion
                                                        </a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                    <th scope="row">{{ $i->id }}</th>
                                    <td class="text-uppercase">
                                        {{ $i->clientes_id > 0 ? $i->relacionClientes->nombre : 'Titular: ' . $i->titular }}
                                    </td>
                                    <td>
                                        @if ($i->detalleReservaciones->count() > 0)
                                            <div class="accordion accordion-flush" id="reserva{{ $i->id }}">
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="flush-headingOne">
                                                        <button class="accordion-button collapsed" type="button"
                                                            data-bs-toggle="collapse"
                                                            data-bs-target="#detail-{{ $i->id }}"
                                                            aria-expanded="false"
                                                            aria-controls="detail-{{ $i->id }}">
                                                            {{ $i->detalleReservaciones->count() }} habitaciones reservadas
                                                        </button>
                                                    </h2>
                                                    <div id="detail-{{ $i->id }}"
                                                        class="accordion-collapse collapse"
                                                        aria-labelledby="flush-headingOne"
                                                        data-bs-parent="#reserva{{ $i->id }}">
                                                        <div class="accordion-body">
                                                            <div class="row">
                                                                @foreach ($i->detalleReservaciones as $dr)
                                                                    <div class="col-12 mb-2">
                                                                        <div class="card">
                                                                            <div class="card-body">
                                                                                <h5 class="card-title">
                                                                                    Habitacion
                                                                                    {{ $dr->relacionHabitaciones->numero_habitacion }}

                                                                                </h5>
                                                                                <p class="card-text">
                                                                                    {{ $dr->relacionTarifas->tarifa }} ·

                                                                                    {{--
                                                                                        Este fragmento de código únicamente se agregó para
                                                                                        evitar que el monto de la reserva saliera duplicado por las dos noches
                                                                                        de la tarifa de carnaval
                                                                                        -id tarifa carnaval 2025 = 4
                                                                                    --}}
                                                                                    @if ($dr->relacionTarifas->temporadas_id == 4)
                                                                                        ${{ number_format(($dr->relacionTarifas->precio / 2) * $dr->dias, 2) }}
                                                                                        {{-- Agregado --}}
                                                                                    @else
                                                                                        ${{ number_format($dr->relacionTarifas->precio * $dr->dias, 2) }}
                                                                                        {{-- Original --}}
                                                                                    @endif
                                                                                    {{-- --}}

                                                                                    <br>
                                                                                    Del {{ $dr->fecha_ingreso }} al
                                                                                    {{ $dr->fecha_salida }}
                                                                                    @if ($dr->ingreso)
                                                                                        <span class="text-success">
                                                                                            Registrado
                                                                                        </span>
                                                                                    @else
                                                                                        @if ($dr->fecha_ingreso > date('Y-m-d'))
                                                                                            <span
                                                                                                class="{{ $dr->estado ? 'text-success' : 'text-danger' }}">
                                                                                                {{ $dr->estado ? 'Activo' : 'Desactivado' }}
                                                                                            </span>
                                                                                        @else
                                                                                            <span class="text-danger">
                                                                                                No ingreso
                                                                                            </span>
                                                                                        @endif
                                                                                    @endif

                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            Aun sin agregar habitaciones a la reserva.
                                        @endif

                                    </td>
                                    <td class="fst-italic">
                                        @if ($i->clientes_id != null)
                                            @forelse ($i->relacionClientes->contactos as $c)
                                                {{ $c->contactos->contacto }}: {{ $c->valor }} <br>
                                            @empty
                                                --No se agrego un contacto a este cliente--
                                            @endforelse
                                        @else
                                            {{ $i->contacto ?? '--No se agrego un contacto a este titular--' }}
                                        @endif

                                    </td>
                                    <td class="text-{{ $i->completa ? 'info' : 'success' }}">


                                        @if ($i->eliminado)
                                            <span class="badge text-bg-danger">Eliminada</span>
                                        @else
                                            {{ $i->completa ? 'Completada' : 'Pendiente de completar' }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $i->users->name }}
                                    </td>
                                    <td title="{{ $i->created_at }}">
                                        {{ \Carbon::parse($i->created_at)->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $p->links() }}

            </div>
        </div>
    </div>
@endsection
