@extends('layouts.section_reporte_eventos')

@section('panel_reporte_eventos')
    <style>
        .column,
        .dia {
            position: relative;
            width: 14.28%;
        }

        .dia {

            max-height: 100px;
            height: 100px;
            cursor: pointer;
        }

        .dia-content {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .ocupado {
            background: #455A64;
            color: #fafafa;
        }

        .dia .tooltip {
            visibility: hidden;
            width: 180px;
            background-color: #2979FF;
            color: #fff;
            text-align: center;
            padding: 8px;
            border-radius: 5px;
            position: absolute;
            z-index: 1;
            bottom: 110%;
            left: 50%;
            margin-left: -75px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .ocupado:hover .tooltip {
            visibility: visible;
            opacity: 1;
        }

        .ocupado:hover {
            background: #009688;
            color: #fafafa;
        }

        .flecha {
            position: absolute;
            width: 0px;
            height: 0px;
            margin-left: 20px;
            border-top: 15px solid #2979FF;
            border-right: 15px solid transparent;
            border-bottom: 15px solid transparent;
            border-left: 15px solid transparent;
        }

        .head {
            background: #CFD8DC;
            padding: 10px;
        }

        .panelCalendar {
            min-height: 85vh;
        }

        body {
            background: #E1F5FE;
        }

        .bold-text {
            font-weight: bold;
        }
    </style>
    <div id="appReporteEventoDescargo" class="container">

        <div class="card-body p-2 ">
            <form action="{{ route('eventos.reporte_descargo_buscar') }}" method="post">
                @csrf
                <div class="row mb-2">
                    <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE DESCARGO DE EVENTOS</div>

                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Eventos
                            </label>
                            <select class="form-select" name="tipo_evento">
                                <option selected value="0">Todos los tipos de eventos</option>
                                @foreach ($tipo as $t)
                                    <option value="{{ $t->id }}"
                                        {{ isset($evento) && $evento == $t->id ? 'selected' : '' }}>
                                        {{ $t->evento }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Inicio
                            </label>
                            <input type="date" name="inicio" id="inicio" class="form-control" value="{{ $inicio ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="mb-3">
                            <label for="" class="form-label">
                                Finalización
                            </label>
                            <input type="date" name="fin" id="fin" class="form-control" value="{{ $fin ?? '' }}" />
                        </div>
                    </div>
                    <div class="col-3 row align-items-center">
                        <div class="col ">
                            <button class="btn btn-light" type="submit" role="button" value="1" name="opcion">
                                <span class="mdi mdi-magnify h5"></span>
                                Buscar
                            </button>
                            <button class="btn btn-light" type="submit" role="button" value="2" name="opcion">
                                <span class="mdi mdi-file-pdf-box h5"></span>
                                PDF
                            </button>
                            <button class="btn btn-light" type="submit" role="button" value="3" name="opcion">
                                <span class="mdi mdi-file-excel h5"></span>
                                XLS
                            </button>
                        </div>
                    </div>
                </div>
            </form>


            @if (isset($eventos))
                @foreach ($vendedor as $v)
                    @php
                        $rVendedor = $eventos->where('users_id', $v->id);
                        $total = $rVendedor->sum('total_evento');
                    @endphp
                    @foreach ($rVendedor as $evento)
                        <div class="row">
                            <div class="col-12">
                                <table
                                    class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                    <thead>
                                        <tr class="bold-text">
                                            <th scope="col">Vendido por</th>
                                            <th scope="col">Nº</th>
                                            <th scope="col">FECHA</th>
                                            <th scope="col">EVENTO</th>
                                            <th scope="col" colspan="4">CLIENTE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bold-text">
                                            <td>{{ $v->name }}</td>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $evento->fecha }}</td>
                                            <td>{{ $evento->tipo_eventos->evento }}</td>
                                            <td colspan="4">{{ $evento->clientes->nombre ?? $evento->titular }}</td>
                                        </tr>
                                        @php
                                            $comandas = $evento->comandasTest()->get();
                                            $ordenes = $evento->ordenesTest()->get();
                                            $comanda_detalles = $comandas->flatMap(function ($comanda) {
                                                return $comanda->detalles_comanda;
                                            });
                                            $detalle_ordenes = $ordenes->flatMap(function ($comanda) {
                                                return $comanda->detalle_orden;
                                            });
                                            $reservas = $evento->reservaciones()->get();
                                            $detalle_reserva = $reservas->flatMap( function ($r){
                                                return $r->detalleReservaciones;
                                            });

                                            $comandados = count($comanda_detalles);
                                            $ordenados = count($detalle_ordenes);

                                        @endphp
                                        @if ($comandados > 0)
                                            <tr>
                                                <td colspan="6">
                                                    <h6>Comandas del evento - {{ $comandados }}
                                                        producto{{ $comandados > 1 ? 's' : '' }}
                                                        comandado{{ $comandados > 1 ? 's' : '' }}</h6>
                                                    <table class="table table-striped" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Concepto</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio</th>
                                                                <th>Nº Comanda</th>
                                                                <th>Usuario</th>
                                                                <th>Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($comanda_detalles as $detalle)
                                                                <tr>
                                                                    <td>{{ $detalle->precios->detalle }}</td>
                                                                    <td>{{ $detalle->cantidad }}</td>
                                                                    <td>${{ number_format($detalle->precio, 2) }}</td>
                                                                    <td>Nº {{ $detalle->comandas_id }}</td>
                                                                    <td>{{ explode('@', $detalle->user_comanda->email)[0] }}
                                                                    </td>
                                                                    <td>{{ $detalle->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($ordenes->isNotEmpty())
                                            <tr>
                                                <td colspan="6">
                                                    <h6>Órden{{ $ordenados > 1 ? 'es' : '' }} del evento - {{ $ordenados }}
                                                    servicio{{ $ordenados > 1 ? 's' : '' }}</h6>
                                                    <table class="table table-striped" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Concepto</th>
                                                                <th>Cantidad</th>
                                                                <th>Precio</th>
                                                                <th>Nº Orden</th>
                                                                <th>Usuario</th>
                                                                <th>Fecha</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($detalle_ordenes as $orden)
                                                                <tr>
                                                                    <td>{{ $orden->servicios->servicio }}</td>
                                                                    <td>{{ $orden->cantidad }}</td>
                                                                    <td>${{ number_format($orden->precio_unitario, 2) }}
                                                                    </td>
                                                                    <td>Nº {{ $orden->ordenes_id }}</td>
                                                                    <td>{{ explode('@', $orden->user_detalle->email)[0] }}
                                                                    </td>
                                                                    <td>{{ $orden->created_at }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($detalle_reserva->isNotEmpty())
                                            <tr>
                                                <td colspan="6">
                                                    <h6>Reservacion{{ count($detalle_reserva) > 1 ? 'es' : '' }} del evento - {{ count($detalle_reserva) }}
                                                    reserva{{ count($detalle_reserva) > 1 ? 's' : '' }}</h6>
                                                    <table class="table table-striped" border="0" cellspacing="0"
                                                        cellpadding="0">
                                                        <thead>
                                                            <tr>
                                                                <th>Fecha ingreso</th>
                                                                <th>Fecha salida</th>
                                                                <th>Habitacion</th>
                                                                <th>Cliente</th>
                                                                <th>Dias</th>
                                                                <th>Tarifa</th>
                                                                <th>Total</th>
                                                                <th>Detalle</th>
                                                                <th>Nº Reserva</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($detalle_reserva as $r)
                                                                <tr>
                                                                    <td>{{ $r->fecha_ingreso }}</td>
                                                                    <td>{{ $r->fecha_salida}}</td>
                                                                    <td>{{$r->relacionHabitaciones->numero_habitacion  }}
                                                                    </td>
                                                                    <td>{{$r->relacionReservaciones->relacionClientes->nombre ?? $r->relacionReservaciones->titular}}</td>
                                                                    <td>{{$r->dias}}</td>
                                                                    <td>{{$r->relacionTarifas->tarifa}}</td>
                                                                    <td>${{number_format(round($r->relacionTarifas->precio * $r->dias, 2),2)}}</td>
                                                                    <td>{{$r->ingreso ? 'Ingreso registrado' : 'Sin ingreso registrado'}}</td>
                                                                    <td>Nº {{ $r->reservaciones_id }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <tbody>
                                    <tr>
                                        <td colspan="3" class="text-uppercase">
                                            Total de eventos autorizados  vendidos por {{ $v->name }}
                                        </td>
                                        <td class="text-end"><b>${{ number_format($total, 2) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
@section('script')
        <script>
        const reporte_descargo = new Vue({
        el: '#appReporteEventoDescargo',
        data: {
            inicio: '',
            fin: '',
        },
        mounted() {
            this.setFechaListeners();
        },
        methods: {
            setFechaListeners() {
                const fecha = document.getElementById("inicio");
                const final = document.getElementById("fin");

                fecha.addEventListener("change", this.validarFechas);
                final.addEventListener("change", this.validarFechas);
            },
            validarFechas() {
                const fecha = document.getElementById("inicio");
                const final = document.getElementById("fin");

                const fechaInicio = new Date(fecha.value);
                const fechaFin = new Date(final.value);

                if (fechaInicio > fechaFin) {
                    alert("La fecha de inicio no puede ser mayor a la fecha de finalización.");
                    fecha.value = final.value;
                } else if (fechaFin < fechaInicio) {
                    alert("La fecha de finalización no puede ser menor que la fecha de inicio del evento.");
                    final.value = fecha.value;
                }
            },
        },
    });
    </script>
@endsection
