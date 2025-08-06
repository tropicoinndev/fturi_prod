@extends('layouts.hab')

@section('content-hab')
    <div id="appReporteHuespedes" class="container">
        <div class="card panelCalendar">
            <x-message></x-message>
            <div class="card-body p-5">
                <form action="{{ route('recepciones.reporte_huesped_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE HUESPEDES
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label text-uppercase">
                                    listado de huespedes del dia
                                </label>
                                <input type="date" name="fecha" class="form-control" value="{{ $fecha ?? '' }}" />
                            </div>
                        </div>

                        <div class="col-4 row align-items-center">
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
                                    <span class="mdi  mdi-email-arrow-right-outline h5"></span>
                                    Email
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                @if (isset($data))
                    <div class="row">
                        <div class="col-12">
                            <table
                                class="table table-light table-striped table-hover table-bordered table-sm table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th >Nº</th>
                                        <th >NOMBRE</th>
                                        <th >DOCUMENTO</th>
                                        <th >NACIONALIDAD</th>
                                        <th >HABITACION</th>
                                        <th >ENTRADA</th>
                                        <th >SALIDA</th>
                                        <th >NOCHES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $r)
                                        <tr>
                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ $r->nombre }}
                                            </td>
                                            <td>
                                                {{ $r->identificacion }} : {{$r->documento }}
                                            </td>
                                            <td>
                                                {{ $r->p ?? 'Salvadoreña'}}
                                            </td>
                                            <td>
                                                {{ $r->n_h ?? 'nacional'}}
                                            </td>
                                            <td>
                                                {{ $r->fecha_ingreso }}
                                            </td>
                                            <td>
                                                {{ $r->fecha_salida}}
                                            </td>
                                            <td>
                                            {{    ((new DateTime($r->fecha_ingreso))
                                            ->diff((new DateTime($r->fecha_salida))))
                                            ->days}}
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
