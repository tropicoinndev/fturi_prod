@extends('layouts.print_b')

@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9.5pt;
        }

        .row {
            width: 25cm;
        }
    </style>
@endsection

@section('titulo')
    <div class="titulo">Reporte de ajuste</div>
@endsection

@section('content')
    @isset($data)
        <table class="table">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Bodega</th>
                    <th>Acción</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Fecha proceso</th>
                    <th>Solicitante</th>
                    <th>Realizado por</th>
                    <th>Autorizado por</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        <th>#{{ $d->existencias_id }}</th>
                        <td>{{ $d->bodega }}</td>
                        <td>{{ $d->accion === 1 ? 'aumento' : 'descarte' }}</td>
                        <td>{{ $d->nombre_producto }}</td>
                        <td style="text-align: right;">{{ number_format($d->cantidad, 2) }}</td>
                        <td style="text-align: center;">{{ $d->fecha_proceso }}</td>
                        <td>{{ $d->user_solicitante }}</td>
                        <td>{{ $d->user_realiza }}</td>
                        <td>{{ $d->user_autoriza }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <p><b>Observación: </b>{{ $data[0]->observacion }}</p>
    @endisset
@endsection
