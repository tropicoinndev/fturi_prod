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
        padding: 2px;
        height: 10px;
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

    footer {
        position: absolute;
        bottom: 0;
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

    .b-1 {
        border: 1px solid #000;
    }

    .bo-1 {
        border-top: 1px solid #000;
        border-bottom: 1px solid #000;
    }

    .vienen td {
        margin-top: 15px;
    }
</style>


@php
    $item = 1;
    $page = 0;
    $first = true;
    $propina = 0;
    $exento = 0;
    $gravado = 0;
    $percepcion = 0;
    $cesc = 0;
    $advalorem = 0;
    $iva = 0;
    $total = 0;
@endphp


@foreach ($comprobantes as $a)
    @if ($page == 0)
        <div class="page">
            <header style="margin-bottom: 5px;">
                <div class="center title">TURÍSTICAS DE ORIENTE S.A. DE C.V.</div>
                <div class="center title mb-1">Libro de ventas al consumidor final</div>
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
                <thead style="margin-bottom: 6px;">
                    <tr>
                        <th scope="col" rowspan="3" class="b-1 center">Nº</th>
                        <th scope="col" rowspan="3" class="b-1 center">DIA</th>
                        <th scope="col" rowspan="3" class="b-1 center">DEL</th>
                        <th scope="col" rowspan="3" class="b-1 center">AL</th>
                        <th colspan="4" scope="col" class="center propias b-1">VENTAS LOCALES</th>
                        <th scope="col" rowspan="3" class="center b-1" style="width: 2.5cm;">CET</th>
                        <th scope="col" rowspan="3" class="center b-1" style="width: 2.5cm;">TOTAL VENTAS</th>
                        <th scope="col" rowspan="3" class="center b-1" style="width: 2.5cm;">Retención
                            {{ env('percepcion', 0.01) * 100 }}%
                        </th>
                    </tr>
                    <tr>
                        <th rowspan="2" scope="col" class="center b-1 ">EXENTAS</th>
                        <th rowspan="2" scope="col" class="center b-1">GRAVADAS </th>
                        <th scope="col" colspan="2" class="center b-1">NO GRAVADAS</th>
                    </tr>
                    <tr>
                        <th scope="col" class="center b-1">PROPINA</th>
                        <th scope="col" class="center b-1">ADV</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$first)
                        @php
                            $page++;
                        @endphp
                        <tr class="bo-1 vienen">
                            <td colspan="4">Vienen</td>
                            <td class="right">${{ number_format($exento, 2) }}</td>
                            <td class="right">${{ number_format($gravado, 2) }}</td>
                            <td class="right">${{ number_format($propina, 2) }}</td>
                            <td class="right">${{ number_format($advalorem, 2) }}</td>
                            <td class="right">${{ number_format($cesc, 2) }}</td>
                            <td class="right">${{ number_format($total, 2) }}</td>
                            <td class="right">${{ number_format($percepcion, 2) }}</td>
                        </tr>
                    @endif
    @endif
    @php
        $exento += $a->exento;
        $gravado += $a->gravado;
        $propina += $a->propina;
        $cesc += $a->cesc;
        $advalorem += $a->advalorem;
        $total += $a->total + $a->percepcion;
        $percepcion += $a->percepcion;
        $iva += $a->iva;
        $page++;
    @endphp
    <tr>
        <th>{{ $item }}</th>
        @php
            $item++;
        @endphp
        <td>
            {{ \Carbon::parse($a->fecha)->format('d') }}
        </td>
        <td>
            {{ $a->inicio }}
        </td>
        <td>
            {{ $a->ultimo }}
        </td>
        <td class="right">
            ${{ number_format($a->exento, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->gravado, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->propina, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->advalorem, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->cesc, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->total + $a->percepcion, 2) }}
        </td>
        <td class="right">
            ${{ number_format($a->percepcion, 2) }}
        </td>
    </tr>
    @if ($page == $cantidad)
        @php
            $first = false;
            $page = 0;
        @endphp
        <tr>
            <td colspan="4" class="bo-1">Pasan</td>
            <td class="right" class="bo-1">${{ number_format($exento, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($gravado, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($propina, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($advalorem, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($cesc, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($total, 2) }}</td>
            <td class="right" class="bo-1">${{ number_format($percepcion, 2) }}</td>
        </tr>
        </tbody>
        </table>
        </div>
        <div class="page-break"></div>
    @elseif($loop->last)
        <tr>
            <td colspan="10" class="right">Ventas Locales Gravadas</td>
            <td class="right">${{ number_format($gravado, 2) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="right">Ventas Exentas</td>
            <td class="right">${{ number_format($exento, 2) }}</td>
        </tr>

        <tr>
            <td colspan="10" class="right">Ventas no gravadas -propina-</td>
            <td class="right">${{ number_format($propina, 2) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="right">Ventas no gravadas -ADV-</td>
            <td class="right">${{ number_format($advalorem, 2) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="right">CET</td>
            <td class="right">${{ number_format($cesc, 2) }}</td>
        </tr>
        @php
            $piva = env('iva', 0.13);
            $totalIva = ($gravado / (1 + $piva)) * $piva;
        @endphp
        <tr>
            <td colspan="10" class="right">IVA</td>
            <td class="right">${{ number_format($totalIva, 2) }}</td>
        </tr>
        <tr>
            <td colspan="10" class="right">Retención {{ env('percepcion', 0.01) * 100 }}%</td>
            <td class="right">${{ number_format($percepcion, 2) }}</td>
        </tr>
        <tr>
            <th colspan="10" class="right">TOTAL VENTAS</th>
            <th class="right">${{ number_format($total, 2) }}</th>
        </tr>
        </tbody>
        </table>
        </div>
    @endif
@endforeach

<footer>
    {{ env('NOMBRE_CONTADOR') }}
</footer>
