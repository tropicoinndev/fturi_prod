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
    <div id="appReporteEventoCocina" class="container">

        <div class="card-body p-2 ">
            <form action="{{ route('eventos.cocina_buscar') }}" method="post">
                @csrf
                <div class="row mb-2">
                    <div class="card-title col-12 h5 text-uppercase mb-4">REPORTE DE PRODUCCION DE COCINA/BAR</div>


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

                        </div>
                    </div>
                </div>
            </form>


            @if (isset($comandas) && count($comandas) > 0)
                <div class="row">
                    <div class="col-4">
                        <span class="b">Sucursal:</span>
                        {{ $caja->sucursales->sucursal ?? '' }}
                    </div>
                    <div class="col-4">
                        <span class="b"> Caja:</span>
                        {{ $caja->caja ?? '' }}
                    </div>
                    <div class="col-4">
                        <span class="b">Turno:</span>
                        {{ $turno->opcion->turno ?? '' }} · {{ $turno->fecha ?? '' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <span class="b">Apertura:</span>
                        {{ $turno->apertura ?? '' }} · {{ $turno->uapertura->name ?? '' }}
                    </div>

                    <div class="col-4">
                        <span class="b">Cierre</span>
                        {{ $turno->cierre ?? '' }} ·
                        @if (isset($turno) && $turno->cierre_users_id > 0)
                            {{ $turno->ucierre->name }}
                        @else
                            Aun sin cierre
                        @endif
                    </div>
                    <div class="col-4">
                        <span class="b">Cantidad: </span>
                        {{ $comandas->count() }} productos comandados
                    </div>

                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-striped" border="0" cellspacing="0" cellpadding="0">
                            <thead>
                                <tr>
                                    <td class="tb-title">#</td>
                                    <td class="tb-title w-15">Concepto</td>
                                    <td class="tb-title fp-title">Producto</td>
                                    <td class="tb-title fp-title">Cantidad</td>
                                    <td class="tb-title fp-title">Precio</td>
                                    <td class="tb-title fp-title">CMDA.</td>
                                    <td class="tb-title fp-title">Usuario</td>
                                    <td class="tb-title fp-title">Fecha</td>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categorias as $cat)
                                    <tr>
                                        <td colspan="9" class="text-center">
                                            {{ $cat->categoria }}
                                        </td>
                                    </tr>
                                    @foreach ($comandas->where('categorias_precios_id', $cat->id) as $c)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $c->detalle }}
                                            </td>
                                            <td>
                                                @if ($c->precios->detalle_producto->isEmpty())
                                                    Produccion
                                                @else
                                                    @php $first = true @endphp
                                                    @foreach ($c->precios->detalle_producto as $p)
                                                        @if (!$first)
                                                            ,
                                                        @else
                                                            @php $first = false @endphp
                                                        @endif
                                                        {{ $p->productos->nombre ?? '' }}
                                                    @endforeach
                                                @endif
                                            </td>


                                            <td class="fp-title">
                                                {{ $c->cantidad }}
                                            </td>
                                            <td class="fp-title">
                                                ${{ number_format($c->precios->precio, 2) }}
                                            </td>
                                            <td class="fp-title">
                                                Nº {{ $c->comandas_id }}
                                            </td>
                                            <td class="fp-title">
                                                {{ explode('@', $c->email)[0] }}
                                            </td>
                                            <td class="fp-title">
                                                {{ $c->created_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach

                            </tbody>

                        </table>
                    </div>

                </div>
            @else
            <p class="text-uppercase">No hay comandas ordenadas</p>
            @endif
        </div>
    </div>
    @endsection
    @section('script')
        <script>
        const reporte_cocina = new Vue({
            el: '#appReporteEventoCocina',
            data: {

                inicio: '',
                fin: '',

            },

            mounted() {
                //validacion maximos y minimos de personas
                document.addEventListener("DOMContentLoaded", function() {

                    //validacion fechas
                    const fecha = document.getElementById("inicio");
                    const final = document.getElementById("fin");

                    fecha.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaInicio > fechaFin) {
                            alert("La fecha de inicio no puede ser mayor a la fecha de finalización.");
                            fecha.value = final.value; // Resetea la fecha de inicio al valor de fecha_fin
                        }
                    });

                    final.addEventListener("change", function() {
                        const fechaInicio = new Date(fecha.value);
                        const fechaFin = new Date(final.value);

                        if (fechaFin < fechaInicio) {
                            alert("La fecha de finalización no puede ser menor que la fecha de inicio del evento.");
                            final.value = fecha.value; // Resetea la fecha de fin al valor de fecha de inicio
                        }
                    });

                });

            },

        });
    </script>
@endsection
