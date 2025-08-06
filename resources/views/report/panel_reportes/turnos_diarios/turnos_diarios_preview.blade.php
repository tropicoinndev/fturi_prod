@extends('layouts.panel_reportes')

@section('panel_reportes')
    <div class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Reporte diarios preview
                </div>
                <div class="col-12 my-2">
                    <a class="btn btn-light" href="{{ route('cajas.reporteTurnosDiarios') }}" role="button">Volver</a>
                </div>
            </div>

            {{--Tabla--}}
            {{-- Caja: {{ $caja ?? '---' }}
            <br><br><br>
            OpTurno: {{ $opTurno ?? '---' }}
            <br><br><br>
            Fecha: {{ $fecha }}
            <br><br><br>
            Turnos: {{ $turnos }} --}}
            {{-- @if((isset($cajas) && $cajas->count() > 0) && (isset($turnos) && $turnos->count() > 0))
                <div class="row">
                    @foreach($cajas as $caja)
                        @php
                            //Filtramos los turnos de la caja actual
                            $turnosCaja = $turnos->where('cajas_id', $caja->id);
                        @endphp

                        @if ($turnosCaja->isNotEmpty())
                            <div class="col-12">
                                <table class="table table-striped table-inverse">
                                    <thead class="thead-inverse">
                                        <tr colspan="12">{{ $caja->caja }}</tr>
                                        <tr>
                                            <th>Turno</th>
                                            <th>Apertura</th>
                                            <th>Cierre</th>
                                            <th>Ver reporte</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($turnosCaja as $turno)
                                            <tr>
                                                <td>{{ $turno->opcion->turno }}</td>
                                                <td>{{ $turno->apertura }} · {{ $turno->uapertura->name }}</td>
                                                <td>{{ $turno->cierre ?? 'Aún sigue abierto' }}
                                                    {{ $turno->ucierre->name ?? '' }}</td>
                                                <td>
                                                    @if (!$turno->estado)
                                                        <a class="btn btn-outline-secondary"
                                                            href="{{ route('cajas.cierre_print', ['id' => \Crypt::encryptString($turno->id)]) }}"
                                                            role="button" target="_blank">PDF</a>
                                                    @else
                                                        <p class="btn btn-outline-secondary">Abierto</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif --}}

        </div>
    </div>
@endsection