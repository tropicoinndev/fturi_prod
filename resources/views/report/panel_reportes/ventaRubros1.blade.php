@extends('layouts.panel_reportes')

@section('css-panel_reportes')
    <style>
        body {
            background: #E1F5FE;
        }
        .table {
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }
    </style>
@endsection

@section('panel_reportes')
    <div id="appVentaByRubros" class="container">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 text-uppercase h3">
                    Reporte de ventas por rubro
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    <form action="{{ route('cajas.ventaRubrosSearch') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="tipo_comprobantes_id" class="form-label">Tipo de comprobate</label>
                                    <select class="form-select" name="tipo_comprobantes_id" id="tipo_comprobantes_id">
                                        <option selected value="0">Todos los comprobantes</option>
                                        @foreach($tipoComprobantes as $tc)
                                            <option value="{{ $tc->token }}" {{ isset($tipoComprobanteId) && $tipoComprobanteId == $tc->token ? 'selected' : '' }}>{{ $tc->tipo }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="cajas_id" class="form-label">Caja</label>
                                    <select class="form-select" name="cajas_id" id="cajas_id">
                                        <option selected value="0">Todas las cajas disponibles</option>
                                        @foreach($cajas as $c)
                                            <option value="{{ $c->id }}" {{ isset($caja->id) && $caja->id == $c->id ? 'selected' : '' }}>
                                                {{ $c->caja }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="mb-2">
                                    <label for="rubros_id" class="form-label">Rubro</label>
                                    <select class="form-select" name="rubros_id" id="rubros_id">
                                        <option selected value="0">Todos los rubros</option>
                                        @foreach($rubros as $r)
                                            <option value="{{ $r->id }}" {{ isset($rubro->id) && $rubro->id == $r->id ? 'selected' : '' }}>{{ $r->rubro }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-end mb-2">
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="inicio" class="form-label">Del</label>
                                    <input type="date" class="form-control" id="inicio" name="inicio" v-model="inicio">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="mb-3">
                                    <label for="fin" class="form-label">Al</label>
                                    <input type="date" class="form-control" id="fin" name="fin" v-model="fin">
                                </div>
                            </div>
                            <div class="col-6 d-flex align-items-center justify-content-start">
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-light me-2" value="1" name="opcion">
                                        <span class="mdi mdi-magnify h5"></span> Buscar
                                    </button>
                                    <button type="submit" class="btn btn-light" value="2" name="opcion">
                                        <span class="mdi mdi-file-pdf-box h5"></span> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{--Tabla--}}
            {{-- {{ $registros }} --}}
            @if(isset($registros) && count($registros) > 0)
                @php
                    $acumuladorVenta = 0;
                    $acumuladodorPropina = 0;
                    $acumuladorIva = 0;
                    $acumuladorCesc = 0;
                    $acumuladorTotal = 0;
                @endphp

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr class="text-center table-secondary">
                                    <th>Com/Ord</th>
                                    <th style="width: 98px;">Fecha</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Venta</th>
                                    <th>Propina</th>
                                    <th>IVA</th>
                                    <th>CET</th>
                                    <th>Total</th>
                                    <th>Factura</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $rubros = [
                                        'Alimentos'=>[],
                                        'Hotel'    =>[],
                                        'Servicios'=>[],
                                        'Bebidas'  =>[],
                                        'Otros'    =>[],
                                    ];
                                @endphp

                                @foreach($registros as $dc)
                                    @switch($dc->token_rubro ?? 0)
                                        @case(12001){{--Alimentos--}}
                                            @php($rubros['Alimentos'][] = ['registro' => $dc, 'caja_nombre' => $dc->caja_nombre ?? 'Sin Caja'])
                                        @break
                                        @case(12002){{--Hotel--}}
                                            @php($rubros['Hotel'][] = ['registro' => $dc, 'caja_nombre' => $dc->caja_nombre ?? 'Sin Caja'])
                                        @break
                                        @case(12003){{--Servicios--}}
                                            @php($rubros['Servicios'][] = ['registro' => $dc, 'caja_nombre' => $dc->caja_nombre ?? 'Sin Caja'])
                                        @break
                                        @case(12004){{--Bebidas--}}
                                            @php($rubros['Bebidas'][] = ['registro' => $dc, 'caja_nombre' => $dc->caja_nombre ?? 'Sin Caja'])
                                        @break
                                        @default
                                            @php($rubros['Otros'][] = ['registro' => $dc, 'caja_nombre' => $dc->caja_nombre ?? 'Sin Caja'])
                                    @endswitch
                                @endforeach

                                @foreach($rubros as $rubro => $items){{--Recorrido por cada rubro--}}
                                    @if(count($items) > 0)
                                        <tr><th colspan="11">Rubro: {{ $rubro }} - Caja: {{ $items[0]['caja_nombre'] }}</th></tr>

                                        @foreach($items as $item) {{-- Recorrido de elementos por cada rubro --}}
                                            @php($dc = $item['registro'])
                                            <tr>
                                                <td class="text-center">{{ $dc->comprobante }}</td>
                                                <td>{{ $dc->c_fecha }}</td>
                                                <td>{{ $dc->concepto }}</td>
                                                <td class="text-center">{{ $dc->cantidad }}</td>
                                                <td class="text-end">${{ number_format($dc->neto, 4) }}</td>
                                                <td class="text-end">${{ number_format($dc->venta, 4) }}</td>
                                                <td class="text-end">${{ number_format($dc->propina, 4) }}</td>
                                                <td class="text-end">${{ number_format($dc->iva, 4) }}</td>
                                                <td class="text-end">${{ number_format($dc->cesc, 4) }}</td>
                                                <td class="text-end">${{ number_format($dc->total, 4) }}</td>
                                                <td class="text-center">{{ $dc->tipo_comprobante_token === 7002 ? 'F' : 'C' }} {{ $dc->c_correlativo }}</td>
                                            </tr>

                                            @php($acumuladorVenta += ($dc->neto * $dc->cantidad))
                                            @php($acumuladodorPropina += $dc->propina)
                                            @php($acumuladorIva += $dc->iva)
                                            @php($acumuladorCesc += $dc->cesc)
                                            @php($acumuladorTotal += $dc->total)
                                        @endforeach
                                        <tr>
                                            <th colspan="5" class="text-center">Subtotal de 1 - {{ $rubro }} -{{ $items[0]['caja_nombre'] }}-</th>
                                            <th class="text-end">${{ number_format($acumuladorVenta, 4) }}</th>
                                            <th class="text-end">${{ number_format($acumuladodorPropina, 4) }}</th>
                                            <th class="text-end">${{ number_format($acumuladorIva, 4) }}</th>
                                            <th class="text-end">${{ number_format($acumuladorCesc, 4) }}</th>
                                            <th class="text-end">${{ number_format($acumuladorTotal, 4) }}</th>
                                            <th></th>
                                        </tr>

                                        {{--Por cada rubro renderizado se limpian las variables acumuladoras--}}
                                        @php($acumuladorVenta = 0)
                                        @php($acumuladodorPropina = 0)
                                        @php($acumuladorIva = 0)
                                        @php($acumuladorCesc = 0)
                                        @php($acumuladorTotal = 0)
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-uppercase">No hay datos aún</p>
            @endif
        </div>
    </div>

    {{--Vue--}}
    <script type="module">
        const app = window.appVue({
            data(){
                return {
                    inicio: '{{ $inicio }}',
                    fin: '{{ $fin }}',
                    turnos: @json($turno_selected ?? []),
                }
            },
            mounted(){
                console.log('appVentaByRubros Mounted.');
            },
            methods: {
                getDate(fecha){
                    const date = new Date(fecha);
                    return [!isNaN(date.getTime()) && date.toISOString().slice(0,10) === fecha, date];
                },
                isValid(){
                    let fi = this.getDate(this.inicio);
                    let ff = this.getDate(this.fin);

                    if(fi[0] && ff[0] && fi[1].getTime() <= ff[1].getTime())
                        return false;
                    else
                        return true;
                },
            },
            computed: {
                //Code...
            },
            watch: {
                //Code...
            },
        });
        app.mount('#appVentaByRubros');
    </script>
@endsection
