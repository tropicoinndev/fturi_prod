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
    </style>
    <div id="appReporteEventoVenta" class="container">

            <div class="card-body p-2 ">
                <form action="{{ route('eventos.reporte_ventas_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE VENTAS DE EVENTOS</div>

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

                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th scope="col">FECHA </th>
                                        <th scope="col">HORARIO</th>
                                        <th scope="col">EVENTO</th>
                                        <th scope="col">CLIENTE</th>
                                        <th scope="col">FORMA DE PAGO</th>
                                        <th scope="col">MONTO</th>
                                        <th scope="col">ESTADO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vendedor as $v)
                                        @php
                                            $rVendedor = $eventos->where('users_id', $v->id);
                                            $total = $rVendedor->sum('montofacturado');

                                        @endphp
                                        <tr>
                                            <th colspan="8" class="text-uppercase">{{ $v->name }}</th>
                                        </tr>

                                        @foreach ($rVendedor as $r)

                                            <tr>
                                                <td>
                                                    {{ $r->fecha}}
                                                </td>
                                                <td>
                                                    {{ \Carbon\Carbon::parse($r->inicio)->format('h:i A') }}-{{\Carbon\Carbon::parse($r->finalizacion)->format('h:i A')}}
                                                </td>
                                                <td>
                                                    {{ $r->tipo_eventos->evento }}
                                                </td>
                                                <td>
                                                    {{ $r->clientes->nombre ?? $r->titular}}
                                                </td>
                                                    <td>
                                                {{ $r->forma_pagos->forma }}

                                            </td>
                                                <td>
                                                {{ number_format($r->montofacturado,2)}}

                                            </td>
                                            <td>
                                                 @if ($r->autoriza)
                                                <span class="text-uppercase" >autorizado</span>
                                            @else
                                                <span class="text-uppercase">sin autorizar</span>
                                            @endif

                                            </td>

                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="5" class="text-uppercase">
                                                {{ $v->name }}: Total de eventos autorizados
                                            </td>
                                            <td class="text-end"><b>${{ number_format($total, 2) }}</b></td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

    </div>
@endsection
@section('script')
        <script>
        const reporte_venta = new Vue({
        el: '#appReporteEventoVenta',
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
