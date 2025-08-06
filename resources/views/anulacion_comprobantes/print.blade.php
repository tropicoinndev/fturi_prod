@extends('layouts.print')
@section('style')
    <style>
        * {
            text-transform: uppercase;
            font-size: 9pt;
        }

        .title-table {
            background: rgb(255, 255, 255);
            font-size: 11pt;
            text-align: center;
            color: rgb(26, 26, 26);
            font-weight: 400;
        }

        .w-15 {
            width: 6cm;
        }

        .fp-title {
            font-size: 8.5pt;
            min-width: 2cm;
            overflow: hidden;

        }

        .dollar {
            font-size: 9.5pt;
            text-align: right;
        }

        .titulo {
            font-size: 12pt;
            color: rgb(26, 26, 26);
            text-align: center;
            font-weight: 600;
            width: 100vh;
        }

        .b {
            font-weight: 500;
            color: #555;
        }

        .b1 {
            font-weight: 100;

        }

        .bt-1 {
            border-top: 1px #000 solid;
        }

        .tb-title {
            background: rgb(203, 203, 203);
            color: rgb(26, 26, 26);
        }

        .space {
            height: 20px;
        }

        table tbody tr td {
            margin-bottom: 30px;
        }
    </style>
@endsection
@section('titulo')
    <div class="titulo">
        Reporte de anulaciones de comprobantes
    </div>
@endsection
@section('content')
    <div>
        <div class="row">
            <div class="col-4">
                <span class="b">Fecha:</span>
                {{ $finicio }} al {{ $ffin }}
            </div>
            <div class="col-4">
                <span class="b"> Búsqueda:</span>
                {{ $busqueda ?? 'Se muestran todos sin distinción de clientes' }}
            </div>
            <div class="col-4">
                <span class="b">Tipo de comprobante:</span>
                {{ $tipo_comprobante }}
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-light" border="0" cellspacing="0" cellpadding="0">
                    <thead>
                        <tr>
                            <td class="tb-title fp-title">No.</td>
                            <td class="tb-title fp-title">T. Comprobante</td>
                            <td class="tb-title w-15">TITULAR</td>
                            <td class="tb-title fp-title">CORRE.</td>
                            <td class="tb-title fp-title">TOTAL</td>
                            <td class="tb-title fp-title">ANULACIÓN</td>
                            <td class="tb-title fp-title">USUARIO</td>
                            <td class="tb-title fp-title">REALIZADO</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($p as $a)
                            <tr style="border-top: 1px solid #666; background: #B2DFDB;">
                                <td>
                                    {{ $loop->index + 1 }}
                                </td>
                                <td>
                                    {{ $a->comprobantes->tipoComprobantes->tipo }}
                                </td>
                                <td>
                                    {{ $a->comprobantes->titular }}
                                </td>
                                <td>
                                    {{ $a->comprobantes->correlativo }}
                                </td>
                                <td>
                                    ${{ number_format($a->comprobantes->total, 2) }}
                                </td>
                                <td>
                                    {{ $a->anulaciones->anulacion }}
                                </td>

                                <td>
                                    {{ $a->users->name }}
                                </td>
                                <td>
                                    {{ $a->created_at }}
                                </td>
                            </tr>
                            <tr>
                                <td colspan="8" class="text-uppercase">
                                    <b>Detalle del comprobante</b>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>
                                        CANTIDAD
                                    </b>
                                </td>
                                <td colspan="2">
                                    <b>
                                        DETALLE
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        UNIT.
                                    </b>
                                </td>
                                <td>
                                    <b>
                                        TOTAL
                                    </b>
                                </td>
                                <td colspan="3" rowspan="{{ $a->comprobantes->detalles->count() + 1 }}">
                                    <b>CUENTAS</b>
                                    <ul>
                                        <li>
                                            Comandas:
                                            @forelse ($a->comprobantes->getAnulacionComandas as $o)
                                                Nº {{ $o->registro }}
                                            @empty
                                                ---
                                            @endforelse
                                        </li>
                                        <li>
                                            Ordenes:
                                            @forelse ($a->comprobantes->getAnulacionOrdenes as $o)
                                                Nº {{ $o->registro }}
                                            @empty
                                                ---
                                            @endforelse
                                        </li>
                                        <li>
                                            Recepciones:
                                            @forelse ($a->comprobantes->getAnulacionEstadias as $o)
                                                Nº {{ $o->registro }}
                                            @empty
                                                ---
                                            @endforelse
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @foreach ($a->comprobantes->detalles as $d)
                                <tr>
                                    <td>
                                        {{ $d->cantidad }}
                                    </td>
                                    <td colspan="2">
                                        {{ $d->concepto }}
                                    </td>
                                    <td>
                                        ${{ number_format($d->total, 2) }}
                                    </td>
                                    <td>
                                        ${{ number_format($d->cantidad * $d->total, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>

                </table>
            </div>

        </div>
    </div>
@endsection
