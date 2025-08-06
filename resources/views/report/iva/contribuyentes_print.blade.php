<style>
    body {
        font-size: 8.5pt;
        color: black;
        font-family: "Times New Roman", serif;
        text-transform: uppercase;
    }


    table {
        width: 100%;
        border-collapse: collapse;

    }

    th,
    td {
        border: 0;
        padding: 4px;
        height: 15px;
        text-align: left;
    }

    th {
        background-color: #ffffff;
    }

    .page-break {
        page-break-after: always;
    }

    .fs-6 {
        font-size: 7pt;
    }

    .fs-7 {
        font-size: 7.8pt;
    }

    .fs-8 {
        font-size: 8.5pt;
    }

    .columnas {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .columnas .w-25 {
        display: inline-table;
        width: 25%;
    }

    .title {
        font-size: 10pt;
        font-weight: 500;
    }

    .center {
        text-align: center;
    }

    .right {
        text-align: right;
    }

    .mb-1 {
        margin-bottom: 8px;
    }

    .bt-1 {
        border-top: 1px solid #000;
    }

    .b-1 {
        border: 1px solid #000;
    }

    .bb-1 {
        border-bottom: 1px solid #000;
    }



    footer {
        position: absolute;
        bottom: -15px;
        left: 0;
        width: 390px;
        text-align: center;
        border-top: 1px solid #000;
        padding: 10px 0;
    }

    .propias {
        font-weight: 600;
        border-bottom: 1px solid #000;
        border-right: 1px solid #000;
    }

    .nogravadas {
        font-weight: 600;
        border-bottom: 1px solid #000;
    }
</style>


@php
    $item = 1;
    $page = 0;
    $first = true;
    $propina = 0;
    $exento = 0;
    $gravado = 0;
    $iva = 0;
    $cesc = 0;
    $advalorem = 0;
    $percepcion = 0;
    $total = 0;
@endphp


@foreach ($comprobantes as $a)
    @php
        $m = 1;
        $token = $a->tipoComprobantes->token;
        $m = $token == 7003 ? -1 : 1;
        $status = '';
        $i = $a->anulacionState;
        $nc = false;
        $nombre = '';
        if (isset($i) && $i?->fecha != $a->fecha) {
            $nc = true;
            $m = 1;
        } elseif (isset($i) && $i->response != null) {
            $json = json_decode($i->response);
            if (isset($json?->estado) && $json?->estado == 'PROCESADO') {
                $m = 0;
            }
        }

        $max = 40;
        $clName = $m == 0 ? 'ANULADO' : $a->clientes->detalle->juridico;
        $tamano = strlen($clName);
        $cliente = $tamano > $max ? substr($clName, 0, $max) . '...' : $clName;

        $dpropina = 0;
        $dcesc = 0;
        $dadvalorem = 0;
        $dexento = 0;
        $dgravado = 0;
        $diva = 0;
        $dpercepcion = 0;
        $dtotal = 0;

        if ($token != 7003) {
            $dpropina = round($a->propina * $m, 2);
            $dcesc = round($a->cesc * $m, 2);
            $dadvalorem = round($a->advalorem * $m, 2);
        }
        $dpercepcion = round($a->percepcion * $m, 2);
        $dexento = round($a->exento * $m, 2);
        $dgravado = round($a->gravado * $m, 2);
        $diva = round($a->iva * $m, 2);
        $dtotal = round($a->total * $m, 2) + $dpercepcion;

    @endphp
    @if ($page == 0)
        <div class="page">
            <header>
                <div class="center title">TURÍSTICAS DE ORIENTE S.A. DE C.V.</div>
                <div class="center title mb-1">Libro de ventas al contribuyente</div>
                <div class="columnas">
                    <div class="w-25">
                        MES: {{ strftime('%B', $fecha->timestamp) }}
                    </div>
                    <div class="w-25">
                        NRC: {{ env('nrc_format') }}
                    </div>
                </div>
                <div class="columnas">
                    <div class="w-25">
                        Año: {{ $fecha->format('Y') }}
                    </div>
                    <div class="w-25">
                        NIT: {{ env('nit_format') }}
                    </div>
                    <div class="w-25">
                        Sucursal: {{ $sucursal->sucursal }}
                    </div>
                </div>
            </header>
            <table class="table">
                <thead>
                    <tr>
                        <th rowspan="3" scope="col" class="center b-1">Nº</th>
                        <th rowspan="3" scope="col" class="center b-1">DIA</th>
                        <th rowspan="3" scope="col" class="center b-1">Corr. Int.</th>
                        <th rowspan="3" scope="col" class="center b-1">Nº control MH</th>
                        <th rowspan="3" scope="col" class="center b-1">NRC</th>
                        <th rowspan="3" scope="col" class="center b-1">CONTRIBUYENTE</th>
                        <td colspan="4" class="center b-1">VENTAS PROPIAS</td>
                        <td rowspan="3" class="center b-1">DEBITO</td>
                        <td rowspan="3" class="center b-1">CET</td>
                        <td rowspan="3" class="center b-1">TOTAL VENTAS</td>
                        <td rowspan="3" class="center b-1">retn {{ env('percepcion', 0.01) * 100 }}%</td>
                    </tr>
                    <tr>
                        <th rowspan="2"class="center b-1">EXENTAS</th>
                        <th rowspan="2" class="center b-1">GRAVADAS</th>
                        <th colspan="2" class="center b-1">NO GRAVADAS</th>
                    </tr>
                    <tr>
                        <th scope="col" class="center b-1">PROPINA</th>
                        <th scope="col" class="center b-1">ADV</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$first)
                        <tr class="bb-1">
                            <td colspan="6" class="center">Vienen</td>
                            <td class="right">${{ number_format($exento, 2) }}</td>
                            <td class="right">${{ number_format($gravado, 2) }}</td>
                            <td class="right">${{ number_format($propina, 2) }}</td>
                            <td class="right">${{ number_format($advalorem, 2) }}</td>
                            <td class="right">${{ number_format($iva, 2) }}</td>
                            <td class="right">${{ number_format($cesc, 2) }}</td>
                            <td class="right">${{ number_format($total, 2) }}</td>
                            <td class="right">${{ number_format($percepcion, 2) }}</td>
                        </tr>
                    @endif
    @endif
    <tr>
        <th>{{ $item }} </th>
        @php
            //Sumatorias
            $propina += $dpropina;
            $cesc += $dcesc;
            $advalorem += $dadvalorem;
            $exento += $dexento;
            $gravado += $dgravado;
            $iva += $diva;
            $percepcion += $dpercepcion;
            $total += $dtotal;
            $item++;

        @endphp
        <td>
            {{ \Carbon::parse($a->fecha)->format('d') }}
        </td>
        <td class="fs-7">
            {{ $a->correlativo }}
        </td>
        <td class="fs-6">
            {{ $a->dteOne?->correlativo }}
        </td>
        <td>
            {{ $m == 0 ? '---' : $a->clientes->detalle->nrc }}
        </td>
        <td class="{{ $tamano > $max ? 'fs-7' : 'fs-8' }}">
            {{ $cliente }}
        </td>
        <td class="right">
            ${{ number_format($dexento, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dgravado, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dpropina, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dadvalorem, 2) }}
        </td>
        <td class="right">
            ${{ number_format($diva, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dcesc, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dtotal, 2) }}
        </td>
        <td class="right">
            ${{ number_format($dpercepcion, 2) }}
        </td>
    </tr>
    @php
        $page++;
    @endphp
    @if ($page == $cantidad && !$loop->last)
        <tr class="bt-1 bb-1">
            <td colspan="6">Pasan</td>
            <td class="right">${{ number_format($exento, 2) }}</td>
            <td class="right">${{ number_format($gravado, 2) }}</td>
            <td class="right">${{ number_format($propina, 2) }}</td>
            <td class="right">${{ number_format($advalorem, 2) }}</td>
            <td class="right">${{ number_format($iva, 2) }}</td>
            <td class="right">${{ number_format($cesc, 2) }}</td>
            <td class="right">${{ number_format($total, 2) }}</td>
            <td class="right">${{ number_format($percepcion, 2) }}</td>
        </tr>
        </tbody>
        </table>
        </div>
        <div class="page-break"></div>
        @php
            $first = false;
            $page = 0;
        @endphp
    @elseif($loop->last)
        <tr>
            <td colspan="6">TOTAL</td>
            <td class="right">${{ number_format($exento, 2) }}</td>
            <td class="right">${{ number_format($gravado, 2) }}</td>
            <td class="right">${{ number_format($propina, 2) }}</td>
            <td class="right">${{ number_format($advalorem, 2) }}</td>
            <td class="right">${{ number_format($iva, 2) }}</td>
            <td class="right">${{ number_format($cesc, 2) }}</td>
            <td class="right">${{ number_format($total, 2) }}</td>
            <td class="right">${{ number_format($percepcion, 2) }}</td>
        </tr>
        </tbody>
        </table>
        </div>
    @endif
@endforeach

<footer>
    {{ env('NOMBRE_CONTADOR') }}
</footer>
