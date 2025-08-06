@extends('layouts.cajas')

@section('panel_caja')
    <div class="row">
        <div class="col-12">

            <h2 class="text-uppercase">Cuentas activas</h2>
            <p>
                Antes de cerrar el turno debe facturar o anular estas cuentas según corresponda. Tener en cuenta que el
                cierre se podrá realizar hasta que no aparezcan cuantas activas
            </p>

        </div>
    </div>
    @if (count($recepcion) > 0 || count($comandas) > 0 || count($ordenes) > 0)
        @if (count($recepcion) > 0)
            <div class="row">
                <div class="col-12">
                    <h4>
                        Recepciones activas
                    </h4>
                </div>
                @foreach ($recepcion as $r)
                    <div class="col-12 col-lg-4">
                        <div class="card border-secondary mb-3">
                            <div class="card-header">Registro Nº {{ $r->id }}, habitación
                                {{ $r->habitaciones->numero_habitacion }}</div>
                            <div class="card-body">
                                <div class="card-title text-uppercase">
                                    {{ $r->clientes->nombre ?? ($r->titular ?? 'No se agrego cliente o titular') }}
                                </div>
                                <p class="card-text">
                                    {{ $r->creacion }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
        @if (count($comandas) > 0)
            <div class="row">
                <div class="col-12">
                    <h4>
                        Comandas activas
                    </h4>
                </div>
                @foreach ($comandas as $c)
                    <div class="col-12 col-lg-4">
                        <div class="card border-secondary mb-3">
                            <div class="card-header">Comanda Nº{{ $c->id }}</div>
                            <div class="card-body">
                                <div class="card-title text-uppercase">
                                    {{ $c->clientes->nombre ?? ($c->titular ?? 'No se agrego cliente o titular') }}
                                </div>
                                <p class="card-text">
                                    {{ $c->creacion }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
        @if (count($ordenes) > 0)
            <div class="row">
                <div class="col-12">
                    <h4>
                        Ordenes activas
                    </h4>
                </div>
                @foreach ($ordenes as $o)
                    <div class="col-12 col-lg-4">
                        <div class="card border-secondary mb-3">
                            <div class="card-header">Orden Nº{{ $o->orden }}</div>
                            <div class="card-body">
                                <div class="card-title text-uppercase">
                                    {{ $o->clientes->nombre ?? ($o->titular ?? 'No se agrego cliente o titular') }}
                                </div>
                                <p class="card-text">
                                    {{ $o->creacion }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
    @else
        <div class="alert alert-secondary" role="alert">
            No hay ninguna cuenta activa
        </div>
    @endif
@endsection
