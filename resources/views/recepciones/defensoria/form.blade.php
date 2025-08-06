@extends('layouts.hab')

@section('content-hab')
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
            width: 0;
            height: 0;
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

        .align-items-end {
            align-items: flex-end !important;
        }
    </style>

    <div class="container">
        <div class="card" style="min-height: 90vh;">
            <div class="card-body p-5">
                <form action="{{ route('defensoriaAcciones') }}" method="POST">
                    @csrf

                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE MENSUAL (DEFENSORIA DEL CONSUMIDOR)
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="sucursales_id" class="form-label">Sucursal:</label>
                                <select class="form-select" aria-label="Default select example" id="sucursales_id"
                                    name="sucursales_id">
                                    @foreach ($sucursales as $s)
                                        <option value="{{ $s->id }}"
                                            {{ isset($sucursalId) && $sucursalId == $s->id ? 'selected' : '' }}>
                                            {{ $s->sucursal }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- <div class="col-2">
                            <div class="mb-3">
                                <label for="f_ingreso" class="form-label">Fecha ingreso:</label>
                                <input type="date" class="form-control" id="f_ingreso" name="f_ingreso" value="{{ $fechaIngreso ?? '' }}">
                            </div>
                        </div> --}}

                        {{-- <div class="col-2">
                            <div class="mb-3">
                                <label for="f_salida" class="form-label">Fecha salida:</label>
                                <input type="date" class="form-control" id="f_salida" name="f_salida" value="{{ $fechaSalida ?? '' }}">
                            </div>
                        </div> --}}

                        <div class="col-8 text-start">
                            <div class="mb-3">
                                <button style="margin-top: 30px;" type="submit" class="btn btn-light" value="1"
                                    name="opcion">
                                    <span class="mdi mdi-magnify h5"></span> Previsualizar
                                </button>

                                @if (request()->routeIs('defensoriaAcciones'))
                                    <button style="margin-top: 30px;" type="submit" class="btn btn-light" value="3"
                                        name="opcion">
                                        <span class="mdi mdi-file-excel-box h5"></span> Generar Excel
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                @if (isset($sucursalId) && $sucursalId != null)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-borderless align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Dia</th>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Habitaciones disponibles</th>
                                    <th scope="col">Habitaciones ocupadas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $ahora = Carbon\Carbon::now();
                                    $mes = $ahora->month - 1;
                                    #$mes = $ahora->month;
                                    $anio = $ahora->year;
                                    $diasDelMes = Carbon\Carbon::create($anio, $mes, 1)->daysInMonth;

                                    $contadorFechas = 0;
                                @endphp

                                @foreach (range(1, $diasDelMes) as $dia)
                                    @php
                                        $fecha = Carbon\Carbon::create($anio, $mes, $dia);

                                        $recepciones = \App\Models\recepciones::whereDate(
                                            'fecha_ingreso',
                                            '<=',
                                            $fecha->format('Y-m-d'),
                                        )
                                            ->whereDate('fecha_salida', '>=', $fecha->format('Y-m-d'))
                                            ->whereHas('habitaciones', function ($q) use ($sucursalId) {
                                                $q->where('sucursales_id', $sucursalId)->where('glorieta', false);
                                            })
                                            #->where('eliminado',false)#Tropico Inn: false, Tropiclub: todas (tomar las facturadas y anuladas tambien)
                                            ->when($sucursalId == 1, function ($q) {
                                                $q->where('eliminado', false);
                                            })
                                            ->get();

                                        $contadorFechas = 0;
                                        foreach ($recepciones as $recep) {
                                            #Contar tambien las fechas si la sucursal es Tropiclub para que tome las facturadas y las anuladas
                                            if ($fecha->format('Y-m-d') < $recep->fecha_salida || $sucursalId == 2) {
                                                $contadorFechas++;
                                            }
                                        }
                                    @endphp

                                    <tr>
                                        <th>{{ $dia }}</th>
                                        <td>{{ $fecha->format('d/m/Y') }}</td>
                                        <td></td>
                                        <td>{{ $contadorFechas }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Aún no hay datos para mostrar!!!</p>
                @endif
            </div>
        </div>
    </div>
@endsection
