@extends('layouts.cajas')

@section('panel_caja')
    <div class="row mb-3 text-uppercase">
        <div class="col-12 h2 text-muted">
            Configuración de cobro
        </div>
        @if (isset($p->titular))
            <div class="col-2 mb-1">

                <b>Titular:</b>

            </div>
            <div class="col-10">
                {{ $p->titular }}
            </div>
        @endif
        @if ($p->clientes_id > 0)
            <div class="col-2">
                <b>Cliente:</b>
            </div>
            <div class="col-10 mb-1">
                {{ $p->clientes->nombre }}
            </div>
            @if (!$p->clientes->tipo_cliente)
                <div class="col-2">
                    <b>Nombre juridico:</b>
                </div>
                <div class="col-10 mb-1">
                    {{ $p->clientes->detalle->juridico }}
                </div>
            @endif
        @endif
        <div class="col-2">
            <b>Tipo comprobante: </b>
        </div>
        <div class="col-10 mb-1">
            {{ $p->tipo_comprobante == 7001 ? 'CREDITO FISCAL' : 'CONSUMIDOR FINAL' }}
        </div>
        <div class="col-12">
            @if ($p->clientes_id > 0 && !$p->clientes->tipo_cliente && $p->tipo_comprobante == 7002)
                <a href="{{ route('cobros.tipo_comprobante', ['id' => Crypt::encryptString($p->id), 'tipo' => Crypt::encryptString('7001')]) }}"
                    class="btn btn-light">
                    <span class="mdi mdi-pencil"></span>
                    Cambiar a credito fiscal
                </a>
            @elseif($p->tipo_comprobante == 7001)
                <a href="{{ route('cobros.tipo_comprobante', ['id' => Crypt::encryptString($p->id), 'tipo' => Crypt::encryptString('7002')]) }}"
                    class="btn btn-light">
                    <span class="mdi mdi-pencil"></span>
                    Cambiar a consumidor final
                </a>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12 text-uppercase fw-bolder mb-2">
            Agregados a este cobro
        </div>
        @php
            $total = 0;
        @endphp
        @forelse ($p->detalleCobros as $o)
            @switch($o->origen)
                @case(1)
                    <div class="col-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Orden No. {{ $o->orden->orden }}
                                    <a href="{{ route('detalle_cobros.confirm', ['id' => \Crypt::encryptString($o->id)]) }}"
                                        class="float-end text-danger">
                                        <span class="mdi mdi-delete"></span>
                                    </a>
                                </h5>
                                <p class="card-text">
                                <div>
                                    {{ $o->orden->detalle_orden->count() }}
                                    {{ $o->orden->detalle_orden->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                    · ${{ number_format($o->orden->getSumDetalleOrden(), 2) }}
                                </div>
                                <div>
                                    <span>
                                        {{ $o->orden->cajas->caja }}
                                    </span>
                                    <span class="float-end">
                                        {{ \Carbon::parse($o->orden->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    @php
                        $total += $o->orden->getSumDetalleOrden();
                    @endphp
                @break

                @case(2)
                    <div class="col-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Estadia Reg. {{ $o->recepcion->id }}
                                    <a href="{{ route('detalle_cobros.confirm', ['id' => \Crypt::encryptString($o->id)]) }}"
                                        class="float-end text-danger">
                                        <span class="mdi mdi-delete"></span>
                                    </a>
                                </h5>
                                <p class="card-text">
                                <div>
                                    Estadia de {{ $o->recepcion->dias }}
                                    {{ $o->recepcion->dias > 1 ? 'dias' : 'dia' }}
                                    ·
                                    ${{ number_format(($o->recepcion->tarifa ?? $o->recepcion->tarifas->monto) * $o->recepcion->dias, 2) }}
                                    | Cargos: ${{ number_format($o->recepcion->cargos->sum('total'), 2) }}
                                </div>
                                <div>
                                    <span class="float-end">
                                        {{ \Carbon::parse($o->recepcion->created_at)->diffForHumans() }}
                                    </span>
                                    <span>
                                        Habitacion {{ $o->recepcion->habitaciones->numero_habitacion }}
                                    </span>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    @php
                        $total += $o->recepcion->tarifas->precio * $o->recepcion->dias;
                        $total += $o->recepcion->cargos->sum('total');
                    @endphp
                @break

                @case(3)
                    <div class="col-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Comanda No. {{ $o->comanda->id }} - Mesa #{{ $o->comanda->mesa }}
                                    <a href="{{ route('detalle_cobros.confirm', ['id' => \Crypt::encryptString($o->id)]) }}"
                                        class="float-end text-danger">
                                        <span class="mdi mdi-delete"></span>
                                    </a>
                                </h5>
                                <p class="card-text">
                                <div>
                                    {{ $o->comanda->detalles_comanda->count() }}
                                    {{ $o->comanda->detalles_comanda->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                    · ${{ number_format($o->comanda->detalles_comanda->sum('total'), 2) }}
                                </div>
                                <div>
                                    <span>
                                        {{ $o->comanda->cajas->caja }}
                                    </span>
                                    <span class="float-end">
                                        {{ \Carbon::parse($o->comanda->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                                </p>
                            </div>
                        </div>
                    </div>
                    @php
                        $total += $o->comanda->detalles_comanda->sum('total');
                    @endphp
                @break

                @default
            @endswitch
            @empty
                <div class="col-12 mb-3 text-danger">
                    No se ha configurado nada aun.
                </div>
            @endforelse
            @if (
                (isset($p->clientes->ordenesActivas) && count($p->clientes->ordenesActivas) > 0) ||
                    (isset($p->clientes->recepcionesActivas) && count($p->clientes->recepcionesActivas) > 0) ||
                    (isset($p->clientes->comandasActivas) && count($p->clientes->comandasActivas) > 0))
                <div class="col-12">
                    Seleccione una o mas cuentas pendientes para agregar a este cobro, y presione <b>Agregar continuar</b>.
                </div>
                <form action="{{ route('cobros.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="cobro" value="{{ Crypt::encryptString($p->id) }}">
                    @if (isset($p->clientes->ordenesActivas) && count($p->clientes->ordenesActivas) > 0)
                        <div class="col-12 text-uppercase mb-2 fw-bolder">
                            Ordenes activas de este cliente
                        </div>
                        <div class="col-12">
                            <div class="row">
                                @foreach ($p->clientes->ordenesActivas as $orden)
                                    <div class="col-4 mb-2">
                                        <input class="btn-check" type="checkbox" name="ordenes[]" value="{{ $orden->id }}"
                                            id="ordenes_{{ $orden->orden }}" multiple>
                                        <label class="card btn btn-primary text-start" for="ordenes_{{ $orden->orden }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Orden No. {{ $orden->orden }}</h5>
                                                <p class="card-text">
                                                <div>
                                                    {{ $orden->detalle_orden->count() }}
                                                    {{ $orden->detalle_orden->count() > 1 ? 'conceptos agregados' : 'concepto agregado' }}
                                                    · ${{ number_format($orden->getSumDetalleOrden(), 2) }}
                                                </div>
                                                <div>
                                                    <span>
                                                        {{ $orden->cajas->caja }}
                                                    </span>
                                                    <span class="float-end">
                                                        {{ \Carbon::parse($orden->created_at)->diffForHumans() }}
                                                    </span>
                                                </div>
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (isset($p->clientes->recepcionesActivas) && count($p->clientes->recepcionesActivas) > 0)
                        <div class="col-12 text-uppercase mb-2 mt-1 fw-bolder">
                            Estadias activas de este cliente
                        </div>
                        <div class="col-12">
                            <div class="row">
                                @foreach ($p->clientes->recepcionesActivas as $recepcion)
                                    <div class="col-4 mb-2">

                                        @if ($p->tipo_comprobante == 7001 && !$p->clientes->tipo_cliente && !$p->clientes->ccf)
                                            <input class="btn-check" type="checkbox" name="estadias[]" value=""
                                                id="estadias_{{ $recepcion->id }}" multiple disabled>
                                        @else
                                            <input class="btn-check" type="checkbox" name="estadias[]"
                                                value="{{ Crypt::encryptString($recepcion->id) }}"
                                                id="estadias_{{ $recepcion->id }}" multiple>
                                        @endif
                                        <label class="card btn btn-primary text-start" for="estadias_{{ $recepcion->id }}">
                                            <div class="card-body">
                                                <div class="card-title">
                                                    @if ($p->tipo_comprobante == 7001 && !$p->clientes->tipo_cliente && !$p->clientes->ccf)
                                                        <small class="text-danger float-end"> No permite CCF</small>
                                                    @endif
                                                    <h5>Estadía Reg. {{ $recepcion->id }}</h5>
                                                </div>
                                                <p class="card-text">
                                                <div>
                                                    Estadia de {{ $recepcion->dias }}
                                                    {{ $recepcion->dias > 1 ? 'dias' : 'dia' }}
                                                    · ${{ number_format($recepcion->tarifas->precio * $recepcion->dias, 2) }}

                                                </div>
                                                <div>
                                                    <small class="float-end">
                                                        {{ \Carbon::parse($recepcion->created_at)->diffForHumans() }}
                                                    </small>
                                                    <span>
                                                        Habitacion {{ $recepcion->habitaciones->numero_habitacion }}
                                                    </span>

                                                </div>
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @if (isset($p->clientes->comandasActivas) && count($p->clientes->comandasActivas) > 0)
                        <div class="col-12 text-uppercase mb-2 mt-1 fw-bolder">
                            Comandas activas de este cliente
                        </div>
                        <div class="col-12">
                            <div class="row">
                                @foreach ($p->clientes->comandasActivas as $comanda)
                                    <div class="col-4 mb-2">
                                        <input class="btn-check" type="checkbox" name="comandas[]"
                                            value="{{ Crypt::encryptString($comanda->id) }}" id="comanda_{{ $comanda->id }}"
                                            multiple>
                                        <label class="card btn btn-primary text-start" for="comanda_{{ $comanda->id }}">
                                            <div class="card-body">
                                                <h5 class="card-title">Comanda No. {{ $comanda->id }} </h5>
                                                <p class="card-text">
                                                <div>

                                                    ${{ number_format($comanda->getTotal()->total, 2) }}
                                                </div>
                                                <div>
                                                    <span class="float-end">
                                                        {{ \Carbon::parse($comanda->created_at)->diffForHumans() }}
                                                    </span>
                                                    <span>
                                                        Mesa #{{ $comanda->mesa }}
                                                    </span>

                                                </div>
                                                </p>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif


            @endif
            <div class="col-12 mt-3 text-uppercase">
                Total del cobro: <b>${{ number_format($total, 2) }}</b>

                <div class="col-12 mt-3">
                    @if (
                        $p->tipo_comprobante == 7001 &&
                            !$p->clientes->tipo_cliente &&
                            !$p->clientes->ccf &&
                            $p->detalleCobros->where('origen', 2)->count() > 0)
                        <div class="alert alert-warning" role="alert">
                            No se puede continuar, el cliente no permite CCF para habitación, hay una habitación agregada. Debe
                            cambiar el tipo de comprobante o eliminar la recepcion de este cobro.
                        </div>
                    @else
                        @if (
                            (isset($p->clientes->ordenesActivas) && count($p->clientes->ordenesActivas) > 0) ||
                                (isset($p->clientes->recepcionesActivas) && count($p->clientes->recepcionesActivas) > 0) ||
                                (isset($p->clientes->comandasActivas) && count($p->clientes->comandasActivas) > 0))
                            <button class="btn btn-primary" value="2" name="opcion">Agregar y continuar</button>
                            <button class="btn btn-light text-primary" value="1" name="opcion">Agregar</button>
                        @endif
                        @if ($p->detalleCobros->count() > 0)
                            <a class="btn btn-light "
                                href="{{ route('comprobantes.cobro', ['id' => Crypt::encryptString($p->id)]) }}"
                                role="button">
                                Omitir, continuar
                            </a>
                        @else
                            @can('cobros.delete')
                                <a class="btn btn-light text-danger"
                                    href="{{ route('cobros.confirm', ['id' => Crypt::encryptString($p->id)]) }}" role="button">
                                    Eliminar
                                </a>
                            @endcan
                        @endif
                    @endif
                </div>
                </form>
            </div>
        @endsection
