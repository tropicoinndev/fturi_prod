@extends('layouts.section_reporte_eventos')

@section('panel_reporte_eventos')
    <div id="appReporteEventoMontaje" class="container">

            <div class="card-body p-3">
                <form action="{{ route('eventos.reporte_montajes_buscar') }}" method="post">
                    @csrf
                    <div class="row mb-2">
                        <div class="card-title col-12 h5 text-uppercase mb-4">
                            REPORTE DE MONTAJES DE SALONES
                        </div>

                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Montaje
                                </label>
                                <select name="montaje" class="form-control" value="{{ $montaje ?? '' }}">
                                    <option value="" selected>Todos los montajes</option>
                                    @foreach ($montajes as $m)
                                        <option value="{{ $m->id }}"
                                            {{ $montaje && $montaje == $m->id ? 'selected' : '' }}>
                                            {{ $m->montaje }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="" class="form-label">
                                    Fecha
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
                                        <th scope="col">FECHA</th>
                                        <th scope="col">HORARIO</th>
                                        <th scope="col">EVENTO</th>
                                        <th scope="col">MONTAJE</th>
                                        <th scope="col">OBSERVACION</th>
                                        <th scope="col">SALONES</th>
                                        <th scope="col">ESTADO SALONES</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $r)
                                    @php
                                        $salonesSeparados = $r->salones->pluck('separado')->contains(true);

                                    @endphp

                                        <tr>
                                            <td>
                                                {{ $r->formateada}}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($r->inicio)->format('h:i A') }}-{{\Carbon\Carbon::parse($r->finalizacion)->format('h:i A')}}
                                            </td>
                                            <td>
                                                {{ $r->tipo_eventos->evento }}
                                            </td>
                                            <td class="text-uppercase">
                                                {{ $r->montajes->montaje ?? 'No requerido' }}
                                            </td>
                                            <td class="text-uppercase">
                                                {{ $r->montaje ?? 'No es requerido' }}
                                            </td>
                                            <td>
                                                {{ implode(', ', $r->salones->pluck('salones.salon')->toArray()) }}

                                            </td>

                                            <td>
                                                  @if ($salonesSeparados)
                                                <span class="text-uppercase" >Separados</span>
                                            @else
                                                <span class="text-uppercase">Unidos</span>
                                            @endif

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
@endsection
