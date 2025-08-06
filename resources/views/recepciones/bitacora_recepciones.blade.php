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

    <div class="container" id="detalleHuesped">
        <div class="card panelCalendar">
            <div class="card-body p-5">
                <form action="{{ route('recepciones.bitacoraRecepcionesSearch') }}" method="POST">
                    @csrf

                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            BITÁCORA DE RECEPCIONES
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-3">
                            <div class="mb-3">
                                <label for="habitaciones_id" class="form-label">Habitación:</label>
                                <select class="form-select" aria-label="Default select example" id="habitaciones_id" name="habitaciones_id">
                                    <option selected value="0">Todas las habitaciones</option>
                                    @foreach($habitaciones as $h)
                                        <option value="{{ $h->id }}" {{ isset($habitacionId) && $habitacionId == $h->id ? 'selected' : '' }}>Habitación {{ $h->numero_habitacion }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="mb-3">
                                <label for="f_ingreso" class="form-label">Fecha ingreso:</label>
                                <input type="date" class="form-control" id="f_ingreso" name="f_ingreso" value="{{ $fechaIngreso ?? '' }}">
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="mb-3">
                                <label for="f_salida" class="form-label">Fecha salida:</label>
                                <input type="date" class="form-control" id="f_salida" name="f_salida" value="{{ $fechaSalida ?? '' }}">
                            </div>
                        </div>

                        <div class="col-2">
                            <label for="" class="form-label">Recepciones:</label>
                            <div style="margin-top: 5px;" class="form-check fs-5">
                                <input class="form-check-input" type="checkbox" value="1" id="anuladas" name="anuladas" @if(isset($anuladas) && $anuladas) checked @endif>
                                <label class="form-check-label" for="anuladas">Anuladas <small>{{ (isset($anuladas) && $anuladas) ? '('.$recepciones->count().')' : '' }}</small></label>
                            </div>
                        </div>

                        <div class="col-3 text-end">
                            <div class="mb-3">
                                <button style="margin-top: 30px;" type="submit" class="btn btn-light" value="1" name="opcion">
                                    <span class="mdi mdi-magnify h5"></span> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(isset($user))
                    <div class="table-responsive">
                        <table class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                            <thead>
                                <tr>
                                    <th scope="col">Cliente</th>
                                    <th scope="col" style="width: 100px;" class="text-center">Fecha ingreso</th>
                                    <th scope="col" style="width: 98px;" class="text-center">Fecha salida</th>
                                    <th scope="col" class="text-center">Usuario</th>
                                    <th scope="col" style="width: 160px;" class="text-center">Creación</th>
                                    <th scope="col" class="text-center">Nº habitación</th>
                                    <th scope="col">Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user as $u)
                                    @php
                                        $recep = $recepciones->where('users_id',$u->id);
                                    @endphp

                                    {{-- {{ $recep }} --}}
                                    @foreach($recep as $r)
                                        <tr>
                                            <td>{{ $r->clientes_id > 0 ? $r->clientes->nombre : $r->titular }}</td>
                                            <td class="text-center">{{ $r->fecha_ingreso }}</td>
                                            <td class="text-center">{{ $r->fecha_salida }}</td>
                                            <td class="text-center">{{ $r->usuarios->name }}</td>
                                            <td class="text-center">{{ $r->created_at }}</td>
                                            <td class="text-center">{{ $r->habitaciones->numero_habitacion }}</td>
                                            <td class="text-center">
                                                {{-- {{ $r->descripcion }} --}}
                                                <a href="{{ route('recepciones.bitacoraRecepcionesShow',['id'=>\Crypt::encryptString($r->id)]) }}" target="_blank" type="button" class="btn btn-outline-secondary" title="Ver mas">
                                                    <span class="mdi mdi-eye"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="mt-4">Aún no hay datos para mostrar.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        
    </script>
@endsection
