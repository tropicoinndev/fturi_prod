@extends('layouts.app')

@section('style')
    @if (!session('bodega'))
        <script>
            window.location = "{{ route('bodegas.login') }}";
        </script>
        {{ exit() }}
    @endif
    <style>
        body {
            background: #EDE7F6;
            margin: 0;
            /* Agregado para evitar el espacio en blanco alrededor del body */
            overflow-x: hidden;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        

        .scrollable-container {
            max-height: 300px;
            overflow-y: auto;
        }

        .scrollable-container2,
        .scrollable-container3 {
            max-height: 200px;
            overflow-y: auto;
        }

        .card-bodega {
            background: {{ session('bodega')->color_fondo }};
            color: {{ session('bodega')->color_texto }};
        }

        .card-menu {
            border-color: #455A64;
            color: #37474F;
            height: 180px;
            overflow: hidden;
        }

        .card-menu .card-title {
            margin-top: 25px;
        }

        .card-menu:hover {
            background: #455A64;
            color: #E8EAF6;
        }
    </style>
@endsection

@section('content')
    <div id="dashboardBodegas" class="main-container">
        <div class="container ">
            <div class="card p-4">
                <div class="card-body ">
                    <!-- Requisiciones pendientes -->
                    @can('requisiciones.create')
                        <div class="card mb-4 text-uppercase">
                            <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                                <span>
                                    Requisiciones Pendientes en Bodega (Por autorizar: {{ $requisiciones->count() }})
                                </span>
                                <a class="btn btn-light m-1 text-uppercase" href="{{ route('requisiciones.autorizarRequisiciones') }}" role="button">
                                    <span class="mdi mdi-store-check-outline"></span>
                                    Ir a solicitudes de requisición
                                </a>
                            </div>
                            <div class="card-body">
                                <div class="scrollable-container">
                                    <table class="table table-striped table-collapse">
                                        <thead>
                                            <tr>
                                                <th># Requisición</th>
                                                <th>Solicitud</th>
                                                <th>Solicitante</th>
                                                <th>Bodega</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($requisiciones as $requisicion)
                                                <tr>
                                                    <td>{{ $requisicion->id }}</td>
                                                    <td>{{ $requisicion->solicitud ?? '' }}</td>
                                                    <td>{{ $requisicion->relacionUsuarios->name }}</td>
                                                    <td>{{ $requisicion->relacionBodegasEntrada->bodega }}</td>
                                                    <td></td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No hay requisiciones pendientes por autorizar</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    @endcan
                    @can('existencias.index')
                        <div class=" col-12 d-flex justify-content-between">
                            <!-- Productos con Existencias Mínimas -->
                                <div class="card col-6  mb-3 text-uppercase">
                                        <div class="card-header fw-bolder d-flex justify-content-between align-items-center">
                                            <span>Productos con Existencias Mínimas</span>
                                            <a class="btn btn-light m-1 text-uppercase" href="{{ route('existencias.existencias_reporte') }}" role="button">
                                                <span class="mdi mdi-cylinder"></span>
                                                Ir existencias
                                            </a>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="scrollable-container2">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">Existencia Mínima</th>
                                                                <th scope="col">Existencia</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($productosconExistencias as $producto)
                                                                <tr>
                                                                    <td>{{ $producto->nombre }}</td>
                                                                    <td>{{ $producto->minimos }}</td>
                                                                    <td class="text-danger">{{ $producto->existencia }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center">Todo está en equilibrio</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                            <!-- Productos Próximos a Vencer -->
                                <div class="card col-6 mb-3 boder-success-subtle text-uppercase">
                                    <div class="card-header fw-bolder  d-flex justify-content-between align-items-center">
                                        <span>Productos Próximos a Vencer</span>
                                        <a class="btn btn-light m-1 text-uppercase" href="{{ route('existencias.reporte_existencia_bodega') }}" role="button">
                                            <span class="mdi mdi-cylinder"></span>
                                            Ir existencias
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="scrollable-container3">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Nombre</th>
                                                                <th scope="col">#Lote</th>
                                                                <th scope="col">Vencimiento</th>
                                                                <th scope="col">Existencias</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($existencias_vencidas as $productoVencido)
                                                                <tr>
                                                                    <td>{{ $productoVencido->nombre }}</td>
                                                                    <td>{{ $productoVencido->id_exis }}</td>
                                                                    <td class="text-danger">{{ $productoVencido->vencimiento_exis }}</td>
                                                                    <td class="text-danger">{{ $productoVencido->existe_avencer }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="4" class="text-center">No hay productos próximos a vencer</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                        </div>
                    @endcan
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 h3 text-uppercase">
                            panel de bodega {{ session('bodega')->bodega }}
                        </div>
                        <div class="col-12 mb-2">
                            Hola,
                            <span class="text-capitalize">
                                {{ auth()->user()->name }}.
                            </span>
                        </div>
                    </div>


                    <div class="row">
                        @can('bodegas.my')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('bodegas.my') }}" class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-file-edit-outline"></span>
                                        </h1>
                                        <p class="card-text">Creacion de requisiciones</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('compras.index')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('compras.index') }}" class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-cart-plus"></span>
                                        </h1>
                                        <p class="card-text">Compras</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('compras.index')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('compras.historialCompras') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-shopping"></span>
                                        </h1>
                                        <p class="card-text">Historial de compras</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('requisiciones.index')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('requisiciones.historialBodegaRequisiciones') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-clipboard-text-clock"></span>
                                        </h1>
                                        <p class="card-text">Historial de requisiciones</p>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('cobros.index')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('requisiciones.requisicion') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-file-multiple"></span>
                                        </h1>
                                        <p class="card-text">Mis requisiciones</p>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('requisiciones.create')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('requisiciones.autorizarRequisiciones') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-text-box-check"></span>
                                        </h1>
                                        <p class="card-text">Autorizacion de requisiciones</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('requisiciones.index')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('requisiciones.requisiciones_reporte') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-chart-box-plus-outline"></span>
                                        </h1>
                                        <p class="card-text">Reporte de requisiciones</p>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can('bodegas.bodega')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('existencias.existencias_reporte') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-tag-check"></span>
                                        </h1>
                                        <p class="card-text">Existencias por producto</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        @can('cajas.create')
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <a href="{{ route('existencias.reporte_existencia_bodega') }}"
                                    class="card text-center text-decoration-none card-menu">
                                    <div class="card-body">
                                        <h1 class="card-title">
                                            <span class="mdi mdi-file-document-check"></span>
                                        </h1>
                                        <p class="card-text">Existencias por bodegas</p>
                                    </div>
                                </a>
                            </div>
                        @endcan
                        <div class="col-12 col-md-6 col-lg-3 mb-3">
                            <a href="{{ route('bodegas.logout') }}"
                                class="card text-center text-decoration-none card-menu">
                                <div class="card-body">
                                    <h1 class="card-title">
                                        <span class="mdi mdi-exit-to-app"></span>
                                    </h1>
                                    <p class="card-text">Salir</p>
                                </div>
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        var dashboard = new Vue({
            el: '#dashboardBodegas',
            data: {
                existencias_vencidas: @json($existencias_vencidas),
                productosconExistencias: @json($productosconExistencias),
                requisiciones: @json($requisiciones),
                tipoFiltrado: 0,
                txtBusqueda: '',
            },
            methods: {},
            mounted() {
                document.documentElement.style.overflowY = 'auto';
            },
            computed: {}
        });
    </script>
@endsection
