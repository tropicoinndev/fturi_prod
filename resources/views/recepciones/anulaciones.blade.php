@extends('layouts.app')
@section('style')
    <style>

    </style>
@endsection
@section('content')
    <div class="container">
        <div class="row col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title h3">Anulación de Estadia Nº {{ $p->id }} | Habitacion
                        {{ $p->habitaciones->numero_habitacion }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted ">Generar anulación</h6>
                    <div class="card-text">
                        <div class="row">
                            <div class="col-12 h5 fw-bolder">
                                Información del cliente
                            </div>
                            <div class="col-2">
                                Cliente:
                            </div>
                            <div class="col-10">
                                {{ $p->clientes->nombre }}
                            </div>
                            <div class="col-2">
                                Dirección:
                            </div>
                            <div class="col-10">
                                {{ $p->clientes->direccion }}
                            </div>
                            <div class="col-2">
                                Observaciones:
                            </div>
                            <div class="col-10">
                                {{ $p->clientes->observaciones ?? '---' }}
                            </div>
                        </div>
                        <div class="row my-4">
                            <div class="col-12 card-title fw-bolder h5">
                                Resumen de estadía
                                @if ($p->facturada)
                                    <span class="float-end text-bg-success p-1 rounded">CUENTA FACTURADA</span>
                                @endif
                            </div>
                            <div class="col-2">
                                Ingreso
                            </div>
                            <div class="col-10">
                                {{ $p->fecha_ingreso }}
                            </div>
                            <div class="col-2">
                                Salida
                            </div>
                            <div class="col-10">
                                {{ $p->fecha_salida }}
                            </div>
                            <div class="col-2">
                                Usuario
                            </div>
                            <div class="col-10">
                                {{ $p->usuarios->name }}
                            </div>
                            <div class="col-2">
                                Fecha / hora de creación:
                            </div>
                            <div class="col-10">
                                {{ $p->created_at }}
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
                                    \Carbon::parse($p->fecha_ingreso)->diffInDays(\Carbon::parse($p->fecha_salida)),
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

                        <form action="{{ route('recepciones.anulaciones') }}" method="post">
                            @csrf
                            <input type="hidden" name="recepcion_id" value="{{ $p->cid }}">
                            <div class="row p-4 rounded border border-1 border-danger">
                                <div class="col-12 h5 fw-bold">
                                    Anulación de la estadía
                                </div>
                                <div class="col-12 mb-3">

                                    <label for="exampleFormControlTextarea1" class="form-label">Justifique la
                                        anulación:</label>
                                    <textarea class="form-control" id="justificacion" name="justificacionAnular"
                                        placeholder="Escriba aquí la justificación de la anulación de esta estadía, si es posible agregue el comprobante donde se realizo el cobro, y la persona que solicita la anulación."
                                        rows="5" required minlength="10" maxlength="250"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" name="confirm"
                                            id="confirm">
                                        <label class="form-check-label" for="confirm">
                                            Confirmo que he verificado la información y esta acorde a lo requerido.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-danger " type="submit">Anular</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
