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

        .card-cargos {
            background: #80CBC4;
            border: none;
            color: #263238;
        }
    </style>

    <div id="appRecepcionShow" class="container">
        <div class="row justify-content-center">
            <div class="col-9 m-auto">
                <div class="row p-4 {{ $p->fecha_salida <= date('Y-m-d') ? 'panel-salida' : 'panel-normal' }}">
                    <div class="col-8 h3 text-uppercase">
                        Habitación {{ $p->habitaciones->numero_habitacion }}
                    </div>
                    <div class="col-4 text-right">
                        <span class="float-end">
                            REG. #{{ $p->id }}
                        </span>
                    </div>
                    <div class="col-12 text-uppercase mb-3">
                        {{ $p->habitaciones->relacionTipoHabitaciones->tipo_habitacion }} |
                        {{ $p->habitaciones->relacionFormaHabitaciones->forma_habitacion }}
                    </div>
                    <div class="col-6 text-uppercase mb-2">
                        <div class="alert" role="alert">
                            <span class="fw-bolder">
                                Entrada
                            </span>
                            <br>
                            {{ date('l, j F Y', strtotime($p->fecha_ingreso)) }}
                        </div>
                    </div>
                    <div class="col-6 text-uppercase">
                        <div class="alert" role="alert">
                            @if ($p->fecha_salida <= date('Y-m-d'))
                                <div class="spinner-grow text-danger float-end" role="status">
                                    <span class="visually-hidden"></span>
                                </div>
                            @endif
                            <span class="fw-bolder">
                                salida
                            </span>
                            <br>
                            {{ date('l, j F Y', strtotime($p->fecha_salida)) }}

                        </div>
                    </div>
                    <!--BOTONES DE CONTROL DE RECEPCION-->
                    <div class="col-12">
                        @if ($p->clientes_id > 0)
                            <a class="btn btn-light text-danger m-1"
                                href="{{ route('recepciones.salida', ['id' => \Crypt::encryptString($p->id)]) }}"
                                role="button">
                                Salida
                            </a>
                            <a class="btn btn-light m-1"
                                href="{{ $p->huespedes->count() > 0 ? route('recepciones.container', ['id' => Crypt::encryptString($p->id)]) : '#' }}"
                                role="button"
                                title="{{ $p->huespedes->count() > 0 ? 'Imprimir tarjeta de registro' : 'Aun no se han agregado huéspedes. Es requerido agregar todos los huéspedes para poder imprimir la tarjeta de registro.' }}">
                                <span class="mdi mdi-printer-check"></span>
                                Imprimir registro
                            </a>

                            @if (session('caja') && !$p->facturada)
                                <button class="btn btn-light dropdown-toggle m-1 " type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="mdi mdi-plus"></span>
                                    Opciones
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @can('anticipos.create')
                                        @if (session('caja'))
                                            @if (!$p->facturada && !$p->comprobante)
                                                <li>
                                                    <a class="dropdown-item" href="#AddAnticipos" data-bs-toggle="modal"
                                                        data-bs-target="#AddAnticipos" role="button">
                                                        <span class="mdi mdi-cash-clock"></span>
                                                        Anticipos
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#editarTarifa" data-bs-toggle="modal"
                                                        data-bs-target="#editarTarifa" role="button">
                                                        <span class="mdi mdi-book-edit"></span>
                                                        Editar tarifa
                                                    </a>
                                                </li>
                                            @endif
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('cajas.login') }}" role="button">
                                                    <span class="mdi mdi-lock-check"></span>
                                                    Iniciar sesión en caja
                                                </a>
                                            </li>
                                        @endif
                                    @endcan
                                    @can('mantenimientos.create')
                                        <li>
                                            <a class="dropdown-item" href="#agregarMantenimiento" data-bs-toggle="modal"
                                                data-bs-target="#agregarMantenimiento" role="button">
                                                <span class="mdi mdi-wrench-cog-outline"></span>
                                                Agregar mant.
                                            </a>
                                        </li>
                                    @endcan
                                    @can('recepciones.edit')
                                        @if (!$p->facturada && !$p->comprobante)
                                            <li>
                                                <a class="dropdown-item" href="#editarSalida" data-bs-toggle="modal"
                                                    data-bs-target="#editarSalida" role="button"
                                                    title="Podra modificar la fecha de salida a una fecha posterior a {{ date('d-m-Y', strtotime($p->fecha_salida)) }}">
                                                    <span class="mdi mdi-calendar-range"></span>
                                                    Prolongación
                                                </a>
                                            </li>
                                        @endif
                                    @endcan
                                    @can('cargos.create')
                                        @if (!$p->facturada && !$p->comprobante)
                                            <li>
                                                <a class="dropdown-item" href="#createCargos" data-bs-toggle="modal"
                                                    data-bs-target="#createCargos" role="button"
                                                    title="Podrá agregar cargos a esta estadía">
                                                    <span class="mdi mdi-archive-plus-outline"></span>
                                                    Cargos
                                                </a>
                                            </li>
                                        @endif
                                    @endcan
                                    <li>
                                        <a class="dropdown-item" href="#addHuesped" data-bs-toggle="modal"
                                            data-bs-target="#addHuesped" role="button"
                                            title="Agregar huéspedes existentes a esta estadía, el primer huésped estará como responsable de firmar la tarjeta de registro">
                                            <span class="mdi mdi-account-plus"></span>
                                            Huésped
                                        </a>
                                    </li>
                                    @if (!$p->facturada && !$p->comprobante)
                                        @can('recepciones.anular')
                                            <li>
                                                <a class="dropdown-item text-danger" href="#anularRecepcion"
                                                    data-bs-toggle="modal" data-bs-target="#anularRecepcion" role="button">
                                                    <span class="mdi mdi-delete"></span>
                                                    Anular recepción
                                                </a>
                                            </li>
                                        @endcan
                                    @endif
                                </ul>
                                <button class="btn btn-light dropdown-toggle m-1 " type="button" id="dropdownMenuButton1"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="mdi mdi-file-document-check"></span>
                                    Cobro
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    @if (!$p->comprobante)
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('recepciones.comprobante', [
                                                    'id' => Crypt::encryptString($p->id),
                                                ]) }}">
                                                Solicitar comprobante
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('cortesias.aplicar', [
                                                    'id' => Crypt::encryptString($p->id),
                                                    'origen' => Crypt::encryptString(2),
                                                ]) }}">
                                                Cortesía
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('cobros.create', [
                                                    'origen' => Crypt::encryptString(2),
                                                    'origen_id' => Crypt::encryptString($p->id),
                                                    'tipo_comprobante' => Crypt::encryptString('7002'),
                                                ]) }}">
                                                Factura
                                            </a>
                                        </li>
                                        @if ($p->clientes_id > 0 && !$p->clientes->tipo_cliente && $p->clientes->ccf)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('cobros.create', [
                                                        'origen' => Crypt::encryptString(2),
                                                        'origen_id' => Crypt::encryptString($p->id),
                                                        'tipo_comprobante' => Crypt::encryptString('7001'),
                                                    ]) }}">
                                                    Crédito fiscal
                                                </a>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            @endif
                        @endif
                        @can('recepciones.update_cliente')
                            @if (!$p->facturada && !$p->comprobante)
                                <a class="btn btn-light m-1" href="#editarCliente" data-bs-toggle="modal"
                                    data-bs-target="#editarCliente" role="button">
                                    <span class="mdi mdi-account-edit"></span>
                                    Editar cliente
                                </a>
                            @endif
                        @endcan

                        @if (!session('caja'))
                            <a class="btn btn-light text-success m-1" href="{{ route('cajas.login') }}"
                                title="Para desbloquear todas las acciones, antes inicie sesión en una caja.">
                                Iniciar sesión en caja
                            </a>
                        @endif
                    </div>
                </div>
                @if ($p->clientes_id > 0)
                    <div class="row p-4 bg-light" id="showHab">
                        <div class="col-12 text-uppercase">
                            <x-message></x-message>
                            <!--RESUMEN DE PAGOS-->
                            <div class="card mb-4 border-success-subtle">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 card-title fw-bolder">
                                            Resumen de pago
                                            @if ($p->facturada)
                                                <span class="float-end text-bg-success p-1 rounded">CUENTA FACTURADA</span>
                                            @endif
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Tarifa:
                                        </div>
                                        <div class="col-10 mb-1 text-uppercase">
                                            ${{ $p->tarifas->precio }} / {{ $p->tarifas->numero_dias }}
                                            {{ $p->tarifas->numero_dias > 1 ? 'días' : 'dia' }} |
                                            <b>
                                                {{ $p->tarifas->tarifa }}
                                            </b>
                                        </div>

                                        @php
                                            $dias = intval(
                                                \Carbon::parse($p->fecha_ingreso)->diffInDays(
                                                    \Carbon::parse($p->fecha_salida),
                                                ),
                                            );

                                            $total = $p->tarifas->monto * $dias;
                                            $agregados = 1 + env('iva', 0.13) + env('cesc', 0.05);
                                            $neto = $total / $agregados;
                                            $cet = $neto * env('cesc', 0.05);
                                            $iva = $neto * env('iva', 0.13);
                                            $estadiaTotal = $neto + $iva + $cet;
                                            $anticipoTotal = (float) $p->getAnticipos->sum('anticipos_sum_monto');
                                            $cargosTotal = $p->cargos->sum('total');
                                            $totalCobro = $estadiaTotal + $cargosTotal - $anticipoTotal;
                                        @endphp

                                        <div class="col-2 fw-medium mb-1">
                                            Tarifa por dia:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($p->tarifas->monto, 2) }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Estadia:
                                        </div>
                                        <div class="col-10 mb-1">
                                            {{ $dias }} {{ $dias > 1 ? 'dias' : 'dia' }}
                                        </div>

                                        <div class="col-2 fw-medium mb-1">
                                            Estadia neto:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($neto, 2) }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            CET:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($cet, 2) }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            IVA:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($iva, 2) }}
                                        </div>

                                        <div class="col-2 fw-medium mb-1">
                                            Total estadía:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($estadiaTotal, 2) }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Total cargos:
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($cargosTotal, 2) }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Anticipos: (-)
                                        </div>
                                        <div class="col-10 mb-1">
                                            ${{ number_format($anticipoTotal, 2) }}
                                        </div>
                                        @if (!$p->facturada)
                                            <div class="col-2 fw-medium mb-1">
                                                <b>
                                                    Dif.:
                                                </b>
                                            </div>
                                            <div class="col-10 mb-1">
                                                <b>
                                                    @if ($totalCobro < 0)
                                                        ${{ number_format($totalCobro, 2) }}
                                                        <span class="badge text-bg-primary ms-2">Anticipo a favor del
                                                            cliente</span>
                                                    @elseif(round($totalCobro, 2) == 0)
                                                        ${{ number_format($totalCobro, 2) }}
                                                        <span class="badge text-bg-primary  ms-2">Pago completo</span>
                                                    @else
                                                        ${{ number_format($totalCobro, 2) }}
                                                        <span class="badge text-bg-danger  ms-2">Pendiente de pago</span>
                                                    @endif

                                                </b>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!--Resumen de asignacion de anticipos-->
                            <div class="card mb-4 boder-success-subtle text-uppercase">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 fw-bolder mb-3">
                                            Anticipos asignados a esta estadia
                                        </div>
                                        @forelse ($p->getanticipos as $a)
                                            <div class="col-12 col-lg-6 mb-4">
                                                <div class="card card-anticipos">
                                                    <div class="card-header">
                                                        #{{ $a->anticipos->id }}
                                                        <span
                                                            class="float-end">${{ number_format($a->anticipos->monto, 2) }}</span>
                                                    </div>
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $a->anticipos->clientes->nombre }}</h5>
                                                        <p class="card-text">{{ $a->anticipos->concepto }}</p>
                                                    </div>
                                                    <div class="card-footer">
                                                        <a class="btn btn-light m-2" target="_blank"
                                                            href="{{ route('anticipos.container', ['id' => Crypt::encryptString($a->anticipos->id)]) }}">
                                                            <span class="mdi mdi-printer"></span>
                                                            Imprimir
                                                        </a>
                                                        <a class="btn btn-light text-danger" href="#"
                                                            data-bs-toggle="modal" data-bs-target="#delAnticipo"
                                                            role="button"
                                                            @click="urlAnticipo = '{{ route('anticipos.destroy_asignacion', ['id' => Crypt::encryptString($a->id)]) }}'">
                                                            <span class="mdi mdi-delete"></span>
                                                            Eliminar asignacion
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                        @empty
                                            <div class="alert alert-light"> No hay anticipos asignados a esta estadia</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <!--Resumen de cargos agregados-->
                            <div class="card mb-4 boder-success-subtle text-uppercase">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 fw-bolder mb-3">
                                            Cargos agregados
                                        </div>
                                        @forelse ($p->cargos as $c)
                                            <div class="col-12 col-lg-6 mb-4">
                                                <div class="card card-cargos">
                                                    <div class="card-body">
                                                        <h5 class="card-title fw-bold">{{ $c->cargos->cargo }}</h5>
                                                        <div class="card-text">
                                                            Monto Unit.: ${{ number_format($c->cargos->precio, 2) }}
                                                        </div>
                                                        <div class="card-text">
                                                            Cantidad: {{ $c->cantidad }}
                                                        </div>
                                                        <div class="card-text fw-bolder">
                                                            Total: ${{ number_format($c->total, 2) }}
                                                        </div>
                                                        <div class="card-text mt-3">
                                                            <a class="btn btn-light text-danger" href="#"
                                                                data-bs-toggle="modal" data-bs-target="#delCargos"
                                                                role="button"
                                                                @click="urlCargo = '{{ route('cargos.delete_recepcion', ['id' => Crypt::encryptString($c->id)]) }}'">
                                                                <span class="mdi mdi-delete"></span>
                                                                Eliminar
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        @empty
                                            <div class="alert alert-light"> No hay cargos agregados a esta estadia</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            <!--INFORMACION DEL CLIENTE-->
                            <div class="card border-success-subtle">
                                <div class="card-body">

                                    <div class="row">
                                        <div class="col-12 card-title fw-bolder">
                                            Información del cliente
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Cliente:
                                        </div>
                                        <div class="col-10 mb-1">
                                            {{ $p->clientes->nombre }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Dirección:
                                        </div>
                                        <div class="col-10 mb-1">
                                            {{ $p->clientes->direccion }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Municipio:
                                        </div>
                                        <div class="col-10 mb-1">
                                            {{ $p->clientes->municipios->municipio ?? '' }}
                                        </div>

                                        @isset($p->clientes->detalle)
                                            <div class="col-2 fw-medium mb-1">
                                                Razón social:
                                            </div>
                                            <div class="col-10 mb-1">
                                                {{ $p->clientes->detalle->juridico }}
                                            </div>
                                            <div class="col-2 fw-medium mb-1">
                                                Giros:
                                            </div>
                                            <div class="col-10 mb-1">
                                                <ul>
                                                    @foreach ($p->clientes->giros as $g)
                                                        <li>
                                                            {{ $g->giros->giro }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            <div class="col-2 fw-medium mb-1">
                                                Excento:
                                            </div>
                                            <div class="col-10 mb-1">
                                                {{ $p->clientes->detalle->exento ? 'Este cliente es excento de impuestos' : 'No es excento' }}
                                            </div>
                                            <div class="col-2 fw-medium mb-1">
                                                Percepcion:
                                            </div>
                                            <div class="col-10 mb-1">
                                                {{ $p->clientes->detalle->percepcion ? 'Este cliente es agente de retencion (' . env('percepcion', '0.01') * 100 . '%)' : 'No es agente de retencion' }}
                                            </div>
                                        @endisset
                                        <div class="col-2 fw-medium mb-1">
                                            Credito:
                                        </div>
                                        <div class="col-10 mb-1 {{ $p->clientes->credito ? 'text-info' : 'text-muted' }}">
                                            {{ $p->clientes->credito ? 'Permite credito' : 'No tiene credito autorizado' }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Descuento:
                                        </div>
                                        <div
                                            class="col-10 mb-1 {{ $p->clientes->descuento ? 'text-info' : 'text-muted' }}">
                                            {{ $p->clientes->descuento ? 'Permite aplicar descuentos' : 'Este cliente no tiene permitido descuentos' }}
                                        </div>
                                        <div class="col-2 fw-medium mb-1">
                                            Observaciones:
                                        </div>
                                        <div class="col-10 mb-1">
                                            {{ $p->clientes->observaciones ?? '---' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- INFORMACION DE CONTACTO -->
                            <div class="card mt-4 border-success-subtle">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 card-title fw-bolder">
                                            Información de contacto e identificaciones del cliente
                                        </div>
                                        @foreach ($p->clientes->identificaciones as $c)
                                            <div class="col-2 fw-medium mb-1">
                                                {{ $c->identificaciones->identificacion }}:
                                            </div>
                                            <div class="col-10 mb-1">
                                                {{ $c->numero }}
                                            </div>
                                        @endforeach
                                        @foreach ($p->clientes->contactos as $c)
                                            <div class="col-2 fw-medium mb-1">
                                                {{ $c->contactos->contacto }}:
                                            </div>
                                            <div class="col-10 mb-1">
                                                {{ $c->valor }}
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                            <!-- HUESPEDES -->
                            <div class="card mt-4 border-success-subtle">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 card-title fw-bolder">
                                            Huespedes
                                        </div>
                                        @forelse ($p->huespedes as $h)
                                            <div class="col-6 mb-3">
                                                <div class="card" style="min-height: 110px;">
                                                    <div class="card-body">
                                                        <a href="{{ route('recepciones.delete_huesped', ['id' => Crypt::encryptString($h->id)]) }}"
                                                            class="btn btn-light float-end text-danger">
                                                            <span class="mdi mdi-delete fs-5"></span>
                                                        </a>
                                                        <h5 class="card-title">{{ $h->huesped->nombre }}</h5>
                                                        <p class="card-text">
                                                            @if ($h->huesped->identificaciones != null)
                                                                {{ $h->huesped->identificaciones->identificacion }}:
                                                                {{ $h->huesped->identificacion ?? 'No se agrego' }}
                                                            @else
                                                                No se agrego identificacion
                                                            @endif
                                                            | Tel.: {{ $h->huesped->telefono ?? 'No se agrego' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12">
                                                <div class="alert alert-light" role="alert">
                                                    <a class="btn btn-light m-2" href="#addHuesped"
                                                        data-bs-toggle="modal" data-bs-target="#addHuesped"
                                                        role="button">
                                                        <span class="mdi mdi-account-plus"></span>
                                                        Huesped
                                                    </a>
                                                    Aun no se han agregado huéspedes a esta estadia.

                                                </div>
                                            </div>
                                        @endforelse



                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row bg-light p-4">
                        <div class="col-12 h5 text-uppercase">
                            <b>Titular:</b>
                            {{ $p->titular }}
                        </div>
                        <div class="col-12">
                            <div class="alert alert-warning" role="alert">
                                Debe asignarse un cliente a esta estadía, para continuar con el registro
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <!--MODAL AGREGAR HUÉSPEDES -->
        <div id="addHuesped" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Registro de huespedes</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="buscarhuesped" class="form-label">Buscar huesped
                                        registrado</label>
                                    <input type="text" class="form-control" id="buscarhuesped"
                                        placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                        v-model="buscarHuesped" @keyup="getHuespedSearch()">

                                    <div class="lista shadow-lg w-25">
                                        <ul class="list-group list-group" v-show="listHuesped.length > 0">
                                            <li v-for="v in listHuesped" :key="v.id"
                                                class="list-group-item list-group-item-action text-uppercase"
                                                @click="setHuesped(v)">
                                                @{{ v.nombre }} · @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identifiacion' }}
                                                @{{ v.identificacion }} · @{{ v.telefono ?? 'Sin telefono' }}
                                            </li>
                                        </ul>
                                        <ul class="list-group list-group"
                                            v-show="listHuesped.length == 0 && buscarHuesped.length > 4">
                                            <li class="list-group-item list-group-item-action text-uppercase">No se
                                                han encontrado registros con los parametros de busqueda, intente
                                                cambiar los parametros de busqueda.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-show="huespedSelect.length > 0">
                            <form action="{{ route('recepciones.huesped') }}" method="post">
                                @csrf
                                <input type="hidden" name="recepciones_id" value="{{ \Crypt::encryptString($p->id) }}">
                                <div class="col-12">
                                    <h5>HUESPEDES AGREGADOS A LA HABITACION</h5>
                                    <div class="card border border-info mb-3" v-for="v in huespedSelect"
                                        :key="v.id">
                                        <input class="d-none" type="checkbox" :value="v.id" name="huespedes[]"
                                            checked>
                                        <div class="card-body text-uppercase">
                                            <h5 class="card-title">
                                                <span class="mdi mdi-close float-end pointer text-danger"
                                                    @click="deletHuesped(v.id)">
                                                </span>
                                                @{{ v.nombre }}
                                            </h5>
                                            <p class="card-text">
                                                @{{ v.identificaciones_id > 0 ? v.identificaciones.identificacion : 'Sin identifiacion' }} @{{ v.identificacion }} · @{{ v.telefono ?? 'Sin telefono' }}
                                                <br>
                                                @{{ v.municipios_id > 0 ? v.municipios.municipio : 'Sin ciudad' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--MODAL ElIMINAR ASIGNACIÓN DE ANTICIPO -->
        <div id="delAnticipo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Eliminar asignacion de anticipo</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    ¿Esta seguro de eliminar esta asignacion de anticipo de la estadia <b>No.
                                        {{ $p->id }}</b>?
                                </div>
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" value="" id="eliminarConfirm"
                                        v-model="confirmDel">
                                    <label class="form-check-label" for="eliminarConfirm">
                                        Estoy seguro de borrar esta asignación.
                                    </label>

                                </div>
                                <a class="btn btn-danger" :href="getUrl()" :class="{ 'disabled': !confirmDel }">
                                    <span class="mdi mdi-delete"></span>
                                    Eliminar asignacion
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--MODAL ElIMINAR CARGOS -->
        <div id="delCargos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content" v-if="urlCargo.length > 0">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Eliminar cargo</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-4">
                                    ¿Esta seguro de eliminar el cargo a esta estadia?
                                </div>
                                <form :action="urlCargo" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="observacion" class="form-label">
                                            Observacion de la eliminacion:
                                        </label>
                                        <textarea class="form-control" name="observacion" id="observacion" rows="3"
                                            placeholder="Escriba las observaciones de la eliminacion (Max: 200 caracteres)"></textarea>
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" type="checkbox" value="1"
                                            id="confirmacionCargoEliminacion" v-model="confirmDelCargo">
                                        <label class="form-check-label" for="confirmacionCargoEliminacion">
                                            Estoy seguro de borrar esta asignación.
                                        </label>
                                    </div>
                                    <button class="btn btn-danger" type="submit"
                                        :class="{ 'disabled': !confirmDelCargo }">
                                        <span class="mdi mdi-delete"></span>
                                        Eliminar cargo
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--MODAL AGREGAR ANTICIPOS -->
        <div id="AddAnticipos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Agregar anticipo</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <x-anticipos-form table="anticipos" :forma="$forma_pagos" :concepto="'Pago de anticipo por estadia del ' .
                            date('d-m-Y', strtotime($p->fecha_ingreso)) .
                            ' al ' .
                            date('d-m-Y', strtotime($p->fecha_salida)) .
                            ', en habitación ' .
                            $p->habitaciones->numero_habitacion" :cliente="$p->clientes_id"
                            :tipo_reservacion="2" :reservacion_id="$p->id" />
                    </div>

                </div>
            </div>
        </div>
        <!--MODAL AGREGAR CARGOS -->
        <div id="createCargos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="Crear cargos"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Agregar cargos</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form action="{{ route('cargos.store_recepcion') }}" method="post">
                                @csrf
                                <input type="hidden" name="recepciones_id" value="{{ $p->cid }}">
                                <div class="mb-3">
                                    <x-input-select :data="$cargos" showName="nombre" label="Seleccione el cargo"
                                        name="cargos_id" required></x-input-select>
                                </div>
                                <div class="mb-3">
                                    <x-input-number name="cantidad" label="Cantidad" placeholder="Cantidad: 1"
                                        value="1" step="1" min="1" required></x-input-number>
                                </div>
                                <div class="mb-3">
                                    <x-input-text-area label="Observaciones" name="observacion"></x-input-text-area>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!--MODAL EDITAR FECHA DE SALIDA -->
        <div id="editarSalida" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Eliminar asignacion de anticipo</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="{{ route('recepciones.update_fecha_salida') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ Crypt::encryptString($p->id) }}">
                                    <div class="mb-4">
                                        <label for="fecha_salida">Escriba la nueva fecha de salida </label>
                                        <input type="date" name="fecha_salida" id="fecha_salida"
                                            min="{{ $p->fecha_salida }}" class="form-control"
                                            :class="{
                                                'is-valid': fecha_salida.length > 5 &&
                                                    validDateOut,
                                                'is-invalid': fecha_salida.length > 5 && !validDateOut
                                            }"
                                            v-model="fecha_salida" @change="apiReservasDisponibles()">
                                        <div class="text-info" v-show="fecha_salida.length > 5 && validDateOut">
                                            <span class="mdi mdi-check fs-4"></span>
                                            Fecha valida
                                        </div>
                                        <div class="text-danger" v-show="fecha_salida.length > 5 && !validDateOut">
                                            <span class="mdi mdi-cancel fs-4"></span>
                                            Ya hay una reservacion para esta fecha, elija otra fecha o cambie el cliente de
                                            habitacion.
                                        </div>
                                    </div>
                                    <div class="form-check mb-4">
                                        <input class="form-check-input" :class="{ 'disabled': !validDateOut }"
                                            type="checkbox" value="1" id="editConfirm" v-model="confirmEdit">
                                        <label class="form-check-label" for="editConfirm">
                                            Estoy seguro de cambiar la fecha de salida.
                                        </label>

                                    </div>
                                    <button type="submit" class="btn btn-success"
                                        :class="{ 'disabled': !getEditable() }">
                                        <span class="mdi mdi-pencil"></span>
                                        Cambiar fecha de salida
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--MODAL EDITAR CLIENTE -->
        <div id="editarCliente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Editar cliente</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="clearClientes()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row" v-show="clienteSelected == null">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="buscarhuesped" class="form-label">Buscar huesped
                                        registrado</label>
                                    <input type="text" class="form-control" id="buscarhuesped"
                                        placeholder="Escriba el nombre, numero de identificacion, o telefono..."
                                        v-model="buscarClientes" @keyup="apiSearchClientes()">

                                    <div class="lista shadow-lg w-25">
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length > 0 && buscarClientes.length > 4">
                                            <li v-for="v in listClientes" :key="'cliente_cod_' + v.id"
                                                class="list-group-item list-group-item-action text-uppercase"
                                                @click="setClienteSelected(v)">
                                                @{{ v.cliente }}
                                            </li>
                                        </ul>
                                        <ul class="list-group list-group"
                                            v-show="listClientes.length == 0 && buscarClientes.length > 4">
                                            <li class="list-group-item list-group-item-action text-uppercase">No se
                                                han encontrado registros con los parametros de busqueda, intente
                                                cambiar los parametros de busqueda.</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="clienteSelected != null && clienteSelected.id > 0">
                            <form action="{{ route('recepciones.update_cliente') }}" method="post">
                                @csrf
                                <input type="hidden" name="recepciones_id" value="{{ \Crypt::encryptString($p->id) }}">
                                <div class="col-12">
                                    <h5>Editar cliente</h5>
                                    <div class="card border border-info mb-3">
                                        <input type="hidden" name="clientes_id" :value="clienteSelected.id">
                                        <div class="card-body text-uppercase">
                                            <h5 class="card-title">
                                                <span class="mdi mdi-close float-end pointer text-danger"
                                                    @click="clearClientes()">
                                                </span>
                                                @{{ clienteSelected.cliente }}
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <h5>Se moveran los siguientes anticipos al nuevo cliente:</h5>
                                    <small>Quite la seccion para los anticipos que no deben cambiar de cliente</small>
                                </div>
                                <div class="row">
                                    @foreach ($p->getAnticipos as $at)
                                        <div class="col-4 mb-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <input type="checkbox" name="anticipos_id[]" class="form-check-input"
                                                        multiple value="{{ Crypt::encryptString($at->anticipos->id) }}"
                                                        id="anticipo_{{ $at->id }}" checked />
                                                    <span class="float-end">No.{{ $at->anticipos->id }}</span>
                                                </div>
                                                <label class="card-body" for="anticipo_{{ $at->id }}">
                                                    <p class="card-text">
                                                        <b>Monto:</b> ${{ number_format($at->anticipos->monto, 2) }}
                                                        <br>
                                                        <b>Concepto:</b> {{ $at->anticipos->concepto }}
                                                    </p>
                                                </label>
                                                <div class="card-footer">
                                                    Creado: {{ $at->anticipos->created_at }}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-12 my-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" v-model="clienteConfirm"
                                            id="confirmEditCliente" value="1">
                                        <label class="form-check-label" for="confirmEditCliente">
                                            Confirmo, estoy seguro de realizar los cambios.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary" :disabled="getValidEditCliente">Editar
                                        cliente </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--MODAL AGREGAR MANTENIMIENTO -->
        <div id="agregarMantenimiento" class="modal fade" tabindex="-1" role="dialog"
            aria-labelledby="my-modal-title" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="my-modal-title">Agregar Mantenimientos a Habitación #
                            {{ $p->habitaciones->numero_habitacion }}</h5>
                        <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            @click="clearClientes()"></button>
                    </div>
                    <form id="mantenimientoForm" action="{{ route('mantenimientos.detalleHabitacion') }}"
                        method="POST">
                        @csrf

                        <div class="modal-body">
                            <div class="mb-3">

                                <div class="form-group">
                                    <label for="my-input">Seleccionar el tipo de mantenimiento a realizar en la
                                        habitacion</label>
                                    <select class="form-select" aria-label="Tipos de mantenimientos"
                                        name="tipo_mantenimientos_id" required>
                                        <option selected value="">Seleccione un tipo de mantenimiento
                                        </option>
                                        @foreach ($tipo_mantenimientos as $tm)
                                            <option value="{{ Crypt::encryptString($tm->id) }}">
                                                {{ $tm->mantenimiento }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">

                                <!-- Campo oculto para el ID de la habitación encriptado -->
                                <input type="hidden" name="habitacion_id"
                                    value="{{ Crypt::encryptString($p->habitaciones->id) }}">
                            </div>
                            <div class="mb-3">
                                <label for="observacion" class="form-label">Observacion:
                                </label>
                                <textarea class="form-control h-100" id="observacion" name="observacion" v-model="observacion"
                                    style="resize: vertical;"></textarea>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button id="guardarBtn" type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        @if (!$p->facturada && !$p->comprobante)
            <!--MODAL AGREGAR MANTENIMIENTO -->
            <div id="editarTarifa" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="my-modal-title">Editar tarifa Habitacion #
                                {{ $p->habitaciones->numero_habitacion }}</h5>
                            <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('recepciones.cambiarTarifa') }}" method="POST">
                                @csrf
                                <input type="hidden" name="recepcion_id" value="{{ $p->cid }}">

                                <div class="mb-3">
                                    Tarifa actual:
                                    <b>
                                        {{ $p->tarifas->tarifa }}
                                        ${{ number_format($p->tarifas->precio, 2) }} ({{ $p->tarifas->numero_dias }}
                                        dia(s))
                                    </b>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="my-input">
                                        Seleccione una tarifa
                                    </label>
                                    <select class="form-select" aria-label="Tipos de mantenimientos" name="tarifas_id"
                                        required>
                                        <option selected value="">Seleccione una tarifa
                                        </option>
                                        @foreach ($tarifas as $tr)
                                            <option value="{{ Crypt::encryptString($tr->tarifas->id) }}">
                                                {{ $tr->tarifas->tarifa }} ${{ number_format($tr->tarifas->precio, 2) }}
                                                ({{ $tr->tarifas->temporadas->temporada }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="justificacion" class="form-label">Justifique el cambio:
                                    </label>
                                    <textarea class="form-control h-100" id="justificacion" name="justificacion" rows="3"
                                        placeholder="Escriba aqui porque debe cambiarse la tarifa de esta habitacion." minlength="10"></textarea>

                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="confirm"
                                            name="confirm" required>
                                        <label class="form-check-label" for="confirm">
                                            Confirmo y estoy seguro de cambiar la tarifa para esta habitacion
                                        </label>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cerrar</button>
                                    <button id="guardarBtn" type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @can('recepciones.anular')
                <!--MODAL AGREGAR MANTENIMIENTO -->
                <div id="anularRecepcion" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header text-bg-danger">
                                <h5 class="modal-title" id="my-modal-title">ANULAR RECEPCION Nº {{ $p->id }}
                                    HABITACION #
                                    {{ $p->habitaciones->numero_habitacion }}
                                </h5>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('recepciones.anulacion') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="recepcion_id" value="{{ $p->cid }}">


                                    <div class="mb-3">
                                        <label for="justificacionAnular" class="form-label">
                                            Justifique la anulacion:
                                        </label>
                                        <textarea class="form-control h-100" id="justificacionAnular" name="justificacionAnular" rows="5"
                                            placeholder="Escriba aqui porque debe anularse esta recepcion." minlength="10"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="confirmAnular" name="confirm" required>
                                            <label class="form-check-label" for="confirmAnular">
                                                Confirmo y estoy seguro de anular esta recepcion.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cerrar</button>
                                        <button id="guardarBtn" type="submit" class="btn btn-danger">Anular</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endcan
        @endif
    </div>
    </div>
@endsection
@section('script-hab')
    <script type="module">
        const app = appVue({
            data() {
                return {
                    buscarHuesped: '',
                    listHuesped: [],
                    huespedSelect: [],
                    cantidadPersona: '{{ $p->habitaciones->relacionFormaHabitaciones->max_personas }}',
                    urlAnticipo: '',
                    urlCargo: '',
                    confirmDel: false,
                    confirmEdit: false,
                    confirmDelCargo: false,
                    fecha_salida: '',
                    validDateOut: false,
                    id: '{{ Crypt::encryptString($p->id) }}',
                    listClientes: [],
                    buscarClientes: '',
                    clienteSelected: null,
                    clienteConfirm: false,
                    clientes_cod: parseInt('{{ $p->clientes_id }}'),
                    observacion: '',
                }
            },
            methods: {
                getHuespedSearch: function() {
                    if (this.buscarHuesped.length > 3) {
                        axios.post('{{ route('huespedes.api_buscar') }}', {
                            'buscar': this.buscarHuesped.toUpperCase(),
                        }).then(r => {
                            if (r.data.list.length)
                                this.listHuesped = r.data.list;
                            else this.listHuesped = [];
                        })
                    } else this.listHuesped = [];

                },
                apiSearchClientes: function() {
                    if (this.buscarClientes.length > 4) {
                        axios.post("{{ route('clientes.apiGetClientes') }}", {
                                busqueda: (this.buscarClientes).toUpperCase(),
                            })
                            .then((rs) => {
                                this.listClientes = rs.data.clientes;
                            })
                            .catch(error => {
                                console.log('Error JS: ', error);
                            })
                    }
                },
                setClienteSelected: function(v) {
                    if (this.clientes_cod != v.id)
                        this.clienteSelected = v;
                    else
                        alert('Esta eligiendo el mismo cliente asignado');
                },
                clearClientes: function() {
                    this.buscarClientes = '';
                    this.listClientes = [];
                    this.clienteSelected = null;
                },
                setHuesped: function(huesped) {
                    if (this.huespedSelect.length < this.cantidadPersona) {
                        this.huespedSelect.push(huesped);

                    } else alert(
                        'Ya se agrego la cantidad de personas requerida para esta habitacion, si necesita agregar mas personas, modifique el campo cantidad de personas.'
                    )
                    this.listHuesped = [];
                    this.buscarHuesped = '';
                },
                deletHuesped: function(id) {
                    this.huespedSelect = this.huespedSelect.filter(h => h.id != id);
                },
                getUrl: function() {
                    if (this.confirmDel)
                        return this.urlAnticipo;
                    else return "#";
                },
                getEditable: function() {
                    return this.confirmEdit && this.validDateOut;
                },
                apiReservasDisponibles: function() {

                    if (this.fecha_salida.length > 5) {
                        //Traer las habitaciones disponibles.
                        axios.post("{{ route('habitaciones.api_get_salidaValid') }}", {
                                fecha_salida: this.fecha_salida,
                                id: this.id
                            })
                            .then((rs) => {
                                this.validDateOut = rs.data.valid;
                                console.log(rs.data)
                            })
                            .catch(error => {
                                console.log('Error2 JS: ', error);
                            })
                    } else {
                        this.estadoReserva = false;
                        this.setMessage('Por favor ingrese una fecha valida.', 'danger');
                    }
                }
            },
            computed: {
                getValidEditCliente: function() {
                    console.log(this.clienteConfirm, this.clienteSelected != null, this.clienteSelected.id > 0)
                    return !(this.clienteConfirm &&
                        this.clienteSelected != null &&
                        this.clienteSelected.id > 0);
                },
            }
        });
        app.mount("#appRecepcionShow");
    </script>
@endsection
