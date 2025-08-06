<table class="table">
    <thead class="thead-inverse">
        <tr>
            <th colspan="8" style="text-align: center;">
                {{ env('empresa') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                PRODUCTOS VENCIDOS ({{ date('Y-m-d') }})
            </th>
        </tr>
        <tr>
            <th>BODEGA</th>
            <th>LOTE</th>
            <th>FECHA VEN.</th>
            <th>TIEMPO VEN.</th>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>PRECIO COSTO</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($vencidos as $e)
            @php
                $total = round($e->existencia * $e->precio_costo, 2);
            @endphp
            <tr>
                <td scope="row" class="text-uppercase">
                    {{ $e->bodegasExistencias->bodega }}
                </td>
                <td scope="row">
                    {{ $e->id }}
                </td>
                <td scope="row">
                    {{ $e->vencimiento }}
                </td>
                <td scope="row">
                    <small>
                        {{ $e->ven }}
                    </small>
                </td>
                <td scope="row">
                    {{ $e->productosExistencias->nombre }}
                </td>
                <td scope="row">
                    {{ $e->existencia }}
                </td>
                <td scope="row">
                    {{ number_format($e->precio_costo, 2) }}
                </td>
                <td scope="row">
                    {{ number_format($total, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
            </tr>
        @endforelse


    </tbody>
</table>

<table class="table ">
    <thead class="thead-inverse">
        <tr>
            <th colspan="8" style="text-align: center;">
                {{ env('empresa') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="text-align: center;">
                PRODUCTOS PRÓXIMOS A VENCER ({{ $prox }})
            </th>
        </tr>
        <tr>
            <th>BODEGA</th>
            <th>LOTE</th>
            <th>FECHA VEN.</th>
            <th>TIEMPO VEN.</th>
            <th>PRODUCTO</th>
            <th>CANTIDAD</th>
            <th>PRECIO COSTO</th>
            <th>TOTAL</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($proximos as $p)
            @php
                $total = round($p->existencia * $p->precio_costo, 2);
            @endphp
            <tr>
                <td scope="row" class="text-uppercase">
                    {{ $p->bodegasExistencias->bodega }}
                </td>
                <td scope="row">
                    {{ $p->id }}
                </td>
                <td scope="row">
                    {{ $p->vencimiento }}
                </td>
                <td scope="row">
                    <small>
                        {{ $p->ven }}
                    </small>
                </td>
                <td scope="row">
                    {{ $p->productosExistencias->nombre }}
                </td>
                <td scope="row">
                    {{ $p->existencia }}
                </td>
                <td scope="row">
                    {{ number_format($p->precio_costo, 2) }}
                </td>
                <td scope="row">
                    {{ number_format($total, 2) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-uppercase">No se encontraron vencimientos</td>
            </tr>
        @endforelse

    </tbody>
</table>
