@extends('layouts.hab')

@section('content-hab')
    <style>
        .message {
            position: fixed;
            top: 10%;
            right: 1%;
            width: 20%;
            z-index: 100;
        }

        body {
            background: #FBE9E7;
        }

        #showHab {
            min-height: 89vh;
        }

        .panel-normal {
            background: #03A9F4;
            color: #FEFEFE;
        }

        .panel-salida {
            background: #E64A19;
            color: #FEFEFE;
        }

        .panel-normal .alert {
            background: #64B5F6;
            border: none;
            color: #263238;
        }

        .panel-salida .alert {
            background: #FF8A65;
            border: none;
            color: #263238;
        }

        .card-anticipos {
            background: #80DEEA;
            border: none;
            color: #263238;
        }
    </style>

    <div id="appRecepcionShow" class="container">
        <div class="row justify-content-center">
            <div class="col-9 m-auto">
                <div class="card shadow p-4">
                    <div class="card-body">
                        <div class="row">
                            <x-message></x-message>
                            <div class="col-12 h4 text-uppercase mb-2">
                                Salida de habitación {{ $p->habitaciones->numero_habitacion }}
                            </div>
                            <div class="col-12 mb-2 text-uppercase">
                                Tarjeta de registro <span class="text-danger">#{{ $p->id }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <b>
                                    Cliente:
                                </b>
                                <span class="text-uppercase">
                                    @if ($p->clientes->tipo_cliente)
                                        {{ $p->clientes->nombre }}
                                    @else
                                        {{ $p->clientes->detalle->juridico }} ({{ $p->clientes->nombre }})
                                    @endif

                                </span>
                            </div>
                            <div class="col-12 mb-2">
                                @if ($p->facturada)
                                    Ya se facturo esta estadia, confirme que ya salio el huesped.
                                @endif
                            </div>
                            <div class="col-12">
                                @if ($p->facturada)
                                    <a href="{{ route('recepciones.salida_confirm', ['id' => Crypt::encryptString($p->id)]) }}"
                                        class="btn btn-primary">Confirmar salida</a>
                                @else
                                    @if ($sugerencias->count() > 0)
                                        <form action="{{ route('cobros.create_form') }}" method="post">
                                            @csrf
                                            <input type="hidden" name="origen" value="{{ Crypt::encryptString('2') }}">
                                            <div class="row mt-2">
                                                <div class="col-12 mb-2">
                                                    ESTE CLIENTE TIENE LA SIGUIENTES ESTADIAS PENDIENTES DE PAGO:
                                                    <br>
                                                    <small>Seleccione una/todas las estadias para realizar el cobro.</small>
                                                </div>
                                                @foreach ($sugerencias as $s)
                                                    <div class="col-6 mb-3">
                                                        <input type="checkbox" class="btn-check"
                                                            value="{{ Crypt::encryptString($s->id) }}" name="origen_id[]"
                                                            multiple id="origen_{{ $s->id }}"
                                                            {{ $p->id == $s->id ? 'checked' : '' }}>
                                                        <label class="card btn btn-dark text-start"
                                                            for="origen_{{ $s->id }}">
                                                            <div class="card-body">
                                                                <h5 class="card-title">Habitación
                                                                    {{ $s->habitaciones->numero_habitacion }}
                                                                    <span class="float-end">
                                                                        <b>Reg.</b>
                                                                        {{ $s->id }}
                                                                    </span>
                                                                </h5>
                                                                <p class="card-text">
                                                                    {{ $s->fecha_ingreso }} al {{ $s->fecha_salida }}
                                                                </p>
                                                            </div>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                    @endif

                                    <div class="row">

                                        <div class="col-12 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    id="salida_recepcion">
                                                <label class="form-check-label" for="salida_recepcion">
                                                    Realizar salida <br>

                                                </label>
                                                <br>
                                                <small>
                                                    Seleccione la opcion <b>Realizar salida</b> si requiere la salida de
                                                    la(s) estadía(s).
                                                    <br>
                                                    (Solo se podran realizar las salidas de las habitación con fecha menor o
                                                    igual a
                                                    {{ date('d-m-Y') }}).
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            @if (session('caja'))
                                                @if ($p->clientes->tipo_cliente)
                                                    <button type="submit" class="btn btn-primary" name="tipo_comprobante"
                                                        value="{{ Crypt::encryptString('7001') }}">
                                                        Crear Consumidor Final
                                                    </button>
                                                @else
                                                    <button type="submit" class="btn btn-primary" name="tipo_comprobante"
                                                        value="{{ Crypt::encryptString('7001') }}">
                                                        Crear Consumidor Final
                                                    </button>
                                                    <button type="submit" class="btn btn-success" name="tipo_comprobante"
                                                        value="{{ Crypt::encryptString('7002') }}">
                                                        Crear Comprobante Credito Fiscal
                                                    </button>
                                                @endif
                                            @else
                                                <a href="{{ route('cajas.login', ['id' => Crypt::encryptString($p->id)]) }}"
                                                    class="btn btn-dark" target="_blank">
                                                    Iniciar sesión en caja
                                                </a>
                                            @endif


                                            <a href="{{ route('recepciones.show', ['id' => Crypt::encryptString($p->id)]) }}"
                                                class="btn btn-light">
                                                Volver
                                            </a>


                                            <button class="btn btn-light dropdown-toggle float-end" type="button"
                                                id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                                Salida
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                @can('recepciones.salida_anticipada')
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                            data-bs-target="#modalSalidaAnticipada">
                                                            Salida anticipada
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('recepciones.salida_sin_cobro')
                                                    <li>
                                                        <a href="#" class="dropdown-item" data-bs-toggle="modal"
                                                            data-bs-target="#modalSalidaSinCobro">
                                                            Salida cobrar después
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>

                                        </div>
                                    </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @can('recepciones.salida_anticipada')
        <div class="modal fade" id="modalSalidaAnticipada" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Salida anticipada de la estadía Nº {{ $p->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('recepciones.salida_anticipada') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $p->cid }}">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <b>Habitación:</b> {{ $p->habitaciones->numero_habitacion }} <br>
                                        <b>Ingreso:</b> {{ $p->fecha_ingreso }} <br>
                                        <b>Salida original:</b> {{ $p->fecha_salida }}<br>
                                        <b>Ingreso realizado por:</b> <span
                                            class="text-uppercase">{{ $p->usuarios->name }}</span>
                                    </div>
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="salida" class="form-label">Fecha de salida</label>
                                            <input type="date" class="form-control" name="salida" id="salida"
                                                aria-describedby="helpId" placeholder="Agregue la fecha de salida"
                                                min="{{ Carbon::parse($p->fecha_ingreso)->addDay()->format('Y-m-d') }}"
                                                max="{{ Carbon::parse($p->fecha_salida)->subDay()->format('Y-m-d') }}"
                                                required />
                                            <small id="helpId" class="form-text text-muted">
                                                La tarifa se calculara con esta fecha
                                                de salida. La fecha de salida debe ser mayor a
                                                {{ Carbon::parse($p->fecha_ingreso)->format('d-m-Y') }}
                                            </small>
                                        </div>
                                        <div class="mb-3">
                                            <label for="razon" class="form-label">
                                                Razón de la salida anticipada
                                            </label>
                                            <textarea class="form-control" id="razon" rows="3" name="razon"
                                                placeholder="Escriba aquí la razón por la que se cambiara la salida de esta estadía" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirm" id="confirm" required>
                                                <label class="form-check-label" for="confirm">
                                                    Confirmo que realizare el cambio de la salida para esta estadía, con mi
                                                    usuario (<b>{{ Auth::user()->name }}</b>) y revisando que la información
                                                    del cambio es correcta acepto la responsabilidad del cambio.
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary">Cambiar salida</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
    @can('recepciones.salida_sin_cobro')
        <div class="modal fade" id="modalSalidaSinCobro" tabindex="-1" role="dialog" aria-labelledby="modalTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitleId">
                            Salida sin cobro de la estadía Nº {{ $p->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('recepciones.salida_sin_cobro') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $p->cid }}">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <b>Habitación:</b> {{ $p->habitaciones->numero_habitacion }} <br>
                                        <b>Ingreso:</b> {{ $p->fecha_ingreso }} <br>
                                        <b>Salida original:</b> {{ $p->fecha_salida }}<br>
                                        <b>Ingreso realizado por:</b>
                                        <span class="text-uppercase">
                                            {{ $p->usuarios->name }}
                                        </span>
                                    </div>
                                    <div class="col-12">

                                        <div class="mb-3">
                                            <label for="razon_salida" class="form-label">
                                                Razón de la salida sin cobro
                                            </label>
                                            <textarea class="form-control" id="razon_salida" rows="3" name="razon"
                                                placeholder="Escriba aquí la razón por la que se cambiara la salida de esta estadía" required></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1"
                                                    name="confirm" id="confirm" required>
                                                <label class="form-check-label" for="confirm">
                                                    Confirmo que realizare la salida de esta estadía, con mi
                                                    usuario (<b>{{ Auth::user()->name }}</b>) y revisando que la información
                                                    confirmando que es correcta, acepto la responsabilidad del cambio.
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Cerrar
                            </button>
                            <button type="submit" class="btn btn-primary">Salida sin cobro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection
