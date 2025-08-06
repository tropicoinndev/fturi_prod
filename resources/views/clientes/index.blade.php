@extends('layouts.clientes_panel')

@section('style-content')
    <style>
        body {
            background: #80DEEA !important;
        }

        .clientes-naturales td {
            background: #FAFAFA !important;
        }

        .clientes-juridicos td {
            background: #FAFAFA !important;
        }

        .clientes-disabled td {
            background: #FBE9E7 !important;
        }

        .clientes td {
            padding: 15px !important;
        }
    </style>
@endsection

@section('content_cliente')
    <div class="container" id="appClientes">
        <div class="card-body p-2">
            <div class="row mb-2">
                <div class="col-12 col-lg-6">
                    <h3 class="card-title text-capitalize">{{ $th['title'] ?? 'Listado' }}</h3>
                    <p class="text-uppercase text-muted">{{ $th['sub'] ?? '' }}</p>
                </div>

                {{--Alerta--}}
                <div class="col-12 col-lg-6"><x-message></x-message></div>

                {{-- Botón agregar y caja de búsqueda --}}
                <div class="row mb-3">
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                        @if (isset($th['btnAdd']) && $th['btnAdd'])
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                <span class="mdi mdi-plus"></span> Agregar
                            </button>
                        @elseif (Route::has($th['table'] . '.create'))
                            <a href="{{ route($th['table'] . '.create') }}" class="btn btn-outline-primary">
                                <span class="mdi mdi-plus"></span> Agregar
                            </a>

                            <!--Este botón unicamente se mostrará en la vista de 'precios'-->
                            <!--Desde el controlador de precios se envía la bandera 'showBtnPrecioCajas' en true-->
                            @can('precios.cajas')
                                @isset($showBtnPrecioCajas)
                                    <a href="{{ route('precios.precioCajasIndex') }}" class="btn btn-outline-secondary ms-3">
                                        <span class="mdi mdi-plus"></span> Asignar precios a cajas
                                    </a>
                                @endisset
                            @endcan
                        @endif
                    </div>

                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <form action="{{ route($th['table'] . '.search') }}" method="post">
                            @csrf
                            <input type="text" class="form-control" placeholder="Buscar..." id="txtBusqueda"
                                name="txtBusqueda" value="{{ $txtBusqueda ?? '' }}" autocomplete="off">
                        </form>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="table-responsive mb-3" style="min-height: 50vh;">
                    <table class="table table-hover table-borderless">
                        <thead class="table-light text-uppercase">
                            <tr>
                                <th scope="col">Acciones</th>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>


                                <th scope="col">Tipo</th>
                                <th scope="col">Categoría</th>
                                <th scope="col">Credito fiscal</th>

                                <th scope="col">Crédito</th>

                                <th scope="col">Estado</th>
                                <th>
                                    Información
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p as $d)
                                <tr
                                    class="clientes {{ !$d->estado ? 'clientes-disabled' : ($d->tipo_cliente ? 'clientes-naturales' : 'clientes-juridicos') }}">
                                    <td class="align-content-center">
                                        <x-acciones :table="$th['table']" :d="$d" />
                                    </td>
                                    <td class="align-content-center">
                                        {{ $d->id }}
                                    </td>
                                    <td class="align-content-center text-uppercase w-25">
                                        {{ $d->nombre }}
                                        @if ($d->nivel_cautela > 0)
                                            <br>
                                            <span
                                                class="badge bg-{{ $d->cautela->color() }}">{{ $d->cautela->value() }}</span>
                                        @endif

                                    </td>


                                    <td>
                                        {{ $d->tipo_cliente ? 'Natural' : 'Juridico' }}
                                    </td>

                                    <td>
                                        @php

                                            $result = array_filter(
                                                $categorias,
                                                fn($item) => $item['id'] == $d->categoria,
                                            );
                                            $result = reset($result);
                                        @endphp


                                        {{ $result['categoria'] ?? 'Sin categorizar' }}

                                    </td>

                                    <td>
                                        @if ($d->ccf)
                                            <span class="text-success">Permitido</span>
                                        @else
                                            No permite
                                        @endif

                                    </td>
                                    <td>
                                        @if ($d->credito)
                                            <span class="text-success">Permitido</span>
                                        @else
                                            No permite
                                        @endif
                                    </td>

                                    <td>
                                        @if ($d->estado)
                                            <span class="text-success">Activo</span>
                                        @else
                                            <span class="text-danger">Inactivo</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-light" type="button" data-bs-toggle="offcanvas"
                                            data-bs-target="#clienteDetalle" aria-controls="offcanvasRight"
                                            @click='cliente= @json($d)'>
                                            <span class="mdi mdi-information-outline h5"></span>
                                            Info</button>



                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="10">Aun no se han agregado datos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if (isset($p) && $p instanceof \Illuminate\Pagination\LengthAwarePaginator && $p->links() != null)
                    <div class="col-12">
                        {{ $p->links() }}
                    </div>
                @endif
                @if (isset($data) && $data instanceof \Illuminate\Pagination\LengthAwarePaginator && $data->links() != null)
                    <div class="col-12">
                        {{ $data->links() }}
                    </div>
                @endif
            </div>
        </div>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="clienteDetalle" aria-labelledby="offcanvasRightLabel"
            v-show="cliente != null">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasRightLabel">Información del cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body" v-if="cliente && cliente != null">
                <h4 class="text-uppercase">
                    @{{ cliente.nombre }}
                </h4>
                <div class="col-12 h5 text-uppercase">
                    <span class="badge"
                        :class="{
                            'bg-primary': cliente.empleado,
                            'bg-success': cliente.accionista,
                            'bg-info': !cliente
                                .accionista && !cliente.empleado
                        }">
                        @{{ cliente.empleado ? 'Es empleado' : (cliente.accionista ? 'Es accionista' : 'Cliente') }}
                    </span>
                </div>
                <div class="row mb-2">
                    <div class="col-12 fw-semibold mb-1">
                        Correo electrónico:
                    </div>
                    <div class="col-12">
                        @{{ cliente.email ?? 'Sin correo electrónico' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 fw-semibold mb-1">
                        Observaciones
                    </div>
                    <div class="col-12 text-uppercase">
                        @{{ cliente.observaciones ?? 'Sin observaciones' }}
                    </div>
                </div>
                <div class="row mb-1">
                    <div class="col-12 fw-semibold mb-1">
                        Dirección:
                    </div>
                    <div class="col-12 text-uppercase">
                        @{{ cliente.direccion ?? 'Sin dirección' }}
                        <p v-if="cliente.municipios && cliente.municipios?.municipio != null">
                            <i>
                                Municipio:
                            </i>
                            @{{ cliente.municipios?.municipio }}
                        </p>
                        <p v-if="cliente.extranjero && cliente.extranjero?.pais != null">
                            <i>
                                Pais (Extranjero):
                            </i>
                            @{{ cliente.extranjero?.pais }}
                        </p>

                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 fw-semibold mb-1">
                        Actividad económica:
                    </div>
                    <div class="col-12 text-uppercase">
                        @{{ cliente.actividades?.codigo ?? '' }} @{{ cliente.actividades?.actividad ?? 'Sin actividad económica ' }}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 fw-semibold mb-1">
                        Identificaciones:
                    </div>
                    <div class="col-12 text-uppercase"
                        v-if="cliente.identificaciones && cliente.identificaciones.length > 0">
                        <ul>
                            <li v-for="i in cliente.identificaciones">
                                <b>
                                    @{{ i?.identificaciones?.identificacion }}:
                                </b>
                                @{{ i?.numero }}
                            </li>
                        </ul>

                    </div>
                    <div class="col-12 text-uppercase"
                        v-if="cliente.identificaciones && cliente.identificaciones.length == 0">
                        Sin identificaciones agregadas
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 fw-semibold mb-1">
                        Contactos:
                    </div>
                    <div class="col-12 text-uppercase" v-if="cliente.contactos && cliente.contactos.length > 0">
                        <ul>
                            <li v-for="i in cliente.contactos">
                                <b>
                                    @{{ i?.contactos?.contacto }}:
                                </b>
                                @{{ i?.valor }}
                            </li>
                        </ul>

                    </div>
                    <div class="col-12 text-uppercase" v-if="cliente.contactos && cliente.contactos.length == 0">
                        Sin contactos agregados
                    </div>
                </div>
                <div class="row mb-2" v-if="cliente.detalle && cliente.detalle != null">
                    <div class="col-12 fw-semibold mb-1">
                        Detalle de contribuyentes
                    </div>
                    <div class="col-12 text-uppercase mb-2">
                        NRC: @{{ cliente.detalle.nrc ?? '---' }}
                    </div>
                    <div class="col-12 text-uppercase mb-2" :class="{ 'text-success': cliente.detalle.exento }">
                        Exento: @{{ cliente.detalle.exento ? 'Es exento' : 'No es exento' }}
                    </div>
                    <div class="col-12 text-uppercase mb-2" :class="{ 'text-success': cliente.detalle.percepcion }">
                        Exento: @{{ cliente.detalle.percepcion ? 'Agente de retención' : 'No aplica retención' }}
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script-content')
    <script type="module">
        var app = window.appVue({
            emits: ['cliente'],
            data() {
                return {
                    cliente: null
                }
            }
        });
        app.mount("#appClientes")
    </script>
@endsection
