@extends('layouts.print_html_optimized')

@section('title_report','REPORTE DE VENTAS POR RUBRO')

@section('header')
    <div class="row">
        <div class="col-6"><p class="text-center">DEL: {{ $inicio }}</p></div>
        <div class="col-6"><p class="text-center">AL: {{ $fin }}</p></div>
    </div>
@endsection

@section('content')
    @php
        $contador = 0;
        $currentPage = 1;

        #Cantidad de registros a mostrar por cada página según su orientación
        #Horizontal: 25 registros por página
        #Vertical:   43 registros por página
        $cantRegistros = $orientacionPagina === 2 ? 25 : 43;
        $totalPages = ceil(count($registros) / $cantRegistros);

        $rubros = [];

        foreach($registros as $dc){
            $caja  = $dc->caja_nombre;
            $rubro = $dc->nombre_rubro;

            #Se inicializa la caja si no existe
            if(!isset($rubros[$caja]))
                $rubros[$caja] = [];

            #Se inicializa el rubro si no existe
            if(!isset($rubros[$caja][$rubro]))
                $rubros[$caja][$rubro] = [];

            #Se agrega el registro al rubro correspondiente
            $rubros[$caja][$rubro][] = $dc;
        }

        #Definir encabezado de la tabla, se renderizara como HTML, no se escaparán carácteres
        $header = '
            <thead>
                <tr>
                    <th scope="col" class="table-border-left-rounded">Com/Ord</th>
                    <th scope="col" class="w-111px">Fecha</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Precio</th>
                    <th scope="col">Venta</th>
                    <th scope="col">Propina</th>
                    <th scope="col">IVA</th>
                    <th scope="col">CET</th>
                    <th scope="col">Total</th>
                    <th scope="col" class="w-111px table-border-right-rounded">Factura</th>
                </tr>
            </thead>';
    @endphp

    <table>
        {!! $header !!}{{--Mostrar encabezado--}}
        <tbody>
            @foreach($rubros as $caja => $rubrosCaja)
                <tr><th colspan="11" class="text-center">Caja: {{ $caja }}</th></tr>

                @foreach($rubrosCaja as $tokenRubro => $items)
                    <tr><th colspan="11" class="text-left">Rubro: {{ $tokenRubro }}</th></tr>

                    @foreach($items as $dc)
                    
                        {{--La validación de la cantidad de registros a mostrar por cada página, se debe realizar en el foreach más interno--}}
                        @if(intval($contador % $cantRegistros) === 0 && $contador != 0){{--Si ya pasaron los registros necesarios y no es el primer registro con indice 0--}}
                            @php($currentPage++)
                            
                            {{--Se comienza cerrando etiquetas de abajo hacia arriba--}}
                            </tbody>{{--Se cierra el tbody---}}
                            </table>{{--Se cierra la tabla---}}
                            </main>{{--Se cierra el contenedor actual---}}
                            <div class="page-break"></div>{{--Se agrega un salto de pagina--}}
                            <main>{{--Y se abre un contenedor nuevo--}}

                            <table>{{--Se abre una nueva tabla con todo y sus encabezados---}}
                                {!! $header !!}{{--Mostrar encabezado--}}
                            <tbody>{{--Se abre el tbody y se continua con los tr de abajo---}}
                        @endif

                        <tr>
                            <td class="text-center">{{ $dc->comprobante }}</td>
                            <td class="text-center">{{ $dc->c_fecha }}</td>
                            <td class="text-truncate">{{ $dc->concepto }}</td>
                            <td class="text-center">{{ $dc->cantidad }}</td>
                            <td class="text-right">${{ number_format($dc->neto, 4) }}</td>
                            <td class="text-right">${{ number_format($dc->venta, 4) }}</td>
                            <td class="text-right">${{ number_format($dc->propina, 4) }}</td>
                            <td class="text-right">${{ number_format($dc->iva, 4) }}</td>
                            <td class="text-right">${{ number_format($dc->cesc, 4) }}</td>
                            <td class="text-right">${{ number_format($dc->total, 4) }}</td>
                            <td class="text-center">{{ $dc->tipo_comprobante_token === 7002 ? 'F' : 'C' }} {{ $dc->c_correlativo }}</td>
                        </tr>
                        
                        @php($contador++)
                    @endforeach

                    <tr>
                        <th colspan="5" class="text-center">Subtotal de {{ $tokenRubro }} - {{ $caja }} -</th>
                        <th class="text-right">${{ number_format(array_sum(array_column($items, 'venta')), 4) }}</th>
                        <th class="text-right">${{ number_format(array_sum(array_column($items, 'propina')), 4) }}</th>
                        <th class="text-right">${{ number_format(array_sum(array_column($items, 'iva')), 4) }}</th>
                        <th class="text-right">${{ number_format(array_sum(array_column($items, 'cesc')), 4) }}</th>
                        <th class="text-right">${{ number_format(array_sum(array_column($items, 'total')), 4) }}</th>
                        <th></th>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@endsection
