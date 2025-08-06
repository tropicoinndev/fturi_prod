@extends('layouts.hab')

@section('content-hab')
<div id="appReservacionesIndex" class="container">
    <div class="row justify-content-center">
        <div class="card mb-3">
            <div class="card-body">
                <h3 class="card-title">Recepciones</h3>
                <p class="text-uppercase text-muted">Listado de recepciones</p>


                <!--Boton agregar y caja de busqueda-->
                <div class="row mb-4">
                    <div class="col-12 col-lg-6">
                        <a href="{{ route('reservaciones.create') }}" class="btn btn-primary">
                            <span class="mdi mdi-plus"></span> Nueva recepcion
                        </a>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">


                        <form action="{{ route('reservaciones.search') }}" method="post">
                            @csrf
                            <input type="text" class="form-control" placeholder="Buscar por nombre de cliente o numero de reservacion..." name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" autocomplete="off" autofocus>

                        </form>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Acciones</th>
                            <th scope="col">#</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Contacto</th>
                            <th scope="col">Estado</th>
                            <th scope="col">Creada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($p as $i)
                        <tr class="{{ $i->completa?'text-muted':'' }}">
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="mdi mdi-cog"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        @if(!$i->completa)
                                        <li>
                                            <a class="dropdown-item" href="{{ route('detalle_reservas.index', ['id'=> \Crypt::encryptString($i->id)]) }}" {{ $i->completa?'disabled':'' }}>
                                                Agregar habitaciones
                                            </a>
                                        </li>
                                        @endif

                                        <li><a class="dropdown-item" href="{{ route('reservaciones.imprimir', ['id'=>Crypt::encryptString($i->id)]) }}">Imprimir</a></li>
                                    </ul>
                                </div>
                            </td>
                            <th scope="row">{{ $i->id }}</th>
                            <td>{{ $i->nombre }}</td>
                            <td>
                                {{ $i->numero_habitaciones }} habitaciones reservadas

                            </td>
                            <td>
                                @if (strlen($i->tipo_contacto) > 0)
                                <b>{{ $i->tipo_contacto }}:</b> {{ $i->valor }}
                                @else
                                {{ $i->contacto ?? '--No se agrego un contacto a este cliente--' }}
                                @endif
                            </td>
                            <td class="text-{{ $i->completa ?'info':'success' }}">{{ $i->completa ? 'Completada' : 'Pendiente de completar'  }}</td>
                            <td title="{{ $i->created_at }}">{{ \Carbon::parse($i->created_at)->diffForHumans() }}</td>
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
