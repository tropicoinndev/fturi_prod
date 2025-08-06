@extends('layouts.excel')

@section('content')
    @isset($data)
        <table>
            <thead>
                <tr>
                    <th colspan="10">
                        <center><b>REPORTE DE AJUSTES DE INVENTARIOS</b></center>
                    </th>
                </tr>
                <tr>
                    <th colspan="10" class="text-center">
                        <center>FECHA: {{ date('Y-m-d H:i:s') }}</center>
                    </th>
                </tr>
                <tr></tr>
                <tr>
                    <th><b>Lote</b></th>
                    <th><b>Bodega</b></th>
                    <th><b>Acción</b></th>
                    <th><b>Producto</b></th>
                    <th><b>Cantidad</b></th>
                    <th><b>Fecha proceso</b></th>
                    <th><b>Solicitante</b></th>
                    <th><b>Realizado por</b></th>
                    <th><b>Autorizado por</b></th>
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

                <tr></tr>
                <tr>
                    <td colspan="10">Observación: {{ $data[0]->observacion }}</td>
                </tr>
            </tbody>
        </table>
    @endisset
@endsection
